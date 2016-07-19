<?php
/**
 * Access to database
 *
 * @copyright ©Leopard
 * @license http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
 *
 * @author Julian Pfeil
 */ 
namespace LSC\lib\system\database;

use PDO;

class Database
{
	public static $InsertId;
	private static $Connection;
	
	/**	
	 * Connects to database
	 * 
	 * @api
	 *
	 * @uses self::$Connection Defines the database-connection
	 *
	 * @return void
	 */
	public static function connect()
	{
		require_once(__LSC_ABSOLUTE_DIR__.'dbconfig.inc.php');
		self::$Connection = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPassword, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
		
		return;
	}
	
	/**
	 * Executes query
	 * 
	 * @api
	 *
	 * @param string $Query Query to execute
	 *
	 * @uses self::$Connection Returns the database-connection
	 * @uses self::$InsertId Defines the insert-id
	 *
	 * @return string $Num Returns number of executed datasets
	 */
	public static function exec($Query)
	{
		$Num = self::$Connection->exec($Query);
		self::$InsertId = self::$Connection->lastInsertId();
		
		return $Num;
	}
	
	/**
	 * Executes query
	 * 
	 * @api
	 *
	 * @param string $Query Query to execute
	 *
	 * @uses self::$Connection Returns the database-connection
	 *
	 * @return string $Num Returns number of executed datasets
	 */
	public static function query($Query)
	{
		$Num = self::$Connection->query($Query);
		
		return $Num;
	}
	
	/**
	 * Fetchs all datasets of the executed query
	 * 
	 * @api
	 *
	 * @param string $Query Query to execute
	 * @param string $FetchStyle Fetch style to return data
	 *
	 * @uses self::$Connection Returns the database-connection
	 * @uses self::query() Executes query
	 *
	 * @return string $Result Returns query-results
	 */
	public static function fetchAll($Query, $FetchStyle = PDO::FETCH_ASSOC)
	{
		$Statement = self::query($Query);
		if (!$Statement) {
			return false;
		}
		$Results = $Statement->fetchAll($FetchStyle);
		
		return $Results;
	}
	
	/**
	 * Escapes strings from outside 
	 * 
	 * @api
	 *
	 * @param string $String String to escape
	 * @param boolean $StripTags If tags in string should be striped
	 *
	 * @uses self::$Connection Returns the database-connection
	 *
	 * @return string $String Returns escaped string
	 */
	public static function escape($String, $StripTags = false)
	{
		if((bool) $StripTags) {
			$String = strip_tags($String);
		}
		$String = self::$Connection->quote($String);
		$String = substr($String, 1, strlen($String) - 2);
		
		return $String;
	}
	
	/**
	 * Inserts dataset
	 * 
	 * @api
	 *
	 * @param string $Table Table to insert in
	 * @param array $InsertData Columns and values to insert
	 *
	 * @uses self::exec() Executes the query
	 *
	 * @return string $Num Returns number of executed datasets
	 */
	public static function insert($Application, $Table, array $InsertData)
	{
		$Columns = [];
		$Values = [];
		foreach ($InsertData as $Column => $Value) {
			$Columns .= $Column.', ';
			$Values .= $Value.', ';
		}
		$Columns = substr($Columns, 0, 2);
		$Values = substr($Values, 0, 2);
		$Num = self::exec('INSERT INTO '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.' ('.$Columns.') VALUES ('.$Values.')');
		
		return $Num;
	}
	
	/**
	 * Selects and returns datasets
	 * 
	 * @api
	 *
	 * @param string $Table Table to select from
	 * @param string $WhereString String containing the wanted where-clause
	 * @param array $Additions Array containing the wanted additions in query
	 * @param boolean $FetchAll If only one dataset or all should be returned
	 * @param string $Columns String containing the wanted columns to select
	 *
	 * @uses self::fetchAll() Fetchs all wanted datasets
	 * @uses self::convertAdditionArrayToString() Converts AdditionArray to AdditionString
	 *
	 * @return string $Result Returns one result or all results
	 */
	public static function select($Application, $Table, $WhereString = '', array $Additions = [], $FetchAll = false, $Columns = '*')
	{
		$AdditionString = self::convertAdditionArrayToString($Additions);
		$Query = 'SELECT '.$Columns.' FROM '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.$WhereString.$AdditionString;
		if(!(bool) $fetchAll) {
			if (!self::fetchAll($Query)) {
				return false;
			} 
			$Result = self::fetchAll($Query)[0];
		} else {
			$Result = self::fetchAll($Query);
		}
		
		return $Result;
	}
	
