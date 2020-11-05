<?php
class TokenBundle {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'token_bundle';
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
	public function setTokenBundleType($tokenBundleType)
	{
		if(null!==$tokenBundleType)
			$this->data['id_token_bundle_type'] = $tokenBundleType->id();
		return $this;
	}
	public function setTokenBundleTypeId($tokenBundleTypeId)
	{
		if(is_numeric($tokenBundleTypeId) and $tokenBundleTypeId>0)
			$this->data['id_token_bundle_type'] =  $tokenBundleTypeId;
		return $this;
	}
	public function tokenBundleType()
	{
		return isset($this->data['id_token_bundle_type'])?(new TokenBundleType($this->data['id_token_bundle_type'])):new TokenBundleType(0);
	}
	public function setNumero($numero)
	{
		$this->data['numero'] = $numero;
		return $this;
	}
	public function numero()
	{
		return isset($this->data['numero'])?$this->data['numero']:'';
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
			return $this->db->update(self::$db_table_name,array('id_token_bundle_type'=>$this->tokenBundleType()->id(),'numero'=>$this->numero(),'description'=>$this->description()),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion(self::$db_table_name,'',$this->tokenBundleType()->id(),$this->numero(),$this->description(), $id_intervenant,Systeme::now());
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
		$query_string = ' select t.id from '.self::$db_table_name.' t order by t.id DESC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($pageSize, $page);
		$rep = TokenBundleObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}

	public static function  allDataAsRecords($pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.* from '.self::$db_table_name.' t order by t.id DESC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}
	
}
 

