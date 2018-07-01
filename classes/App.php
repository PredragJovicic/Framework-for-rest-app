<?php

abstract class App
{
	protected static $url;
	protected static $apikey;
	protected static $database;
	protected static $host;
	protected static $username;
	protected static $password;
	protected static $logs_dir;
	protected static $logs_email;
	protected static $connection;

    protected static function Connect()
    {
		self::$connection = new mysqli( self::$host, self::$username, self::$password, self::$database);
        if (self::$connection->connect_error) {
            die("Greska pri konekciji: " . self::$connection->connect_error);
        }
        mysqli_query(self::$connection, "SET NAMES utf8");
        mysqli_query(self::$connection, "SET CHARACTER SET utf8");
        mysqli_query(self::$connection, "SET COLLATION_CONNECTION='utf8_general_ci'");
    }

    protected static function Response($result,$http_response_code)
    {
		http_response_code ($http_response_code);
		header('Content-Type: application/json');
		exit( json_encode(["response" => $result]) );
    }
}
