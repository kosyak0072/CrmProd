<?php

/**
 * class MySQL
 * MySQL Client
 * @author Saymon
 */
class MySQL
{
	/**
	 * @var string
	 */
	protected $_strDbHost;
	protected $_strDbUser;
	protected $_strDbPass;
	protected $_strDbName;
	
	/**
	 * @var resource
	 */
	protected $_connectionId;
	
	public function __construct()
	{
		if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASS') || !defined('DB_NAME')) {
			throw new Exception('One or more of MySQL DB Setings is not defined');
		}
		
		$this->_strDbHost = DB_HOST;
		$this->_strDbUser = DB_USER;
		$this->_strDbPass = DB_PASS;
		$this->_strDbName = DB_NAME;
	}
	
    protected function _connect()
	{
		$connectionId = mysql_connect($this->_strDbHost, $this->_strDbUser, $this->_strDbPass);
		
		if(!$connectionId) {
			throw new Exception('can\'t conect to MySQL Server: "' . $this->_strDbHost 
								. '" for user "' . $this->_strDbUser 
								. '" with password "' . $this->_strDbPass . '"!');
		}
		
		if(!@mysql_select_db($this->_strDbName)) {
			throw new Exception('Successfully coneected to MySQL Server: "' . $this->_strDbHost 
								. '" for user "' . $this->_strDbUser 
								. '" with password "' . $this->_strDbPass 
								. '"! But can\'t select database: "' . $this->_strDbName . '"');
		}
		$this->_connectionId = $connectionId;
	}

	protected function _close()
	{
		if(!@mysql_close($this->_connectionId)) {
		  throw new Exception('Can\'t close MySQL connection!');
		}
	}

	protected function _query($sqlQuery, $boolReturnInsertId = false)
	{
		$sqlResult = mysql_query($sqlQuery, $this->_connectionId);
		if ($boolReturnInsertId)
			$sqlResult = mysql_insert_id();
		$sqlErrorMessage = mysql_error();
		if($sqlErrorMessage)
		{
		    $this->_close();
            throw new Exception('MySQL Error: ' . $sqlErrorMessage . ' for SQL Query: ' . $sqlQuery);
		}
		return $sqlResult;
	}
	
	public function exec($sqlQuery, $boolReturnInsertId = false)
	{
	    $this->_connect();
	    $sqlResult = $this->_query($sqlQuery, $boolReturnInsertId);
	    $this->_close();
	    return $sqlResult;
	}
	
	/**
     * Singleton instance
     * @var MySQL
     */
    protected static $_objInstance = NULL;
    
    /**
     * Get singleton instance
     * @return MySQL
     */
    public static function getInstance()
    {
        if (is_null(self::$_objInstance)) {
            self::$_objInstance = new self();
        }
        return self::$_objInstance;
    }
	
}