<?php
class IndexController extends baseController
{
	protected $_strAction = '';
	protected $_arrParams = array();
	public $renderer = NULL;

	public function nameAction()
	{
		if( isset($_POST['but']) )
		{
			$pass = md5($_POST['pass']);
			$login = $this->pdo->quote($_POST['login']);
			$result = $this->pdo->query("SELECT userStatus, userName, userID FROM crmuser where userPass='$pass' AND userLogin=$login")->fetch(PDO::FETCH_ASSOC);
			if( !empty($result) && $result['userStatus'] == 1)
			{
				$_SESSION['user'] = $result['userName'];
				$_SESSION['userID'] = $result['userID'];
			}
		}
		View::getInstance()->render();
	}


	public  function regAction()
	{
		if( isset($_POST['reg']) )
		{
			//проверить данные
			if( empty($_POST['fio']) || empty($_POST['login']) || empty($_POST['pass']) || empty($_POST['pass1']) || empty($_POST['email']) || empty($_POST['mobilePhone']) )
			{
				$this->view->error = "Не все поля заполены";
				View::getInstance()->render();
				return ;
			}
			if( empty($_POST['spec']) )
			{
				$this->view->error = "Выберите хотябы 1 специализацию";
				View::getInstance()->render();
				return ;
			}
			if( $_POST['pass'] != $_POST["pass1"] )
			{
				$this->view->error = "Пароли не совпадают";
				View::getInstance()->render();
				return ;
			}

			preg_match('/(\+38[0-9]{10})/',$_POST['mobilePhone'],$rr);
			if( empty($rr[1]) )
			{
				$this->view->error = "телефон не в верном формате. Пример - +380951324567";
				View::getInstance()->render();
				return ;
			}
			$ins = "";
			$ins .= $this->pdo->quote($_POST['fio']);
			$ins .= ','.$this->pdo->quote($_POST['login']);
			$ins .= ','.$this->pdo->quote(md5($_POST['pass']));
			$ins .= ','.$this->pdo->quote($_POST['email']);
			$ins .= ','.$this->pdo->quote($_POST['mobilePhone']);
			$ins .= $_POST['phone'] ? ','.$this->pdo->quote($_POST['phone']) : ",''";
			$ins .= $_POST['web'] ? ','.$this->pdo->quote($_POST['web']) : ",''";
			$ins .= $_POST['ICQ'] ? ','.$this->pdo->quote($_POST['ICQ']) : ",''" ;
			$ins .= $_POST['skype'] ? ','.$this->pdo->quote($_POST['skype']) : ",''";
			$ins .= $_POST['adress'] ? ','.$this->pdo->quote($_POST['adress']) : ",''";
			$ins .= ",'".date("d.m.Y")."'";
			// специальность
			$insProf = "";
			$insProfValue = "";

			foreach ($_POST['spec'] as $val)
			{
				$tmp = "";
				if( empty($val) ) continue;
				$tmp = explode("|",$val);
				$insProf .= !empty($insProf) ?  ", ".$tmp[0] : "".$tmp[0];
				$insProfValue .= !empty($insProfValue) ?  ",".$this->pdo->quote($tmp[1]) : "".$this->pdo->quote($tmp[1]);
			}
			$user = $this->pdo->exec("INSERT INTO crmuser (userName,userLogin,userPass,userEmail,userMobilePhone,userPhone,userWeb,userIcq,userSkype,userAdress,userDataAdd,userStatus) VALUE ($ins,0)");
			if( $this->pdo->errorCode()==23000 )
			{
				$this->view->error = "Извините, но такой пользователь уже существует";
				View::getInstance()->render();
				return ;
			}
			$lastID = $this->pdo->lastInsertId();
			$this->pdo->exec("INSERT INTO crmautorproffesion (userID,$insProf) VALUE ($lastID,$insProfValue)");
			$this->view->reg = true;
		}
		View::getInstance()->render();
	}

