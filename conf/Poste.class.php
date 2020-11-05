<?php
class Poste{
    var $db;
	var $data=array('id'=>0);
	function __construct($id_poste){
        $this->db = new DataBase();
		if(is_numeric($id_poste)&&$id_poste>0)
		{
			$this->data = $this->db->queryOneRecord('select p.* from poste p where p.id='.$id_poste);
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
		return isset($this->data['designation'])?$this->data['designation']:'';
	}
	public function description()
	{
		return isset($this->data['description'])?$this->data['description']:'';
	}
	public function isChefDirection()
	{
		return isset($this->data['chef_direction_state'])?$this->data['chef_direction_state']==1:false;
	}
	
}
 

