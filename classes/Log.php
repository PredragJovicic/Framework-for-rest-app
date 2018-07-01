<?php

class Log extends App
{
  private static $message,$priority,$logmethod;


  public static function info(string $message,string $priority = "NORMAL")
  {
    self::$logmethod = "INFO";
    self::$message = $message;
    self::$priority = strtoupper($priority);

    self::logging();
  }
  public static function debug(string $message,string $priority = "NORMAL")
  {
    self::$logmethod = "DEBUG";
    self::$message = $message;
    self::$priority = strtoupper($priority);

    self::logging();
  }
  public static function error(string $message,string $priority = "NORMAL")
  {
    self::$logmethod = "ERROR";
    self::$message = $message;
    self::$priority = strtoupper($priority);

    self::logging();
  }
  private static function logging()
  {
    $date = date("Y.m.d H:i:s");
    $newlog =  $date . " ". self::$logmethod . " priority ".self::$priority . " : " . self::$message . PHP_EOL;

    if (!file_exists(self:: $logs_dir)) {
        mkdir(self:: $logs_dir, 0777, true);
    }

    if(self::$priority == "HIGH")
    {
      error_log($newlog, 1, self:: $logs_email);
    }

    $filename = date("Y.m.d")."_logs.log";
    error_log($newlog, 3, self:: $logs_dir."/".$filename  );
  }


}
