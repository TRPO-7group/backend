<?php

/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 22.10.2017
 * Time: 15:46
 */
class MainClass
{
    static $INDIVIDUAL=1;
    static $EDU = 0;
    static $BOTH = 2;

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
            $newRep = array(
                "id" => $row["rep_id"],
                "name" => MainClass::getRepositoryName($row["rep_url"]),
                "url" => $row["rep_url"],
                "description" => $row["rep_description"],
                "is_ind" => $row["is_ind"],
                "parent_rep" => $row["pater_rep"],
                "owner" => $row["rep_owner"],
                "discipline" => $row['name']
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