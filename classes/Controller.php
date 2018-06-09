<?php

class Controller extends App
{
	protected static $request;

	public static function setController(array $route,array $request)
    {
		if(!empty(self::$database)){
			App::Connect();
		}
		self::$request = $request;
		$controller = $route['controller'].'( $request )';
		$result = eval("return $controller;");
		
		self::Response($result,200);
    }
	protected static function Login(array $request)
    {		
		return User::Login($request);
    }
	protected static function Logout(array $request)
    {
		return User::Logout($request);
    }
	protected static function Register(array $request)
    {
        return User::Register($request);
    }
	
	protected static function base64_to_img($img,$name,$dir)
    {
        $encdata = base64_decode($img);
        $filename = time() .'_'.str_replace(" ","",$name) . '.png';
        file_put_contents($dir.'/'.$filename, $encdata);

        return $filename;
    }
    
	protected static function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

}