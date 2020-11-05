<?php
/*Rôle : 
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:19/02/2013
*/
session_start();

require_once("../../../../../requirements.php");

if(isset($_SESSION['id_intervenant']))
{	
	$db = DataBase::getInstance();
	$intervenant = new Intervenant($_SESSION['id_intervenant']);
}
else exit();
