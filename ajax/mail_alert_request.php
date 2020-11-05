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
	Systeme::init(true);// Le true permet de tenter de lancer les alertes mails si possible
	if(isset($_GET['req'])) 
	{
		switch($_GET['req'])
		{
			case 1: // lancement des alertes sms
				$sent_count = 0;
			    $jobs = MailJob::all();
			    foreach ($jobs as $key => $job) {		    	
			    	if(Systeme::sendMail($job->objet(),$job->message(),array($job->destinataire()),$job->attachement(),$job->source()))
			    		{
			    			$job->setSent(1)
			    				->dbSave($id_intervenant);
			    				$sent_count++;
			    		}
			    }				
				$rep_json = array('status'=>1,'message'=>count($sent_count));
				echo json_encode($rep_json);
			break;						
		}		
	}
}

?>