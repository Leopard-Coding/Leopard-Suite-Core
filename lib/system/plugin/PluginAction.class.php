<?php
namespace LSC\lib\system\plugin;

use LSC\lib\system\database\Database;

class PluginAction extends PluginEditor
{
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
	
	private static function readPackageInfo($PackagePath)
	{
		require(__LSC_ABSOLUTE_DIR__.$PackagePath.'package.php');
		
		return $PackageInfo;
	}
	
	private static function executeDatabaseFile($PackagePath, $PackageInfo)
	{
		$DatabaseFile = __LSC_ABSOLUTE_DIR__.$PackagePath.$PackageInfo['pluginFiles']['databaseFile'];
		require($DatabaseFile);
		
		return;
	}
	
	public static function installPlugin()
	{
		$PackagePath = 'install/';
		$PackageInfo = self::readPackageInfo($PackagePath);
		self::executeDatabaseFile($PackagePath, $PackageInfo);
		
		return;
	}
}
