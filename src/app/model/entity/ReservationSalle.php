<?php
class ReservationSalle {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'app_reservation_salle';
	function __construct($id=0){
        $this->db = DataBase::getInstance();
		if(is_numeric($id)&&$id>0)
		{
			$this->queryData($id);
		}
		else $this->data = array('id'=>0);
    }
	private function queryData($id)
	{
		$this->data = $this->db->queryOneRecord('select t.* from '.self::$db_table_name.' t where t.id='.$id);
	}
	protected function data()
	{
	  return $this->data;
	}
	public function setData($data)
	{
		if($data!==null && count($data)>0) $this->data = $data;
		return $this;
	}
	public function id()
	{
	  return isset($this->data['id'])?$this->data['id']:0;
	}
	public function setDemande($demande)
	{
		if(null!==$demande)
			$this->data['id_demande'] = $demande->id();
		return $this;
	}
	public function setDemandeId($demandeId)
	{
		if(is_numeric($demandeId) and $demandeId>0)
			$this->data['id_demande'] =  $demandeId;
		return $this;
	}
	public function demande()
	{
		return isset($this->data['id_demande'])?(new Demande($this->data['id_demande'])):new Demande(0);
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
	public function setSalle($salle)
	{
		if(null!==$salle)
			$this->data['id_salle'] = $salle->id();
		return $this;
	}
	public function setSalleId($salleId)
	{
		if(is_numeric($salleId) and $salleId>0)
			$this->data['id_salle'] =  $salleId;
		return $this;
	}
	public function salle()
	{
		return isset($this->data['id_salle'])?(new Salle($this->data['id_salle'])):new Salle(0);
	}
    
	public function dbSave($id_intervenant)
	{
		
		if($this->id()>0) // update
		{
			return $this->db->update(self::$db_table_name,array('id_demande'=>$this->demande()->id(),'id_salle'=>$this->salle()->id(),'date_debut'=>$this->dateDebut(),'date_fin'=>$this->dateFin()),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion(self::$db_table_name,'',$this->demande()->id(),$this->salle()->id(), $this->dateDebut(), $this->dateFin(),$id_intervenant,Systeme::now());
			$this->queryData($this->db->lastTabId(self::$db_table_name));
			return $ok;
		}		
		
	}
	public function dateDebutFr()
	{
		return Systeme::dateTimeToFrench($this->dateDebut());
	}
	public function dateFinFr()
	{
		return Systeme::dateTimeToFrench($this->dateFin());
	}
	public function horaire()
	{
		$dateTimeDebut = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateDebut());
		$dateTimeFin = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateFin());
		if($this->dateDebutWithoutTimes()==$this->DateFinWithoutTimes())
		{			
			return Systeme::dateToFrench($this->dateDebutWithoutTimes()).' de '.$dateTimeDebut->format('H:i').' à '.$dateTimeFin->format('H:i');
		}
		else
		{
			return 'du '.$this->dateDebutFr().' à '.$this->dateFinFr();
		}
	}
	public function dateDebutWithoutTimes()
	{
		$dateTimeDebut = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateDebut());
		return $dateTimeDebut->format('Y-m-d');
	}
	public function heureDebut()
	{
		$dateTimeDebut = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateDebut());
		return intval($dateTimeDebut->format('H'));
	}
	public function minuteDebut()
	{
		$dateTimeDebut = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateDebut());
		return intval($dateTimeDebut->format('i'));
	}
	public function secondeDebut()
	{
		$dateTimeDebut = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateDebut());
		return intval($dateTimeDebut->format('s'));
	}
	public function DateFinWithoutTimes()
	{
		$dateTimeFin = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateFin());
		return $dateTimeFin->format('Y-m-d');
	}
	public function heureFin()
	{
		$dateTimeFin = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateFin());
		return intval($dateTimeFin->format('H'));
	}
	public function minuteFin()
	{
		$dateTimeFin = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateFin());
		return intval($dateTimeFin->format('i'));
	}
	public function secondeFin()
	{
		$dateTimeFin = DateTime::createFromFormat('Y-m-d H:i:s',$this->dateFin());
		return intval($dateTimeFin->format('s'));
	}


	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();	
	}

	public static function  allAsRecords($id_demande, $pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_demande = '.$id_demande.' order by t.id ASC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($id_demande, $pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($id_demande, $pageSize, $page);
		$rep = ReservationSalleObjectBuilder::build(Systeme::array_key_values($records,'id'),array('key'=>'id','order'=>'ASC'));
		return $rep;
	}

	public function searchAsRecords($date_debut,$date_fin,$pageSize=-1, $page=1)
	{

	}
	
}
 

