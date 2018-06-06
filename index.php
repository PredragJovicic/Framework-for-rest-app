<?php

include_once "classes/Autoloader.php";
	
	$router = new Router($config);
	$router::CheckInput();
	$router::Run();
	
