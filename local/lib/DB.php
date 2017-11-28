<?php
class DB{

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
            $offset = DB::getOffset($page,$countOnPage);
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


    static public function insertRow($table, $fields, $values)
    {
        $pdo = new PDO('mysql:host=' . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $quest = array();
        for ($i = 0; $i<count($fields); $i++)
            $quest[] = "?";
        $sql = "INSERT INTO $table (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $quest). ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        return $stmt->rowCount();
    }



    static public function deleteRow($table, $whereMask, $values)
    {
        $pdo = new PDO('mysql:host=' . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $sql = "DELETE FROM " . $table . " WHERE " . $whereMask;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
    }


}