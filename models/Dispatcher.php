<?php

/**
 * @author Saymon
 */
class Dispatcher
{
	
	/**
	 * @param string $strAction
	 * @param array $arrParams
	 * @throws Exception
	 */
	
	public function dispatch($url)
	{
		if(empty($url))
		{
			$strNameSpace = "User";
			$strNameConlroller = "Index" . 'Controller';
			$strMethodName = "name" . 'Action';
		}
		else 
		{
			preg_match_all("#\/([a-z]+)#i", $url,$out);
			try
			{
				if(count($out[1]) != 3) throw new Exception('вы ввели неверный url');	
			}
			catch (Exception $e)
			{
				die ($e->getMessage());
			}
			$strNameSpace = $out[1][0];
			$strNameConlroller = $out[1][1] . 'Controller';
			$strMethodName = $out[1][2] . 'Action';				
		}
		try 
		{
			if( !file_exists( APP_FOLDER . "/" . $strNameSpace ) ) throw new Exception("not found this NameSpace");
			if( !file_exists( APP_FOLDER . "/" . $strNameSpace . '/Controllers/' . $strNameConlroller . '.php' ) ) throw new Exception("not fount this Controller");
			else
				require_once APP_FOLDER . "/" . $strNameSpace . '/Controllers/' . $strNameConlroller . '.php';
			if( !method_exists($strNameConlroller, $strMethodName) )  throw new Exception("method exist");
		}
		catch (Exception $e)
		{
			die( $e->getMessage() );
		}
		View::getInstance()->setTemplate(isset($out[1][2]) ? $out[1][2] : "name", isset($out[1][0]) ? $out[1][0] : "User");
		$controller  = new $strNameConlroller();
		$controller->$strMethodName();		
	}
	
	/**
     * Singleton instance
     * @var Dispatcher
     */
	protected static $_objInstance = NULL;
    
    /**
     * Get singleton instance
     * @return Dispatcher
     */
    public static function getInstance()
    {
        if (is_null(self::$_objInstance)) {
            self::$_objInstance = new self();
        }
        return self::$_objInstance;
    }
    
}