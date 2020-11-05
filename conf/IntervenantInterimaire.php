<?php
class IntervenantInterimaire {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	function __construct($id=0){
        $this->db = new DataBase();
		if(is_numeric($id)&&$id>0)
		{
			$this->queryData($id);
		}
		else $this->data = array('id'=>0);
    }
	private function queryData($id)
	{
		$this->data = $this->db->queryOneRecord('select t.* from intervenant_interimaire t where t.id='.$id);
	}
	protected function data()
	{
	  return $this->data;
	}
	public function setData($data)
	{
		if($data!==null && count($data)>0) $this->data = $data;
	}
	public function id()
	{
	  return isset($this->data['id'])?$this->data['id']:0;
	}
	public function setLeavingInterId($id_leaving_inter)
	{
		$this->data['id_leaving_inter'] = $id_leaving_inter;
		return $this;
	}
	public function leavingInterId()
	{
		return isset($this->data['id_leaving_inter'])?$this->data['id_leaving_inter']:0;
	}
	public function setInterimaireInterId($id_interimaire_inter)
	{
		$this->data['id_interimaire_inter'] = $id_interimaire_inter;
		return $this;
	}
	public function interimaireInterId()
	{
		return isset($this->data['id_interimaire_inter'])?$this->data['id_interimaire_inter']:0;
	}
	public function setDateDebut($date_debut)
	{
		$this->data['date_debut'] = $date_debut;
		return $this;
	}
	public function dateDebut()
	{
		return isset($this->data['date_debut'])?$this->data['date_debut']:'';
	}
	public function setDateFin($date_fin)
	{
		$this->data['date_fin'] = $date_fin;
		return $this;
	}
	public function dateFin()
	{
		return isset($this->data['date_fin'])?$this->data['date_fin']:'';
	}
	public function setEnabled($enabled)
	{
		$this->data['enabled'] = $enabled;
		return $this;
	}
	public function enabled()
	{
		return isset($this->data['enabled'])?$this->data['enabled']:'';
	}	
    public function insertDate()
	{
		return isset($this->data['date_enregistrement'])?$this->data['date_enregistrement']:'';
	}
	public function insertUser()
	{
		return isset($this->data['id_intervenant'])?(new Intervenant($this->data['id_intervenant'])):(new Intervenant(0));
	}
	public function dbSave($id_intervenant)
	{
		if($this->id()>0) // update
		{
			return $this->db->update('intervenant_interimaire',array('id_leaving_inter'=>$this->leavingInterId(),'id_interimaire_inter'=>$this->interimaireInterId(),'date_debut'=>$this->dateDebut(),'date_fin'=>$this->dateFin(),'enabled'=>$this->enabled()),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion('intervenant_interimaire','',$this->leavingInterId(),$this->interimaireInterId(),$this->dateDebut(),$this->dateFin(),$this->enabled(),$id_intervenant,Systeme::now());
			$this->queryData($this->db->lastTabId('intervenant_interimaire'));
			return $ok;
		}
		
		
	}

	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = new DataBase();		
	}

	public static function allUserInterimaireAsRecords($id_user, $id_interimaire = 0, $pageSize=-1,$page=1)
	{ 
		self::init();
		$records = array();
		/*$query_string = 'select distinct t.id from intervenant_interimaire t where t.enabled = 1 and t.id_leaving_inter= '.$id_user;
		
		if($id_interimaire>0)
		{
			$query_string.=' and t.id_interimaire_inter = '.$id_interimaire;
		}
		if($query_string!='')
		{
			$query_string.=' order by t.id desc';
			$query_string = DataBase::paginate($query_string,$pageSize,$page);			
		}

		$records = self::$static_db->queryAllRecords($query_string);
		*/
		return $records;
	}

	public static function allUserInterimaire($id_user, $id_interimaire = 0, $pageSize=-1,$page=1)
	{ 
		$records = self::allUserInterimaireAsRecords($id_user, $id_interimaire,$pageSize,$page);
		$rep = IntervenantInterimaireObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}
	public static function userInterimaire($id_user)
	{
		$alls = self::allUserInterimaire($id_user);
		if(count($alls)==1)
		{
			return $alls[0];
		}
		return new IntervenantInterimaire(0);
	}

	public static function userInterimaireInter($id_user)
	{
		$userInterimaire = self::userInterimaire($id_user);
		return new Intervenant($userInterimaire->interimaireInterId());
	}

	public static function disableAllUserInterimaire($id_user)
	{
		self::init();
		return self::$static_db->update('intervenant_interimaire',array('date_fin'=>Systeme::now(),'enabled'=>0),array('id_leaving_inter'=>$id_user));
	}


	public function allUserOfInterimaireAsRecords($id_interimaire,$pageSize=-1,$page=1)
	{
		self::init();
		$records = array();
		$query_string = 'select distinct t.id from intervenant_interimaire t where t.enabled = 1 and t.id_interimaire_inter = '.$id_interimaire;
		
		if($query_string!='')
		{
			$query_string.=' order by t.id desc';
			$query_string = DataBase::paginate($query_string,$pageSize,$page);			
		}
        
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public function AllUserOfInterimaire($id_interimaire,$pageSize=-1,$page=1)
	{
		$records = self::allUserOfInterimaireAsRecords($id_interimaire,$pageSize,$page);
		$rep = IntervenantInterimaireObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function allUserOfInterimaireInter($id_interimaire)
	{
		$alls = self::AllUserOfInterimaire($id_interimaire);
		$rep = array();
		foreach ($alls as $key => $t) {
			$rep[] = new Intervenant($t->leavingInterId());
		}
		return $rep;
	}

}
 

