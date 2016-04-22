<?php

class ClassAutoloader {

    public function __construct() {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className) {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $file = __DIR__ . '/src/' . $path . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }

}

$autoloader = new ClassAutoloader();


