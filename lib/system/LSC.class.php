<?php
namespace LSC\lib\system;

use LSC\lib\extension\Leopard\HuntingRouter,
	LSC\lib\system\handler\ErrorHandler,
	LSC\lib\system\handler\AutoloadingHandler,
	LSC\lib\system\database\Database;

class LSC
{
	private static $AutoloadingHandler;
	private static $ErrorHandler;
	
	public function __construct()
	{
		self::loadAutoloadingHandler();
		self::loadErrorHandler();
		self::loadRouting();
		self::connectToDatabase();
		
		return;
	}
	
	private static function loadAutoloadingHandler()
	{
		require_once(__LSC_ABSOLUTE_DIR__.'lib/system/handler/AutoloadingHandler.class.php');
		self::$AutoloadingHandler = new AutoloadingHandler('LSC');
		
		return;
	}
	
	private static function loadErrorHandler()
	{
		self::$ErrorHandler = new ErrorHandler;
		
		return;
	}
	
	private static function loadRouting()
	{
		$Routing = HuntingRouter::startRouting();
		define('__LSC_DIR__', HuntingRouter::getURL());
		define('__LSC_RELATIVE_DIR__', HuntingRouter::getCurrentPath(true));
		if (!$Routing) {
			#404
			return false;
		}
		
		return true;
	}
	
	private static function connectToDatabase()
	{
		Database::connect();
		
		return;
	}
}
