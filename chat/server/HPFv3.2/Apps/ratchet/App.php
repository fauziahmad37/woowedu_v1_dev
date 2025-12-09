<?php
header("Content-Type: text/plain");
include_once(__DIR__ . "/Addons/vendor/autoload.php");

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class MyChat implements MessageComponentInterface {
    protected $clients;
	private $rooms = [];
	
    public function __construct(){
		echo "Server Called\n";
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn){		
		$url = str_replace("/echo/", "", $conn->httpRequest->getUri()->getPath());
		
		$us = explode("/", $url);
		
		if(count($us) == 2){
			$this->rooms[$us[0]][$us[1]] = $conn; 
		}
    }

    public function onMessage(ConnectionInterface $from, $msg){
		$url = str_replace("/echo/", "", $from->httpRequest->getUri()->getPath());
		$us = explode("/", $url);
		
		if(count($us) == 2){
			if(isset($this->rooms[$us[0]]) && is_array($this->rooms[$us[0]])){
				foreach($this->rooms[$us[0]] as $user => $conn){
					$conn->send(base64_encode(json_encode([
						"from"		=> $us[1],
						"message"	=> $msg
					])));
				}
			}
		}
	}

    public function onClose(ConnectionInterface $conn){
        $this->clients->detach($conn);
		$url = str_replace("/echo/", "", $conn->httpRequest->getUri()->getPath());
		
		$us = explode("/", $url);
		
		if(count($us) == 2){
			if(isset($this->rooms[$us[0]][$us[1]])){
				unset($this->rooms[$us[0]][$us[1]]); 
			}
		}
    }

    public function onError(ConnectionInterface $conn, \Exception $e){
		echo "Server Error \n";
		print_r($e->getMessage());
        $conn->close();
    }
}

$app = new HttpServer(new WsServer(new MyChat()));

//For none SSL server, use this code: (comment it if want to use ssl)
$server = IoServer::factory(
	$app,
	PORT
);
$server->run();

/*
//For SSL server can user this code:
$loop = \React\EventLoop\Factory::create();

$secure_websockets = new \React\Socket\Server(HOST . ":" . PORT, $loop);
$secure_websockets = new \React\Socket\SecureServer($secure_websockets, $loop, [
	'local_cert' => SSL_CERT,
	'local_pk' => SSL_KEY,
	'verify_peer' => false,
	'allow_self_signed' => true,
]);

$secure_websockets_server = new \Ratchet\Server\IoServer($app, $secure_websockets, $loop);
$secure_websockets_server->run();
*/