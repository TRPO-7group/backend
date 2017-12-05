<?php
session_start();
if ($_SESSION["auth_info"] && $_SESSION["auth_info"]["user_id"] == $params["id"])
{
    if ($_POST["save"] == "Y")
    {
        //сохраняем инфу о юзвере
        $name = htmlspecialchars($_POST["name"]);
        $pulpit = htmlspecialchars($_POST["pulpit"]);
        $email = htmlspecialchars($_POST["email"]);
        $mask = "name=?, pulpit=?, user_mail=?";
        $values= array($name, $pulpit, $email );
        $preview_img = false;
        if ($_FILES["foto"]["tmp_name"])
        {
            $uploaddir = SERVER_PATH_ROOT . "/upload/";
            $nameFile = md5(time() . basename($_FILES['foto']['name']) ) . basename($_FILES['foto']['name']);
            $uploadfile = $uploaddir . $nameFile;
            move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile);
            $preview_img = $uploadfile;
            $mask .= ", preview_img=?";
            $values[] = "/reposit-catalog/upload/" . $nameFile;
        }
        $values[] =  $_SESSION["auth_info"]["user_id"];
        DB::updateRow(
            "user",
            $mask,
            "user_id=?",
           $values
        );

        $userInfo = DB::getList("user","*", false, "user_id=" . $_SESSION["auth_info"]["user_id"]);
        $_SESSION["auth_info"] = $userInfo[0];
    }


    //достаем список учебных репов, принадлежащих юзверю, и репов привязанных к ним
    $repList = DB::getList("rep", "rep_id", false, "is_ind = ". MainClass::$EDU ." AND rep_owner=" . $_SESSION["auth_info"]["user_id"]);
    $rep = new Repository();

    $idChildReps = array();
    $usersChildReps = array();
    foreach ($repList as $repElem)
    {
        $rep->loadById($repElem["rep_id"]);
        $arResult["rep_list"][$repElem["rep_id"]]["info"] = $rep->getRepInfo();
        $arResult["rep_list"][$repElem["rep_id"]]["child_reps"] = $rep->getChildReps();
        $idChildReps[] = $repElem["rep_id"];
        foreach ( $arResult["rep_list"][$repElem["rep_id"]]["child_reps"] as $child) {
            $usersChildReps[] = $child["user_id"];
        }

    }


if ($idChildReps && $usersChildReps) {
    $statusList = DB::getList("rep_user_status",
        "*", false,
        "rep_id IN (" . implode(", ", $idChildReps) . ") AND user_id IN (" . implode(", ", $usersChildReps) . ")",
        false,
        false, $exists, "status");
     $arResult["status"] = array();
    foreach ($statusList as $status) {
        $arResult["status"][$status["rep_id"]][$status["user_rep"]][$status["user_id"]] = $status["status"];
        if ($status["status"] == MainClass::$REP_USER_STATUS_INVITED) {
            if (!isset($arResult["status"][$status["rep_id"]]["invited_count"]))
                $arResult["status"][$status["rep_id"]]["invited_count"] = 1;
            else
                $arResult["status"][$status["rep_id"]]["invited_count"]++;
        }
    }
}

$arResult["desciplines"] = DB::getList("disc", "*", false, false, false, false, $next, "sort");
}
else

{
    header("HTTP/1.1 404 Not Found");
    die;
}

require $template . ".php";