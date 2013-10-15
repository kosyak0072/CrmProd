<?php

/**
 * Customer
 * @author Saymon
 */
class Customer
{
	protected $_tableName = 'customer';
	/**
	 * @return array
	 */
	
	public function __construct()
	{
		$this->installTable();
	}
	
	public function installTable()
	{
		if (!$this->isTableExixst()) {
			MySQL::getInstance()->exec(
									   "CREATE TABLE `customer` (
									   `cstmr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
									   `cstmr_name` varchar(255) NOT NULL DEFAULT '',
									   PRIMARY KEY (`cstmr_id`)
									   ) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8"
									   );
			header('location: /');
		}
	}
	
	/**
	 * @return boolean
	 */
	public function isTableExixst()
	{
		$sqlRes = MySQL::getInstance()->exec('SHOW TABLES FROM `' . DB_NAME . '`');
		while (list($strTableName) = mysql_fetch_row($sqlRes)) {
			if ($this->_tableName == $strTableName) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public function getAll()
	{
		$arrCustomers = array();
		$sqlRes = MySQL::getInstance()->exec('SELECT * FROM ' . $this->_tableName);
		
		while (list($id, $strName) = mysql_fetch_row($sqlRes)) {
			$arrCustomers[$id] = $strName;
		}
		
		return $arrCustomers;
	}
	
	/**
	 * @param string $strName
	 */
	public function add($strName)
	{
		MySQL::getInstance()->exec('INSERT INTO ' . $this->_tableName . ' (cstmr_name) VALUES (\'' . $strName . '\')');
	}
	
	/**
	 * @param int $id
	 * @param string $strName
	 */
	public function update($id, $strName)
	{
		MySQL::getInstance()->exec('UPDATE ' . $this->_tableName . ' SET cstmr_name = \'' . $strName . '\' WHERE cstmr_id = ' . $id);
	}
	
	/**
	 * @param int $id
	 */
	public function delete($id)
	{
		MySQL::getInstance()->exec('DELETE from ' . $this->_tableName . ' WHERE cstmr_id = ' . $id);
	}
	
}