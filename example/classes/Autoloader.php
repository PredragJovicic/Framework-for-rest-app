<?php
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers, apikey, Origin");

include_once "config.php";

function autoloader($className) {

	
    $filename = "classes/" . $className . ".php";
    if (is_readable($filename)) {
        include_once $filename;
    }
	$filename = "controllers/" . $className . ".php";
    if (is_readable($filename)) {
        include_once $filename;
    }
	$filename = "models/" . $className . ".php";
    if (is_readable($filename)) {
        include_once $filename;
    }
	$filename = "helpers/" . $className . ".php";
    if (is_readable($filename)) {
        include_once $filename;
    }
	
	include_once "routes.php";
	
}

spl_autoload_register("autoloader");
