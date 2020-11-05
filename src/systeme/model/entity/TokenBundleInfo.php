<?php
class TokenBundleInfo {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'token_bundle_info';
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
	public function setToken($token)
	{
		if(null!==$token)
			$this->data['id_token'] = $token->tokenId();
		return $this;
	}
	public function setTokenId($tokenId)
	{
		if(is_numeric($tokenId) and $tokenId>0)
			$this->data['id_token'] =  $tokenId;
		return $this;
	}
	public function token()
	{
		return isset($this->data['id_token'])?(new Token($this->data['id_token'])):new Token(0);
	}
	public function setTokenBundle($tokenBundle)
	{
		if(null!==$tokenBundle)
			$this->data['id_token'] = $tokenBundle->id();
		return $this;
	}
	public function setTokenBundleId($tokenBundleId)
	{
		if(is_numeric($tokenBundleId) and $tokenBundleId>0)
			$this->data['id_token_bundle'] =  $tokenBundleId;
		return $this;
	}
	public function tokenBundle()
	{
		return isset($this->data['id_token_bundle'])?(new TokenBundle($this->data['id_token_bundle'])):new TokenBundle(0);
	}

	public function setValidState($valid_state)
	{
		$this->data['valid_state'] = $valid_state;
		return $this;
	}
	public function validState()
	{
		return isset($this->data['valid_state'])?$this->data['valid_state']:0;
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
			return $this->db->update(self::$db_table_name,array('id_token'=>$this->token()->tokenId(),'id_token_bundle'=>$this->tokenBundle()->id(),'valid_state'=>$this->validState()),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion(self::$db_table_name,'',$this->token()->tokenId(), $this->tokenBundle()->id(), $this->validState(), $id_intervenant,Systeme::now());
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
		$rep = TokenBundleInfoObjectBuilder::build(Systeme::array_key_values($records,'id'));
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
 

