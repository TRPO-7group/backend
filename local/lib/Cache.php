<?php
class Cache{
    private $_cacheFolder;
    public function __construct(){
        $this->_cacheFolder = $_SERVER["DOCUMENT_ROOT"] . '/reposit-catalog/cache/';
    }

    /**
     * чтение
     *
     * @param mixed $key
     */
    public function load($key){
        $file = $this->_cacheFolder . md5($key);
        if(file_exists($file)){
            $data = unserialize(file_get_contents($file));

            if(time() <= $data['time'] + $data['ttl']){
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
    public function save($key, $data, $time){
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
    public function remove($key){
        $file = $this->_cacheFolder . md5($key);
        if(file_exists($file)){
            unlink($file);
        }
    }
}