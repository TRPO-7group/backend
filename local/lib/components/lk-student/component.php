<?php
session_start();
if ($_SESSION["auth_info"] && $_SESSION["auth_info"]["user_id"] == $params["id"])
{
    if ($_POST["save"] == "Y")
    {
        //сохраняем инфу о юзвере
        $name = htmlspecialchars($_POST["name"]);
        $group = htmlspecialchars($_POST["group"]);
        $email = htmlspecialchars($_POST["email"]);
        $mask = "name=?, group=?, user_mail=?";
        $values= array($name, $group, $email );
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

    $user = MainClass::getUser();
    $list = DB::getList("rep_user_status", "rep_id", false, "user_id=" . $user["user_id"] . " AND  status=" . MainClass::$REP_USER_STATUS_TEACHER_INVITE . " OR " . "status=" . MainClass::$REP_USER_STATUS_ACCEPTED);
    $reps = array();

    foreach ($list as $elem)
    {
        $reps[] = $elem["rep_id"];
    }

    $reps = array_unique($reps);

    $repository = new Repository();

    foreach ($reps as $rep)
    {
        $repository->loadById($rep);

        $arResult["rep_list"][$repository->getId()] = $repository->getRepInfo();
    }

    $statusList = DB::getList("rep_user_status", "*",
        false,
        "user_id=" . $user["user_id"] . " AND (status=" . MainClass::$REP_USER_STATUS_TEACHER_INVITE . " OR status=" . MainClass::$REP_USER_STATUS_ACCEPTED . ")",
        false, false, $next, " status desc");

    $arResult["teacher_inv_cnt"] = 0;
    $arResult["status"] = array();
    foreach ($statusList as $status)
    {
        if ($status["status"] == MainClass::$REP_USER_STATUS_TEACHER_INVITE)
            $arResult["teacher_inv_cnt"]++;
        $arResult["status"]["list"][] = $status["rep_id"];
        $arResult["status"][$status["rep_id"]] = $status["status"];
    }

    $arResult["rep_ind_list"] = DB::getList("rep", "rep_id", false, "rep_owner=" . $user["user_id"] . " AND " . MainClass::$INDIVIDUAL);
    foreach ($arResult["rep_ind_list"] as &$item)
    {
        $repository->loadById($item["rep_id"]);
        $item = $repository->getRepInfo();
    }
}
else

{
    echo "Страница в разработке";
    header("HTTP/1.1 404 Not Found");
    die;
}

require $template . ".php";