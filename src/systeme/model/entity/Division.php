<?php
class Division {
	public static $static_db;
    protected $db;
	protected $data=array('id'=>0);
	function __construct($id=0){
        //$this->db = new DataBase();
        $this->db = DataBase::getInstance();
		if(is_numeric($id)&&$id>0)
		{
			$this->queryData($id);
		}
		else $this->data = array('id'=>0);
    }
	private function queryData($id)
	{
		$this->data = $this->db->queryOneRecord('select d.* from division d where d.id='.$id);
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
	public function setServiceId($id_service)
	{
		if(is_numeric($id_service) and $id_service>0)
			$this->data['id_service'] =  $id_service;
		return $this;
	}
	public function service()
	{
		return isset($this->data['id_service'])?(new Service($this->data['id_service'])):new Service(0);
	}
	
	public function setDesignation($designation)
	{
		$this->data['designation'] = $designation;
		return $this;
	}
	public function designation()
	{
		return isset($this->data['designation'])?$this->data['designation']:'';
	}
	public function nom()
	{
		return $this->designation();
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

	public static function init()
	{
		if(self::$static_db==null)	self::$static_db = DataBase::getInstance();	
	}

	public static function  allAsRecords($pageSize=-1, $page=1)
	{
		self::init();
		$records = array();
		$query_string = ' select d.id from division d order by d.designation ASC';
		$query_string = DataBase::paginate($query_string, $pageSize,$page);            
		$records = self::$static_db->queryAllRecords($query_string);
		return $records;
	}

	public static function all($pageSize=-1, $page=1)
	{
		$records = self::allAsRecords($pageSize, $page);
		$rep = array();
		foreach ($records as $key => $record) {
			$rep[] = new Division($record['id']);
		}
		return $rep;
	}
}
 

