<?php
/*
  Rôle: Gère les processus d'enregistrement des tokens et affiliés
  Auteur: CODO Paterne
  Date de création:16/01/2018
  
*/
class TokenFormManager {
	public static $db;
    public function __construct(){
	    
	}
	public static function init()
	{
		self::$db = DataBase::getInstance();
	}
	public static function processForwardTokens($target_user_id,$tokens,$id_intervenant)
    {
    	$result = array('code'=>0,'message'=>'');
    	
    	if($target_user_id<=0)
    	{
    		return $result = array('code'=>0,'message'=>'Vueillez sélectionner un destinataire');
    	}
    	if($tokens===null or count($tokens)==0)
    	{
    		return $result = array('code'=>0,'message'=>'Vueillez sélectionner au moins un élément');
    	}
    	if($id_intervenant<=0)
    	{
    		return $result = array('code'=>0,'message'=>'Vueillez fournir l\'intervenant connecté!');
    	}
    	$success_count = 0;
       	foreach ($tokens as $key => $token) {
            $tokenForward = new TokenForward();
    		$tokenForward->setToken($token)
    					 ->setTargetUserId($target_user_id);
    		if($tokenForward->dbSave($id_intervenant))
    		{
    			$success_count++;
    		}
            // mise à jour de TokenForward pour spécifier qu'un l'élément a été reçu, vu et retransmis
            self::$db->execute('update '.TokenForward::$db_table_name.' set viewed = 1, received = 1, id_next_forward = '.$tokenForward->id().' where id_token = '.$token->tokenId().' and id_target_user = '.$id_intervenant.' and id < '.$tokenForward->id());
            
    	}
    	if($success_count==count($tokens))
    	{
    		$result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
    	}
    	return $result;
    }	

    public static function processChangeTokensState($new_token_state_id, $observation, $tokens,$id_intervenant)
    {
        $result = array('code'=>0,'message'=>'');
        
        if(!TokenState::isValidTokenStateId($new_token_state_id))
        {
            return $result = array('code'=>0,'message'=>'Vueillez chosir une action valide');
        }
        if($tokens===null or count($tokens)==0)
        {
            return $result = array('code'=>0,'message'=>'Vueillez sélectionner au moins un élément');
        }
        if($id_intervenant<=0)
        {
            return $result = array('code'=>0,'message'=>'Vueillez fournir l\'intervenant connecté!');
        }
        $success_count = 0;
        foreach ($tokens as $key => $token) {
            $tokenProgress = new TokenProgress();
            $tokenProgress->setToken($token)
                         ->setTokenStateId($new_token_state_id)
                         ->setTokenForwardId($token->currentForwardId()) // faisons reference à la transmission lui permettant de récevoir l'élément afin de lui changer d'état
                         ->setObservation($observation);
            if($tokenProgress->dbSave($id_intervenant))
            {
                $success_count++;
            }
            // mise à jour de TokenForward pour spécifier qu'un l'élément a été reçu et vu
            self::$db->update(TokenForward::$db_table_name,array('viewed'=>1,'received'=>1), array('id_token'=>$token->tokenId(),'id_target_user'=>$id_intervenant));
            
        }        

        if($success_count==count($tokens))
        {
            $result = array('code'=>1,'message'=>'Enregistrement bien effectué!');
        }
        return $result;
    }   
}

 