	/**
	 * Updates datasets
	 * 
	 * @api
	 *
	 * @param string $Table Table to update in
	 * @param string $WhereString String containing the wanted where-clause
	 * @param array $Additions Array containing the wanted additions in query
	 * @param array $SetData Array containing the set-clauses
	 *
	 * @uses self::exec() Executes query
	 * @uses self::convertAdditionArrayToString() Converts AdditionArray to AdditionString
	 *
	 * @return string $Num Returns number of executed datasets
	 */
	public static function update($Application, $Table, array $SetData, $WhereString = '', array $Additions = [])
	{
		$AdditionString = self::convertAdditionArrayToString($Additions);
		$SetString = '';
		foreach ($SetData as $SetClause) {
			$SetString .= $SetClause.', ';
		}
		$SetString = substr($SetString, 0, 2);
		$Query = 'UPDATE '.$Application.'_'.$Table.' SET '.$SetString.$WhereString.$AdditionString;
		$Num = self::exec($Query);
		
		return $Num;
	}
	
	/**
	 * Deletes datasets
	 * 
	 * @api
	 *
	 * @param string $Table Table to delete from
	 * @param string $WhereString String containing the wanted where-clause
	 * @param array $Additions Array containing the wanted additions in query
	 *
	 * @uses self::exec() Executes query
	 * @uses self::convertAdditionArrayToString() Converts AdditionArray to AdditionString
	 *
	 * @return string $Num Returns number of executed datasets
	 */
	public static function delete($Application, $Table, $WhereString = '', array $Additions = [])
	{
		$AdditionString = self::convertAdditionArrayToString($Additions);
		$Query = 'DELETE FROM '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.$WhereString.$AdditionString;
		$Num = self::exec($Query);
		
		return $Num;
	}
	
	/**
	 * Creates a table
	 * 
	 * @api
	 *
	 * @param string $Table Tablename to use to create the table
	 * @param array $Columns Columns with their data to add the table
	 * @param aray $Additions Array containing the wanted additions in query
	 *
	 * @uses self::exec() Executes query
	 * @uses self::convertAdditionArrayToString() Converts AdditionArray to AdditionString
	 *
	 * @return boolean True on theoretical success, false on failure
	 */
	public static function createTable($Application, $Table, array $Columns, array $Keys = [])
	{
		$ColumnString = '';
		$KeyString = '';
		if (count($Columns) > 0) {
			foreach ($Columns as $Column) {
				$ColumnString .= $Column.', ';
			}
			$ColumnString = substr($ColumnString, 0, -2);
		}
		if (count($Keys) > 0) {
			foreach ($Keys as $Key) {
				$ColumnString .= $Key.', ';
			}
			$KeyString = substr($KeyString, 0, -2);
		}
		$Query = 'CREATE TABLE '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.' (
			'.$ColumnString.'
			'.$KeyString.'
		)';
		self::dropTable($Application, $Table);
		self::exec($Query);
		
		return true;
	}
	
	/**
	 * Drops a table
	 * 
	 * @api
	 *
	 * @param string $Table Table to drop
	 *
	 * @uses self::exec() Executes query
	 *
	 * @return void
	 */
	public static function dropTable($Application, $Table)
	{
		$Query = 'DROP TABLE IF EXISTS '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table;
		self::exec($Query);
		
		return;
	}
	
	/**
	 * Converts AdditionArray into useable AdditionString
	 *
	 * @param array $Additions AdditionArray to convert
	 *
	 * @return void
	 */
	private static function convertAdditionArrayToString(array $Additions) 
	{
		$AdditionString = '';
		if (count($Additions) > 0) {
			foreach ($Additions as $Addition) {
				$AdditionString .= ' '.$Addition;
			}
		}
		
		return $AdditionString;
	}
	
	public static function addColumns($Application, $Table, $Columns)
	{
		if (is_array($Columns)) {
			foreach ($Columns as $Column) {
				$Query = 'ALTER TABLE '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.' ADD COLUMN '.$Column;
				self::exec($Query);
			}
		} else {
			$Query = 'ALTER TABLE '.$Application.__LSC_INSTALLATION_NUMBER__.'_'.$Table.' ADD COLUMN '.$Columns;
			self:;exec($Query);
		}
		
		return;
	}
	
	public static function addForeignKey(array $Base, array $Target, $OnDelete = 'CASCADE')
	{
		$Query = 'ALTER TABLE '.$Base['application'].__LSC_INSTALLATION_NUMBER__.'_'.$Base['table'].' ADD FOREIGN KEY ('.$Base['column'].') REFERENCES '.$Target['application'].__LSC_INSTALLATION_NUMBER__.'_'.$Target['table'].' ('.$Target['column'].') ON DELETE '.$OnDelete;
		self::exec($Query);
		
		return;
	}
}
