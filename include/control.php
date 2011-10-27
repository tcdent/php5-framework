<?php

define('INCLUDE_PATH', dirname(__FILE__));

/**
 * mixed include_directory(string $directory)
 * 
 * Iterate over the contents of a directory and include all valid PHP files.
 */

function include_directory($directory){
    if(!is_dir($directory)) throw new Exception(sprintf(
        "\"%s\" is not a valid directory.", $directory));

    if(!$path = opendir($directory)) throw new Exception(sprintf(
        "Could not open directory \"%s\".", $directory));

    $return = array();
    while(($name = readdir($path)) !== FALSE){
        $file = implode('/', array($directory, $name));
        $valid_filename = array_pop(explode('.', $file)) == "php";
        $valid_start = file_get_contents($file, 0, NULL, 0, 2) == "<?";

        if($valid_filename && $valid_start)
            $return[] = include_once($file);
    }
    closedir($path);
    return $return;
}

/**
 * mixed import(string $include)
 * 
 * Include the first applicable file, package or directory. Applicable
 * includes found in the calling file's directory will take precedence over
 * those in the application's include path. Regular files take precedence
 * over packages and directories. Directories containing an __init__.php file
 * (a "package") must specify any subsequent includes there. Only files in the
 * root level of a directory will be included; create a package to specify
 * any additional includes.
 */

function import($_include){
    extract($GLOBALS, EXTR_SKIP);
    
    $_backtrace = debug_backtrace();
    $_paths = array(dirname($_backtrace[0]['file'])); // Calling directory's path.
    if(defined('APPLICATION_PATH')) $_paths[] = APPLICATION_PATH;
    if(defined('FRAMEWORK_PATH')) $_paths[] = FRAMEWORK_PATH;
    if(defined('INCLUDE_PATH')) $_paths[] = INCLUDE_PATH;

    $_names = array(
        $_include . '.php',          // File
        $_include . '/__init__.php', // Package
        $_include                    // Directory
    );

    foreach($_paths as $_path){
        foreach($_names as $_name){
            $_file = implode('/', array($_path, $_name));
            if(!file_exists($_file)) continue;
            
            return is_dir($_file)? 
                include_directory($_file) : include_once($_file);
        }
    }

    throw new Exception(sprintf("Could not include \"%s\".", $_include));
}


import('lib/framework');
import('application');

