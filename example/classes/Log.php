<?php

class Log extends App
{
  private static $message,$priority,$logmethod;


  public static function info(string $message,string $priority = "LOW")
  {
    self::$logmethod = "INFO";
    self::$message = $message;
    self::$priority = strtoupper($priority);

    self::logging();
  }
  public static function debug(string $message,string $priority = "LOW")
  {
    self::$logmethod = "DEBUG";
    self::$message = $message;
    self::$priority = strtoupper($priority);

    self::logging();
  }
  public static function error(string $message,string $priority = "LOW")
  {
    self::$logmethod = "ERROR";
    self::$message = $message;
    self::$priority = strtoupper($priority);

    self::logging();
  }
  private static function logging()
  {
    $date = date("Y.m.d H:i:s");
    $newlog =  $date . " ". self::$logmethod . " priority ".self::$priority . " : " . self::$message;

    if (!file_exists(self:: $logs_dir)) {
        mkdir(self:: $logs_dir, 0777, true);
    }

    if(self::$priority == "HIGH")
    {
      error_log($newlog, 1, self:: $logs_email);
      $newlog = $newlog." - EMAIL SEND TO ADMINISTRATOR";
    }
    elseif(self::$priority == "MEDIUM")
    {
      $url = self::$log_channel;
      $httpmethod = 'POST';
      $data_json = json_encode(["logs" => $newlog]);
      $resjson = self::sendData($url,$httpmethod,$data_json);
      $res = json_decode($resjson);

      if($res->result == "ERROR"){
        $newlog = $newlog." - CURL ERROR - RESPONSE NOT APPLICATION/JSON STATUS CODE ".$res->responceCode." - SEND TO CHANNEL";
      }else{
        $res1= json_decode($res->result);
        $newlog = $newlog." - ".$res1->result." STATUS CODE ".$res->responceCode." - SEND TO CHANNEL";
      }
    }

    error_log($newlog . PHP_EOL, 3, self:: $logs_dir."/".self::$log_file  );
  }


}
