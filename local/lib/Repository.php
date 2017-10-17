<?php

class Repository
{

    private $id;
    private $url;

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    private function getDescriptors(){
        return array(
            0 => array("pipe", "r"),  // stdin - канал, из которого дочерний процесс будет читать
            1 => array("pipe", "w"),  // stdout - канал, в который дочерний процесс будет записывать
            2 => array("pipe", "w") // stderr - файл для записи
        );
    }

    private function makeStoragePath()
    {
        return $_SERVER["DOCUMENT_ROOT"] . "/reposit-catalog/storage/" . $this->getName() . "_" . md5($this->getUrl());
    }

    private function makeRepositPath()
    {
        return $this->makeStoragePath() . "/" . $this->getName();
    }

    public function getName()
    {
        preg_match("/.*\/(.*).git/", $this->getUrl(),$matches);
        return $matches[1];
    }

    private function cloneReposit()
    {
        $descriptors = $this->getDescriptors();
        $proccess = proc_open("git clone " . $this->getUrl(),$descriptors,$pipes,$this->makeStoragePath());
        if (is_resource($proccess))
        {
            $errors = stream_get_contents($pipes[2]);
            proc_close($proccess);
            $proccess = proc_open("git checkout master",array(),$pipes,$this->makeRepositPath());
            proc_close($proccess);
            return strlen($errors) == 0 ? true : $errors;
        }
        return $proccess;
    }

    public function loadById($id)
    {
        /*
         * Выборка из бд, заполнение
         * */
        $this->setId($id);
        $this->setUrl("https://github.com/alexeykotelevskiy/reposit-catalog.git");
    }

    private function updateReposit()
    {
        $descriptors = $this->getDescriptors();
        $process = proc_open("git pull origin master", $descriptors,$pipes,$this->makeRepositPath());
        proc_close($process);
    }

    private function checkReposit()
    {
        $path = $this->makeRepositPath();
        if (!file_exists($path))
        {
            mkdir($this->makeStoragePath());
            return $this->cloneReposit();
        } else
            $this->updateReposit();
    }

    public function getUserCommits()
    {
        $this->checkReposit();
        $descriptors = $this->getDescriptors();
        $process = proc_open("git log --pretty=format:'next-commit:%H|%an|%at|%s'", $descriptors, $pipes,$this->makeRepositPath());
        if (is_resource($process))
        {
            $first = true;
            $res = array();
            while(!feof($pipes[1]))
            {
                $line = stream_get_line($pipes[1],5000,"\nnext-commit:");

                $message = explode("|",$line,4);
                if ($first)
                {
                    //Костыль, чтобы удалить next-commit: из первой строки
                    $first = false;
                    $message[0] = substr($message[0],strlen("next-commit:"));
                }
                $res[] = array(
                    "sha" => $message[0],
                    "author_name" => $message[1],
                    "date" => $message[2],
                    "message" => $message[3]
                );
            }
            return $res;
        }
        return false;
    }
}