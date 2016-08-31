<?php
namespace LSC\lib\system\plugin;

use LSC\lib\system\database\Database,
	LSC\lib\system\util\FileUtil;

class PluginAction extends PluginEditor
{
	private static $InstallPath = __LSC_ABSOLUTE_DIR__.'install/';
	
	public static function addPlugin($AddData)
	{
		parent::add($AddData);
		
		return;
	}
	
	public static function changePlugin($ChangeData, $Where)
	{
		parent::change($ChangeData, $Where);
		
		return;
	}
	
	public static function deletePlugin($Where)
	{
		parent::delete($Where);
		
		return;
	}
	
	private static function getInfo()
	{
		require(self::$InstallPath.'Plugin.class.php');
		$PluginInfo = LSC\install\Plugin::$Plugin;
		
		return $PluginInfo;
	}
	
	private static function installDatabase($PluginInfo)
	{
		$DatabaseFile = self::$InstallPath.$PluginInfo['pluginFiles']['databaseFile'];
		require($DatabaseFile);
		
		return;
	}
	
	public static function installPlugin($ArchiveIdentifier)
	{
		#
		
		return;
	}
}
