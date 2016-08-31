<?php
namespace LSC;

use LSC\lib\system\LSC,
	LSC\lib\system\plugin\PluginAction;

session_start();

define('__LSC_ABSOLUTE_DIR__', str_replace('\\', '/', dirname(__FILE__)).'/');
define('__LSC_INSTALLATION_NUMBER__', 1);

require_once(__LSC_ABSOLUTE_DIR__.'lib/system/LSC.class.php');
new LSC;

PluginAction::installPlugin('com.leopard-coding.lsc_v0.1.0-alpha.zip');
