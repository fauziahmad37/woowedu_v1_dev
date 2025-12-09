<?php

class Model_student extends CI_Model {

	public function __construct(){ 
        parent::__construct();
    }

	public function getAll($limit = null, $page = null, $filter = []){
		if(!empty($filter['namaSiswa']))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($filter['namaSiswa'])).'%\'', NULL, FALSE);

		if(!empty($filter['class_id']))
			$this->db->where('s.class_id', $filter['class_id']);

		$this->db->where('s.sekolah_id', $_SESSION['sekolah_id']);
		$this->db->select('s.*, k.class_name');
		$this->db->from('student s');
		$this->db->join('kelas k', 's.class_id=k.class_id', 'left');
		$this->db->order_by('student_name', 'asc');

		if(!empty($limit))
			$this->db->limit($limit, $page);
			
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getAllCount($filter = []){
		if(!empty($filter['namaSiswa']))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($filter['namaSiswa'])).'%\'', NULL, FALSE);

		if(!empty($filter['class_id']))
			$this->db->where('s.class_id', $filter['class_id']);

		$this->db->where('s.sekolah_id', $_SESSION['sekolah_id']);
		$this->db->join('kelas k', 'k.class_id = s.class_id', 'left');

		$query = $this->db->get('student s');
		return $query->num_rows();
	}

	public function get_history($limit = null, $page = null, $filter){
		if(!empty($filter['namaSiswa']))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($filter['namaSiswa'])).'%\'', NULL, FALSE);

		if(!empty($filter['kelas']))
			$this->db->where('s.class_id', $filter['kelas']);

		$this->db->where('s.sekolah_id', $_SESSION['sekolah_id']);
		$this->db->select('s.*, k.class_name, u.active');
		$this->db->from('student s');
		$this->db->join('users u', 's.nis=u.username and u.sekolah_id='.$_SESSION['sekolah_id'], 'left');
		$this->db->join('kelas k', 's.class_id=k.class_id', 'left');
		$this->db->order_by('student_name', 'asc');
		$this->db->limit($limit, $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_total_history($filter){
		if(!empty($filter['namaSiswa']))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($filter['namaSiswa'])).'%\'', NULL, FALSE);

		if(!empty($filter['kelas']))
			$this->db->where('s.class_id', $filter['kelas']);

		$this->db->where('s.sekolah_id', $_SESSION['sekolah_id']);
		$this->db->join('kelas k', 'k.class_id = s.class_id', 'left');
		$this->db->join('users u', 's.nis=u.username and u.sekolah_id='.$_SESSION['sekolah_id'], 'left');

		$query = $this->db->get('student s');
		return $query->num_rows();
	}

	public function get_total_exam($class_id, $start_dt, $end_dt){
		$this->db->where('class_id', $class_id);

		if(!empty($start_dt)){
			$this->db->where('DATE(start_date) >=', $start_dt);
			$this->db->where('DATE(end_date) <=', $end_dt);
		}
		return $this->db->get('exam')->num_rows();
	}

	public function average_exam_score($student_id, $start_dt, $end_dt){
		$this->db->select('AVG(exam_total_nilai) as exam_total_nilai');
		$this->db->where('student_id', $student_id);

		if(!empty($start_dt)){
			$this->db->where('DATE(exam_submit) >=', $start_dt);
			$this->db->where('DATE(exam_submit) <=', $end_dt);
		}

		return $this->db->get('exam_student')->row_array();
	}

	public function get_task($limit = null, $page = null, $student_id){
		$class_id = $this->get_class($student_id);

		$this->db->select('t.*, s.subject_name, g.teacher_name, t.score');
		$this->db->from('task t');
		// $this->db->join('materi m', 'm.materi_id = t.materi_id');
		$this->db->join('subject s', 's.subject_id = t.subject_id');
		$this->db->join('teacher g', 'g.teacher_id = t.teacher_id');
		$this->db->where('t.class_id', $class_id);
		$this->db->order_by('available_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_total_task($student_id, $filter = null){
		$class_id = $this->get_class($student_id);

		$this->db->select('t.*, m.title');
		$this->db->from('task t');
		$this->db->join('materi m', 'm.materi_id = t.materi_id', 'left');
		$this->db->where('class_id', $class_id);

		if(isset($filter['start_dt'])){
			$this->db->where('DATE(t.available_date) >=', $filter['start_dt']);
			$this->db->where('DATE(t.due_date) <=', $filter['end_dt']);
		}
		return $this->db->get()->num_rows();
	}

	public function get_total_task_submit($student_id = null, $filter = null){
		$this->db->select('ts.student_id, ts.task_id');
		$this->db->from('task_student ts');
		$this->db->where('student_id', $student_id);

		if(isset($filter['start_dt'])){
			$this->db->where('DATE(t.available_date) >=', $filter['start_dt']);
			$this->db->where('DATE(t.due_date) <=', $filter['end_dt']);
		}

		$this->db->join('task t', 't.task_id=ts.task_id');
		$this->db->group_by('student_id, ts.task_id');
		return $this->db->get()->num_rows();
	}

	public function get_kehadiran($limit = null, $page = null, $student_id){ 
		$this->db->select('id,sesi_title,sesi_date,sesi_jam_start,sesi_jam_end,status');
		$this->db->from('sesi_attendances ss');
		$this->db->join('sesi s', 's.sesi_id = ss.sesi_id'); 
		$this->db->where('ss.student_id', $student_id);
		$this->db->order_by('sesi_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_total_row_kehadiran($student_id){ 
		$this->db->select('id,sesi_title,sesi_date,sesi_jam_start,sesi_jam_end');
		$this->db->from('sesi_attendances ss');
		$this->db->join('sesi s', 's.sesi_id = ss.sesi_id'); 
		$this->db->where('ss.student_id', $student_id);
		return $this->db->get()->num_rows();
	}
	
	public function get_exam($limit = null, $page = null, $student_id){
		$class_id = $this->get_class($student_id);
	//	$this->db->select('e.*, s.subject_name, ec.category_name, t.teacher_name');
		$this->db->select('e.*, s.subject_name, t.teacher_name, ec.category_name');
		$this->db->from('exam e');
		$this->db->join('subject s', 's.subject_id = e.subject_id');
		$this->db->join('exam_category ec', 'e.category_id = ec.category_id');
		$this->db->join('teacher t', 't.teacher_id = e.teacher_id', 'left');
		$this->db->where('e.class_id', $class_id);
		$this->db->order_by('start_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}	

	public function get_total_row_exam($student_id){
		$class_id = $this->get_class($student_id);
		$this->db->select('e.*, s.subject_name');
		$this->db->from('exam e');
		$this->db->join('subject s', 's.subject_id = e.subject_id');
		$this->db->join('exam_category ec', 'e.category_id = ec.category_id');
		$this->db->where('e.class_id', $class_id);
		return $this->db->get()->num_rows();
	}

	public function get_class($student_id){
		$this->db->where('student_id', $student_id);
		return $this->db->get('student s')->row_array()['class_id'];
	}

	public function get_student_class($username){
		$teacher = $this->db->where('nik', $username)->get('teacher')->row_array();

		$this->db->select('COUNT(s.class_id) as value, k.class_name as category');
		$this->db->from('student s');
		$this->db->join('kelas k', 'k.class_id = s.class_id');
		$this->db->where('sekolah_id', $teacher['sekolah_id']);
		$this->db->where('s.ta_aktif', 1);
		$this->db->group_by('s.class_id, k.class_name');
		$this->db->order_by('s.class_id', 'asc');
		return $this->db->get()->result_array();
	}

	public function download($class = null, $nama = null){
		if(!empty($nama))
			$this->db->where('LOWER(student_name) LIKE \'%'.trim(strtolower($nama)).'%\'', NULL, FALSE);

		if(!empty($class))
			$this->db->where('class_id', $class);

		$this->db->select('s.nis, s.student_name, s.gender, s.address, s.phone, s.email');
		$this->db->from('student s');
		return $this->db->get()->result_array();
	}

	public function get_history_book($limit = null, $page = null, $filter){
		$this->db->select('book_id, max(start_time), book_code, title, cover_img, author, publish_year, description, c.category_name');
		$this->db->from('read_log');
		$this->db->join('ebooks', 'ebooks.id = read_log.book_id');
		$this->db->join('categories c', 'c.id = CAST(ebooks.category_id AS INTEGER)', 'left');
		$this->db->where('member_id', $filter['user_id']);
		$this->db->group_by('book_id, book_code, title, cover_img, author, publish_year, description, c.category_name');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_history_book_total($filter){
		$this->db->select('book_id, max(start_time), book_code, title, cover_img, author, publish_year, description');
		$this->db->from('read_log');
		$this->db->join('ebooks', 'ebooks.id = read_log.book_id');
		$this->db->where('member_id', $filter['user_id']);
		$this->db->group_by('book_id, book_code, title, cover_img, author, publish_year, description');
		return $this->db->get()->num_rows();
	}
	
	public function get_exam_soal($exam_id)
	{
		$this->db->select('s.soal_id,question,question_file,
										choice_a,choice_b,choice_c,choice_d,choice_a_file,
										choice_b_file,choice_c_file,choice_d_file,s.answer,s.type');
		$this->db->from('soal s');		 
		$this->db->join('soal_exam se','s.soal_id=se.soal_id');		 
		$this->db->where('se.exam_id',$exam_id);  
		$this->db->order_by('se.no_urut'); 
		$query=$this->db->get();
	//	print_r($this->db->last_query());  
		if($query->num_rows()>0)
		{
			return $query->result_array(); 
		}
		else
		{
			return null;
		}				
	}	

	public function get_materi_guru(int $limit = null, int $page = null, array $filters = []){
		if(!empty($filters[0]['search']['value'])){
			$this->db->where('mk.class_id', $filters[0]['search']['value']);
		}

		if(!empty($filters[1]['search']['value'])){
			$this->db->where('m.teacher_id', $filters[1]['search']['value']);
		}

		if(!empty($filters[2]['search']['value'])){
			$this->db->where('m.subject_id', $filters[2]['search']['value']);
		}

		$this->db->select('m.*, s.subject_name, t.teacher_name');
		$this->db->from('materi_kelas mk');
		$this->db->join('materi m', 'm.materi_id = mk.materi_id');
		$this->db->join('subject s', 's.subject_id = m.subject_id');
		$this->db->join('teacher t', 't.teacher_id = m.teacher_id', 'left');
		
		$this->db->order_by('available_date', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}

	public function get_total_row_materi_guru($student_id){
		$class_id = $this->get_class($student_id);
		$this->db->select('m.*');
		$this->db->from('materi_kelas mk');
		$this->db->join('materi m', 'm.materi_id = mk.materi_id');
		$this->db->where('mk.class_id', $class_id);
		return $this->db->get()->num_rows();
	}

	public function log_task($params)
	{ 
		$teacher_id 				= $this->session->userdata('teacher_id');
 
		$this->db->select('task_student.*');
		$this->db->from('task_student');
		$this->db->join('task', 'task_student.task_id = task.task_id');
		$this->db->where('student_id', $params['id']);
		$this->db->where('teacher_id', $teacher_id);
		
		$_data = $this->db->get()->result_array();
		return $_data;													
	}
	
	public function log_exam($params)
	{														
	
		$teacher_id 				= $this->session->userdata('teacher_id');	
 		
		$this->db->select('exam_student.*');
		$this->db->from('exam_student');
		$this->db->join('exam', 'exam_student.exam_id = exam.exam_id');
		$this->db->where('exam_student.student_id', $params['id']);
		$this->db->where('teacher_id', $teacher_id);
		
		$_data = $this->db->get()->result_array();		 	
		return $_data;														
	}
	
	public function log_activity($params)
	{														
	
		$teacher_id 				= $this->session->userdata('teacher_id');	
 
		$this->db->select('*');
		$this->db->from('v_activity'); 
		$this->db->where('student_id', $params['id']);
		$this->db->where('teacher_id', $teacher_id);
		if(!empty($params['start_date']))
		$this->db->where('tgl_submit>=', $params['start_date']);
		if(!empty($params['end_date']))
		$this->db->where('tgl_submit<=', $params['end_date']);
		$query =  $this->db->get(); 
		
		return $query->result_array();												
	}	
	
	public function get_total_log_activity($params)
	{														
		$teacher_id 				= $this->session->userdata('teacher_id');	
 
		$this->db->select('*');
		$this->db->from('v_activity'); 
		$this->db->where('student_id', $params['id']);
		$this->db->where('teacher_id', $teacher_id);
		if(!empty($params['start_date']))
		$this->db->where('tgl_submit>=', $params['start_date']);
		if(!empty($params['end_date']))
		$this->db->where('tgl_submit<=', $params['end_date']);
		
		$query = $this->db->get();
		return $query->num_rows();									
	}	

	public function getNis($student_id) {
		$this->db->select('nis');
		$this->db->from('student');
		$this->db->where('student_id', $student_id);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row()->nis;
		}
		return false;
	}
	
	public function get_absensi($limit = null, $page = null, $userid){ 
		$this->db->select('userid, date(log_time) as tanggal, min(log_time)::time as absen_masuk, max(log_time)::time as absen_pulang');
		$this->db->from('absensi a');
		$this->db->where('userid', $userid);
		$this->db->group_by('userid, date(log_time)');
		$this->db->order_by('date(log_time)', 'desc');
		$this->db->limit($limit, $page);
		return $this->db->get()->result_array();
	}	
	
	public function get_total_row_absensi($userid){
		$this->db->select('*');
		$this->db->from('absensi'); 
		$this->db->where('userid', $userid);
		return $this->db->get()->num_rows();
	}	
}
