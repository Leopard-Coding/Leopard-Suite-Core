<?php
namespace LSCInstall;

use LSC\lib\system\database\Database;

Database::createTable(
	'lsc',
	'acp_option_values',
	[
		'valueID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY',
		'value MEDIUMTEXT NOT NULL',
		'optionID INT(10) NOT NULL'
	]
);
