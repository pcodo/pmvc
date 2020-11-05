<?php
/*
  Rôle: Gère la soumission des formulaires en ce qui concerne le contrôle du code de retour et la fermeture de la boîte de dialogue
  Auteur: CODO Paterne
  Date de création:06/04/2018
  
*/
class AppFormErrorController extends SystemeFormErrorController{
	public function __construct(){
	    
	}
	public static function init()
	{
		self::$db = DataBase::getInstance();
	}
	
}

 

