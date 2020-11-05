<?php
/*
  Rôle: Gère la configuration du système au niveau utilisateur (menu configuration)
  Auteur: CODO Paterne
  Date de cr�ation:22/09/2017
  
*/
class DemandeFormManager {
	public static $db;
    public function __construct(){
	    
	}
	public static function init()
	{
		self::$db = DataBase::getInstance();
	}
	public static function processAddDemandeSubmit($id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			self::$db->startTransaction();
			$objet = isset($_POST['objet'])?self::$db->escape($_POST['objet']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			$id_structure = isset($_POST['structure'])?self::$db->escape($_POST['structure']):0;
			if(trim($objet)=='' || $id_structure <=0)
			{
				return $result = array('code'=>0,'message'=>'La structure et l\'objet de la demande sont des champs obligatoires à renseigner!');
			}
			$demande =  new Demande();
			$demande->setObjet($objet)
					  ->setDescription($description)
					  ->setStructureId($id_structure)
					  ->setValidState(1);
			$demande_ok = false;
			if($demande->dbSave($id_intervenant))
			{
				$demande_ok = true;				
			}
			$index = isset($_POST['index'])?self::$db->escape($_POST['index']):0;
			$checked = 0;
			$success = 0;
			for($i=0;$i<$index;$i++)
			{
				$salle_id = isset($_POST['salle_'.$i])?self::$db->escape($_POST['salle_'.$i]):0;
				$date_debut = isset($_POST['date_debut_'.$i])?self::$db->escape($_POST['date_debut_'.$i]):'';				
				$heure_debut = isset($_POST['heure_debut_'.$i])?self::$db->escape($_POST['heure_debut_'.$i]):'';
				$min_debut = isset($_POST['min_debut_'.$i])?self::$db->escape($_POST['min_debut_'.$i]):'';
				$sec_debut = isset($_POST['sec_debut_'.$i])?self::$db->escape($_POST['sec_debut_'.$i]):'';
				
				$date_fin = isset($_POST['date_debut_'.$i])?self::$db->escape($_POST['date_debut_'.$i]):'';
				//$date_fin = isset($_POST['date_fin_'.$i])?self::$db->escape($_POST['date_fin_'.$i]):'';
				$heure_fin = isset($_POST['heure_fin_'.$i])?self::$db->escape($_POST['heure_fin_'.$i]):'';
				$min_fin = isset($_POST['min_fin_'.$i])?self::$db->escape($_POST['min_fin_'.$i]):'';
				$sec_fin = isset($_POST['sec_fin_'.$i])?self::$db->escape($_POST['sec_fin_'.$i]):'';
				if(trim($date_debut)=='' || $salle_id <=0 || intval($heure_debut) ==0 || intval($heure_fin) ==0)
				{
					return $result = array('code'=>0,'message'=>'Veuillez spécifier la salle, la date et heure de début/fin pour chaque ligne des réservations!');
				}
				if($heure_debut>$heure_fin || ($heure_debut==$heure_fin&&$min_debut>=$min_fin))
				{
					return $result = array('code'=>0,'message'=>'L\'heure de début ne peut être posterieure à l\'heure de fin');
				}
				$heure_debut = str_pad($heure_debut,2,'0',STR_PAD_LEFT);                 
                $min_debut = str_pad($min_debut,2,'0',STR_PAD_LEFT);                  
                $sec_debut = str_pad($sec_debut,2,'0',STR_PAD_LEFT);

                $heure_fin = str_pad($heure_fin,2,'0',STR_PAD_LEFT);                 
                $min_fin = str_pad($min_fin,2,'0',STR_PAD_LEFT);                  
                $sec_fin = str_pad($sec_fin,2,'0',STR_PAD_LEFT);   

                $date_debut.=' '.$heure_debut.':'.$min_debut.':'.$sec_debut;     
                $date_fin.=' '.$heure_fin.':'.$min_fin.':'.$sec_fin;  
                
                if(isset($_POST['reservation_'.$i]) && $date_debut!='' && $date_fin!='')
                {
                	$checked++;
                	$dateDebut = DateTime::createFromFormat('d/m/Y H:i:s',$date_debut);
                	$dateFin = DateTime::createFromFormat('d/m/Y H:i:s',$date_fin); 
                	$records = Demande::searchForOverllappingAsRecords($dateDebut->format('Y-m-d H:i:s'),$dateFin->format('Y-m-d H:i:s'),$salle_id);
                	if(count($records)>0)
                	{
                		$salle = new Salle($salle_id);
                		$record = $records[0];
                		$d = new Demande($record['id']);
                		return $result = array('code'=>0,'message'=>'La salle '.$salle->nom().' est déjà prise pour la période du '.$date_debut.' au '.$date_fin.' par le/la '.$d->structure()->nom().' : \" '.$d->objet(). '\" '.$d->horaire());
                	}
                	$reservation = new ReservationSalle();
                	$reservation->setDemande($demande)
                				->setSalleId($salle_id)
                				->setDateDebut($dateDebut->format('Y-m-d H:i:s'))
                				->setDateFin($dateFin->format('Y-m-d H:i:s'));
                	if($reservation->dbSave($id_intervenant))
                	{
                		$success++;
                	}
                }                
			}
			if($demande_ok && $checked == $success && $success>0)
			{
				self::$db->commit();
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');				
			}
			else
			{
				self::$db->rollback();
				$result = array('code'=>0,'message'=>'Echec lors de l\'enregistrement. Veuillez renseigner au moins une réservation !');
			}
			
		}
		return $result;
	}
	
