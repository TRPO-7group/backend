<?php
$rep = new Repository();
if ($params['id'] <= 0 || !$rep->loadById(intval($params['id'])))
{
    header("HTTP/1.1 404 Not Found");
    die;
}
$arResult["repository_name"] = $rep->getName();
$arResult["repository_description"] = $rep->getDescription();

$list = $rep->getChildReps();
foreach ($list as $item)
{
    if ($item["status"] == MainClass::$REP_USER_STATUS_ACCEPTED) {
        $arResult["child_reps"][] = $item;
    }

}

$user = MainClass::getUser();
$arResult["show_button"] = false;
if ($user["user_id"] && $user["user_type"] == MainClass::$USER_TYPE_STUDENT)
{
    $list = DB::getList("rep_user_status", "id", false,
        "user_id=" . $user["user_id"] . " AND rep_id=" . $params["id"]);
    if (!count($list))
        $arResult["show_button"] = true;
    $repository = new Repository();
    $arResult["rep_ind_list"] = DB::getList("rep", "rep_id", false, "rep_owner=" . $user["user_id"] . " AND is_ind=" . MainClass::$INDIVIDUAL);
    foreach ($arResult["rep_ind_list"] as &$item)
    {

        $repository->loadById($item["rep_id"]);
        $item = $repository->getRepInfo();

    }

}

require $template . ".php";