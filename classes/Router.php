<?php

class Router extends App
{
	private static $routes= array();
	private static $method;

	public function __construct(array $config)
	{	
		self::$url = $config['url'];
		self::$apikey = $config['apikey'];
		self::$database = $config['database'];
		self::$host = $config['host'];
		self::$username = $config['username'];
		self::$password = $config['password'];
		self::$routes = Route::getRoutes();
	}

	public static function CheckInput()
	{
		
		    if($_SERVER['HTTP_APIKEY'] != self::$apikey)
		    {
			    exit(json_encode( [ "response" => "Api key error"]));		
		    }
		
		    if($_SERVER['CONTENT_TYPE'] != "application/json")
		    {
			    exit(json_encode( [ "response" => "Not application/json"]));
		    }
	
	}
	public static function Run()
	{
		$schema = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		$webroute1 = trim(str_replace(self::$url,"",$schema.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),"/");
		self::$method = $_SERVER['REQUEST_METHOD'];
		$webroute = explode("/",$webroute1);
		$count = count($webroute);

		$route = array();
		$buffer = '';
	
		foreach($webroute as $webroutename)
		{
			$buffer .= $webroutename;
		
			foreach(self::$routes as $routename)
			{
				if(trim($buffer,"/") == $routename['route'] && self::$method == $routename['method']  && $count == $routename['count'])
				{
					$route = $routename;	
				}
			}
			$buffer .= "/";			
		}
		
		if(!empty($route)){
			
			$request = array();
			$data = file_get_contents('php://input');
			$jsondata = json_decode($data,true);
			
			if(!empty($jsondata)){
				$request['body'] = $jsondata;
			}
			if($route['count_p'] != 0){
				$parametarsvalue = array_slice($webroute, $route['count_r']);
				$parametarskey = $route['parametars'];
				$c=array_combine($parametarskey,$parametarsvalue);
				$request = array_merge($request, $c);
				
			}
			
			Controller::setController($route,$request);
		}else{
		    
		    http_response_code (404);
			exit(0);
		}	
	}

}