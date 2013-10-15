<?php

class Renderer
{
	/**
	 * @var string
	 */
	protected $_strTemplateFileName = '';
	
	/**
	 * @var string
	 */
	protected $_strNameSpace = '';
	
	/**
	 * @param string $strFileName
	 * @return Renderer
	 */
	public function setTemplate($strFileName, $strNameSpace)
	{
		$this->_strTemplateFileName = $strFileName;
		$this->_strNameSpace = $strNameSpace;
		return $this;
	}
	
	public function render()
	{
		$strTemplateFilePath = APP_FOLDER . DIRECTORY_SEPARATOR . $this->_strNameSpace . DIRECTORY_SEPARATOR. 'View' . DIRECTORY_SEPARATOR . $this->_strTemplateFileName . '.phtml';
		try{
			if (!file_exists($strTemplateFilePath)) {
			throw new Exception('представление не найдено"' . $strTemplateFilePath . '"');
			}
			else
			{	
				//дописать через 1 аблон хедера и футера
				include $strTemplateFilePath;
			}
				 
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	/**
     * Singleton instance
     * @var Renderer
     */
    protected static $_objInstance = NULL;
    
    /**
     * Get singleton instance
     * @return Renderer
     */
    public static function getInstance()
    {
        if (is_null(self::$_objInstance)) {
            self::$_objInstance = new self();
        }
        return self::$_objInstance;
    }
}