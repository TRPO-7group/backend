<?php
require_once "php_init.php";
require_once "dbconf.php";
require_once "MainClass.php";
require_once "Cache.php";
class Repository
{
    public static $PERIOD_MOUNTH = 30;
    public static $PERIOD_THREE_MOUNH =90;
    public static $PERIOD_WEEK = 7;

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


    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    private $id;
    private $url;
    private $description;
    private $individual;
    private $parent_rep;
    private $owner;
    private $discpline;
    private $language;

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
        return MainClass::getDescriptors();
    }

    private function makeStoragePath()
    {
        return SERVER_PATH_ROOT . "/storage/" . $this->getName() . "_" . md5($this->getUrl());
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
        $id = intval($id);
        $oCache = new Cache();
        $res = $oCache->load("repository_" . $id);
        if (!$res) {


            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $mysqli->set_charset("utf8");
            $query = $mysqli->query("SELECT * FROM rep WHERE rep_id=$id");
            $res = $query->fetch_assoc();
            $mysqli->close();
            $oCache->save("repository_" . $id,$res,86400);
        }
        if (!$res)
            return false;
        $this->setId($res['rep_id']);
        $this->setUrl($res['rep_url']);
        $this->setDescription($res['rep_description']);
        $this->setDiscpline($res['rep_disc']);
        $this->setIndividual($res['is_ind']);
        $this->setOwner($res['rep_owner']);
        $this->setParentRep($res['pater_rep']);
        $this->setLanguage(FileTypes::getTypeRep($this->makeStoragePath()));
    return true;
    }

    public function updateReposit()
    {
        if (!file_exists($this->makeRepositPath())) return;
        $obCache = new Cache();
        $res = $obCache->load("repository_update_" . $this->getId());
        if (!$res)
        {
            $obCache->save("repository_update_" . $this->getId(),1,30*60);
        }
        else
            return;
        $descriptors = $this->getDescriptors();
        $process = proc_open("git checkout -f", $descriptors,$pipes,$this->makeRepositPath());
        if (is_resource($process)){
            stream_get_contents($pipes[1]);
           proc_close($process);
        }
        $descriptors = $this->getDescriptors();
        $process2 = proc_open("git fetch --all", $descriptors,$pipes,$this->makeRepositPath());

        if (is_resource($process2)) {
            stream_get_contents($pipes[1]);
            proc_close($process2);
        }
        $descriptors = $this->getDescriptors();
        $process3 = proc_open("git pull --all", $descriptors,$pipes,$this->makeRepositPath());

        if (is_resource($process3)){
            stream_get_contents($pipes[1]);
            proc_close($process3);
        }
       }

    private function checkReposit()
    {
        $path = $this->makeRepositPath();
        if (!file_exists($path))
        {
            mkdir($this->makeStoragePath());
            return $this->cloneReposit();
        }
    }


    public function getCommitInfoFilesList($period = false,  $fileMask = false){
        $commitsList = $this->getUserCommits($period, $lastCommit);
        $res = array();
        foreach ($commitsList as $commit)
        {
            $result = $this->getCommitInfoFiles($commit["sha"], $fileMask);
            if ($result)
                $res[$commit["sha"]] = $result;
        }
        return $res;
    }

    public function getCommitInfoLinesList($period = false, $fileMask = false){
        $commitsList = $this->getUserCommits($period,  $lastCommit);
        $res = array();
        foreach ($commitsList as $commit)
        {
            $result = $this->getCommitInfoLines($commit["sha"], $fileMask);
            if ($result)
                $res[$commit["sha"]] = $result;
        }
        return $res;
    }

    public function getCommitInfoLines($sha, $fileMask = false){
        $descriptors = $this->getDescriptors();
        $obCache = new Cache();
        $cacheKey = $sha;
        if ($fileMask)
            $cacheKey .= md5(serialize($fileMask));
        $res = $obCache->load($cacheKey,"info_lines");
        if (!$res) {
            $process = proc_open("git show --numstat $sha", $descriptors, $pipes, $this->makeRepositPath());
            $res = false;
            if (is_resource($process)) {
                $out = stream_get_contents($pipes[1]);
                preg_match("/\n(\n(\d+)\t+(\d+)\t+(.*))+$/", $out, $matches);
                $out = explode("\n", $matches[0]);

                foreach ($out as $file) {
                    if (strlen($file)) {
                        $fileInfo = explode("\t", $file, 3);
                        $ok = true;
                        if (is_array($fileMask)) {
                            $extInfo = pathinfo($fileInfo[2]);
                            $ok = in_array($extInfo["extension"], $fileMask);
                        }
                        if ($ok) {
                            $res[] = array(
                                "file_name" => $fileInfo[2],
                                "add" => $fileInfo[0],
                                "delete" => $fileInfo[1]
                            );
                        }
                    }

                }
            }
            $obCache->save($cacheKey,$res,2592000* 3,"info_lines" );
        }
        return $res;
    }

    public function getCommitInfoFiles($sha, $fileMask = false)
    {
        $this->checkReposit();
        $descriptors = $this->getDescriptors();
        $obCache = new Cache();
        $cacheKey = $sha;
        if ($fileMask)
            $cacheKey .= md5(serialize($fileMask));
        $res = $obCache->load($cacheKey,"info_files");
        if (!$res) {
            $process = proc_open("git show --name-status $sha", $descriptors, $pipes, $this->makeRepositPath());
            $res = false;
            if (is_resource($process)) {
                $out = stream_get_contents($pipes[1]);
                preg_match("/\n(\n([A-Z]+)\t+(.*))+$/", $out, $matches);
                $out = explode("\n", $matches[0]);
                foreach ($out as $file) {
                    if (strlen($file)) {
                        $fileInfo = explode("\t", $file, 2);
                        $ok = true;
                        if (is_array($fileMask)) {
                            $extInfo = pathinfo($fileInfo[1]);
                            $ok = in_array($extInfo["extension"], $fileMask);
                        }
                        if ($ok) {
                            $res[$fileInfo[0]][] = $fileInfo[1];
                        }
                        }
                }
                proc_close($process);
            }
            $obCache->save($cacheKey,$res,2592000 * 3, "info_files");
        }
    return $res;
    }


    public function getUserCommits($period = false, &$lastCommit)
    {
        if ($period===false)
            $period = self::$PERIOD_MOUNTH;
        $now = new DateTime();
        $date = new DateTime();
        $this->checkReposit();
        $descriptors = $this->getDescriptors();
        $process = proc_open("git log --all --pretty=format:'next-commit:%H|%an|%at|%s'", $descriptors, $pipes,$this->makeRepositPath());
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

                $date->setTimestamp($message[2]);
                $diff = ($now->getTimestamp() - $message[2]) / (60*60*24);
                if ($diff > $period) {
                    $lastCommit =  array(
                        "sha" => $message[0],
                        "author_name" => $message[1],
                        "date" => $message[2],
                        "message" => $message[3]
                    );
                    break;
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

    public function getLink(){
        return "/reposit-catalog/detail.php?id=" . $this->getId();
    }

    public function getChildReps()
    {
        $listReps = DB::getList("rep_user_status","*",array('rep' => array("rep_user_status.user_rep", "rep.rep_id"), "user" => array("rep_user_status.user_id", "user.user_id")),"rep_user_status.rep_id=" . $this->getId());
        $commits = $this->getUserCommits(false,  $lastCommit);
        $res = array();
        foreach ($listReps as $member)
        {
            $res[] = array(
                "id" => $member["id"],
                "rep_id" => $member["rep_id"],
                "user_name" => $member["name"],
                "user_id" => $member["user_id"],
                "last_commit" => $lastCommit["date"],
                "status" => $member["status"]
            );
        }
        return $res;
    }

    public function getRepInfo()
    {

        $commits = $this->getUserCommits(false, $lastCommit);
        $discipline_name = null;

        if ($this->getDiscpline()) {
            $obCache = new Cache();
            $discipline_name = $obCache->load("disciplines_" . $this->getDiscpline(),"disciplines");
            if (!$discipline_name) {
                $disciplines = DB::getList("disc", "*", false, "id=" . $this->getDiscpline());
                $discipline_name = $disciplines[0]["name"];
                $obCache->save("disciplines_" . $this->getDiscpline(),$discipline_name,3600,"disciplines");
            }

        }

        return array(
            "id" => $this->getId(),
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "is_individual" => $this->getIndividual(),
            "owner" => $this->getOwner(),
            "url" => $this->getUrl(),
            "discipline" => array("id" => $this->getDiscpline(), "name" => $discipline_name),
            "last_commit" => $lastCommit["date"],
            "link" => $this->getLink()
        );
    }
}

class FileTypes{
    private static $types = array(
        "JavaScript" => array("*.js"),
        "C++" => array("*.cpp", "*.hpp"),
        "C" => array("*.c", "*.h"),
        "PHP" => array("*.php"),
        "HTML/CSS" => array("*.html","*.css"),
        "Java" => array("*.java")
    );



    public static function getTypeRep($path)
    {
        $typesLoc = self::$types;
        foreach ($typesLoc as $key => $typeArr)
        {
            foreach ($typeArr as $keyType =>  $type)
            {
                $typesLoc[$key][$keyType] = "\"" . $type . "\"";
            }
        }
        foreach ($typesLoc as $key => $typesArr) {
            $res[$key] = shell_exec("find  \"$path\" -type f -name " . implode(" -o -name ", $typesArr) . " |  wc -l");
        }
        $keys = array_keys($typesLoc);
        $max = $keys[0];
        foreach ($res as $key => $typeCount)
        {
            if ($res[$max] < $typeCount)
                $max = $key;
        }
        if  ($res[$max] == 0)
            return false;
        return $max;
    }
}