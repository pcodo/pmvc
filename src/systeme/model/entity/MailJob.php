<?php
class MailJob {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'mail_job';
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
	public function setObjet($objet)
	{
		$this->data['objet'] = trim($objet);
		return $this;
	}
	public function objet()
	{
		return isset($this->data['objet'])?$this->data['objet']:'';
	}
	public function setMessage($message)
	{
		$this->data['message'] = trim($message);
		return $this;
	}
	public function message()
	{
		return isset($this->data['message'])?$this->data['message']:'';
	}	
	public function setAttachement($attachement)
	{
		$this->data['attachement'] = trim($attachement);
		return $this;
	}
	public function attachement()
	{
		return isset($this->data['attachement'])?$this->data['attachement']:'';
	}
	public function setSource($source)
	{
		$this->data['source'] = trim($source);
		return $this;
	}
	public function source()
	{
		return isset($this->data['source'])?$this->data['source']:'';
	}
	public function setDestinataire($destinataire)
	{
		$this->data['destinataire'] = trim($destinataire);
		return $this;
	}
	public function destinataire()
	{
		return isset($this->data['destinataire'])?$this->data['destinataire']:'';
	}	
	public function setSent($sent)
	{
		$this->data['sent'] = $sent;
		return $this;
	}
	public function sent()
	{
		return isset($this->data['sent'])?$this->data['sent']:0;
	}
	public function sentDate()
	{
		return isset($this->data['sent_date'])?$this->data['sent_date']:'';
	}	
	public function sentUser()
	{
		return isset($this->data['sent_user'])?(new Intervenant($this->data['sent_user'])):(new Intervenant(0));
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
		$sent_date = '';
		$sent_user = 0;
		if($this->sent())
		{
			$sent_user = $id_intervenant;
			$sent_date = Systeme::now();
		}
		if($this->id()>0) // update
		{
			return $this->db->update(self::$db_table_name,array('objet'=>$this->objet(),'message'=>$this->message(),'attachement'=>$this->attachement(),'source'=>$this->source(),'destinataire'=>$this->destinataire(),'sent'=>$this->sent(),'sent_user'=>$sent_user,'sent_date'=>$sent_date),array('id'=>$this->id()));
		}
		else if($id_intervenant>0)// nouvel enregistrement
		{
			$ok = $this->db->insertion(self::$db_table_name,'',$this->objet(),$this->message(),$this->attachement(),$this->source(),$this->destinataire(),$this->sent(),$sent_user,$sent_date,$id_intervenant,Systeme::now());
			$this->queryData($this->db->lastTabId(self::$db_table_name));
			return $ok;
		}
				
	}
	
	
	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();	
	}

	public static function  allAsRecords($sent = 0,$pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t where t.sent = '.$sent.' order by t.id ASC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($sent = 0,$pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($sent,$pageSize, $page);
		$rep = MailJobObjectBuilder::build(Systeme::array_key_values($records,'id'));
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
	
}
 

