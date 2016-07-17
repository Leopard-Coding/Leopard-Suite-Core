<?php
/**
 * Autoloads classes through use-blocks
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */ 
namespace LSC\lib\system\autoload;
 
class AutoloadHandler
{
    private $Namespace;

	/**
	 * Sets handlers and activates error-reporting if debug-mode is active
	 *
	 * @param string $Namespace Contains namespace-base
	 *
	 * @uses $this->Namespace Variable contains namespace-base to include classes
	 * @uses $this->registerAutoLoader() Registers AutoLoader
	 *
	 * @return void
	 */ 
    public function __construct($Namespace = 'LSC')
    {
	    $this->Namespace = $Namespace;
	    $this->registerAutoLoader();
	    
	    return;
    }

	/**
	 * Registers AutoLoader
	 *
	 * @return void
	 */ 
    private function registerAutoLoader()
    {
	    spl_autoload_register(array($this, 'loadClass'));
	    
	    return;
    }

	/**
	 * Loads class throug use-block and namespace
	 *
	 * @param string $ClassName Contains namespace and classname
	 *
	 * @uses $this->Namespace Variable contains namespace-base to include classes
	 *
	 * @return boolean Returns true on success, false on failure
	 */ 
    public function loadClass($ClassName)
    {
	    if ($this->Namespace !== null) {
	    	$ClassName = str_replace($this->Namespace.'\\', '', $ClassName);
	    }
        $ClassName = str_replace('\\', DIRECTORY_SEPARATOR, $ClassName);

	    $File = __LSC_ABSOLUTE_DIR__.$ClassName.'.class.php';
		
	    if(file_exists($File))
	    {
	        require_once($File);
	        
	        return true;
	    } else {
	    	return false;
	    }
    }
}
