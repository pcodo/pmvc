<?php
/*Rôle : Gère les requêtes asynchrones du projet Epension
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:09/09/2016
*/
session_start();
require_once("../requirements.php");


if(isset($_SESSION['id_intervenant']))
{
	$id_intervenant = $_SESSION['id_intervenant'];
	$db = new DataBase();
	Systeme::init(false);// Le true permet de tenter de lancer les alertes mails si possible
	
	if(isset($_GET['req'])) 
	{
		switch($_GET['req'])
		{
			case 0:
				$sys_date = new DateTime();
				$token = sha1('sm::'.$sys_date->format('d--m--Y H:i:s'));
				$_SESSION['sm_token'] = $token;
				echo json_encode($token);
			break;
		}
		
	}
}

?>