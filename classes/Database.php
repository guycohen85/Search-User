<?php
class Database
{
	private $host = 'localhost';
	private $username = 'root';
	private $password = '';
	private $db = 'qmarkets';

	private static $instance = null;
	public $connection;

	private function __construct()
	{
		try {
			$this->connection = new PDO("mysql:host=$this->host;dbname=$this->db", $this->username, $this->password);
			// set the PDO error mode to exception
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// echo 'Connection success';
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	}

	public static function get_instance()
	{
		if (self::$instance == null) {
			self::$instance = new Database();
		}

		return self::$instance;
	}
}
