<?php
class Pays {
    protected $db;
	protected $data=array('id'=>0);
	function __construct($id=0){
        $this->db = new DataBase();
		if(is_numeric($id)&&$id>0)
		{
			$this->queryData($id);
		}
		else $this->data = array('id'=>0);
    }
	private function queryData($id)
	{
		$this->data = $this->db->queryOneRecord('select p.* from pays p where p.rowid='.$id);
	}
	protected function data()
	{
	  return $this->data;
	}
	public function id()
	{
	  return isset($this->data['rowid'])?$this->data['rowid']:0;
	}
	public function code()
	{
		return isset($this->data['code'])?$this->data['code']:'';
	}
	public function nom($lang='fr')
	{
		return isset($this->data[$lang])?$this->data[$lang]:'';
	}
}
 

