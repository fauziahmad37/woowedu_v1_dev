<?php

class Model_task extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	public function get_tasks($limit, $offset, array $filters = []){

		$this->db->select('t.*, tc.teacher_name, sj.subject_name, k.class_name');
		$this->db->from('task t');
		$this->db->join('kelas k', 'k.class_id = t.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		// $this->db->limit('3');

		if(!empty($filters[0]['search']['value']))
			$this->db->where('t.teacher_id', $filters[0]['search']['value']);

		$this->db->limit($limit, $offset);
		$this->db->order_by('due_date', 'DESC');

		return $this->db->get()->result_array();
	}

	public function count_get_tasks(array $filters = []){

		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('task t');
		$this->db->join('kelas k', 'k.class_id = t.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		// $this->db->limit('3');

		if(!empty($filters[0]['search']['value']))
			$this->db->where('t.teacher_id', $filters[0]['search']['value']);

		$this->db->order_by('due_date', 'DESC');

		return $this->db->get()->num_rows();
	}

	public function get_tasks_detail($id){
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('task t');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		$this->db->where('t.task_id', $id);

		return $this->db->get()->row_array();
	}

	public function get_teacher_task_by_id($teacher_id){
		$this->db->select('t.*, tc.teacher_name, sj.subject_name');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = t.subject_id', 'left');
		$this->db->limit('9');
		$this->db->where('t.teacher_id', $teacher_id);

		return $this->db->get()->result_array();
	}
	
	
	public function get_student_task($limit = null, $page = null, $mapel, $startDate, $endDate){
		
		$classId = $this->session->userdata('class_id');
		$this->db->select('t.*, sj2.subject_name as subject_name2, tc.teacher_name, k.class_name');
		$this->db->from('task t');
		$this->db->join('kelas k', 'k.class_id = t.class_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj2', 'sj2.subject_id = t.subject_id', 'left'); 
		$this->db->where('t.class_id', $classId);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(t.available_date) <=', date('Y-m-d', strtotime($endDate)));
		
		if(!empty($endDate))
			$this->db->where('date(t.due_date) >=', date('Y-m-d', strtotime($startDate)));

		if(empty($endDate))
			$this->db->where('date(due_date) >=', date('Y-m-d'));

		$this->db->order_by('t.due_date', 'DESC');
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_student_total_task($mapel, $startDate, $endDate){
		
		$username 	= $this->session->userdata('username');  
		$this->db->select('t.task_id');
		$this->db->from('student s');
		$this->db->join('task t', 't.class_id = s.class_id', 'left');
		
		$this->db->where('nis', $username);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(t.available_date) <=', date('Y-m-d', strtotime($endDate)));
		
		if(!empty($endDate))
			$this->db->where('date(t.due_date) >=', date('Y-m-d', strtotime($startDate)));

		if(empty($endDate))
			$this->db->where('date(due_date) >=', date('Y-m-d'));

		$query = $this->db->get();
		return $query->num_rows();
	}	
	
	
	public function get_teacher_task($limit = null, $page = null, $mapel, $startDate, $endDate){
		
		$teacher_id 	= $this->session->userdata('teacher_id');
		$this->db->select('t.*, tc.teacher_name, sj.subject_name, sj2.subject_name as subject_name2, k.class_name');
		$this->db->from('task t');
		$this->db->join('kelas k', 'k.class_id = t.class_id', 'left');
		$this->db->join('materi m', 'm.materi_id = t.materi_id', 'left');
		$this->db->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left');
		$this->db->join('subject sj', 'sj.subject_id = m.subject_id', 'left');
		$this->db->join('subject sj2', 'sj2.subject_id = t.subject_id', 'left');
		$this->db->where('t.teacher_id', $teacher_id);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(t.available_date) <=', date('Y-m-d', strtotime($endDate)));
		
		if(!empty($endDate))
			$this->db->where('date(t.due_date) >=', date('Y-m-d', strtotime($startDate)));

		// if(empty($endDate))
		// 	$this->db->where('date(due_date) >=', date('Y-m-d'));
 
		$this->db->order_by('t.due_date', 'DESC');
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_teacher_total_task($mapel, $startDate, $endDate){
		
		$teacher_id 	= $this->session->userdata('teacher_id');  
		
		$this->db->where('teacher_id', $teacher_id);
		
		if(!empty($mapel))
			$this->db->where('t.subject_id',$mapel);

		if(!empty($startDate))
			$this->db->where('date(t.available_date) <=', date('Y-m-d', strtotime($endDate)));
		
		if(!empty($endDate))
			$this->db->where('date(t.due_date) >=', date('Y-m-d', strtotime($startDate)));

		// if(empty($endDate))
		// 	$this->db->where('date(due_date) >', date('Y-m-d'));

		$query = $this->db->get('task t');
		return $query->num_rows();
	}		
	
	public function get_mapel(){
		
		
		$this->db->select('s.subject_id, subject_name');
		$this->db->from('subject s');
		$this->db->where('sekolah_id', $_SESSION['sekolah_id']);
		
		$user_level 				= $this->session->userdata('user_level');
		
		// jika user login sebagai guru
		if($user_level == 3 ){
			$teacher_id 	= $this->session->userdata('teacher_id'); 
			$this->db->join('subject_teacher st','s.subject_id=st.subject_id');
			$this->db->where('teacher_id',$teacher_id);
		}elseif($user_level == 4 ){
		// jika user login sebagai murid
			$class_level_id 	= $this->session->userdata('class_level_id');  
			$this->db->where('class_level_id',$class_level_id);			
		}
		return $this->db->get()->result_array();
	}

	public function get_all_siswa_task($task_id){
		$class_id = $this->get_tasks_detail($task_id)['class_id'];

		$this->db->select('*');
		$this->db->from('student s');
		$this->db->where('s.class_id', $class_id);
		return $this->db->get()->result_array();
	}

}
