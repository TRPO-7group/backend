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

    static  public function getRepositoryList($type = 2, $group = true)
    {

        $query = "";
        if ($type != MainClass::$BOTH)
        {
            $query = "WHERE rep.is_ind = $type";
        }
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $mysqli->set_charset("utf8");
        $q = $mysqli->query("SELECT * FROM rep INNER JOIN disc ON (rep.rep_disc = disc.id) " . $query);
        $res = array();
        while ($row = $q->fetch_assoc())
        {
            $rep = new Repository();
            $rep->loadById($row["rep_id"]);
            $commits = $rep->getUserCommits();
            $newRep = array(
                "id" => $row["rep_id"],
                "name" => MainClass::getRepositoryName($row["rep_url"]),
                "url" => $row["rep_url"],
                "description" => $row["rep_description"],
                "is_ind" => $row["is_ind"],
                "parent_rep" => $row["pater_rep"],
                "owner" => $row["rep_owner"],
                "discipline" => $row['name'],
                "last_commit" => $commits[0]["date"]
            );

            if ($group)
                $res[$row['name']][] = $newRep;
            else
                $res[] = $newRep;
        }
        $mysqli->close();
        return $res;

    }

}