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
        if (($type == MainClass::$BOTH || $type == MainClass::$INDIVIDUAL) && !$user_id) return array();
        if ($type == MainClass::$BOTH) {
            $where = "rep.is_ind=" . MainClass::$EDU;
            $list1 = DB::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage, "sort");
            $where = "rep.is_ind=" . MainClass::$INDIVIDUAL . " AND rep_owner=" . $user_id;
            $list2 = DB::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage, "sort");
            $list = array_merge($list1, $list2);
        } else
            {
                if ($type == MainClass::$INDIVIDUAL)
                {
                    $where = "rep.is_ind=" . MainClass::$INDIVIDUAL . " AND rep_owner=" . $user_id;
                    $list = $list1 = DB::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage, "sort");
                }
                else
                {
                    $where = "rep.is_ind=" . MainClass::$EDU;
                    $list = $list1 = DB::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage, "sort");
                }
            }
        //$list = DB::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage, "sort");
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
                "link" => $rep->getLink(),
                "language" => $rep->getLanguage() ? $rep->getLanguage() : "Язык не определен"
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

    public static function getRepDetailInfo($rep_id , $period = 30, $fileMask = false)
    {

        $rep = new Repository();
        $rep->loadById($rep_id);
        $arResult["repository_id"] = $rep->getId();
        $arResult["repository_name"] = $rep->getName();
        $arResult["repository_description"] = $rep->getDescription();
        $arResult["repository_url"] = $rep->getUrl();
        $arResult['all_commits_list'] = $rep->getUserCommits($period);
        $arResult['commits_lines'] = $rep->getCommitInfoLinesList($period, $fileMask);
        $arResult['commits_files'] = $rep->getCommitInfoFilesList($period, $fileMask);
        $arResult["repository_owner"] = $rep->getOwner();
        $arResult["really_commits_list"] = array_merge(array_keys( $arResult['commits_lines']),  array_keys($arResult['commits_files']));

        $dateNow = new DateTime();

        $arResult['dates'] = array();
        foreach ($arResult['all_commits_list'] as $commit) {
            if (in_array($commit["sha"],$arResult["really_commits_list"] )) {
                $dateNow->setTimestamp($commit["date"]);
                $arResult['dates'][$dateNow->format("d.m")][] = $commit["sha"];
            }
        }

        $arResult['commit_chart'] = array();
        $arResult['lines_chart'] = array();
        $arResult['files_chart'] = array();

        $arResult["all_lines_add"] = 0;
        $arResult["all_lines_delete"] = 0;

        $arResult["all_files_add"] = 0;
        $arResult["all_files_delete"] = 0;
        $arResult["all_files_modified"] = 0;
        $dateNow = new DateTime();

        for ($i = 0; $i < $period; $i++) {
            $formated = $dateNow->format("d.m");
            $arResult['commit_chart'][$formated] = 0;
            $arResult['lines_chart'][$formated] = array("add" => 0, "delete" => 0);
            $arResult["files_chart"][$formated] = array("add" => 0, "modifed" => 0, "delete" => 0);
            if (array_key_exists($formated, $arResult["dates"])) {
                foreach ($arResult["dates"][$formated] as $sha) {
                    $arResult['commit_chart'][$formated]++;
                    foreach ($arResult["commits_lines"][$sha] as $commit) {
                        $arResult["all_lines_add"] += $commit['add'];
                        $arResult["all_lines_delete"] += $commit['delete'];

                        $arResult['lines_chart'][$formated]["add"] += $commit['add'];
                        $arResult['lines_chart'][$formated]["delete"] += $commit['delete'];
                    }

                    foreach ($arResult["commits_files"][$sha]["A"] as $file) {
                        $filesAddForPopup[$formated][] = "+ " . $file;
                    }

                    foreach ($arResult["commits_files"][$sha]["M"] as $file) {
                        $filesModifiedForPopup[$formated][] = $file;
                    }

                    foreach ($arResult["commits_files"][$sha]["D"] as $file) {
                        $filesDeleteForPopup[$formated][] = "- " . $file;
                    }
                    $arResult["all_files_add"] += count($arResult["commits_files"][$sha]["A"]);
                    $arResult["all_files_modified"] += count($arResult["commits_files"][$sha]["M"]);
                    $arResult["all_files_delete"] += count($arResult["commits_files"][$sha]["D"]);

                    $arResult["files_chart"][$formated]['add'] += count($arResult["commits_files"][$sha]["A"]);
                    $arResult["files_chart"][$formated]['modifed'] += count($arResult["commits_files"][$sha]["M"]);
                    $arResult["files_chart"][$formated]['delete'] += count($arResult["commits_files"][$sha]["D"]);
                }
            }
            $dateNow->sub(new DateInterval("P1D"));
        }
        $arResult["commit_chart"] = array_reverse($arResult["commit_chart"]);
        $arResult["files_chart"] = array_reverse($arResult["files_chart"]);
        $arResult["lines_chart"] = array_reverse($arResult["lines_chart"]);
        return $arResult;
    }


    public static function getUserByEmail($email)
    {
        $user = DB::getList("user", "*", false, "user_mail='".$email . "'");
        return $user[0];
    }

    public static function getMasks()
    {
        $list = DB::getList("masks", "masks.*, mask_category.name as category_name", array("mask_category" => array("masks.category", "mask_category.id")));
        $res = array();
        foreach ($list as $mask)
        {
            //$mask["value"] = explode(", ", $mask["value"]);
            $res[$mask["category_name"]][] = $mask;
        }
        return $res;
    }

    public static function pre($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

}