<?php
/*Rôle : Gère les requêtes asynchrones du projet VS
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
session_start();
require_once("../conf/Systeme.class.php");
require_once("../conf/Intervenant.class.php");
require_once("../conf/DataBase.class.php");
require_once("../includes/sm/epension/controller/PensionEntityManager.php");
if(isset($_SESSION['id_intervenant']))
{
	$id_intervenant = $_SESSION['id_intervenant'];
	$db = new DataBase();
	Systeme::init(false);// Le true permet de tenter de lancer les alertes mails si possible
	PensionEntityManager::init();
	$id_magasin = $_SESSION['magasin'];
	if(isset($_GET['req'])) // la liste des gammes de produits 
	{
		switch($_GET['req'])
		{
			case 0:// recherche des dossiers par lp, rtr_order, rtr_order_date
				$lp = isset($_GET['lp'])?$db->escape($_GET['lp']):'';
				$rtr_order = isset($_GET['rtr_order'])?$db->escape($_GET['rtr_order']):'';
				$rtr_order_date = isset($_GET['rtr_order_date'])?$db->escape($_GET['rtr_order_date']):'';
				//$dossiers = PensionEntityManager::dossiersByLPAndRetirementOrder($lp,$rtr_order,$rtr_order_date);
				$rep = array('id'=>0);
				echo json_encode($rep);
			break;
		}
		
	}
}

?>