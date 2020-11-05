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
				$json_rep = array('status'=>1,'sms_server_full_address'=>SMS_SERVER_FULL_ADDRESS);
				echo json_encode($json_rep);
			break;
			case 1: // lancement des alertes sms
				$id_alert_point =  isset($_GET['id_alert_point'])?$_GET['id_alert_point']:0;
				$rep_json = array('status'=>0,'message'=>'');
				$alertPoints = array();
				if($id_alert_point>0) // gère seulement le seul point envoyé
				{
					$alertPoints[] = new AlertPoint($id_alert_point);					
				}
				else // gère tous les point d'alerte
				{
					$alertPoints = AlertPoint::all();					
				}

				// gestion des alertes
				foreach ($alertPoints as $key => $alertPoint) {
					$records = AlertPoint::dossierToAlertPointAsRecords($alertPoint->id());
					$rep = DossierObjectBuilder::build(Systeme::array_key_values($records,'id_dossier'));
					foreach ($rep as $key => $dossier) {
						if($dossier->usager()->telephone()!='')
						{
							$message = AlertPoint::buildDossierAlertMessage($dossier,$alertPoint);
							if(Systeme::sendSms($dossier->usager()->telephone(),$message))
							{
								//$dossier->setAlerted($alertPoint->id(),$message,$id_intervenant);
							}
							
						}						
					}						
				}
				echo json_encode($rep_json);

			break;
			case 2: // recupération des dossiers à alerter
				$id_alert_point =  isset($_GET['id_alert_point'])?$_GET['id_alert_point']:0;
				$dossiers = array();
				$alertPoints = array();
				if($id_alert_point>0) // gère seulement le seul point envoyé
				{
					$alertPoints[] = new AlertPoint($id_alert_point);					
				}
				else // gère tous les point d'alerte
				{
					$alertPoints = AlertPoint::all();					
				}

				// gestion des alertes
				$alertPoints = array_reverse($alertPoints);
				foreach ($alertPoints as $key => $alertPoint) {
					$records = AlertPoint::dossierToAlertPointAsRecords($alertPoint->id());
					$rep = DossierObjectBuilder::build(Systeme::array_key_values($records,'id_dossier'));
					foreach ($rep as $key => $dossier) {
						if($dossier->usager()->telephone()!='')
						{
							$message = AlertPoint::buildDossierAlertMessage($dossier,$alertPoint);
							if(!in_array($dossier->id(), Systeme::array_key_values($dossiers,'id_dossier')))
							{
								$dossiers[] = array('id_dossier'=>$dossier->id(),'telephone'=>$dossier->usager()->telephone(),'message'=>$message);	
							}												
							$dossier->setAlerted($alertPoint->id(),$message,$id_intervenant);
						}						
					}						
				}
				echo json_encode($dossiers);

			break;

			case 3: // envoi de la liste des dossiers alertés
				$rep_json = array('status'=>0,'message'=>'');
				$id_dossiers = array();
				if(isset($_GET['id_dossiers']))
				{ // plusieurs ids de dossiers pourraient etre envoyés pour un traitement en lot, mais pour le moment, on envoie qu'un seul dossier.
					$id_dossiers = json_decode(preg_replace('#'.$tag .'#','"',$_GET['id_dossiers']),true);
					foreach($id_dossiers as $data)
					{
						$dossier = new Dossier($data['id_dossier']);
						$dossier->setAlerted($data['id_alert_point'],$data['message'],$id_intervenant);
					}
				}				
				echo json_encode($rep_json);
			break;
		}
		
	}
}

?>