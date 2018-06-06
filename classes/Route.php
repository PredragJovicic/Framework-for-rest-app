<?php

class Route{

	private static $routes= array();
	private static $url; 
	private static $apikey;
	
		
	public static function url($url)
	{
		self::$url = $url;
	}
	
	public static function apikey($apikey)
	{
		self::$apikey = $apikey;
	}
	
	public static function parseRoute($routeadvars)
	{
		$routeadvars = trim($routeadvars,"/");
		
		if(strpos($routeadvars,"{") !== false )
			{
				$routeadvars = str_replace("}","",$routeadvars);
				$routeadvars = explode("{",$routeadvars);
				$route = trim($routeadvars[0],"/");
				unset($routeadvars[0]);
				$parametars = explode("/",implode("",$routeadvars));
				$count_r = count(explode("/",$route));
				
				return array("route" => $route, "count_r" => $count_r, "parametars" => $parametars, "count_p" => count($routeadvars));	
			}
		
				$count_r = count(explode("/",$routeadvars));
				if(!$routeadvars){
					$count_r = 0;
				}
				
		return array("route" => $routeadvars, "count_r" => $count_r, "parametars" => [], "count_p" => 0);		
	}

	public static function get($routeadvars,$controllermetod)
	{
		$newroute = self::parseRoute($routeadvars);
		$fullurl =  trim($routeadvars,"/");
		$count = count(explode("/",$fullurl));
		self::$routes[] = array("route" => $newroute['route'],"count_r" => $newroute['count_r'],
		"parametars" => $newroute['parametars'], "count_p" => $newroute['count_p'],
		"count" => $count,"method" => "GET","controller" => str_replace("@","::",$controllermetod));
		
	}
	
	public static function post($routeadvars,$controllermetod)
	{
		
		if(strpos($routeadvars,"{") == 0 ){
			$newroute = self::parseRoute($routeadvars);
			$fullurl =  trim($routeadvars,"/");
			$count = count(explode("/",$fullurl));
		self::$routes[] = array("route" => $newroute['route'],"count_r" => $newroute['count_r'],
		"parametars" => $newroute['parametars'], "count_p" => $newroute['count_p'],
		"count" => $count,"method" => "POST","controller" => str_replace("@","::",$controllermetod));
		}else{
			die("Route colection Error at route : <b style='color:red'>".$routeadvars."</b>");
		}
	}
	
	public static function put($routeadvars,$controllermetod)
	{
		if(strpos($routeadvars,"{") > 0 ){		
			$newroute = self::parseRoute($routeadvars);
			$fullurl =  trim($routeadvars,"/");
			$count = count(explode("/",$fullurl));

		self::$routes[] = array("route" => $newroute['route'],"count_r" => $newroute['count_r'],
		"parametars" => $newroute['parametars'], "count_p" => $newroute['count_p'],
		"count" => $count,"method" => "PUT","controller" => str_replace("@","::",$controllermetod));
		}else{
			die("Route colection Error at route : <b style='color:red'>".$routeadvars."</b>");
		}
	}
	
	public static function delete($routeadvars,$controllermetod)
	{
		if(strpos($routeadvars,"{") > 0 ){	
			$newroute = self::parseRoute($routeadvars);
			$fullurl =  trim($routeadvars,"/");
			$count = count(explode("/",$fullurl));

		self::$routes[] = array("route" => $newroute['route'],"count_r" => $newroute['count_r'],
		"parametars" => $newroute['parametars'], "count_p" => $newroute['count_p'],
		"count" => $count,"method" => "DELETE","controller" => str_replace("@","::",$controllermetod));
		}else{
			die("Route colection Error at route : <b style='color:red'>".$routeadvars."</b>");
		}
	}
	
	public static function getRoutes()
	{
		return self::$routes;
	}
	
	public static function getUrl()
	{
		return self::$url;
	}
	
	public static function getApikey()
	{
		return self::$apikey;
	}

}