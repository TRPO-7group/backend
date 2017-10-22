<?php
require_once "dbconf.php";
require_once "MainClass.php";

class Repository
{
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * @param mixed $individual
     */
    public function setIndividual($individual)
    {
        $this->individual = $individual;
    }

    /**
     * @return mixed
     */
    public function getParentRep()
    {
        return $this->parent_rep;
    }

    /**
     * @param mixed $parent_rep
     */
    public function setParentRep($parent_rep)
    {
        $this->parent_rep = $parent_rep;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getDiscpline()
    {
        return $this->discpline;
    }

    /**
     * @param mixed $discpline
     */
    public function setDiscpline($discpline)
    {
        $this->discpline = $discpline;
    }

    private $id;
    private $url;
    private $description;
    private $individual;
    private $parent_rep;
    private $owner;
    private $discpline;

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
        return MainClass::getRepositoryName($this->getUrl());
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
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $mysqli->set_charset("utf8");
        $query = $mysqli->query("SELECT * FROM rep WHERE rep_id=$id");
        $res = $query->fetch_assoc();
        if (!$res)
            return false;
        $this->setId($res['rep_id']);
        $this->setUrl($res['rep_url']);
        $this->setDescription($res['rep_description']);
        $this->setDiscpline($res['rep_disp']);
        $this->setIndividual($res['is_ind']);
        $this->setOwner($res['rep_owner']);
        $this->setParentRep($res['pater_rep']);
        $mysqli->close();
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