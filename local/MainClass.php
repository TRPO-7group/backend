<?php

class MainClass
{


    static function getEventsList($client, $login, $limit, $IdFrom, $compare = "old")
    {
        $result = array();
        $page = 1;
        $page_response = $client->api("user")->publicEvents($login, $page);
        while (!empty($page_response))
        {
            $result = array_merge($result,$page_response);
            $page++;
            $page_response = $client->api("user")->publicEvents($login, $page);
        }
        if ($IdFrom)
        {
            $new_result = array();
            $keyId = -1;
            foreach ($result as $key => $value)
                if ($value["id"] == $IdFrom)
                {
                    $keyId = $key;
                    break;
                }


                if ($compare == "old" || !$compare) {
                    for ($i = $keyId + 1; $i < count($result); $i++)
                        $new_result[] = $result[$i];
                }
                else if ($compare == "new")
                {
                    for ($i = 0; $i < $keyId; $i++)
                        $new_result[] = $result[$i];
                }
        $result = $new_result;
        }

        if ($limit)
        {
            if (count($result) > $limit)
            {
                if (!$compare || $compare == "old")
                    array_splice($result, -(count($result) - $limit));
                else if ($compare == "new")
                    array_splice($result, (count($result) - $limit));
            }
        }

        return $result;
    }
}