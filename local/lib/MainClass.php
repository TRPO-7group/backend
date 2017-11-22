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


    static  public function getRepositoryList($type = 2, $group = true, $page = false, $countOnPage=false, &$existNextPage = false)
    {
        $where = "";
        if ($type != MainClass::$BOTH)
        {
            $where = "rep.is_ind=$type";
        }

        $list = DB::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage);
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

            if ($group)
                $res[$row['name']][] = $newRep;
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


}