<?php

/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 22.09.2017
 * Time: 23:23
 */
class GitHubApi
{
    static $API_URL = "https://api.github.com/";


    private static function execReq($options)
    {
        $connection = curl_init();
        if (empty($options[CURLOPT_USERAGENT]))
            $options[CURLOPT_USERAGENT] ="Awesome-Octocat-App";
        foreach ($options as $key => $option)
        {
           curl_setopt($connection, $key, $option);
        }
        $result = curl_exec($connection);
        curl_close($connection);
        return $result;
    }

    /**
     * @param $params - массив входных данных. ключ LOGIN - логин пользователя
     *
     */
    static function getUserInfo($params)
    {
        $options = array();
        $options[CURLOPT_URL] =self::$API_URL . "users/" . $params["LOGIN"];
        return self::execReq($options);
    }

}