<?php

include_once('dbconnection.php');

class User
{
	private $username;
	private $password;
	private $conn;
	private $balance;

	function __construct()
	{
		$db = new DbConnection();
		$this->conn = $db->getConnection();
	}

	public function getUser()
	{
		$this->validateAuth();

		$stmt = $this->conn->prepare("SELECT * FROM api_user WHERE username=:username AND password=:password");
		$stmt->bindParam(':username', $this->username);
		$password = md5($this->password);
		$stmt->bindParam(':password', $password);
		$stmt->execute();

		$row = $stmt->fetch();

		if(empty($row)) {
			http_response_code(404);
			echo 'ERROR: INVALID USER PROVIDED.';
			exit;
		}

		$this->balance = $row['balance'];

		return $this;
	}

	public function charge()
	{
		$stmt = $this->conn->prepare("UPDATE api_user SET balance=balance-1 WHERE username=:username AND password=:password");
		$stmt->bindParam(':username', $this->username);
		$password = md5($this->password);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
	}

	public function validateBalance()
	{
		if($this->balance < 1) {
			http_response_code(400);
			echo 'ERROR: INSUFFICIENT BALANCE.';
			exit;
		}
	}

	protected function validateAuth()
	{
		$username = @$_REQUEST['USERNAME']; 
		$password = @$_REQUEST['PASSWORD'];

		if (!isset($username) || !isset($password)) {
			http_response_code(404);
			echo 'ERROR: INVALID AUTH PARAMETERS.';
			exit;
		}

		$this->username = $username;
		$this->password = $password;
	}
}