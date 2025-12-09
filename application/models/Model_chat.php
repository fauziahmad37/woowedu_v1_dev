<?php

class Model_chat extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	//untuk daftar kelas bagi guru
	public function get_teacher_class($params=array())
	{ 
 		$teacher_id 				= $this->session->userdata('teacher_id');
		$this->db->select('k.class_name , k.class_id');
		$this->db->from('kelas k'); 
		$this->db->join('class_teacher ct', 'ct.class_id=k.class_id'); 
		$this->db->where('teacher_id', $teacher_id);   
		$this->db->order_by('k.class_name', 'asc');
		return $this->db->get()->result_array();
	}
	
	//untuk daftar guru
	public function get_teacher_list($params=array())
	{
		$class_id 				= $this->session->userdata('class_id');
		$this->db->select('t.teacher_id ,t.nik , t.teacher_name ');
		$this->db->from('teacher t'); 
		$this->db->join('class_teacher ct', 'ct.teacher_id=t.teacher_id'); 
		$this->db->where('class_id', $class_id);   
		$this->db->order_by('t.teacher_name', 'asc');
		return $this->db->get()->result_array();
	}	
	
	
	public function get_history($params=array()){
		$userid 				= $this->session->userdata('userid');
		$this->db->select('*');
		$this->db->from('users_chat'); 
		$this->db->where('uc_from',$params['uc_from']); 
		$this->db->order_by('uc_date', 'asc');
		$this->db->limit($params['limit'], $params['offset']);
		$query = $this->db->get();
		return $query->result_array();
	}

	  
	
}
