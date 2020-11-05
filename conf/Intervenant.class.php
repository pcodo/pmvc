<?php
class Intervenant{
    var $db;
	var $data=array('id'=>0);
	function __construct($id_intervenant){
        $this->db = new DataBase();
		if(is_numeric($id_intervenant)&&$id_intervenant>0)
		{
			$this->data = $this->db->queryOneRecord('select inter.*, p.designation poste from `intervenant` inter left join `poste` p on p.id = inter.id_poste where inter.id='.$id_intervenant);
			$this->sous_menus=array();
		}
		else $this->data = array('id'=>0);
    }
	public function data()
	{
	  return $this->data;
	}
	public function id()
	{
	  return isset($this->data['id'])?$this->data['id']:0;
	}
	public function nom()
	{
		return isset($this->data['nom'])?$this->data['nom']:'';
	 
	}
	public function prenom()
	{
	   return isset($this->data['prenom'])?$this->data['prenom']:'';
	}
	public function fullName()
	{
		return $this->nom().' '.$this->prenom();
	}
	public function id_poste()
	{
		return isset($this->data['id_poste'])?$this->data['id_poste']:0;
	}
	public function poste()
	{
		return isset($this->data['poste'])?$this->data['poste']:'';
	}
	public function password()
	{
		return isset($this->data['mdp'])?$this->data['mdp']:'';		
	}
	// retourne les menus classés par catégorie de l'intervenant
	public function nav_menus()
	{
	    return Systeme::nav_menus($this->id_poste());
	}
	// retournes les sous_menus de l'intervenant selon le menu cliqué
	public function sous_menus($id_menu=0)
	{
	    $this->sous_menus = Systeme::sous_menus($id_menu,$this->id_poste());
		return $this->sous_menus;
	}
	public function hasSousMenu($id_sm)
	{
		foreach($this->sous_menus as $sm)
		{
			if($sm['id']==$id_sm) return true;
		}
		return false;
	}

	public function interimaire()
	{
		return IntervenantInterimaire::userInterimaireInter($this->id());		
	}
	
}
 

