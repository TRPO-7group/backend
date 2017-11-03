<?php
class MainClass
{
    static $INDIVIDUAL=1;
    static $EDU = 0;
    static $BOTH = 2;

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


    static public function getOffset($page, $count){
        return array(
            "offset" => ($page-1) * $count,
            "limit" => $count
        );
    }

    static public function getList($table, $select ="*", $join=false, $where=false, $page=false, $countOnPage=false, &$existNextPage)
    {
        $query = "SELECT $select FROM $table";
        if ($join)
        {
            foreach ($join as $key => $item) {
                $query .= " INNER JOIN $key ON ($item[0]=$item[1])";
            }
        }
        if ($where)
            $query .= " WHERE $where";
        if ($page>0 && $countOnPage>0)
        {
            $offset = MainClass::getOffset($page,$countOnPage);
            $query .= " LIMIT " . ($offset['limit'] + 1) . " OFFSET " . $offset['offset'];
        }
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $mysqli->set_charset("utf8");
        $q = $mysqli->query($query);
        $res = array();
        $count = 0;
        $existNextPage = false;
        while ($row = $q->fetch_assoc())
        {
            $count++;
            if ($page>0 && $countOnPage>0 && $count > $countOnPage)
            {
                $existNextPage = true;
                break;
            }
            $res[] = $row;
        }
        $mysqli->close();
    return $res;
    }

    static  public function getRepositoryList($type = 2, $group = true, $page = false, $countOnPage=false, &$existNextPage = false)
    {
        $where = "";
        if ($type != MainClass::$BOTH)
        {
            $where = "WHERE rep.is_ind = $type";
        }

        $list = MainClass::getList("rep","*",array("disc" => array("rep.rep_disc", "disc.id")), $where,$page,$countOnPage,$existNextPage);
        $res = array();
        foreach ($list as $row) {
            $rep = new Repository();
            $rep->loadById($row["rep_id"]);
            $commits = $rep->getUserCommits();
            $tegsList = MainClass::getList("reptegs","*",
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
                "tegs" => $tegs
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


}