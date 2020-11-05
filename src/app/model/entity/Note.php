<?php
class Note {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	public static $db_table_name = 'app_note';
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
		$this->data['objet'] = $objet;
		return $this;
	}
	public function objet()
	{
		return isset($this->data['objet'])?$this->data['objet']:'';
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
	public function setDemande($demande)
	{
		if(null!==$demande)
			$this->data['id_demande'] = $demande->id();
		return $this;
	}
	public function setDemandeId($id_demande)
	{
		if(is_numeric($id_demande) and $id_demande>0)
			$this->data['id_demande'] =  $id_demande;
		return $this;
	}
	public function demande()
	{
		return isset($this->data['id_demande'])?(new Demande($this->data['id_demande'])):new Demande(0);
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
		if($this->objet()!='')
		{
			if($this->id()>0) // update
			{
				return $this->db->update(self::$db_table_name,array('id_demande'=>$this->demande()->id(),'objet'=>$this->objet(),'description'=>$this->description(),'id_file'=>$this->file()->id()),array('id'=>$this->id()));
			}
			else if($id_intervenant>0)// nouvel enregistrement
			{
				$ok = $this->db->insertion(self::$db_table_name,'',$this->demande()->id(),$this->objet(),$this->description(), $this->file()->id(), $id_intervenant,Systeme::now());
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

	public static function  allAsRecords($id_demande, $pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select t.id from '.self::$db_table_name.' t where t.id_demande = '.$id_demande.' order by t.id DESC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($id_demande, $pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($id_demande, $pageSize, $page);
		$rep = NoteObjectBuilder::build(Systeme::array_key_values($records,'id'));
		return $rep;
	}
	public static function findOne($id_note)
	{
		$rep = NoteObjectBuilder::build(array($id_note));
		if(count($rep)>0)
		{
			return $rep[0];
		}
		else return null;
	}
}
 