	public function recoveryAction()
	{
		if( isset($_POST['recovery']) )
		{
			$email = $this->pdo->quote($_POST['recEmail']);
			$login = $this->pdo->quote($_POST['recLogin']);
			$phone = $this->pdo->quote($_POST['recPhone']);
			$result = $this->pdo->query("SELECT userID FROM crmuser where userMobilePhone=$phone AND userLogin=$login AND userEmail=$email")->fetch(PDO::FETCH_ASSOC);
			if( !empty($result) ){
				$this->view->recSuccess = "Пользователь был найден.";
				$_SESSION['recUserID'] = $result['userID'];
			}
			else
				$this->view->error = "Такого пользавателя небыло найдено";
		}

		if( isset($_POST['recNewPass']) && !empty($_POST['recNewPass']) )
		{
			$pass = md5($_POST['recNewPass']);
			$result = $this->pdo->exec("UPDATE crmuser SET userPass='$pass' WHERE userID = {$_SESSION['recUserID']}");
			if($result)
			{
				unset($_SESSION['recUserID']);
				View::getInstance()->setTemplate("name", "User");
				View::getInstance()->setAction("nameAction", "IndexController");
			}
			else
			{
				$this->view->error = "Введите пароль!";
			}
		}
		View::getInstance()->render();
	}

	public function searchAction()
	{
		if( isset($_POST['search']) && !empty($_POST['spec']) )
		{
			$searchProf = "";
			foreach ($_POST['spec'] as $val)
			{
				$tmp = "";
				if( empty($val) ) continue;
				$tmp = explode("|",$val);
				$searchProf .= (!empty($searchProf) ? "OR " : ""). "{$tmp[0]} = {$this->pdo->quote($tmp[1])}";
			}
			$this->view->autors = $this->pdo->query("select t1.* FROM crmuser AS t1 LEFT JOIN crmautorproffesion AS t2 ON t1.userID=t2.userID WHERE $searchProf")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($this->view->autors as &$val)
			{
				$val['disciplins'] = $this->pdo->query("select * FROM crmautorproffesion where userID = ".$val['userID'])->fetch(PDO::FETCH_ASSOC);
			}
			$this->view->searchActive = true;
			View::getInstance()->setTemplate("main", "User");
			View::getInstance()->render();
			exit;
		}

		$this->view->searchActive = true;
		View::getInstance()->render();
	}

	public function newUserAction()
	{
		$this->view->autors = $this->pdo->query("select * FROM crmuser WHERE userStatus=0")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($this->view->autors as &$val)
		{
			$val['disciplins'] = $this->pdo->query("select * FROM crmautorproffesion where userID = ".$val['userID'])->fetch(PDO::FETCH_ASSOC);
		}
		View::getInstance()->setTemplate("main", "User");
		View::getInstance()->render();
	}
	

