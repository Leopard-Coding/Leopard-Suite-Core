<?php
/**
 * Core class
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */ 
namespace LSC\lib\system;

use LSC\lib\extension\Leopard\HuntingRouter,
	LSC\lib\system\error\ErrorHandler,
	LSC\lib\system\autoload\AutoloadHandler;

class LSC
{
	private static $AutoloadHandler;
	private static $ErrorHandler;
	
	public static function loadAutoloadHandler()
	{
		require_once(__LSC_ABSOLUTE_DIR__.'lib/system/autoload/AutoloadHandler.class.php');
		self::$AutoloadHandler = new AutoloadHandler;
		
		return;
	}
	
	public static function loadErrorHandler()
	{
		self::$ErrorHandler = new ErrorHandler;
		
		return;
	}
	
	public static function loadRouting()
	{
		$Routing = HuntingRouter::startRouting();
		
		return;
	}
}
