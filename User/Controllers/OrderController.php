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
}