	public function mainAction()
	{/*FIX ME prepare*/
		if(!empty($_POST['ajax']) && !empty($_POST['action']) && $_POST['action'] == 'save')
		{//D("UPDATE crmuser SET userName=".$this->pdo->quote($_POST['fio']).', userEmail='.$this->pdo->quote($_POST['email']).' ,userStatus='.$this->pdo->quote($_POST['groupSelect']).', userAdress='.$this->pdo->quote($_POST['address']).', userTown='.$this->pdo->quote($_POST['town'])." where userID=".$_POST['userID'],1);

			$query = 'userName=?,
		            userEmail=?,
		            userStatus=?,
					userAdress=?';
			$params = array(
					$_POST['fio'],
					$_POST['email'],
					$_POST['groupSelect'],
					$_POST['address']
			);
			$where = "WHERE userID = ".$_POST['userID'];
			$result = $this->pdo->exec("UPDATE crmuser SET userName=".$this->pdo->quote($_POST['fio']).', userEmail='.$this->pdo->quote($_POST['email']).' ,userStatus='.$this->pdo->quote($_POST['groupSelect']).', userAdress='.$this->pdo->quote($_POST['address'])." where userID=".$_POST['userID']);
			//$this->pdo->prepare("UPDATE crmuser SET $query $where");
			//$this->pdo->execute($params);
			if($result)
			{
				 if( $_POST['groupSelect'] == 3 )
				 {
				 	$to = $_POST['email'];
					$from = "dipsverka@gmail.com";
					$headers    = array
				    (
				        'Content-Type: text/html; charset="UTF-8";',
				        'From: ' . $from,
				        'Reply-To: ' . $from
				    );
				    $msg = "<html><body>";
				    $msg .= "<p>Вы были приняты в ситему DipSverka.</p>";
				    $msg .= "</html></body>";
					mail($to, 'DipSverka', $msg, implode("\n", $headers));
				 }
				die('OK');
			}
			else die('ERROR');
		}
		else if (!empty($_POST['ajax']) && !empty($_POST['action']) && $_POST['action'] == 'delete')
		{
			$where = "WHERE userID = ".$_POST['userID'];
			$result = $this->pdo->exec("DELETE FROM crmuser $where");
			if($result) die('OK');
			else die('ERROR');
		}
		if( isset($_GET['search']) )
		{

			$this->view->autors = $this->pdo->query("select * FROM crmuser  WHERE userName = {$this->pdo->quote($_GET['search'])} OR userEmail = {$this->pdo->quote($_GET['search'])} OR userMobilePhone = {$this->pdo->quote($_GET['search'])} ")->fetchAll(PDO::FETCH_ASSOC);
			foreach ($this->view->autors as &$val)
			{
				$val['disciplins'] = $this->pdo->query("select * FROM crmautorproffesion where userID = ".$val['userID'])->fetch(PDO::FETCH_ASSOC);
			}
			$this->view->searchActive = true;
			View::getInstance()->render();
			exit ;
		}
		if( empty($_SESSION['user']) ) header("Location: http://dipsverka.com/User/Index/Name");
		$this->view->autors = $this->pdo->query("select * FROM crmuser")->fetchAll(PDO::FETCH_ASSOC);
		foreach ($this->view->autors as &$val)
		{
			$val['disciplins'] = $this->pdo->query("select * FROM crmautorproffesion where userID = ".$val['userID'])->fetch(PDO::FETCH_ASSOC);
		}
		View::getInstance()->render();
	}

