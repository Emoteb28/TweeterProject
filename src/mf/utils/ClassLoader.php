<?php
namespace mf\utils;

class ClassLoader {

    public $prefix;

    public function __construct($repClass)
    {
        $this->prefix = $repClass;
    }

    public function loadClass($className)
    {
        $path = $this->prefix."\\".$className.".php";
        $path = str_replace("\\", DIRECTORY_SEPARATOR , $path);

        if(file_exists($path))
        {
            require_once $path;
        }else{
            // echo $path." : does NOT exist !";
        }
    }

    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }
}
