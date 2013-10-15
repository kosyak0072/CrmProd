<?php 
class CRM
{
	public $title;
	public $url;
	public $pdo;
	
	function __construct()
	{
		session_start();
		$this->url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : "";
		Dispatcher::getInstance()->dispatch($this->url);
		
	}

}
?>