<?php
class OrderController extends baseController
{
	protected $_strAction = '';
	protected $_arrParams = array();
	public $renderer = NULL;

	public function newOrderAction()
	{
			$this->view->orders = $this->pdo->query("SELECT * FROM crmOrder WHERE orderStatus=0")->fetchAll(PDO::FETCH_ASSOC);
			View::getInstance()->setTemplate("orderlist", "User");
			View::getInstance()->render();
	}

	public  function orderlisAction()
	{
		if( !empty($_GET['search']) )
		{
			if( is_numeric($_GET['search']) )
			{
				$this->view->orders = $this->pdo->query('SELECT * FROM crmOrder WHERE orderID ='.$_GET["search"].' LIMIT 1')->fetchAll(PDO::FETCH_ASSOC);
				$this->view->searchActive = true;
			}
			else $this->view->orders = "";
			View::getInstance()->render();
			exit;
		}
		if( !empty($_POST['ajax']) && $_POST['ajax'] == 'edit')
		{
			die('OK');
			
		}
		else if( !empty($_POST['ajax']) && $_POST['ajax'] == 'delete' )
		{
			
				die('OK');
			
		}

		$this->view->orders = $this->pdo->query("SELECT * FROM crmOrder")->fetchAll(PDO::FETCH_ASSOC);
		View::getInstance()->render();
	}
}