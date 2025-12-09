<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_teacher');
		$this->load->model('model_task');
		$this->load->helper(['assets_helper', 'customstring', 'cek']);
		
		$this->load->library('xlsxwriter');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){
		// $data['page_js'][] = ['path' => 'assets/js/student_detail.js'];

		// ############### Start Chart Siswa di tiap Kelas ############### 
		$kelas = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->order_by('class_level_id', 'ASC')->get('kelas')->result_array();

		$dataCartKelas = [];
		foreach ($kelas as $key => $value) {
			$rowTeachers = $this->db->where('class_id', $value['class_id'])
				->get('class_teacher')->num_rows();
			$dataCartKelas[$key][] = $value['class_name'];
			$dataCartKelas[$key][] = ($rowTeachers) ? $rowTeachers : 0;
		}

		$data['dataCartKelas'] = $dataCartKelas;

		// ############### End Chart Siswa di tiap Kelas ###############

		// ############### Start Chart Guru Aktif / Tidak Aktif ###############
		$teachersStatusChart = [];
		// $teachersStatus = $this->db->select("case 
		// 				when status = 0 then 'Tidak Aktif' 
		// 				when status = 1 then 'Aktif' end as status, count(status) as total")
		// 			->where('sekolah_id', $_SESSION['sekolah_id'])
		// 			->group_by('status')
		// 			->get('teacher t')->result_array();
		$teachersStatus = $this->db->query("select 'aktif' as status, count(*) as total from teacher t 
			where sekolah_id = ".$_SESSION['sekolah_id']." and status = 1
			union
			select 'tidak aktif' as status, count(*) as total from teacher t 
			where sekolah_id = ".$_SESSION['sekolah_id']." and status = 0;")->result_array();

		foreach ($teachersStatus as $key => $value) {
			$teachersStatusChart[$key][] = $value['status'];
			$teachersStatusChart[$key][] = $value['total'];
		}

		// ############### End Chart Guru Aktif / Tidak Aktif ###############

		$data['teacher_status_chart'] = $teachersStatusChart;

		$data['kelas'] = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])
			->order_by('class_name', 'ASC')
			->get('kelas k')->result_array();

		$this->template->load('template', 'teacher/index', $data);
	}

	/**
	 * GET
	 * return JSON
	 */
	public function getAll() {
        $draw = $this->input->post('draw', TRUE);
        $limit = $this->input->post('length', TRUE);
        $offset = $this->input->post('start', TRUE);
        $filters = $this->input->post('columns');

        $data = $this->model_teacher->getAll($limit, $offset, $filters);
        
        $resp = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('teacher'),
            'recordsFiltered' => $this->model_teacher->getCountAll($filters)
        ];

        echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

	// public function get_task_chart(){
	// 	$sekolah_id = $this->session->userdata('sekolah_id');

	// 	$post = $this->input->post();

	// 	$start = new DateTime($post['start']);
	// 	$end = new DateTime($post['end']);
	// 	$end = $end->modify('+1 day');

	// 	$interval = DateInterval::createFromDateString('1 day');
	// 	$period = new DatePeriod($start, $interval, $end);

	// 	$data = [];
	// 	foreach ($period as $key => $dt) {
	// 		$data[$key]['tanggal'] 	= $dt->format("Y-m-d");
	// 		$hasil 	= $this->model_teacher->get_all_task_by_date($sekolah_id, $dt->format("Y-m-d"))->num_rows();
	// 		$data[$key]['value'] 	= ($hasil) ? $hasil : 0;
	// 	}

	// 	header('Content-Type: application/json');
	// 	echo json_encode($data);
	// }

	public function get_total(){
		$sekolah_id = $this->session->userdata('sekolah_id');
		$data['total_row'] = $this->db->where('sekolah_id', $sekolah_id)->where('status', 1)->get('teacher')->num_rows();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function detail($id = ''){
		if(!$id) redirect(base_url('teacher'));

		$data['detail']	= $this->db->where('teacher_id', $id)->get('teacher')->row_array();
		$dataUser = $this->db->where('username', $data['detail']['nik'])->get('users')->row_array();
		$data['detail']['photo'] = ($dataUser) ? $dataUser['photo'] : 'user.png';
		$data['detail']['nama_sekolah'] = $this->db->where('sekolah_id', $data['detail']['sekolah_id'])->get('sekolah')->row_array()['sekolah_nama'];

		$data['total_task'] = $this->db->get_where('task', ['teacher_id' => $id])->num_rows();
		$data['total_exam'] = $this->db->get_where('exam', ['teacher_id' => $id])->num_rows();

		$this->template->load('template', 'teacher/detail', $data);
	}

	public function get_class(){
		$data = $this->db->get('kelas')->result_array();

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function search(){
		$get = $this->input->get();
		$username 	= $this->session->userdata('username');
		$user_level = $this->db->where('username', $username)->get('users')->row_array()['user_level'];
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 10;
		$filter		= $get['filter'];

		$page = ($page - 1) * $limit;

		$allTeacher = $this->model_teacher->getTeacherClass($limit, $page, $filter);

		foreach ($allTeacher as $key => $val) {
			// get kelas-kelas teacher
			$a = $this->db->where('teacher_id', $val['teacher_id'])
					->join('kelas k', 'k.class_id=ct.class_id', 'left')
					->order_by('class_name', 'asc')
					->get('class_teacher ct')->result_array();
			
			// ambil nama kelasnya aja
			$class = [];
			foreach ($a as $key2 => $val2) {
				$class[$key2] = $val2['class_name'];
			}

			// get mapel teacher
			$b = $this->db->where('teacher_id', $val['teacher_id'])
					->join('subject s', 's.subject_id=st.subject_id', 'left')
					->get('subject_teacher st')->result_array();

			// ambil nama mapel nya aja
			$mapel = [];
			foreach ($b as $key3 => $val3) {
				$mapel[$key3] = $val3['subject_name'];
			}

			$allTeacher[$key]['teacher_class'] = implode(', ', $class); 
			$allTeacher[$key]['mapel'] = implode(', ', $mapel); 
		}


		$data['user_level'] 	= $user_level;
		$data['data'] 			= $allTeacher;
		$data['total_records'] 	= $this->model_teacher->getCountTeacherClass($filter);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_summary(){
		$post 				= $this->input->post();
		$data['total_exam']	= $this->model_teacher->get_total_exam($post['teacher_id'], $post['start'], $post['end']);
		$data['total_task'] = $this->model_teacher->get_total_task($post['teacher_id'], $post['start'], $post['end']);

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_exam(){
		$get = $this->input->get();
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 3;

		$page = ($page - 1) * $limit;

		$data['data']			= $this->model_teacher->get_exam($limit, $page, $get['teacher_id']);
		$data['total_records'] 	= $this->model_teacher->get_total_row_exam($get['teacher_id']);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_task(){
		$get = $this->input->get();
		$page 		= isset($get['page']) ? (int)$get['page'] : 1;
		$limit 		= isset($get['limit']) ? (int)$get['limit'] : 10;

		$page = ($page - 1) * $limit;

		$data['data']	= $this->model_teacher->get_task($limit, $page, $get['teacher_id']);
		$data['total_records'] 	= $this->db->where('teacher_id', $get['teacher_id'])->get('task')->num_rows();
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	public function ujian() {
 
		
		$data['mapelop'] = $this->model_teacher->get_mapel();
		$data['kelasop'] = $this->model_teacher->get_kelas();
		$this->load->view('header');
		$this->load->view('teacher/ujian', $data);
		$this->load->view('footer');
	}
	
	public function getujianlist()
	{
		$username 	= $this->session->userdata('username');
		$user_level 				= $this->session->userdata('user_level');
		$teacher_id 				= $this->session->userdata('teacher_id');
		$page 		= isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit 		= isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
		$mapel		= $_GET['mapel'];
		$kelas		= $_GET['kelas'];
		$startDate	= $_GET['startDate'];
		$endDate	= $_GET['endDate'];

		$page = ($page - 1) * $limit;

		$data['user_level'] 	= $user_level;
		
 
		$data['task'] 			= $this->model_teacher->get_ujian($limit, $page, $mapel,$kelas, $startDate, $endDate);
		$data['total_records'] 	= $this->model_teacher->get_total_ujian($mapel,$kelas,$startDate, $endDate);			
 

		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);		
	}	
		
		
	public function createujian($id = ''){
		$post = $this->input->post();
		$data['mapelop'] = $this->model_teacher->get_mapel();
		$data['kelasop'] = $this->model_teacher->get_kelas();

 
		if($id != ''){
			$data['id'] = $id;
			$data['data'] = $this->db->where('exam_id', $id)->get('exam')->row_array();
		}

		$this->load->view('header');
		$this->load->view('teacher/createujian', $data);
		$this->load->view('footer');
	}		

	public function saveujian(){ 
		$post = $this->input->post();
		$teacher_id = $this->session->userdata('teacher_id'); 
 
		$data = [
			'teacher_id' 	=> $teacher_id,
			'start_date' 		=> $post['tanggal_start'].' '.$post['jamstart'],
			'end_date' 		=> $post['tanggal_end'].' '.$post['jamend'], 
			'subject_id'		=> $post['select_mapel'],
			'class_id'		=> $post['select_kelas'] 
		];
		$insert = $this->db->insert('exam', $data);
		if($insert){
			$resp = ['success'=>true, 'message'=>'Data berhasil disimpan'];
		}else{
			$resp = ['success'=>false, 'message'=>'Data gagal disimpan'];
		}

		$this->session->set_flashdata('simpan', $resp);
		redirect(base_url('teacher/ujian'));
		 
	}
	 	
		
	public function timeline()
	{ 
 
		$data = array();

		$this->load->view('header');
		$this->load->view('teacher/timeline', $data);
		$this->load->view('footer');
	}	
 
	public function loadtimeline(){
			$this->load->model('model_sesi');
		$params['sdate']	= $_GET['start'];
		$params['edate']	= $_GET['end']; 
		
		$teacher = $this->db->where('nik', $this->session->userdata('username'))->get('teacher')->row();
		$teacher_ids[] = $teacher->teacher_id; 

		$params['teacher_id'] = $teacher_ids;
		 
		
		$sesi = $this->model_sesi->data_timeline($params);

		$list = []; 
		foreach($sesi->result() as $data) {
			$tanggal =  $data->sesi_date;	 
			$list[] = [
				'id' 			=> $data->sesi_id,
				'title' 		=> $data->sesi_title,
				'teacher' 		=> $data->teacher_name,
				'subject_name'	=> $data->subject_name, 
				'start' 		=> $data->sesi_date.'T'.$data->sesi_jam_start,
				'end' 			=> $data->sesi_date.'T'.$data->sesi_jam_end,
				'start_time' 			=>  $data->sesi_jam_start,
				'end_time' 			=>  $data->sesi_jam_end,
				'sesi_note'		=> strip_tags($data->sesi_note)
			];
		}	
		if($list == null) $list[] = array(0);
		echo json_encode($list);			
		exit;
	}

	public function download(){
		$datas = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->get('teacher')->result_array();
		$sekolah = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->get('sekolah')->row_array();

		$header2 = array(
			'NIK'		=>'string',
			'Nama' 		=>'string',
			'Alamat'	=>'string',
			'Telp'		=>'string',
			'Email'		=>'string',
			'Status'	=>'string',
		);

		// FORMAT FILE NAME = DETAIL_SELLING_MARKETPLACE - USER ID - TIMESTAMP
		$name = './assets/files/downloads/teachers/data_guru_'.str_replace(' ', '', $sekolah['sekolah_nama']).date('YmdHis').'.xlsx';
		
		$writer = new xlsxwriter();

		$header_style = array(
			'widths'		=>array(25,25,25,25,25,25),
			'font'			=>'Arial',
			'font-size'		=>12, 
			'wrap_text'		=>true, 
			'border'		=>'left,right,top,bottom',
			'border-style'	=>'medium', 
			'border-color'	=>'#4A8C42', 
			'valign'		=>'top', 
			'color'			=>'#FFFFFF', 
			'fill'			=>'#4A8C42'
		);



		$writer->writeSheetHeader('Sheet1', $header2, $header_style);	
		$writer->writeSheetRow('Sheet1', ['Data Guru'.$sekolah['sekolah_nama']]);

		foreach($datas as $row){
			$status = ($row['status'] == 1) ? 'aktif' : 'tidak aktif';
			$writer->writeSheetRow('Sheet1', [
				$row['nik'], 
				$row['teacher_name'], 
				$row['address'], 
				$row['phone'], 
				$row['email'],
				$status
			]);
		}
		
		$writer->writeToFile($name);
		
		header("Content-Description: File Transfer"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Disposition: attachment; filename=\"". basename($name) ."\""); 

		readfile($name);
		exit(); 
	}
	
}
