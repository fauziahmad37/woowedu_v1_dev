<?php

class Model_sesi extends CI_Model {
 
	public function get_sesi($filter = [], $limit = 0, $offset = 0)
	{
		if(isset($filter['id']))
			$this->db->where('sesi_id', $filter['id']);

		// filter kelas id
		if(isset($filter[0]['search']['value']) && !empty($filter[0]['search']['value']))
			$this->db->where('sesi.class_id', $filter[0]['search']['value']);

		$this->db->join('kelas k', 'k.class_id=sesi.class_id', 'left');
		$this->db->join('teacher t', 't.teacher_id=sesi.teacher_id', 'left');

		if($limit > 0)
			$this->db->limit($limit, $offset);

		$this->db->order_by('sesi_id', 'desc');

		return $this->db->get('sesi');
	}
 
	public function datasesi($param=array())
	{		
  
		$this->db->select('sesi_id,	sesi_title,	sesi_date,sesi_jam_start, sesi_jam_end,	teacher_id, k.class_name, s.subject_name');
		$this->db->from('sesi');	 
		$this->db->where('sesi_date >=',date("Y-m-d",strtotime($param['sdate']))); 
		$this->db->where('sesi_date <=',date("Y-m-d",strtotime($param['edate']))); 
		// $this->db->where('teacher_id',$param['teacher_id']);
		$this->db->where_in('sesi.class_id', $param['class_ids']);
		$this->db->join('kelas k', 'k.class_id=sesi.class_id', 'left');
		$this->db->join('subject s', 's.subject_id=sesi.subject_id', 'left');

		$query = $this->db->get();
		return $query;
	}

	public function data_sesi_student($param = []){
		$this->db->select('sesi_id,	sesi_title,	sesi_date, sesi_jam_start, sesi_jam_end, sesi.teacher_id, teacher_name, subject.subject_name, sesi_note');
		$this->db->from('sesi');
		$this->db->join('teacher', 'teacher.teacher_id = sesi.teacher_id', 'left');
		$this->db->join('subject', 'subject.subject_id = sesi.subject_id', 'left');
		$this->db->where('sesi_date >=', date("Y-m-d",strtotime($param['sdate']))); 
		$this->db->where('sesi_date <=', date("Y-m-d",strtotime($param['edate']))); 

		if(isset($param['class_id'])){
			$this->db->where('sesi.class_id', $param['class_id']);
		}

		// $this->db->where_in('sesi.teacher_id', $param['teacher_id']);

		$query = $this->db->get();
		return $query;
	}
	
	public function data_timeline($param = []){ 
		$this->db->select('sesi_id,	sesi_title,	sesi_date, sesi_jam_start, sesi_jam_end, sesi.teacher_id, teacher_name, subject.subject_name, sesi_note');
		$this->db->from('sesi');
		$this->db->join('teacher', 'teacher.teacher_id = sesi.teacher_id', 'left');
		$this->db->join('materi', 'materi.materi_id = sesi.materi_id', 'left');
		$this->db->join('subject', 'subject.subject_id = materi.subject_id', 'left');
		$this->db->where('sesi_date >=', date("Y-m-d",strtotime($param['sdate']))); 
		$this->db->where('sesi_date <=', date("Y-m-d",strtotime($param['edate']))); 

	  $this->db->where_in('sesi.teacher_id', $param['teacher_id']);

		$query = $this->db->get();
		return $query;
	}	

	public function get_absen_sesi($filter = []){
		if(isset($filter['sesi_id']) && !empty($filter['sesi_id']))
			$this->db->where('sesi_id', $filter['sesi_id']);

		if(isset($filter['student_id']) && !empty($filter['student_id']))
			$this->db->where('student_id', $filter['student_id']);

		return $this->db->get('sesi_attendances');
	}
	
}
