<?php

if($_SERVER['SERVER_ADDR'] == "192.168.10.155" || $_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR']){
    define('ENVIRONMENT', 'dev');
} elseif(strpos('dev', $_SERVER['SERVER_NAME']) !== FALSE){
    define('ENVIRONMENT', 'stage');
} else {
    define('ENVIRONMENT', 'production');
}

import(ENVIRONMENT);
import('common');

$_ENV['CONFIG'] = array_merge_recursive($_ENV['CONFIG'], $_ENV['CONFIG:'.ENVIRONMENT]);

