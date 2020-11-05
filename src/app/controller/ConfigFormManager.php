<?php
/*
  Rôle: Gère la configuration du système au niveau utilisateur (menu configuration)
  Auteur: CODO Paterne
  Date de cr�ation:22/09/2017
  
*/
class ConfigFormManager {
	public static $db;
    public function __construct(){
	    
	}
	public static function init()
	{
		self::$db = DataBase::getInstance();
	}
	public static function processAddDepartementSubmit($id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			
			if(trim($nom)=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le nom de la structure');
			}
			$departement =  new Departement();
			$departement->setNom($nom)
					  ->setDescription($description) ;
			if($departement->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}
			
		}
		return $result;
	}
	
	public static function processUpdateDepartementSubmit($id_intervenant,$id_departement)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			if(!is_numeric($id_departement)||$id_departement<0)
			{
				return $result = array('code'=>0,'message'=>'Veuillez séléctionner une structure valide');
			}
			
			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			
			
			if(trim($nom)=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le nom de la structure');
			}
			$departement =  new Departement($id_departement);
			$departement->setNom($nom)
					  ->setDescription($description);
			if($departement->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}					
		}
		return $result;
	}
	
	public static function processAddCommuneSubmit($id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$id_departement = isset($_POST['id_departement'])?self::$db->escape($_POST['id_departement']):0;
			if(!is_numeric($id_departement)||$id_departement<1)
			{
				return $result = array('code'=>0,'message'=>'Vueillez sélectionner le département et renseigner le nom de la commune');
			}

			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			
			if(trim($nom)=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le nom de la structure');
			}
			$commune =  new Commune();
			$commune->setNom($nom)
					  ->setDescription($description) 
					  ->setDepartementId($id_departement);
			if($commune->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}
			
		}
		return $result;
	}
	
	public static function processUpdateCommuneSubmit($id_intervenant,$id_commune)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			if(!is_numeric($id_commune)||$id_commune<1)
			{
				return $result = array('code'=>0,'message'=>'Veuillez séléctionner une structure valide');
			}
			
			$id_departement = isset($_POST['id_departement'])?self::$db->escape($_POST['id_departement']):0;
			if(!is_numeric($id_departement)||$id_departement<1)
			{
				return $result = array('code'=>0,'message'=>'Vueillez sélectionner le département et renseigner le nom de la commune');
			}

			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			
			
			if(trim($nom)=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le nom de la structure');
			}
			$commune =  new Commune($id_commune);
			$commune->setNom($nom)
					  ->setDescription($description)
					  ->setDepartementId($id_departement);
			if($commune->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}					
		}
		return $result;
	}
	public static function processAddStructureSubmit($id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			$telephone = isset($_POST['telephone'])?self::$db->escape($_POST['telephone']):'';
			$telephone_alt = isset($_POST['telephone_alt'])?self::$db->escape($_POST['telephone_alt']):'';
			$mail = isset($_POST['mail'])?self::$db->escape($_POST['mail']):'';
			$mail_alt = isset($_POST['mail_alt'])?self::$db->escape($_POST['mail_alt']):'';
			$id_departement = isset($_POST['id_departement'])?self::$db->escape($_POST['id_departement']):0;
			if(!is_numeric($id_departement)||$id_departement<1)
			{
				return $result = array('code'=>0,'message'=>'Vueillez sélectionner le département et renseigner le nom de la commune');
			}
			$id_structure_type = isset($_POST['structure_type'])?self::$db->escape($_POST['structure_type']):'';
         	if($id_structure_type==0)
         	{
         		$new_structure_type_nom = isset($_POST['new_structure_type_nom'])?self::$db->escape($_POST['new_structure_type_nom']):'';
         		
         		if($new_structure_type_nom=='')
         		{
         			return $result = array('code'=>0,'message'=>'Vueillez renseigner le type de structure ou en créer un nouveau');
         		}
         		$structureType = new StructureType();
         		$structureType->setNom($new_structure_type_nom)
         				      ->dbSave($id_intervenant);
         		$id_structure_type = $structureType->id();
         	}
			if(trim($nom)=='' || $id_structure_type=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le type de la structure et son nom!');
			}
			$structure =  new Structure();
			$structure->setNom($nom)
					  ->setDescription($description)
					  ->setTelephone($telephone)
					  ->setTelephoneAlt($telephone_alt)
					  ->setMail($mail)
					  ->setMailAlt($mail_alt)
					  ->setDepartementId($id_departement)
					  ->setStructureTypeId($id_structure_type);
			if($structure->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}
			
		}
		return $result;
	}
	
	public static function processUpdateStructureSubmit($id_intervenant,$id_structure)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			if(!is_numeric($id_structure)||$id_structure<0)
			{
				return $result = array('code'=>0,'message'=>'Veuillez séléctionner une structure valide');
			}
			
			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			$telephone = isset($_POST['telephone'])?self::$db->escape($_POST['telephone']):'';
			$telephone_alt = isset($_POST['telephone_alt'])?self::$db->escape($_POST['telephone_alt']):'';
			$mail = isset($_POST['mail'])?self::$db->escape($_POST['mail']):'';
			$mail_alt = isset($_POST['mail_alt'])?self::$db->escape($_POST['mail_alt']):'';
			$id_departement = isset($_POST['id_departement'])?self::$db->escape($_POST['id_departement']):0;
			if(!is_numeric($id_departement)||$id_departement<1)
			{
				return $result = array('code'=>0,'message'=>'Vueillez sélectionner le département et renseigner le nom de la commune');
			}
			$id_structure_type = isset($_POST['structure_type'])?self::$db->escape($_POST['structure_type']):'';
         	if($id_structure_type==0)
         	{
         		$new_structure_type_nom = isset($_POST['new_structure_type_nom'])?self::$db->escape($_POST['new_structure_type_nom']):'';
         		
         		if($new_structure_type_nom=='')
         		{
         			return $result = array('code'=>0,'message'=>'Vueillez renseigner le type de structure ou en créer un nouveau');
         		}
         		$structureType = new StructureType();
         		$structureType->setNom($new_structure_type_nom)
         				      ->dbSave($id_intervenant);
         		$id_structure_type = $structureType->id();
         	}
			if(trim($nom)=='' || $id_structure_type=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le type de la structure et son nom!');
			}
			$structure =  new Structure($id_structure);
			$structure->setNom($nom)
					  ->setDescription($description)
					  ->setTelephone($telephone)
					  ->setTelephoneAlt($telephone_alt)
					  ->setMail($mail)
					  ->setMailAlt($mail_alt)
					  ->setDepartementId($id_departement)
					  ->setStructureTypeId($id_structure_type);
			if($structure->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}					
		}
		return $result;
	}

	public static function processAddPostSubmit($id_intervenant)
	{
		$error_msg = '';
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$subject = (isset($_POST['subject']) and $_POST['subject']!='' )? self::$db->escape($_POST['subject']) : '';
			$message = isset($_POST['message'])?self::$db->escape($_POST['message']):'';
			$image_html_field_name = 'image_uploaded';
			$enabled = 0;
            if(isset($_POST['enabled']))
            {
            	$enabled = 1;
            }

			$file = new File(0,$image_html_field_name);
			$file->dbSave($id_intervenant);
			

			if($subject!='' || $message!='' || $file->id() > 0)
			{
				$post = new Post();
				$post->setSubject($subject)
					 ->setMessage($message)
					 ->setEnabled($enabled)
					 ->setFile($file);
				
				if($post->dbSave($id_intervenant))
				{
					$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
				}
				else
				{
					$result = array('code'=>0,'message'=>'Erreur lors de l\'enregistrement!');
				}
			}
			else
				$result = array('code'=>0,'message'=>'Veuillez renseigner au moins un champ du formulaire.');				
			
		}
		return $result;
	}

	public static function processUpdatePostSubmit($id_intervenant,$id_post)
	{
		$error_msg = '';
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			if($id_post<=0)
			{
				return $result = array('code'=>0,'message'=>'Veuillez sélectionner une publication');
			}

			$subject = (isset($_POST['subject']) and $_POST['subject']!='' )? self::$db->escape($_POST['subject']) : '';
			$message = isset($_POST['message'])?self::$db->escape($_POST['message']):'';
			$image_html_field_name = 'image_uploaded';
            
            $enabled = 0;
            if(isset($_POST['enabled']))
            {
            	$enabled = 1;
            }
			$post = new Post($id_post);

			$file = $post->file();
			$file->setConstructInfo($image_html_field_name);
			$file->dbSave($id_intervenant);
			

			if($subject!='' || $message!='' || $file->id() > 0)
			{
				$post->setSubject($subject)
					 ->setEnabled($enabled)
					 ->setMessage($message);					
				
				if($post->dbSave($id_intervenant))
				{
					$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
				}
				else
				{
					$result = array('code'=>0,'message'=>'Erreur lors de l\'enregistrement!');
				}
			}
			else
				$result = array('code'=>0,'message'=>'Veuillez renseigner au moins un champ du formulaire.');				
			
		}
		return $result;
	}

	public static function processDisableAllPostPostSubmit($id_intervenant)
	{
		$error_msg = '';
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$count  = 0;
            if(isset($_POST['disabled']))
            {
            	$posts = Post::allCreated();
            	foreach ($posts as $key => $post) {
            		$post->setEnabled(0)
            			 ->dbSave($id_intervenant);
            		$count++;
            	}
            }
            
            $result = array('code'=>1,'message'=>$count.' publications ont été mise(s) jour.');				
			
		}
		return $result;
	}

	public static function processAddSalleSubmit($id_intervenant)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			$nombre_place = isset($_POST['nombre_place'])?self::$db->escape($_POST['nombre_place']):0;
			
			if(trim($nom)=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le nom de la salle');
			}
			$salle =  new Salle();
			$salle->setNom($nom)
					  ->setDescription($description)
					  ->setNombrePlace($nombre_place) ;
			if($salle->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}
			
		}
		return $result;
	}
	
	public static function processUpdateSalleSubmit($id_intervenant,$id_salle)
	{
		$result = array('code'=>0,'message'=>'');
		if(isset($_POST['valider']))
		{
			if(!is_numeric($id_salle)||$id_salle<0)
			{
				return $result = array('code'=>0,'message'=>'Veuillez séléctionner une salle valide');
			}
			
			$nom = isset($_POST['nom'])?self::$db->escape($_POST['nom']):'';
			$description = isset($_POST['description'])?self::$db->escape($_POST['description']):'';
			$nombre_place = isset($_POST['nombre_place'])?self::$db->escape($_POST['nombre_place']):0;
			
			if(trim($nom)=='')
			{
				return $result = array('code'=>0,'message'=>'Veuillez renseigner le nom de la structure');
			}
			$salle =  new Salle($id_salle);
			$salle->setNom($nom)
					  ->setDescription($description)
					  ->setNombrePlace($nombre_place) ;
			if($salle->dbSave($id_intervenant))
			{
				$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
			}					
		}
		return $result;
	}
	
}

 

