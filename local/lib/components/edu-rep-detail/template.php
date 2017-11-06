<?php
foreach ($arResult["child_reps"] as $rep)
{
    MainClass::includeComponent("rep-detail","edu-template",array('id' => $rep['rep_id'], 'owner_name' => $rep['user_name']));
}
