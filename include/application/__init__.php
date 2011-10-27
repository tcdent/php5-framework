<?php

error_reporting(E_ALL);
session_start();

define('APPLICATION_PATH', dirname(__FILE__).'/');

import('config');
import('database');

$GLOBALS['db'] = new Database_MySQLConnector($_ENV['CONFIG']['db']);

import('helpers');
import('controllers');
import('models');

