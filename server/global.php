<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('HOST', 'localhost');
define('USER', 'sudip');
define('PASSWORD', 'password');
define('DATABASE', 'blog');

function get_connection(){
    return new mysqli(HOST, USER, PASSWORD, DATABASE);
}

?>