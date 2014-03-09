<?php

class DbConnection
{
	private $conn;
	private $dbHost = 'localhost';
	private $dbName = 'sms_servis';
	private $dbUsername = 'root';
	private $dbPassword = 'password';

	function __construct()
	{
		$this->setConnection();	
	}

	private function setConnection()
	{
		try {
		    $conn = new PDO('mysql:host='.$this->dbHost.';dbname='.$this->dbName, $this->dbUsername, $this->dbPassword);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		 
		} catch(PDOException $e) {
			http_response_code(500);
		    echo 'ERROR: ' . $e->getMessage();
		    exit;
		}

		$this->conn =$conn;
	}

	public function getConnection()
	{
		if(!$this->conn) {
			$this->setConnection();
		}

		return $this->conn;
	}
}