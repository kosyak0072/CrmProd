<?php
class View
{
	public $_strTemplateFileName = '';
	public $_strNameSpace = '';

	public function setTemplate($strFileName, $strNameSpace)
	{
		$this->_strTemplateFileName = $strFileName;
		$this->_strNameSpace = $strNameSpace;
	}

	public function setAction($strMethodName, $strNameConlroller)
	{
		Dispatcher::getInstance()->dispatch("/User/Index/name");
		require_once APP_FOLDER . "/User/Controllers/" . $strNameConlroller . '.php';
		$strNameConlroller::getInstance()->$strMethodName();
	}

	public function render()
	{
		$this->strTemplateFilePath = APP_FOLDER . DIRECTORY_SEPARATOR . $this->_strNameSpace . DIRECTORY_SEPARATOR. 'View' . DIRECTORY_SEPARATOR . $this->_strTemplateFileName . '.phtml';
		$strListFolePath = APP_FOLDER . DIRECTORY_SEPARATOR . 'list.phtml';
		try{
			if (!file_exists($this->strTemplateFilePath)) {
				throw new Exception('action not found"' . $this->strTemplateFilePath . '"');
			}
			else
			{
				include $strListFolePath;
			}

		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
	}

	public function url($url)
	{
		return DOMAIN . $url;
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