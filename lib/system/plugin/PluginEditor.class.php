<?php
namespace LSC\lib\system\plugin;

use LSC\lib\system\database\Database;

class PluginEditor
{
	public static function add($AddData)
	{
		Database::insert('lsc_core', 'plugin', $AddData);
		
		return;
	}
	
	public static function change($ChangeData, $Where)
	{
		Database::update('lsc_core', 'plugin', $ChangeData, $Where);
		
		return;
	}
	
	public static function delete($Where)
	{
		Database::delete('lsc_core', 'plugin', $Where);
		
		return;
	}
}
