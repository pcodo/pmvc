<?php
class Commune {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'app_commune';
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
	public function setNom($nom)
	{
		$this->data['nom'] = $nom;
		return $this;
	}
	public function nom()
	{
		return isset($this->data['nom'])?$this->data['nom']:'';
	}
	public function setDescription($description)
	{
		$this->data['description'] = $description;
		return $this;
	}
	public function description()
	{
		return isset($this->data['description'])?$this->data['description']:'';
	}
	public function setDepartement($departement)
	{
		if(null!==$departement)
			$this->data['id_departement'] = $departement->id();
		return $this;
	}
	public function setDepartementId($departementId)
	{
		if(is_numeric($departementId) and $departementId>0)
			$this->data['id_departement'] =  $departementId;
		return $this;
	}
	public function departement()
	{
		return isset($this->data['id_departement'])?(new Departement($this->data['id_departement'])):new Departement(0);
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
		if($this->nom()!='')
		{
			if($this->id()>0) // update
			{
				return $this->db->update(self::$db_table_name,array('nom'=>$this->nom(),'description'=>$this->description(),'id_departement'=>$this->departement()->id()),array('id'=>$this->id()));
			}
			else if($id_intervenant>0)// nouvel enregistrement
			{
				$ok = $this->db->insertion(self::$db_table_name,'',$this->nom(),$this->description(),$this->departement()->id(), $id_intervenant,Systeme::now());
				$this->queryData($this->db->lastTabId(self::$db_table_name));
				return $ok;
			}
		}
		else
		{
			return false;
		} 
		
	}
	

	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();	
	}

	public static function  allAsRecords($pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t order by t.nom ASC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($pageSize, $page);
		$rep = CommuneObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}
	public static function findOne($id_commune)
	{
		$rep = CommuneObjectBuilder::build(array($id_commune));
		if(count($rep)>0)
		{
			return $rep[0];
		}
		else return null;
	}
}
 

