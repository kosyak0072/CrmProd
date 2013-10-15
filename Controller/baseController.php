<?php
abstract class baseController
{
	public $view;
	public $pdo;

	function __construct()
	{
		$this->view = View::getInstance();
		$this->pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));
	}

}