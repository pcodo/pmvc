<?php
/*Rôle : Index du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
session_start();
require_once("../conf/Systeme.class.php");
require_once("../conf/Intervenant.class.php");
require_once("../conf/DataBase.class.php");
if(isset($_SESSION['id_intervenant']))
{
	$db = new DataBase();
	if(isset($_GET['req'])&&$_GET['req']==0) // la liste des gammes de produits 
	{
		$gammes = $db->queryAllRecords('select * from gamme_produit');
		echo json_encode($gammes);
	}
}

?>