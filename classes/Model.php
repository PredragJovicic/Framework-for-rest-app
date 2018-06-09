<?php

class Model extends Controller
{
	
	public static function All()
	{
		$sql = "select * from ".strtolower(self::getTable());
		$getquery = mysqli_query(self::$connection,$sql);
		
		return mysqli_fetch_all ($getquery, MYSQLI_ASSOC);
	}
	public static function Select(array $request)
	{
		$condition = '';
		foreach($request as $key => $value){
			$condition .= $key." = '".$value."' && ";
		}
		
		$sql = "select * from ".strtolower(self::getTable())." where ".trim($condition,"&& ");
		$getquery = mysqli_query(self::$connection,$sql);
		if (!$getquery){
			
			return "Error description: " . mysqli_error(self::$connection);
		  }
		  
		return mysqli_fetch_all ($getquery, MYSQLI_ASSOC);
	}
	public static function Insert(array $request)
	{
		$keys = "";
		$values = "";
		$request['body']['access_token'] = self::createAccessToken();
	
		foreach($request['body'] as $key => $value){
			if($key == 'password'){
				$value = md5($value);
				$request['body']['password'] = $value;
			}
			if(strpos($key,'file_') !== false){
				$value = self::base64_to_img($value,$key,'files');
			}
			$keys .= self::input($key).",";
			$values .= "'".self::input($value)."',";
	
		}
		
		$sql = "insert into ".strtolower(self::getTable())." (".trim($keys,",").") values (".trim($values,",").")";
		if(!mysqli_query(self::$connection,$sql)){
			return "Error description: " . mysqli_error(self::$connection);
		}
		
		return "Success";
	}
	public static function Update(array $request)
	{
		$update = " set ";
		foreach($request['body'] as $key => $value){
			if($key == 'password'){
				$value = md5($value);
				$request['body']['password'] = $value;
			}
			$update .= self::input($key)."='".self::input($value)."',";
		}
		$condition = '';
		unset($request['body']);
		foreach($request as $key => $value){
			$condition .= $key." = '".$value."' &&";
		}
		
		$sql = "update ".strtolower(self::getTable()).trim($update,",")." where ".trim($condition,"&&");
		if(!mysqli_query(self::$connection,$sql)){
			return "Error description: " . mysqli_error(self::$connection);
		}
		
		return "Success";
	}
	public static function Delete(array $request)
	{
		$condition = '';
		unset($request['body']);
		foreach($request as $key => $value){
			$condition .= $key." = '".$value."' &&";
		}
	
		$sql = "delete from ".strtolower(self::getTable())." where ".trim($condition,"&&");
		if(!mysqli_query(self::$connection,$sql)){
			return "Error description: " . mysqli_error(self::$connection);
		}
		
		return "Success";
	}
	public static function Login(array $request)
	{
        $email = $request['body']['email'];
        $password = md5($request['body']['password']);

        $sql="SELECT * FROM ".strtolower(self::getTable())." WHERE email='$email' && password='$password'";
        $result = mysqli_query(self::$connection,$sql);

        if ($result->num_rows == 1) {

			$user_data = mysqli_fetch_all($result, MYSQLI_ASSOC)[0];
            $id = $user_data['id'];

            $token = self::createAccessToken();
            $sql1="UPDATE ".strtolower(self::getTable())." SET access_token='$token' WHERE id=$id";
            mysqli_query(self::$connection,$sql1);

			return $user_data;
        }

        return 'Check data!';
	}
	public static function Logout(array $request)
	{
		$access_token = $request['access_token'];
		$token = null;
		$sql1="UPDATE ".strtolower(self::getTable())." SET access_token=null WHERE access_token='$access_token'";
		mysqli_query(self::$connection,$sql1);

        return 'Success';
	}
	public static function Register(array $request)
	{
        return self::Insert($request);
	}
		
	public static function Search(array $request,array $columns,array $orderBy)
	{
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$search = trim($request['body']['search']);
			$offset = $request['body']['offset'];
			$limit = $request['body']['limit'];
		}
		
		if($_SERVER['REQUEST_METHOD'] == "GET")
		{
			$search = trim(urldecode($request['search']));
			$offset = $request['offset'];
			$limit = $request['limit'];
		}
		$table = strtolower(self::getTable());
		
		return self::SearchEngine($table, $search, $columns, $orderBy, $offset, $limit);
	}
	
	public static function SearchEngine($table, $search, array $columns,array $orderBy, $offset, $limit)
	{
		$search_exploded = explode (" ", $search);
		$construct = "";
		foreach($search_exploded as $search_each)
		{
			$s=strlen($search_each);
			if($s>3){
				$search_each = substr($search_each, 0, $s-1);
			}
			$conct = '';;
			foreach($columns as $column){
				$conct .= " || " . $column . " LIKE '%$search_each%'";
			}

			$constrall=" ( ".trim($conct," || ")." ) ";			
			$construct .= " && ".$constrall;
		}
		if (empty($search)){
			$query= 'SELECT * FROM '.$table.' order by '.$orderBy[0].' '.$orderBy[1].' LIMIT '.$offset.','. $limit;
		}else{
			$query= 'SELECT * FROM '.$table.' WHERE '.trim($construct," && ").' order by  '.$orderBy[0].' '.$orderBy[1].' LIMIT '.$offset.','. $limit;
		}
		$getquery = mysqli_query(self::$connection,$query);

		return mysqli_fetch_all ($getquery, MYSQLI_ASSOC);
		
	}
	
	public static function CheckAccessToken()
    {
		$access_token = null;
		if(!empty(self::$request['access_token']))
		{
			$access_token = self::$request['access_token'];
		}
		if(!empty(self::$request['body']['access_token']))
		{
			$access_token = self::$request['body']['access_token'];
		}

		$message="Access token error";
		if($access_token)
		{
			$sql="SELECT * from ".strtolower(self::getTable())." WHERE access_token='$access_token'";
			$result = mysqli_query(self::$connection,$sql);
			$user_data = mysqli_fetch_array($result);
			$count_row = $result->num_rows;

			if ($count_row == 0 || $user_data['access_token'] != self::$request['access_token']) {
				self::Response($message,200);
			}
		}else{
				self::Response($message,200);
		}
    }
	
	
	
	protected static function getTable()
	{
		return get_called_class();
	}
	protected static function input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
	protected static function base64_to_img($img,$name,$dir)
    {
        $encdata = base64_decode($img);
        $filename = time() .'_'.str_replace(" ","",$name) . '.png';
        file_put_contents($dir.'/'.$filename, $encdata);

        return $filename;
    }
    protected static function createAccessToken()
    {
        $token = bin2hex(random_bytes(64));
	
        $sql2="SELECT * FROM ".strtolower(self::getTable())." WHERE access_token='$token'";
        $result = mysqli_query(self::$connection,$sql2);
        $count_row = $result->num_rows;
        if ($count_row == 0) {
            return $token;
        }
        self::createAccessToken();
    }
	
}