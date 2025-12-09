<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materi extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('model_mapel');
		$this->load->model('model_materi');
		$this->load->helper('assets');

		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index()
	{
		$datamodel = 'table';
		if(!empty($this->input->get('mode')) && in_array($this->input->get('mode'), ['table', 'grid']))
			$datamodel = $this->input->get('data_model');

		// $data['page_js'][] = ['path' => 'assets/libs/sweetalert2/sweetalert2.min.js'];
		if($datamodel == 'grid')
			$data['page_js'][] = ['path' => 'assets/js/materi_grid.js', 'defer' => true, 'type' => 'module'];
		else
			$data['page_js'][] = ['path' => 'assets/js/materi_table.js', 'defer' => true];

		$data['page_css'] = [
			// 'assets/libs/sweetalert2/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/materi.css'
		];

		$data['datamodel'] = $datamodel;

		$this->template->load('template', 'mapel/index', $data);
	}

	/**
	 * list of subject
	 *
	 * @return void
	 */
	public function list(): void {
		$draw	= $this->input->get('draw') ?? '';
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$count  = $this->db->count_all_results('materi');
		$filter = $this->input->get('columns');

		$username = $_SESSION['username'];

		// jika user level guru
		if($_SESSION['user_level'] == 3){
			$teacherId = $this->db->where('nik', $username)->get('teacher')->row_array()['teacher_id']; // get teacher id
			$filter[2]['search']['value'] = $teacherId;
		}

		// jika user level murid
		if($_SESSION['user_level'] == 4){
			$classIds[0] = $_SESSION['class_id']; // get class id
			$filter[4]['search']['value'] = $classIds;
		}
		
		// jika user level ortu
		if($_SESSION['user_level'] == 5){
			$parentId = $this->db->where('username', $username)->get('parent')->row_array()['parent_id']; // get parent id
			// get students id
			$students = $this->db->where('parent_id', $parentId)->get('student')->result_array();
			$studentIds = array_column($students, 'student_id');

			// get class id
			$classIds = $this->db->where_in('student_id', $studentIds)->get('student')->result_array();
			$classIds = array_column($classIds, 'class_id');

			$filter[4]['search']['value'] = $classIds;
		}

		$data   = $this->model_mapel->get_all($limit, $offset, $filter);


		foreach ($data as $key => $val) {
			$dir = str_replace('\application\controllers','',__DIR__).'/assets/files/materi/'; // get direktory file
			if(file_exists($dir.$val['materi_file'])){
				$data[$key]['file_size'] = filesize($dir.$val['materi_file']);
			}else{
				$data[$key]['file_size'] = 0;
			}
		}

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			// 'recordsTotal' 		=> $count,
			'recordsTotal' 		=> count($data),
			'recordsFiltered'	=> $this->model_mapel->num_all($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	/**
	 * list of materi sekolah
	 *
	 * @return void
	 */
	public function list_materi_sekolah(): void {
		$draw	= $this->input->get('draw') ?? '';
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$count  = $this->db->count_all_results('materi');
		$filter = $this->input->get('columns');

		// get all user level kepsek 
		$getAllKepsek = $this->db->select('t.teacher_id')
			->where('u.user_level', 6)
			->where('u.sekolah_id', $_SESSION['sekolah_id'])
			->join('teacher t', 't.nik = u.username')
			->get('users u')->result_array();

		$teacher_ids = array_column($getAllKepsek, 'teacher_id');
		$filter[3]['search']['value'] = $teacher_ids;

		$data   = $this->model_mapel->get_all_materi_sekolah($limit, $offset, $filter);

		foreach ($data as $key => $val) {
			$dir = FCPATH.'assets/files/materi/'; // get direktory file
			if(file_exists($dir.$val['materi_file'])){
				$data[$key]['file_size'] = filesize($dir.$val['materi_file']);
			}else{
				$data[$key]['file_size'] = 0;
			}
		}

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			// 'recordsTotal' 		=> $count,
			'recordsTotal' 		=> count($data),
			'recordsFiltered'	=> $this->model_mapel->num_all_materi_sekolah($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	/**
	 * list f materi
	 *
	 * @return void
	 */
	public function getAll(): void {
        $draw = $this->input->post('draw', TRUE);
        $limit = $this->input->post('length', TRUE);
        $offset = $this->input->post('start', TRUE);
        $filters = $this->input->post('columns');

        $data = $this->model_materi->getAll($limit, $offset, $filters);
        
        $resp = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->getCountAll($filters),
            'recordsFiltered' => $this->model_materi->getCountAll($filters)
        ];

        echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

	/**
	 * list f materi
	 *
	 * @return void
	 */
	public function getAllSubject(): void {
		$filter = [];
		$filter[0] = (isset($_GET['subject_id'])) ?? $_GET['subject_id'];
        $data = $this->model_materi->getAllSubject();
        echo json_encode(['data' => $data], JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

	/**
	 * GET
	 * @return JSON
	 */
	public function getAllMateri(){
		$materies = $this->db->select("st.teacher_id, s.subject_id, s.subject_name")
						->from('subject_teacher st')
						->where('teacher_id', $_SESSION['teacher_id'])
						->join('subject s', 's.subject_id = st.subject_id')
						->get()->result_array();

		header('Content-Type: application/json');
		echo json_encode($materies, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	public function materi_saya(){

		$data['page_css'] = [
			// 'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => 'assets/js/_materi_saya.js'];

		$teacher_id = $_SESSION['teacher_id'];

		$data['mapels'] = $this->db->where('teacher_id', $teacher_id)->join('subject s', 's.subject_id=st.subject_id')->get('subject_teacher st')->result_array();

		$this->template->load('template', 'mapel/materi_saya', $data);
	}

	/**
	 * GET list_materi_saya
	 */
	public function list_materi_saya(): void {
		$draw	= $this->input->get('draw') ?? '';
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$count  = $this->db->count_all_results('materi');
		$filter = $this->input->get('columns');

		$filter[0]['search']['value'] = isset($filter[0]['search']['value']) ? $_SESSION['teacher_id'] : null;
		
		$data   = $this->model_mapel->get_all_materi_saya($limit, $offset, $filter);

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			'recordsTotal' 		=> count($data),
			'recordsFiltered'	=> $this->model_mapel->num_all_materi_saya($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	public function store_materi_saya(){

		$teacher_id 		= $this->input->post('teacher_id');
        $title              = trim($this->input->post('input_materi', TRUE));
		$jenis_materi		= $this->input->post('a_jenis_materi');
		$subject			= $this->input->post('subject_id');
        $ava_date           = date('Y-m-d');//trim($this->input->post('a_materi_date', TRUE));
        // $subject_name       = trim($this->input->post('a_materi_subject_text', TRUE));
		$link				= trim($this->input->post('a_tautan'), true);
        // video
        $video_name         = $_FILES['a_materi_video']['name'];
        $video_size         = $_FILES['a_materi_video']['size']; 
 
        header('Content-Type: application/json');

		// VALIDATION INPUT
			//    if(empty($title) || empty($subject) || empty($teacher) || empty($ava_date) || empty($video_name)) 
			if(empty($title) || empty($subject) || empty($jenis_materi)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			// JIKA JENIS MATERI FILE & a_materi_video NYA KOSONG
			if( ($jenis_materi == 'file') && empty($video_name) ){
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			// JIKA JENIS MATERI LINK & INPUT LINK NYA KOSONG
			if( ($jenis_materi == 'link') && empty($link) ){
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

        $this->db->where('LOWER(title) = ', strtolower(trim($title)));
        $_exists = $this->db->get_where('materi', ['subject_id' => $subject])->num_rows();

        if($_exists > 0)
        {
            http_response_code(422);
			$m = ['err_status' => 'error', 'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')];
			exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }

        // UPLOAD FILE
		if(!empty($video_name)){

			$dir = './assets/files/materi/'; 
	 
			 // cek if dir is exists, if not then make the directory and set permission to be accessible
			 if(!is_dir($dir))
				 @mkdir($dir, 0777);
			 // set name for the videofile
					 
			$_n_subject_name =  str_replace(array(' ','"'),array("_","_"),strtolower($subject_name));
			$_n_title =  str_replace(array(' ','"'),array("_","_"),strtolower($title));
					 
			 $filename = $dir.DIRECTORY_SEPARATOR.$_n_subject_name.'-'.$_n_title;
			 // get the extension
			 $ext = pathinfo(basename($video_name), PATHINFO_EXTENSION);
			 // now let's move the uploaded file to the directory
			 //echo $_FILES['a_materi_video']['tmp_name'].'=='. FCPATH.$filename.'.'.$ext;
			 $move = move_uploaded_file($_FILES['a_materi_video']['tmp_name'], FCPATH.$filename.'.'.$ext);
			 if(!$move) 
			 {
				 http_response_code(422);
				 $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
				 echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				 return;
			 }
		}


        $data = [
            // 'tema_title'        => $tema_title,  
            // 'sub_tema_title'    => $sub_tema_title,  
			// 'no_urut'           => $no_urut, 
			'teacher_id'		=> $teacher_id,
            'title'             => $title,  
            'subject_id'        => $subject, 
            'available_date'    => $ava_date, 
            'materi_file'       => ($video_name) ? $_n_subject_name.'-'.$_n_title.'.'.$ext : $link,
            'type'              => $jenis_materi,
			'edit_at'			=> date('Y-m-d H:i:s'),
        ];
        //if(!empty($parent)) $data['parent_id']=$parent;
        //if(!empty($require)) $data['materi_require']=$require; 

        if(!$this->db->insert('materi', $data))
        {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }
 

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;

		// $_FILES['input_file']
		// save materi ==========================================================

		// header('Content-Type: application/json');
		// echo json_encode(['post' => $_POST], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);

	}

	/**
	 * view modal relasi
	 */
	public function relasi(){
		$this->load->view('mapel/relasi', true);
	}

	public function set_relasi(){
		$post = $this->input->post();
		$materi_id = $post['a_materi_id'];

		$this->db->delete('materi_kelas', ['materi_id' => $materi_id]);

		foreach ($post['teacher_class'] as $val) {
			$insert = $this->db->insert('materi_kelas', ['class_id'=>$val, 'materi_id'=>$materi_id]);
		}

		if(!$insert){
			$data = ['success'=>false, 'message'=>'Data gagal di simpan!'];
		}else{
			$data = ['success'=>true, 'message'=>'Data berhasil di simpan!'];
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	/**
	 * Delete, Post ID
	 */
	function delete(){
		header('Content-Type: application/json');

		$post = file_get_contents('php://input');
        $post  = json_decode($post, TRUE);

		// cek di materi_kelas sudah ada atau belum datanya
		$materi_kelas = $this->db->where('materi_id', $post['materi_id'])->get('materi_kelas')->num_rows(); 
		if($materi_kelas > 0){
			$this->db->delete('materi_kelas', ['materi_id' => $post['materi_id']]);
		}

		$materi = $this->db->where('materi_id', $post['materi_id'])->get('materi')->row_array();
		// delete file materi
		if($materi['type'] == 'file') unlink('assets/files/materi/'.$materi['materi_file']);

		$delete = $this->db->delete('materi', ['materi_id' => $post['materi_id']]);

		if($delete){
			$res = ['success' => true, 'message' => 'Data berhasil di hapus !!!'];
		}else{
			$res = ['success' => false, 'message' => 'Data gagal di hapus !!!'];
		}
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
	}

	/**
	 * Index Materi Sekolah
	 */
	public function materi_sekolah(){
		$datamodel = 'table';
		if(!empty($this->input->get('mode')) && in_array($this->input->get('mode'), ['table', 'grid']))
			$datamodel = $this->input->get('data_model');

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/libs/sweetalert2/sweetalert2.min.js'];
		if($datamodel == 'grid')
			$data['page_js'][] = ['path' => 'assets/js/materi_grid.js', 'defer' => true, 'type' => 'module'];
		else
			$data['page_js'][] = ['path' => 'assets/js/materi_sekolah.js', 'defer' => true];

		$data['page_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/libs/sweetalert2/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/materi.css'
		];

		$data['datamodel'] = $datamodel;
		
		// Login kepala sekolah bisa upload semua mapel
		$data['mapels'] = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->get('subject s')->result_array();

		$this->template->load('template', 'mapel/materi_sekolah', $data);
	}

	/**
	 * Materi Global
	 */
	public function tutorial(){
		$datamodel = 'table';
		if(!empty($this->input->get('mode')) && in_array($this->input->get('mode'), ['table', 'grid']))
			$datamodel = $this->input->get('data_model');

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/libs/sweetalert2/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/moment/moment.js'];
		if($datamodel == 'grid')
			$data['page_js'][] = ['path' => 'assets/js/materi_grid.js', 'defer' => true, 'type' => 'module'];
		else
			$data['page_js'][] = ['path' => 'assets/js/_materi_global.js', 'defer' => true];

		$data['page_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/libs/sweetalert2/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/materi.css'

		];

		$data['datamodel'] = $datamodel;

		$this->template->load('template', 'mapel/materi_global', $data);
	}

	/**
	 * GET list_materi_saya
	 */
	public function list_tutorial(): void {
		$draw	= $this->input->get('draw') ?? '';
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$count  = $this->db->count_all_results('materi');
		$filter = $this->input->get('columns');
		
		$data   = $this->model_mapel->get_all_tutorial($limit, $offset, $filter);

		foreach ($data as $key => $val) {
			$dir = str_replace('\application\controllers','',__DIR__).'/assets/files/materi/'; // get direktory file
			if(file_exists($dir.$val['materi_file'])){
				$data[$key]['file_size'] = filesize($dir.$val['materi_file']);
			}else{
				$data[$key]['file_size'] = 0;
			}
		}

		$json = [
			'draw'				=> $draw,
			'data' 		   		=> $data,
			'recordsTotal' 		=> count($data),
			'recordsFiltered'	=> $this->model_mapel->num_all_tutorial($filter)
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_QUOT);
	}

	/**
	 * POST
	 * @return JSON
	 */
	public function save() {
        
		$teacher_id 		= $this->input->post('teacher_id');
        $title              = trim($this->input->post('a_materi_title', TRUE));
		$jenis_materi		= $this->input->post('a_jenis_materi');
		$subject			= $this->input->post('a_materi_subject');
        $ava_date           = date('Y-m-d');//trim($this->input->post('a_materi_date', TRUE));
        $subject_name       = trim($this->input->post('a_materi_subject_text', TRUE));
		$link				= trim($this->input->post('a_tautan'), true);
        // video
        $video_name         = $_FILES['a_materi_video']['name'];
        $video_size         = $_FILES['a_materi_video']['size'];
 
        header('Content-Type: application/json');

		// VALIDATION INPUT
			//    if(empty($title) || empty($subject) || empty($teacher) || empty($ava_date) || empty($video_name)) 
			if(empty($title) || empty($subject) || empty($jenis_materi)) {
				http_response_code(422);
				$msg = [
					'err_status' => 'error', 
					'message' => $this->lang->line('woow_is_required'),
					'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			// JIKA JENIS MATERI FILE & a_materi_video NYA KOSONG
			if( ($jenis_materi == 'file') && empty($video_name) ){
				http_response_code(422);
				$msg = [
					'err_status' => 'error', 
					'message' => $this->lang->line('woow_is_required'),
					'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			// JIKA JENIS MATERI LINK & INPUT LINK NYA KOSONG
			if( ($jenis_materi == 'link') && empty($link) ){
				http_response_code(422);
				$msg = [
					'err_status' => 'error', 
					'message' => $this->lang->line('woow_is_required'),
					'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

        $this->db->where('LOWER(title) = ', strtolower(trim($title)));
        $_exists = $this->db->get_where('materi', ['subject_id' => $subject])->num_rows();

        if($_exists > 0)
        {
            http_response_code(422);
			$m = [
				'err_status' => 'error', 
				'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists'),
				'token' => $this->security->get_csrf_hash()];
			exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }

        // UPLOAD FILE
		if(!empty($video_name)){

			$dir = './assets/files/materi/'; 
	 
			 // cek if dir is exists, if not then make the directory and set permission to be accessible
			 if(!is_dir($dir))
				 @mkdir($dir, 0777);
			 // set name for the videofile
					 
			$_n_subject_name =  str_replace(array(' ','"'),array("_","_"),strtolower($subject_name));
			$_n_title =  str_replace(array(' ','"'),array("_","_"),strtolower($title));
					 
			 $filename = $dir.DIRECTORY_SEPARATOR.$_n_subject_name.'-'.$_n_title;
			 // get the extension
			 $ext = pathinfo(basename($video_name), PATHINFO_EXTENSION);
			 $allowed = array("jpeg", "jpg", "png", "pdf", "doc", "docx", "xls", "xlsx", "pptx", "ppt", "mp3", "mp4", "webp");

			 // EXTENSI FILE YANG DI IZINKAN
			 if(!in_array($ext, $allowed)){
				http_response_code(422);
				
				$msg = [
					'err_status' => 'error', 
					'message' => 'Hanya jpg, jpeg, png, pdf, doc, docx, xls, xlsx, pptx, ppt, mp3, mp4 yang di izinkan.', 
					'token' => $this->security->get_csrf_hash()];

				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
			 }

			 // UKURAN FILE MAXIMUM YANG DI IZINKAN
			 if(round($video_size/1000) > 10000){
				http_response_code(422);
				
				$msg = [
					'err_status' => 'error', 
					'message' => 'Ukuran file maksimal 10MB', 
					'token' => $this->security->get_csrf_hash()];

				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
			 }

			 // now let's move the uploaded file to the directory
			 //echo $_FILES['a_materi_video']['tmp_name'].'=='. FCPATH.$filename.'.'.$ext;
			 $move = move_uploaded_file($_FILES['a_materi_video']['tmp_name'], FCPATH.$filename.'.'.$ext);
			 if(!$move) 
			 {
				 http_response_code(422);
				 $msg = [
					'err_status' => 'error', 
					'message' => $this->lang->line('woow_form_error'),
					'token' => $this->security->get_csrf_hash()];
				 echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				 return;
			 }
		}


        $data = [
            // 'tema_title'        => $tema_title,  
            // 'sub_tema_title'    => $sub_tema_title,  
			// 'no_urut'           => $no_urut, 
			'teacher_id'		=> $teacher_id,
            'title'             => $title,  
            'subject_id'        => $subject, 
            'available_date'    => $ava_date, 
            'materi_file'       => ($video_name) ? $_n_subject_name.'-'.$_n_title.'.'.$ext : $link,
            'type'              => $jenis_materi,
			'edit_at'			=> date('Y-m-d H:i:s'),
			'materi_file_size'	=> ($video_name) ? round($video_size/1000) : 0
        ];
        //if(!empty($parent)) $data['parent_id']=$parent;
        //if(!empty($require)) $data['materi_require']=$require; 

        if(!$this->db->insert('materi', $data))
        {
			http_response_code(422);
			$msg = [
				'err_status' => 'error', 
				'message' => $this->lang->line('woow_form_error'),
				'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }
 

        http_response_code(200);
		$msg = [
			'err_status' => 'success', 
			'message' => $this->lang->line('woow_form_success'), 
			'token' => $this->security->get_csrf_hash()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }

	/**
	 * POST
	 * @return JSON
	 */
    public function edit() {
        $id           = trim($this->input->post('a_id', TRUE));
        $tema_title        = trim($this->input->post('a_materi_tema_title', TRUE));
        $sub_tema_title        = trim($this->input->post('a_materi_sub_tema_title', TRUE));	
        $no_urut        = trim($this->input->post('a_materi_no_urut', TRUE));			
        $title        = trim($this->input->post('a_materi_title', TRUE));
        $subject      = trim($this->input->post('a_materi_subject', TRUE));
        $link      = trim($this->input->post('a_tautan', TRUE));
		$jenis_materi = $this->input->post('a_jenis_materi');
        //$parent      = trim($this->input->post('a_materi_parent', TRUE));
        //$require      = trim($this->input->post('a_materi_require', TRUE));
        //$teacher      = trim($this->input->post('a_materi_teacher', TRUE));
        $ava_date     = trim($this->input->post('a_materi_date', TRUE));
        $description  = trim($this->input->post('a_materi_note', TRUE));
        $subject_name = trim($this->input->post('a_materi_subject_text', TRUE));
        // vidceo
        $video_name   = $_FILES['a_materi_video']['name'];
        $video_size   = $_FILES['a_materi_video']['size'];
        $data         = [];
        header('Content-Type: application/json');

        if(empty($title) || empty($subject) ) 
        {
            http_response_code(422);
            $msg = [
				'err_status' => 'error', 
				'message' => $this->lang->line('woow_is_required'),
				'token' => $this->security->get_csrf_hash()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }
		/*
        if(!validateDate($ava_date))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => 'Data yang di input tidak valid'];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }*/

        $this->db->where('LOWER(title) = ', strtolower(trim($title)));
        $this->db->where('materi_id <> ', $id);
        $_exists = $this->db->get_where('materi', ['subject_id' => $subject])->num_rows();

        if($_exists > 0)
        {
            http_response_code(422);
			$m = [
				'err_status' => 'error', 
				'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists'),
				'token' => $this->security->get_csrf_hash()];
			exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }

        // UPLOAD FILE
        // cek if request has upload
        $file = NULL;
        if(!empty(trim($_FILES['a_materi_video']['name'])))
        {
            $dir = './assets/files/materi/'; 
        
            // set name for the videofile
            $filename = $dir.DIRECTORY_SEPARATOR.str_replace([' ', ':'], '_', strtolower($subject_name)).'-'.str_replace([' ', ':'], '_', strtolower($title));
            // get the extension
            $ext = pathinfo(basename($video_name), PATHINFO_EXTENSION);
            // cek if file exists, if true then delete file
            if(file_exists(basename(FCPATH.$filename.'.'.$ext)))
                unlink(FCPATH.$filename.'.'.$ext);
            // now let's move the uploaded file to the directory
            $move = move_uploaded_file($_FILES['a_materi_video']['tmp_name'], FCPATH.$filename.'.'.$ext);
            if(!$move) 
            {
                http_response_code(422);
                $msg = [
					'err_status' => 'error', 
					'message' => $this->lang->line('woow_form_error'),
					'token' => $this->security->get_csrf_hash()];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

            $file = basename($filename.'.'.$ext);
        }

        $data = [
            'tema_title'        => $tema_title,  
            'sub_tema_title'    => $sub_tema_title,  
			// 'no_urut'           => $no_urut, 
		    'title'             => $title, 
            'subject_id'        => $subject,
            // 'available_date'    => date('Y-m-d'),
            'note'              => $description,
			'edit_at'			=> date('Y-m-d H:i:s'),
        ];

        if(!empty($file))
            $data['materi_file'] = $file;

		// JIKA jenis_materi adalah link
		if($jenis_materi == 'link') $data['materi_file'] = $link;

			//	if(!empty($parent)) $data['parent_id']=$parent;
			//	if(!empty($require)) $data['materi_require']=$require;
        if(!$this->db->update('materi', $data, ['materi_id' => $id]))
        {

			http_response_code(422);
			$msg = [
				'err_status' => 'error', 
				'message' => $this->lang->line('woow_form_error'),
				'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        http_response_code(200);
		$msg = [
			'err_status' => 'success', 
			'message' => $this->lang->line('woow_form_success'), 
			'ext' => $data, 
			'token' => $this->security->get_csrf_hash()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }
}
