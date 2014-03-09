<?php

include_once('dbconnection.php');

class Sender
{
	private $message;
	private $source;
	private $destination;
	private $conn;
	private $providerGateway = 'http://api.provider.net/bin/send?USERNAME=username&PASSWORD=passwd';

	function __construct()
	{
		$db = new DbConnection();
		$this->conn = $db->getConnection();
	}

	public function sendSms(User $user)
	{
		$sendUrl = $this->providerGateway.'&MESSAGE='.$this->message.'&DESTADDR='.$this->destination.'&SOURCEADDR='.$this->source;
		
		// Handle appropriate response from provider
		$send = file_get_contents($sendUrl);
		$resultArray = explode("\n", $send);
		$user->charge();

		print(@$resultArray[2]);
	}

	public function validateInput()
	{
		$message = @$_REQUEST['MESSAGE']; 
		$source = @$_REQUEST['SOURCEADDR']; 
		$destination = @$_REQUEST['DESTADDR'];

		if (!isset($message) || !isset($source) || !isset($destination)) {
			http_response_code(400);
			echo 'ERROR: INVALID PARAMETERS PROVIDED.';
			exit;
		}

		if(strlen($message) > 160) {
			http_response_code(400);
			echo 'ERROR: MESSAGE TOO LONG.';
			exit;
		}

		if(strlen($source) > 11) {
			http_response_code(400);
			echo 'ERROR: SOURCE TOO LONG.';
			exit;
		}

		if(!is_numeric($destination)) {
			http_response_code(400);
			echo 'ERROR: INVALID DESTINATION.';
			exit;
		}

		$this->message = urlencode($message);
		$this->source = urlencode($source);
		$this->destination = urlencode($destination);
	}
}