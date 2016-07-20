<?php
namespace LSC\lib\system\database;

use PDO;

class Database
{
	public static $InsertId;
	private static $Connection;
	
	public static function connect()
	{
		require_once(__LSC_ABSOLUTE_DIR__.'dbconfig.inc.php');
		try {
			self::$Connection = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPassword, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return;
	}
	
	public static function exec($Query)
	{
		try {
			$Num = self::$Connection->exec($Query);
			self::$InsertId = self::$Connection->lastInsertId();
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return $Num;
	}
	
	public static function query($Query)
	{
		try {
			$Num = self::$Connection->query($Query);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return $Num;
	}
	
	public static function execute($Statement)
	{
		try {
			$Statement->execute();
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return;
	}
	
	public static function prepare($Query)
	{
		try {
			$Statement = self::$Connection->prepare($Query);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return $Statement;
	}
	
	public static function bindValue($Statement, $Placeholder, $Variable, $Type)
	{
		try {
			$Statement->bindValue($Placeholder, $Variable, $Type);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return;
	}
	
	public static function escape($String, $StripTags = false)
	{
		if((bool) $StripTags) {
			$String = strip_tags($String);
		}
		$String = self::$Connection->quote($String);
		$String = substr($String, 1, strlen($String) - 2);
		
		return $String;
	}
	
	public static function insert($Application, $Table)
	{
		
	}
	
	public static function delete($Application, $Table, $Where = '')
	{
		$Query = 'DELETE FROM '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.$Where;
		try {
			$Num = self::exec($Query);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return $Num;
	}
	
	public static function select($Application, $Tables, $Where = '', $Columns = '*', $All = false)
	{
		$Query = 'SELECT '.$Columns.' FROM '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.$Where;
		try {
			if(!(bool) $All) {
				$Result = self::fetch($Query);
		
				return $Result;
			} else {
				$Results = self::fetch($Query, true);
		
				return $Results;
			}
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public static function update($Application, $Table, array $SetData, $Where = '')
	{
		$SetString = '';
		foreach ($SetData as $SetClause) {
			$SetString .= $SetClause.', ';
		}
		$SetString = substr($SetString, 0, 2);
		$Query = 'UPDATE '.$Application.'_'.$Table.' SET '.$SetString.$Where;
		try {
			$Num = self::exec($Query);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return $Num;
	}
	
	public static function fetch($Query, $All = false, $Statement = null)
	{
		try {
			if ($Statement === null) {
				$Statement = self::query($Query);
			}
			if (!(bool) $All) {
				$Result = $Statement->fetchAll($FetchStyle)[0];
			
				return $Result;
			} else {
				$Results = $Statement->fetchAll($FetchStyle);
		
				return $Results;
			}
		} catch (\PDOException $e) {
			throw $e;
		}
	}
	
	public static function addTable($Application, $Table, array $Columns, $DropTable = true, array $Keys = [])
	{
		$ColumnString = '';
		$KeyString = '';
		if (count($Columns) == 0) {
			try {
				throw new Exception('At least one column needed');
			}
			catch (\Exception $e) {
				throw $e;
			}
		}
		foreach ($Columns as $Column) {
			$ColumnString .= $Column.', ';
		}
		$ColumnString = substr($ColumnString, 0, -2);
		if (count($Keys) > 0) {
			foreach ($Keys as $Key) {
				$ColumnString .= $Key.', ';
			}
			$KeyString = substr($KeyString, 0, -2);
		}
		try {
			if ($DropTable) {	
				self::dropTable($Application, $Table);
			}
			$Query = 'CREATE TABLE '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.' (
				'.$ColumnString.'
				'.$KeyString.'
			)';
			self::exec($Query);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return;
	}
	
	public static function dropTable($Application, $Table)
	{
		$Query = 'DROP TABLE IF EXISTS '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table;
		try {
			self::exec($Query);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return;
	}
	
	public static function addColumns($Application, $Table, array $Columns)
	{
		foreach ($Columns as $Column) {
			$Query = 'ALTER TABLE '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.' ADD COLUMN '.$Column;
			try {
				self::exec($Query);
			} catch (\PDOException $e) {
				throw $e;
			}
		}
		
		return;
	}
	
	public static function addForeignKey(array $Base, array $Target, $OnDelete = 'CASCADE')
	{
		if (!array_key_exists('application', $Base) || !array_key_exists('table', $Base) || !array_key_exists('column', $Base) || !array_key_exists('application', $Target) || !array_key_exists('table', $Target) || !array_key_exists('column', $Target)) {
			try {
				throw new Exception('Array $Base and $Target need entries \'application\', \'table\' and \'column\'');
			}
			catch (\Exception $e) {
				throw $e;
			}
		}
		$Query = 'ALTER TABLE '.$Base['application'].__LSC_INSTALLATION_NUMBER__.'_'.$Base['table'].' ADD FOREIGN KEY ('.$Base['column'].') REFERENCES '.$Target['application'].__LSC_INSTALLATION_NUMBER__.'_'.$Target['table'].' ('.$Target['column'].') ON DELETE '.$OnDelete;
		try {
			self::exec($Query);
		} catch (\PDOException $e) {
			throw $e;
		}
		
		return;
	}
}
