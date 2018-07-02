<?php

abstract class App
{
	protected static $url;
	protected static $apikey;
	protected static $database;
	protected static $host;
	protected static $username;
	protected static $password;
	protected static $logs_dir = 'logs';
	protected static $log_file;
	protected static $logs_email;
	protected static $log_channel;
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

		protected static function sendData(string $url,string $httpmethod,string $data_json)
	  {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json','Content-Length: ' . strlen($data_json)));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $httpmethod);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$httpCode = curl_getinfo($ch);
			$httpCode = $httpCode['http_code'];
			$response  = curl_exec($ch);
			curl_close($ch);

			if(json_decode($response) == false){
					return json_encode(["result"=>"ERROR","responceCode"=>$httpCode]);
			}

		  return json_encode(["result"=>$response,"responceCode"=>$httpCode]);

	  }
}
