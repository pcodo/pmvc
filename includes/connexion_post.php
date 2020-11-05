<?php
/*Rôle : Validateur d'authentification
  Auteur: CODO Paterne, ingénieur en réseaux informatiques et Internet 
  Date de création:28/02/2013
*/
session_start();
require_once("../conf/Systeme.class.php");
require_once("../conf/Intervenant.class.php");
require_once("../conf/DataBase.class.php");
Systeme::init();
$db = new DataBase();
$sec_1_state = true;
if(isset($_POST['login'])&&$_POST['login']!=''&&isset($_POST['mdp'])&&$_POST['mdp']!=''&&($sec_1_state = Systeme::securityCheck()))
{
    $checking = Systeme::login(($_POST['login']),md5($_POST['mdp']));
	if($checking)
	{
		$_SESSION['id_intervenant'] = $checking['id'];
		// Initialisation du système par rapport aux alertes par mails
		$last_static_params = $db->queryOneRecord('select * from static_params order by id desc limit 0,1');
		$active_alerte_mail_state= isset($last_static_params['active_alerte_mail_state'])?$last_static_params['active_alerte_mail_state']:0;
		if($active_alerte_mail_state==1)
		{
			Systeme::initMailAlerteSession($active_alerte_mail_state);
		}
		
	}
	else
	{
		// eh bein, on ne fait rien:: il pourra jamais se connecter !!
	}
}
header('location:../index.php?sec_1='.($sec_1_state?1:0));

?>
