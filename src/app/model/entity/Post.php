<?php
class Post {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'app_post';
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
	}
	public function id()
	{
	  return isset($this->data['id'])?$this->data['id']:0;
	}
	public function setSubject($subject)
	{
		$this->data['subject'] = $subject;
		return $this;
	}
	public function subject()
	{
		return isset($this->data['subject'])?$this->data['subject']:'';
	}
	public function setMessage($message)
	{
		$this->data['message'] = $message;
		return $this;
	}
	public function message()
	{
		return isset($this->data['message'])?$this->data['message']:'';
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
	public function setFile($file)
	{
		if(null!==$file and $file->id()>0)
		{
			$this->data['file'] = $file;
			$this->data['id_file'] = $file->id();
		}
		return $this;
	}
	public function setFileId($id_file)
	{
		if(is_numeric($id_file)&&$id_file>0)
			$this->data['id_file'] = $id_file;
		return $this;
	}
	public function file()
	{
		if(isset($this->data['file']))
		{
			return $this->data['file'];
		}
		else if(isset($this->data['id_file']))
		{
			$this->data['file'] = new File($this->data['id_file']);
			return $this->data['file'];
		}
		else return new File();
		
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
		if($this->subject()!='' || $this->message()!='' || $this->file()->id() > 0)
		{
			if($this->id()>0) // update
			{
				return $this->db->update(self::$db_table_name,array('subject'=>$this->subject(),'message'=>$this->message(),'enabled'=>$this->enabled(),'id_file'=>$this->file()->id()),array('id'=>$this->id()));
			}
			else if($id_intervenant>0)// nouvel enregistrement
			{
				$ok = $this->db->insertion(self::$db_table_name,'',$this->subject(),$this->message(),$this->file()->id(),$this->enabled(),$id_intervenant,Systeme::now());
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

	public static function allCreatedAsRecords($enabled_state = -1, $id_author = 0, $pageSize=-1,$page=1)
	{ 
		self::init();
		$records = array();
		$query_string = 'select distinct p.id id_post from '.self::$db_table_name.' p';
		
		if($enabled_state>=0)
		{
			if(!Systeme::str_contains($query_string,'where'))
				$query_string.=' where p.enabled = '.$enabled_state;
			else
				$query_string.=' AND p.enabled = '.$enabled_state;			
		}

		if($id_author>0)
		{
			if(!Systeme::str_contains($query_string,'where'))
				$query_string.=' where p.id_intervenant = '.$id_author;
			else
				$query_string.=' AND p.id_intervenant = '.$id_author;			
		}

		if($query_string!='')
		{
			$query_string.=' order by p.id desc';
			$query_string = DataBase::paginate($query_string,$pageSize,$page);			
		}

		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function allCreated($enabled_state = -1, $id_author = 0, $pageSize=-1,$page=1)
	{ 
		$records = self::allCreatedAsRecords($enabled_state, $id_author,$pageSize,$page);
		$rep = PostObjectBuilder::build(Systeme::array_key_values($records,'id_post'));
		return $rep;
	}
}
 

