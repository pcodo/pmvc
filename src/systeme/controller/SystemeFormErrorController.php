<?php
/*
  Rôle: Gère la soumission des formulaires en ce qui concerne le contrôle du code de retour et la fermeture de la boîte de dialogue
  Auteur: CODO Paterne
  Date de création:06/04/2018
  
*/
class SystemeFormErrorController {
	public static $db;
    public function __construct(){
	    
	}
	public static function init()
	{
		self::$db = DataBase::getInstance();
	}
	public static function checkSubmitResult($result)
	{
		if($result==null or count($result) <= 0 or !array_key_exists('code', $result) or !array_key_exists('message', $result))
			Systeme::debug('Le tableau de résultat est invalide');
		
		if($result['code'])
		{
			$flashMessage = '<span style=\"color:yellow;\">'.$result['message'].'</span>';
			echo '<script> $(function(){ $("#form_header").html("'.$flashMessage.'"); setTimeout("parent.$.fancybox.close()",2000); parent.location.reload();});</script>';
		}
		else
		{
			$flashMessage = '<h3 style=\"color:pink;font-weight:bold;\">'.$result['message'].'</h3>';
			echo '<script> $(function(){ $("#form_header h3").html("'.$flashMessage.'");}); </script>';
		}
	}
	
}

 