	public static function processUpdateDemandeSubmit($id_intervenant,$id_demande)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			self::$db->startTransaction();
			if(!is_numeric($id_demande)||$id_demande<0)
			{
				return $result = array('code'=>0,'message'=>'Veuillez séléctionner une demande valide');
			}
			
			$objet = isset($_POST['objet'])?self::$db->escape($_POST['objet']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			$id_structure = isset($_POST['structure'])?self::$db->escape($_POST['structure']):0;
						
			if(trim($objet)=='' || $id_structure <=0)
			{
				return $result = array('code'=>0,'message'=>'La structure et l\'objet de la demande sont des champs obligatoires à renseigner!');
			}
			$demande =  new Demande($id_demande);
			$demande->setObjet($objet)
					  ->setDescription($description)
					  ->setStructureId($id_structure);
			$demande_ok = false;
			if($demande->dbSave($id_intervenant))
			{
				$demande_ok = true;				
			}
			$index = isset($_POST['index'])?self::$db->escape($_POST['index']):0;
			if($index >0 ) 
			{
				$demande->removeAllReservation();
			}
			$checked = 0;
			$success = 0;			
			for($i=0;$i<$index;$i++)
			{
				$salle_id = isset($_POST['salle_'.$i])?self::$db->escape($_POST['salle_'.$i]):0;
				$date_debut = isset($_POST['date_debut_'.$i])?self::$db->escape($_POST['date_debut_'.$i]):'';
				$heure_debut = isset($_POST['heure_debut_'.$i])?self::$db->escape($_POST['heure_debut_'.$i]):'';
				$min_debut = isset($_POST['min_debut_'.$i])?self::$db->escape($_POST['min_debut_'.$i]):'';
				$sec_debut = isset($_POST['sec_debut_'.$i])?self::$db->escape($_POST['sec_debut_'.$i]):'';

				$date_fin = isset($_POST['date_debut_'.$i])?self::$db->escape($_POST['date_debut_'.$i]):'';
				//$date_fin = isset($_POST['date_fin_'.$i])?self::$db->escape($_POST['date_fin_'.$i]):'';
				$heure_fin = isset($_POST['heure_fin_'.$i])?self::$db->escape($_POST['heure_fin_'.$i]):'';
				$min_fin = isset($_POST['min_fin_'.$i])?self::$db->escape($_POST['min_fin_'.$i]):'';
				$sec_fin = isset($_POST['sec_fin_'.$i])?self::$db->escape($_POST['sec_fin_'.$i]):'';
				if(trim($date_debut)=='' || $salle_id <=0 || intval($heure_debut) ==0 || intval($heure_fin) ==0)
				{
					return $result = array('code'=>0,'message'=>'Veuillez spécifier la salle, la date et heure de début/fin pour chaque ligne des réservations!');
				}
				if($heure_debut>$heure_fin || ($heure_debut==$heure_fin&&$min_debut>=$min_fin))
				{
					return $result = array('code'=>0,'message'=>'L\'heure de début ne peut être posterieure à l\'heure de fin');
				}
				$heure_debut = str_pad($heure_debut,2,'0',STR_PAD_LEFT);                 
                $min_debut = str_pad($min_debut,2,'0',STR_PAD_LEFT);                  
                $sec_debut = str_pad($sec_debut,2,'0',STR_PAD_LEFT);

                $heure_fin = str_pad($heure_fin,2,'0',STR_PAD_LEFT);                 
                $min_fin = str_pad($min_fin,2,'0',STR_PAD_LEFT);                  
                $sec_fin = str_pad($sec_fin,2,'0',STR_PAD_LEFT);   

                $date_debut.=' '.$heure_debut.':'.$min_debut.':'.$sec_debut;     
                $date_fin.=' '.$heure_fin.':'.$min_fin.':'.$sec_fin;  
                
                if(isset($_POST['reservation_'.$i]) && $date_debut!='' && $date_fin!='')
                {
                	$checked++;
                	$dateDebut = DateTime::createFromFormat('d/m/Y H:i:s',$date_debut);
                	$dateFin = DateTime::createFromFormat('d/m/Y H:i:s',$date_fin);
                	$records = Demande::searchForOverllappingAsRecords($dateDebut->format('Y-m-d H:i:s'),$dateFin->format('Y-m-d H:i:s'),$salle_id); 
                	if(count($records)>0)
                	{
                		$salle = new Salle($salle_id);
                		$record = $records[0];
                		$d = new Demande($record['id']);
                		return $result = array('code'=>0,'message'=>'La salle '.$salle->nom().' est déjà prise pour la période du '.$date_debut.' au '.$date_fin.' par le/la '.$d->structure()->nom().' : \" '.$d->objet(). '\" '.$d->horaire());
                	}
                	$reservation = new ReservationSalle();
                	$reservation->setDemande($demande)
                				->setSalleId($salle_id)
                				->setDateDebut($dateDebut->format('Y-m-d H:i:s'))
                				->setDateFin($dateFin->format('Y-m-d H:i:s'));
                	if($reservation->dbSave($id_intervenant))
                	{
                		$success++;
                	}
                }                
			}
			if($demande_ok && $checked == $success && $success>0)
			{
				self::$db->commit();
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');				
			}
			else
			{
				self::$db->rollback();
				$result = array('code'=>0,'message'=>'Echec lors de l\'enregistrement. Veuillez renseigner au moins une réservation !');
			}				
		}
		return $result;
	}   
    
	public static function forwardDemandeSubmit($demandes,$id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$selected_user = self::$db->escape($_POST['id_intervenant_assigned']);
			
			$tokens = array();
			foreach ($demandes as $key => $demande) {
				if(isset($_POST['item_'.$demande->id()]))
				{
					$tokens[] = $demande->token();
				}
			}
			$result = TokenFormManager::processForwardTokens($selected_user,$tokens,$id_intervenant);			
		}
		return $result;
	}

	public static function validateDemandeSubmit($demandes,$id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$new_token_state_id = isset($_POST['new_token_state_id'])?self::$db->escape($_POST['new_token_state_id']):0;
			$observation = isset($_POST['observation'])?self::$db->escape($_POST['observation']):'';
			
			if($new_token_state_id == REJECTED and $observation=='')
			{
				return $result = array('code'=>0,'message'=>'Une observation est requise en cas de rejet!');
			}

			$tokens = array();
			foreach ($demandes as $key => $demande) {
				if(isset($_POST['item_'.$demande->id()]))
				{
					$tokens[] = $demande->token();					
				}
			}
          
			$result = TokenFormManager::processChangeTokensState($new_token_state_id,$observation,$tokens,$id_intervenant);	

			if($result['code'])
			{
				foreach ($demandes as $key => $demande) {
					$demande = new Demande($demande->id());
					$subject = 'Traitement de la demande N°'.$demande->numero().' pour : '.$demande->objet();
					$message = 'Votre demande de salle pour la période du '.$demande->horaire().' a été '.$demande->currentState()->nameFr().'e dépuis le '.Systeme::dateToFrench(Systeme::now_date());
					if(trim($demande->currentObservation())!='')
					{
						$message.=' avec comme observation: '.$demande->currentObservation();
					}
					$destinataire = $demande->structure()->mail();
					if(trim($destinataire)=='') 
					{
						$destinataire = $demande->structure()->mailAlt();
					}
					if(trim($destinataire)!='') 
					{
						$job = new MailJob();
						$job->setSource(MyPHPMailer::$mail_source)
							->setDestinataire($destinataire)
							->setObjet($subject)
							->setMessage($message)
							->dbSave($id_intervenant);
					}					
				}				
			}		
		}
		return $result;
	}

	public static function addNoteSubmit($id_demande,$id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$objet = isset($_POST['objet'])?self::$db->escape($_POST['objet']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			
			if(trim($objet)=='' || $description=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner l\'objet et la descripiton');
			}
			self::$db->startTransaction();
			$file =  new File(0,'fichier');
			$file->dbSave($id_intervenant);
			$note =  new Note();
			$note->setObjet($objet)
					  ->setDescription($description) 
					  ->setDemandeId($id_demande)
					  ->setFile($file);
			if($note->dbSave($id_intervenant))
			{
				self::$db->commit();
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}
			else
			{
				self::$db->rollback();
				$result = array('code'=>0,'message'=>'Erreur lors de l\'enregistrement!');
			}
			
		}
		return $result;
	}

	public static function updateNoteSubmit($id_note,$id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$objet = isset($_POST['objet'])?self::$db->escape($_POST['objet']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			
			if(trim($objet)=='' || $description=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner l\'objet et la descripiton');
			}
			self::$db->startTransaction();
			$note =  new Note($id_note);
			$file = $note->file();
			$file->setConstructInfo('fichier');
			$file->dbSave();
			$note->setObjet($objet)
					  ->setDescription($description);
			if($note->dbSave($id_intervenant))
			{
				self::$db->commit();
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}
			else
			{
				self::$db->rollback();
				$result = array('code'=>0,'message'=>'Erreur lors de l\'enregistrement!');
			}
			
		}
		return $result;
	}


	public static function disableDemandesSubmit($demandes,$id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$success = 0;
			$checked = 0;
			self::$db->startTransaction();
			foreach ($demandes as $key => $demande) {
				if(isset($_POST['item_'.$demande->id()]))
				{
					$checked++;
					$demande->setValidState(0);
					if($demande->dbSave($id_intervenant))
					{						
						$success++;
					}		
				}
			}
			if($checked == $success && $success>0)
			{
				self::$db->commit();
				$result = array('code'=>1,'message'=>'Désactivation bien effectuée!');				
			}
			else
			{
				self::$db->rollback();
				$result = array('code'=>0,'message'=>'Erreur lors de la désactivation des demandes sélectionnées.');
			}				
		}
		return $result;
	}
	
}

 

