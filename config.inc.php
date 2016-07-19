<?php
/**
 * Initiates everything
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */
namespace LSC;

use LSC\lib\system\LSC,
	LSC\lib\extension\Leopard\HuntingRouter,
	LSC\lib\system\database\Database;

ob_start();
session_start();

define('__LSC_ABSOLUTE_DIR__', str_replace('\\', '/', dirname(__FILE__)).'/');
define('__LSC_INSTALLATION_NUMBER__', 1);

require_once(__LSC_ABSOLUTE_DIR__.'lib/system/LSC.class.php');
LSC::loadAutoloadHandler();
LSC::loadErrorHandler();
LSC::loadRouting();

define('__LSC_DIR__', HuntingRouter::getURL());
define('__LSC_RELATIVE_DIR__', HuntingRouter::getCurrentPath(true));

Database::connect();
