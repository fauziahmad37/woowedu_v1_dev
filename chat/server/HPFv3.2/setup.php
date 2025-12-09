<?php
require_once(__DIR__ . "/Misc/document_access.php");

// if(!defined("NONE_HTTP")){
	// require_once(__DIR__ . "/Misc/https_handler.php");
	
	// define("AES_PASSWORD", md5(".kjfgn/laksg/lkasjgk;ljasljlasjflkasjlfjaslkfjklasjflajs;flkja;lfkaslkfdj;lasfd"));

	// define("TOKEN",  "WEB:" . $_SERVER['REMOTE_ADDR']. ":" . (F::GetTime() + (60 * 15)));

	// $ipdate = TOKEN;
	// $pass = (AES_PASSWORD);
	// $iv = Encrypter::CreateIv();
	// base64_encode($iv);

	// $encrypt = Encrypter::AESEncrypt($ipdate, $pass, $iv);
	// $input = base64_encode($iv) . ":" . base64_encode($encrypt);
	// define("TOKEN_SECURE", $input);

	// #IPStack Setup
	// define("IPSTACK", "cd7c9139c0be306232a864030bea9814");

	// define("UNIQUE", hash("sha256", time() . F::UniqKey() . $_SERVER["REMOTE_ADDR"] . rand(10000, 99999)));
// }

require_once(__DIR__ . "/Misc/session.php");

#Put 1 to enable show error or otherwise put 0.
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

