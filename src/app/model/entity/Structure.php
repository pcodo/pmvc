<?php
class Structure {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'app_structure';
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
	public function telephone()
	{
		return isset($this->data['telephone'])?$this->data['telephone']:'';
	}
	public function setTelephone($telephone)
	{
		$telephone = preg_replace('#(\s)#', '', $telephone);
		$this->data['telephone'] = $telephone;
		return $this;
	}
	public function telephoneAlt()
	{
		return isset($this->data['telephone_alt'])?$this->data['telephone_alt']:'';
	}
	public function setTelephoneAlt($telephone_alt)
	{
		$telephone_alt = preg_replace('#(\s)#', '', $telephone_alt);
		$this->data['telephone_alt'] = $telephone_alt;
		return $this;
	}
	public function mail()
	{
		return isset($this->data['mail'])?$this->data['mail']:'';
	}
	public function setMail($mail)
	{
		$mail = preg_replace('#(\s)#', '', $mail);
		$this->data['mail'] = $mail;
		return $this;
	}
	public function mailAlt()
	{
		return isset($this->data['mail_alt'])?$this->data['mail_alt']:'';
	}
	public function setMailAlt($mail_alt)
	{
		$mail_alt = preg_replace('#(\s)#', '', $mail_alt);
		$this->data['mail_alt'] = $mail_alt;
		return $this;
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
	public function setStructureType($structureType)
	{
		if(null!==$structureType)
			$this->data['id_structure_type'] = $structureType->id();
		return $this;
	}
	public function setStructureTypeId($structureTypeId)
	{
		if(is_numeric($structureTypeId) and $structureTypeId>0)
			$this->data['id_structure_type'] =  $structureTypeId;
		return $this;
	}
	public function structureType()
	{
		return isset($this->data['id_structure_type'])?(new StructureType($this->data['id_structure_type'])):new StructureType(0);
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
				return $this->db->update(self::$db_table_name,array('nom'=>$this->nom(),'description'=>$this->description(),'telephone'=>$this->telephone(),'telephone_alt'=>$this->telephoneAlt(),'mail'=>$this->mail(),'mail_alt'=>$this->mailAlt(),'id_departement'=>$this->departement()->id(),'id_structure_type'=>$this->structureType()->id()),array('id'=>$this->id()));
			}
			else if($id_intervenant>0)// nouvel enregistrement
			{
				$ok = $this->db->insertion(self::$db_table_name,'',$this->nom(),$this->description(),$this->telephone(),$this->telephoneAlt(),$this->mail(), $this->mailAlt(), $this->departement()->id(), $this->structureType()->id(), $id_intervenant,Systeme::now());
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
		$rep = StructureObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}
	public static function searchAsRecords($params = array(),$pageSize=-1,$page=1)
	{
		self::init();
		$nom = array_key_exists('nom', $params)?trim($params['nom']):'';    	
    	$query_string = '';

    	if($nom!='')
		{
			$query_string = 'select distinct s.id from '.self::$db_table_name.' s where LOWER(s.nom) like "%'.strtolower($nom).'%" OR LOWER(s.description) like "%'.strtolower($nom).'%"';
		}

		$records = array();
		if($query_string!='')
		{
			$query_string = Database::paginate($query_string,$pageSize,$page);
			$records = self::$static_db->queryAllRecords($query_string);
		}		
		
		return $records;
	}

	public static function search($params = array(), $pageSize=-1, $page=1)
    {
    	$records = self::searchAsRecords($params, $pageSize, $page);
		$rep = StructureObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
    }
}
 

