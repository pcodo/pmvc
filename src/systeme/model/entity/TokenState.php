<?php
class TokenState {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'token_state';
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
	public function setName($name)
	{
		$this->data['name'] = $name;
		return $this;
	}
	public function name()
	{
		return isset($this->data['name'])?$this->data['name']:'';
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
	public function setNameFr($name_fr)
	{
		$this->data['name_fr'] = $name_fr;
		return $this;
	}
	public function nameFr()
	{
		return isset($this->data['name_fr'])?$this->data['name_fr']:'';
	}
	public function setDescriptionFr($description_fr)
	{
		$this->data['description_fr'] = $description_fr;
		return $this;
	}
	public function descriptionFr()
	{
		return isset($this->data['description_fr'])?$this->data['description_fr']:'';
	}	
	
	public function dbSave($id_intervenant)
	{
		
		if($this->id()>0) // update
		{
			return $this->db->update(self::$db_table_name,array('name'=>$this->name(),'description'=>$this->description(),'name_fr'=>$this->name(),'description_fr'=>$this->description()),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion(self::$db_table_name,'',$this->name(),$this->description(),$this->nameFr(),$this->descriptionFr());
			$this->queryData($this->db->lastTabId(self::$db_table_name));
			return $ok;
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
		$query_string = ' select t.id from '.self::$db_table_name.' t order by t.id ASC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($pageSize, $page);
		$rep = TokenStateObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function  allDataAsRecords($pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.* from '.self::$db_table_name.' t order by t.nom ASC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}
	
	public static function isValidTokenStateId($token_state_id)
	{
		return ($token_state_id!==null && is_numeric($token_state_id) && $token_state_id >0 && $token_state_id < 14);
	}
}
 

