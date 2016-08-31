<?php
namespace LSC\lib\system\handler;
 
class AutoloadingHandler
{
    private $Namespace;

    public function __construct($Namespace)
    {
	    $this->Namespace = $Namespace;
	    $this->registerAutoLoader();
	    
	    return;
    }

    private function registerAutoLoader()
    {
	    spl_autoload_register(array($this, 'loadClass'));
	    
	    return;
    }

    public function loadClass($ClassName)
    {
	    if ($this->Namespace !== null) {
	    	$ClassName = str_replace($this->Namespace.'\\', '', $ClassName);
	    }
        $ClassName = str_replace('\\', DIRECTORY_SEPARATOR, $ClassName);

	    $File = __LSC_ABSOLUTE_DIR__.$ClassName.'.class.php';
		
	    if(file_exists($File)) {
	        require_once($File);
	    } else {
			try {
				throw new \Exception('File '.__LSC_ABSOLUTE_DIR__.$ClassName.'.class.php does not exist!');
			} 
			catch (Exception $e) {
				throw $e;
			}
		}
    }
}
