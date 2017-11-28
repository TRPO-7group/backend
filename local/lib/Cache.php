<?php
class Cache{
    private $_cacheFolder;
    public function __construct(){
        $this->_cacheFolder = $_SERVER["DOCUMENT_ROOT"] . '/reposit-catalog/cache/';
    }

    private function checkDir($dir)
    {
      if (!file_exists($this->_cacheFolder  . $dir . "/"))
          mkdir($this->_cacheFolder . $dir . "/",0775);
      return $this->_cacheFolder . $dir . "/";
    }
    /**
     * чтение
     *
     * @param mixed $key
     */
    public function load($key, $dir=false){
        if ($dir)
            $file = $this->checkDir($dir) . md5($key);
        else
                $file = $this->_cacheFolder . md5($key);
        if(file_exists($file)){
            $data = unserialize(file_get_contents($file));

            if(time()  <= ($data['time'] + $data['ttl'])){
                return $data['data'];
            }
            unlink($file);
        }

        return FALSE;
    }

    /**
     * добавление
     *
     * @param mixed $key
     * @param mixed $data
     * @param mixed $time
     */
    public function save($key, $data, $time, $dir = false){
        if ($dir)
            $file = $this->checkDir($dir) . md5($key);
        else
            $file = $this->_cacheFolder . md5($key);
        $content['data'] = $data;
        $content['time'] = time();
        $content['ttl'] = $time;
        if(file_put_contents($file, serialize($content))){
            @chmod($file, 0777);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * удаление
     *
     * @param mixed $key
     */
    public function remove($key, $dir=false){
        if ($dir)
            $file = $this->checkDir($dir) . md5($key);
        else
            $file = $this->_cacheFolder . md5($key);
        if(file_exists($file)){
            unlink($file);
        }
    }
}