	public  function orderlistAction()
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
			$query = 'orderName=?,
		            predmet=?,
		            predmetType=?,
		            orderCount=?,
					listSource=?,
					orderDtEnd=?,
					orderAddInform=?,
					customerName=?,
					customerEmail=?,
					customerPhone=?';
			$params = array(
					$_POST['orderName'],
					$_POST['orderPredmet'],
					$_POST['OrderType'],
					$_POST['orderCount'],
					$_POST['orderListSource'],
					$_POST['orderDtEnd'],
					$_POST['orderAddInform'],
					$_POST['customerName'],
					$_POST['customerEmail'],
					$_POST['cutomerPhone']
			);
			$where = "WHERE orderID =".$_POST['orderID'];
			$updateOrder = $this->pdo->prepare("UPDATE crmOrder SET $query $where");
			//$result = $this->pdo->exec("UPDATE crmOrder SET orderName='1' $where");
			$result = $updateOrder->execute($params);
			if($result)
				die('OK');
			else
				die('Error');
		}
		else if( !empty($_POST['ajax']) && $_POST['ajax'] == 'delete' )
		{
			$result = $this->pdo->exec("DELETE FROM crmOrder WHERE orderID=".$_POST['orderID']);
			if($result)
				die('OK');
			else
				die('Error');
		}

		$this->view->orders = $this->pdo->query("SELECT * FROM crmOrder")->fetchAll(PDO::FETCH_ASSOC);
		View::getInstance()->render();
	}

	public function orderAction()
	{
		if( isset($_POST['order']) )
		{
			if( empty($_POST['site']) )
			{
				$this->error = "Неверная форма обратитесь к владельцу сервиса";
			}
			$_SESSION['site'] = $_POST['site'];

			if( empty($_POST['orderName']) || empty($_POST['predmet']) || empty($_POST['predmetType']) || empty($_POST['orderCount']) || empty($_POST['listSource'])
			|| empty($_POST['orderDtEnd']) || !isset($_POST['addInf']) || empty($_POST['customerName']) || empty($_POST['customerEmail']) || empty($_POST['cutomerPhone']) )
			{
				$this->view->error = "Не все поля заполены";
				View::getInstance()->render();
				return ;
			}
			if( !is_numeric($_POST['orderCount'])  && !is_numeric($_POST['listSource']) )
			{
				$this->view->error = "в полях не верный формат данных";
				View::getInstance()->render();
				return ;
			}
			preg_match('#[0-9]{2}.[0-9]{2}.[0-9]{4}#', $_POST['orderDtEnd'],$timeEnd);
			if( empty($timeEnd[0]) )
			{
				$this->view->error = "Дата в неправильном формате";
			}

			$tmpTime = explode(".", $timeEnd[0]);
			$day = $tmpTime[0];
			$month = $tmpTime[1];
			$year = $tmpTime[2];
			if( $day <= 31 && $month <= 12 && $year >= 2013 )
			{
				$query = 'orderName,
			            predmet,
			            predmetType,
			            orderCount,
						listSource,
						orderDtAdd,
						orderDtEnd,
						orderAddInform,
						customerName,
						customerEmail,
						customerPhone,
						orderGetSite,
						orderStatus';
				$params = array(
						$_POST['orderName'],
						$_POST['predmet'],
						$_POST['predmetType'],
						$_POST['orderCount'],
						$_POST['listSource'],
						date("d.m.Y"),
						$timeEnd[0],
						$_POST['addInf'],
						$_POST['customerName'],
						$_POST['customerEmail'],
						$_POST['cutomerPhone'],
						$_POST['site'],
						0
				);
				$insorder = $this->pdo->prepare("INSERT INTO crmOrder ($query) VALUE (?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$succes = $insorder->execute($params);
				unset($_SESSION['site']);
				//@mkdir("files", 0777);
				
				if($succes)
				{
					$to = $_POST['customerEmail'];
					$from = $_POST['site'] == 'http://www.diplomkin.com.ua/' ? "diplomkin@gmail.com" : ($_POST['site']== 'http://vipdiplom.kharkov.ua/' ? 'vipdiplom2011@gmail.com' : ( $_POST['site'] == 'http://www.alldp.com.ua/' ? "alldp.com.ua@gmail.com": "alldp.com.ua@gmail.com" ));

					$headers    = array
				    (
				        'Content-Type: text/html; charset="UTF-8";',
				        'From: ' . $from,
				        'Reply-To: ' . $from
				    );
				    $msg = "<html><body>";
				    $msg .= "<p>Ваша заявка была принята. В близжайшее время наш менеждер свяжится с вами.</p>";
				    $msg .= "</html></body>";
					mail($to, 'Заказ работы', $msg, implode("\n", $headers));
					if( !empty($_FILES['file']['tmp_name']) )
						copy($_FILES['file']['tmp_name'], 'files/'.$_FILES['file']['name']);
					$this->view->success = "Спасибо за заказ. В ближайшее время наш менеждер свяжится с вами.";
				}
			}
			else
				$this->view->error = "Дата в неправильном формате";
		}
		View::getInstance()->render();
	}

	public  function logoutAction()
	{
		session_unset();
		header( 'Location: /' );
	}

	function fixEncoding($in_str)
	{
		$cur_encoding = mb_detect_encoding($in_str) ;
		if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
			return $in_str;
		else
			return utf8_encode($in_str);
	}

	protected static $_objInstance = NULL;
	/**
	 * Get singleton instance
	 * @return View
	 */
	public static function getInstance()
	{
		if (is_null(self::$_objInstance)) {
			self::$_objInstance = new self();
		}
		return self::$_objInstance;
	}
}
?>