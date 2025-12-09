<?php

class Model_notif extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	public function get_sesi($filter = []){
		if(isset($filter['sekolah_id']))
			$this->db->where('k.sekolah_id', $filter['sekolah_id']);

		if(isset($filter['class_id']))
			$this->db->where('sesi.class_id', $filter['class_id']);

		if(isset($filter['teacher_id']))
			$this->db->where('sesi.teacher_id', $filter['teacher_id']);
			
		$this->db->where('DATE(sesi_date) >=', date("Y-m-d", strtotime("-1 months")));
		$this->db->join('kelas k', 'k.class_id = sesi.class_id', 'left');

		return $this->db->get('sesi');
	}

	public function get_task($filter = []){
		if(isset($filter['sekolah_id']))
			$this->db->where('k.sekolah_id', $filter['sekolah_id']);

		if(isset($filter['class_id']))
			$this->db->where('t.class_id', $filter['class_id']);
		
		if(isset($filter['teacher_id']))
			$this->db->where('t.teacher_id', $filter['teacher_id']);

		$this->db->where('DATE(available_date) >=', date("Y-m-d", strtotime("-1 months")));
		$this->db->join('kelas k', 'k.class_id = t.class_id', 'left');

		return $this->db->get('task t');
	}

	public function get_notif($filter = []){
		if(isset($filter['type']))
			$this->db->where('type', $filter['type']);
		
		if(isset($filter['sesi_id']))
			$this->db->where('sesi_id', $filter['sesi_id']);
		
		if(isset($filter['task_id']))
			$this->db->where('task_id', $filter['task_id']);
		
		if(isset($filter['user_id']))
			$this->db->where('user_id', $filter['user_id']);
		
		return $this->db->get('notif');
	}
}
