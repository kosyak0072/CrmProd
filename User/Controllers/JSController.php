<?php
class JSController extends baseController
{
	protected $_strAction = '';
	protected $_arrParams = array();
	public $renderer = NULL;

	public function getNewTaskUserAction()
	{
		if( !empty($_POST['ajax']) )
		{
			$newuser = $this->pdo->query('SELECT COUNT(*) AS count FROM crmuser WHERE userStatus=0')->fetch(PDO::FETCH_ASSOC);
			$newTask = $this->pdo->query('SELECT COUNT(*) AS count FROM crmOrder WHERE orderStatus=0')->fetch(PDO::FETCH_ASSOC);
			die("OK|{$newuser['count']}|{$newTask['count']}");
		}
	}
}