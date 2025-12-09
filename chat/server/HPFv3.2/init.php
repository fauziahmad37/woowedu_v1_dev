<?php
require_once(__DIR__ . "/Misc/document_access.php");
require_once(__DIR__ . "/Misc/autoload.php");

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
session_start();

header('X-Frame-Options: SAMEORIGIN');
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000");
//header("Set-Cookie: promo_shown=1; SameSite=Lax");
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");

###################################################
###################DO NOT EDIT BELOW###############
###################################################
/*#*/ define("SYSTEM", __DIR__ . "/");	   		  #
/*#*/ define("MISC", __DIR__ . "/Misc/");	   	  #
/*#*/ define("CORE", __DIR__ . "/Core/");	   	  #
/*#*/ define("APPS", SYSTEM . "Apps/");	   		  #
/*#*/ define("DEF_NAME", "Developed with HPF");	  #
###################################################

define("ROUTE", Input::get("route"));

require_once(__DIR__ . "/setup.php");

#Web Application
require_once(__DIR__ . "/Apps/Apps.php");