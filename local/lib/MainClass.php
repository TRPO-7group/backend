<?php
class MainClass
{
    static $INDIVIDUAL=1;
    static $EDU = 0;
    static $BOTH = 2;

    static $USER_TYPE_STUDENT = 0;
    static $USER_TYPE_TEACHER = 1;


    static $REP_USER_STATUS_NOT_INVITED = 0;
    static $REP_USER_STATUS_INVITED = 1;
    static $REP_USER_STATUS_ACCEPTED = 2;
    static $REP_USER_STATUS_TEACHER_INVITE = 3;

    static public function getDescriptors(){
        return array(
            0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
            1 => array("pipe", "w"),  // stdout - канал, в который дочерний процесс будет записывать
            2 => array("pipe", "w") // stderr - файл для записи
        );
    }


    static public function getRepositoryName($url)
    {
        preg_match("/.*\/(.*).git/", $url,$matches);
        return $matches[1];
    }


    static  public function getRepositoryList($type = 2, $group = true, $page = false, $countOnPage=false, &$existNextPage = false, $user_id = false)
    {
        $where = "";
        if ($type != MainClass::$BOTH)
        {
            $where = "rep.is_ind=$type";
        }
        $list = DB::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage, "sort");
        $res = array();
        foreach ($list as $row) {
            $rep = new Repository();
            $rep->loadById($row["rep_id"]);
            $commits = $rep->getUserCommits();
            $tegsList = DB::getList("reptegs","*",
                array("teg" => array("reptegs.tegid", "teg.teg_id")),
                "repid=".$row["rep_id"]
                );
            $tegs = array();
            foreach ($tegsList as $item) {
                $tegs[] = $item["teg_name"];
            }
            $newRep = array(
                "id" => $row["rep_id"],
                "name" => MainClass::getRepositoryName($row["rep_url"]),
                "url" => $row["rep_url"],
                "description" => $row["rep_description"],
                "is_ind" => $row["is_ind"],
                "parent_rep" => $row["pater_rep"],
                "owner" => $row["rep_owner"],
                "discipline" => $row['name'],
                "last_commit" => $commits[0]["date"],
                "tegs" => $tegs,
                "link" => $rep->getLink()
            );

            if ($group) {
                $res[$row['name']][] = $newRep;
            }
            else
                $res[] = $newRep;
        }
        return $res;

    }


    public static function getWord($number, $suffix) {
        $keys = array(2, 0, 1, 1, 1, 2);
        $mod = $number % 100;
        $suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
        return $suffix[$suffix_key];
    }


    public static function includeComponent($component, $template, $params){
        if (!strlen($template))
        {
            $template = "template";
        }
        require COMPONENT_PATH . "/$component/component.php";
    }


    public static function addInvite($rep_id, $user_id)
    {
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $mysqli->set_charset("utf8");
        $list = DB::getList("rep_user_status","id, status",false,"rep_id=$rep_id AND user_id=$user_id");
        if (count($list) > 0 && $list[0]['status'] == MainClass::$REP_USER_STATUS_NOT_INVITED)
        {
            $mysqli->query("ALTER TABLE rep_user_status SET status=" . MainClass::$REP_USER_STATUS_INVITED . " WHERE rep_id=$rep_id && user_id=$user_id");
        } else if (count($list) == 0)
        {

        }
        $mysqli->close();
    }

    public static function getUserLink($user_id)
    {
        return "/reposit-catalog/user.php?id=" . intval($user_id);
    }

    public static function getUser(){
        session_start();
        return $_SESSION["auth_info"];
    }


    public static function isRepExists($url)
    {
        $list = DB::getList("rep", "rep_id", false, "rep_url='" . $url . "'");
        return $list[0]["rep_id"];
    }

    public static function addRep($url, $descr, $user, $disc, $repType)
    {
        if (!$id = self::isRepExists($url))
            DB::insertRow("rep", array("rep_url", "rep_description", "rep_owner", "is_ind", "rep_disc"), array($url, $descr, $user, $repType, $disc));
        else
            return $id;
        $id = self::isRepExists($url);
        return $id;
    }


    public static function isUserExists($google_id)
    {
        $users = DB::getList("user","user_id", false,"google_id=" . $google_id);
        if (count($users))
        {
            return $users[0]["user_id"];
        }
        return false;
    }

    public static function addNewUser($name, $email, $google_id, $img = null)
    {
        $users = DB::getList("user","*", false,"google_id=" . $google_id);
        if (!count($users))
        {
            DB::insertRow("user",
                array('user_mail', 'user_type','name','preview_img','google_id'),
                array($email, '0', $name, $img, $google_id));
        }
        $users = DB::getList("user","user_id", false,"google_id=" . $google_id);
        return $users[0]["user_id"];
    }

    public static function isExistsStatus($rep_id, $user_id, $user_rep)
    {
        $list = DB::getList("rep_user_status", "id", false, "rep_id=$rep_id AND user_id=$user_id AND user_rep=$user_rep");
        if (count($list))
        {
            return $list[0]["id"];
        }
        return false;
    }

    public static function newStatus($rep_id, $user_id, $user_rep, $status)
    {
        if ($id = self::isExistsStatus($rep_id, $user_id, $user_rep))
        {
            DB::updateRow("rep_user_status","status=?", "id=?", array($status, $id));
            return $id;
        }
        else
        {
            DB::insertRow("rep_user_status",
                array("rep_id", "user_id", "user_rep", "status"),
                array($rep_id, $user_id, $user_rep, $status));
            $id = self::isExistsStatus($rep_id, $user_id, $user_rep);
            return $id;
        }

    }

    public static function addRepWithForks($url, $descr, $disc, $userId)
    {

        if ($eduRep = self::addRep($url, $descr, $userId, $disc, MainClass::$EDU)){
        preg_match("/.*\/(.*)\/(.*).git/",$url,$matches);
        $userName = $matches[1];
        $reposName = $matches[2];

        $client = new \Github\Client();
        $list = $client->api("repos")->forks()->all($userName , $reposName);
        foreach ($list as $fork)
        {
            $userId = self::isUserExists($fork["owner"]["id"]);
            if(!$userId)
                $userId = self::addNewUser($fork["owner"]["login"], $fork["owner"]["email"], $fork["owner"]["id"]);
           $idRep = self::addRep($fork["html_url"], null, $userId, null, MainClass::$INDIVIDUAL);
            if (!self::isExistsStatus($eduRep, $userId, $idRep))
            {
                self::newStatus($eduRep, $userId, $idRep,MainClass::$REP_USER_STATUS_ACCEPTED);
            }
        }
        }
    }
}