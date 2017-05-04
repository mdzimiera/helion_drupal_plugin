<?php

/**
 * Description of cache
 *
 * @author sebastiankarpeta
 */
class cache {
    
    private $fileName = "";
    private $systemTmpPath;
    private $cachePath;
    private $gzip = FALSE;
    
    public function __construct($options = null) {
        
        if (is_array($options)) {
            if (isset($options["gzip"]) && !empty($options["gzip"]))
                $this->gzip = TRUE;
        }
        
        $this->systemTmpPath = sys_get_temp_dir();
        
        if (is_writable($this->systemTmpPath)) {
            
            $this->cachePath = $this->systemTmpPath . "/cache_helion_module";
            
            if (!is_dir($this->cachePath))
                    mkdir($this->cachePath, 0777);
        }

    }
    
    public function isCached($fileName) {
        $this->fileName = $fileName . ".html";
        $ret = file_exists($this->cachePath . "/" . $this->fileName) ? TRUE : FALSE;

        if (file_exists($this->cachePath . "/" . $this->fileName))
            if( date ("Y-m-d", filemtime($this->cachePath . "/" . $this->fileName)) < date("Y-m-d"))
                $ret = FALSE;
        
        return $ret;
    }
    
    public function getCached() {
        $content = file_get_contents($this->cachePath . "/" . $this->fileName); 
        $content = $this->gzip ? gzinflate(substr($content, 10,-8)) : $content;
        return $content;
    }


    public function save($content) {
        if ($this->fileName && preg_match("/^ksiegarnia-/", $this->fileName)) {
            $fb = fopen($this->cachePath . "/" . $this->fileName, "w+");
                $content = $this->gzip ? gzencode($content, 9) : $content;
                fwrite($fb, $content);
            fclose($fb);
        }
    }
    
    
    public function clearCacheFiles() {
        $dir = $this->cachePath . "/";
        
        foreach (new DirectoryIterator($dir) as $fileInfo)
            if(!$fileInfo->isDot())
                unlink($dir . $fileInfo->getFilename());
        
    }
    
    public function removeCacheDir() {
        if ($this->clearCacheFiles())
            @rmdir($this->cachePath);
    }
    
}