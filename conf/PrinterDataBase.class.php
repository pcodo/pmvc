<?php 
require_once("config.php");
class PrinterDataBase extends DataBase
{
	public function __construct($dbHost="localhost",$dbName="epprinting",$dbUser="root",$dbMdp=DBPWD)
	{
		parent::__construct($dbHost,$dbName,$dbUser,$dbMdp);		
	}	
}


?>
