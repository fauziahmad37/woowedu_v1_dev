<?php

use FontLib\Table\Type\head;
use SebastianBergmann\Environment\Console;

defined('BASEPATH') OR exit('No direct script access allowed');

class Asesmen extends CI_Controller {

	private $settings;

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_asesmen','model_asesmen_mandiri','model_settings', 'model_subject_teacher', 'model_class_teacher', 'model_student']);
		$this->load->helper(['assets_helper', 'customstring', 'cek']);
		
		if (!isset($_SESSION['username'])) redirect('auth/login');

		$this->settings = json_decode(json_encode($this->model_settings->get_settings()), TRUE);
	}

	/**
	 * return VIEW
	 */
	public function index(){

		$header['page_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => 'assets/js/_asesmen.js'];

		$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : null;
		$class_id = isset($_SESSION['class_id']) ? $_SESSION['class_id'] : null;

		if(!is_null($teacher_id)){
			$data['mapels'] = $this->model_subject_teacher->get($teacher_id);
		}else{
			$classLevelId = $_SESSION['class_level_id'];
			$data['mapels'] = $this->db->where('class_level_id', $classLevelId)->get('subject')->result_array();
		}

		$data['classes'] = $this->db->where('teacher_id', $teacher_id)->join('kelas k', 'k.class_id = ct.class_id')->get('class_teacher ct')->result_array();
		$data['teacher_id'] = $teacher_id;
		$data['class_id'] = $class_id;

		$this->template->load('template', 'asesmen/index', $data);
	}

		/**
	 * @param $id
	 * Return View
	 */
	public function view($id = ''){
		$header['page_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/_create_asesmen_standard.css',
		];

		// page js
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
			$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
			$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
			$data['page_js'][] = ['path' => 'assets/js/_asesmen.js'];
			$data['page_js'][] = ['path' => 'assets/js/_skema_penilaian.js'];
			$data['page_js'][] = ['path' => 'assets/js/asesmen_revamp/_create_asesmen_standard.js'];
		
		$data['exam_id'] = $id;
		$data['exam_header'] = $this->db->where('exam_id', $id)
						->join('subject s', 's.subject_id = e.subject_id', 'left')
						->join('kelas k', 'k.class_id = e.class_id', 'left')
						->get('exam e')->row_array();

		$data['soal_exam'] = $this->db->where('se.exam_id', $id)
						->join('soal s', 's.soal_id = se.soal_id', 'left')
						->order_by('grouping', 'ASC')
						->get('soal_exam se')->result_array();
		
		// GET DRAG & DROP CHOICES & PAIRING
		foreach($data['soal_exam'] as $key => $value){
			$drag_drop = $this->db->where('soal_id', $value['soal_id'])->get('soal_dragdrop_question')->result_array();
			$data['soal_exam'][$key]['drag_drop'] = $drag_drop;

			$soal_pairing = $this->db->where('soal_id', $value['soal_id'])->get('soal_pairing_question')->result_array();
			$data['soal_exam'][$key]['pairing'] = $soal_pairing;
		}

		// DATA CARD DASHBOARD SISWA MENGUMPULKAN
		$filters['class_id'] = $data['exam_header']['class_id'];
		$filters['exam_id'] = $id;
		$data['total_siswa'] = $this->model_student->getAllCount($filters);
		$data['total_belum_mengerjakan'] = $data['total_siswa'] - $this->model_asesmen->getCountStudentCollect($filters);
		$data['total_menunggu_penilaian'] = $this->db->where('exam_total_nilai', null)->where('exam_id', $id)->get('exam_student')->num_rows();
		$data['total_sudah_dinilai'] = $this->db->where('exam_total_nilai !=', null)->where('exam_id', $id)->get('exam_student')->num_rows();

		$this->template->load('template', 'asesmen/view', $data);
	}

	/**
	 * @param $id
	 * Return View
	 */
	public function exam_student($id = ''){
		$header['page_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		// page js
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
			$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
			$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
			$data['page_js'][] = ['path' => 'assets/js/_exam_student.js'];
		
		$data['exam_id'] = $id;
		$data['exam'] = $this->db->select('e.*')
						->from('exam e')
						->where('e.exam_id', $id)
						->join('student s', 's.student_id = e.student_id', 'left')
						->join('exam_student es', 'es.exam_id = e.exam_id', 'left')
						->get()->row_array();

		$data['total_murid'] = $this->db->where('class_id', $data['exam']['class_id'])->get('student')->num_rows();
		$data['total_mengerjakan'] = $this->db->where('exam_id', $id)->get('exam_student')->num_rows();
		
		$this->template->load('template', 'asesmen/exam_student', $data);
	}

	/** 
	 * return VIEW
	 */
	public function create_standar($id = ''){
		// PAGE CSS
			$header['add_css'] = [
				'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
				'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			];

		// PAGE JS
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
			$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
			$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
			$data['page_js'][] = ['path' => $this->config->item('admin_url').'assets/new/libs/randomString.js'];
			$data['page_js'][] = ['path' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'];
			$data['page_js'][] = ['path' => 'assets/js/_create_asesmen_standar.js'];
			// $data['page_js'][] = ['path' => 'assets/js/_question_new.js', 'defer' => true];
			// $data['page_js'][] = ['path' => 'admin/assets/new/libs/datatables.net-buttons-bs4/js/select.bootstrap4.min.js'];
			// $data['page_js'][] = ['path' => 'admin/assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true];

		$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : null;
		$data['teacher_id'] = $teacher_id;

		if($id){
			$data['exam'] = $this->db->where('exam_id', $id)->get('exam')->row_array();
		}

		if(!is_null($teacher_id)){
			$data['mapels'] = $this->model_subject_teacher->get($teacher_id);
		}else{
			$classLevelId = $_SESSION['class_level_id'];
			$data['mapels'] = $this->db->where('class_level_id', $classLevelId)->get('subject')->result_array();
		}

		$data['categories'] = $this->db->get('exam_category')->result_array();
		$data['classes'] = $this->model_class_teacher->get($teacher_id);
		$data['is_update'] = FALSE;
		if(!empty($id))
			$data['is_update'] = TRUE;
		
		$this->template->load('template', 'asesmen/create_standar', $data);
	}

	/**
	 * return VIEW
	 */
	public function create_mandiri($id = ''){
		$header['page_css'] = [
			'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		// PAGE JS
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
			$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
			$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
			$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
			// $data['page_js'][] = ['path' => 'admin/assets/new/libs/randomString.js'];
			$data['page_js'][] = ['path' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'];
			$data['page_js'][] = ['path' => 'assets/js/_create_asesmen_mandiri.js'];
			$data['page_js'][] = ['path' => 'assets/js/_question_new_mandiri.js', 'defer' => true];

		$data['student_id'] = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : '';
		$data['teacher_id'] = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : '';
		$data['sekolah_id'] = $_SESSION['sekolah_id'];

		if($data['teacher_id']){
			$data['classes'] = $this->db->where('teacher_id', $data['teacher_id'])
								->join('kelas k', 'k.class_id = ct.class_id', 'left')->get('class_teacher ct')->result_array();
		}

		if($id){
			$data['exam'] = $this->db->where('exam_id', $id)->get('exam')->row_array();
		}

		$data['categories'] = $this->db->get('exam_category')->result_array();

		$data['mapels'] = $this->db->where('sekolah_id', $data['sekolah_id'])->where('class_level_id', $_SESSION['class_level_id'])->get('subject')->result_array();

		// $this->load->view('header', $header);
		// $this->load->view('asesmen/create_mandiri', $data);
		// $this->load->view('footer');
		$this->template->load('template', 'asesmen/create_mandiri', $data);
	}

	/**
	 * GET
	 * return JSON
	 */
	public function getAll() {
        $draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_asesmen->getAllSoal($limit, $offset, $filters);

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('soal'),
            'recordsFiltered' => $this->model_asesmen->getCountAllSoal($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

	/** 
	 * GET
	 * return JSON
	 */
	public function getAsesmen(){
		$draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_asesmen->getAllAsesmen($limit, $offset, $filters);
		
		// jika student_id tidak kosong -- atau ketika user login sebagai murid
		if(!empty($filters[6]['search']['value'])) {
			foreach ($data as $key => $value) {
				// get data exam_student
				$exam_student = $this->db->where('student_id', $filters[6]['search']['value'])
								->where('exam_id', $value['exam_id'])
								->get('exam_student')->row_array();
				$data[$key]['exam_submit'] = ($exam_student) ? $exam_student['exam_submit'] : null; 
				$data[$key]['exam_total_nilai'] = ($exam_student) ? $exam_student['exam_total_nilai'] : null; 
			}
		}else{
			foreach ($data as $key => $value) {
				// get data exam_student
				$data[$key]['exam_submit'] = null; 
				$data[$key]['exam_total_nilai'] = null;
			}
		}

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => $this->model_asesmen->getAcountAllAsesmen($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/** 
	 * GET
	 * return JSON
	 */
	public function getAsesmenKhusus(){
		$draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');
		
		if(isset($_SESSION['teacher_id'])){
			$teacherSubject = $this->db->get_where('subject_teacher', ['teacher_id' => $_SESSION['teacher_id']])->result_array();
			$teacherSubject = array_column($teacherSubject, 'class_id');
		}

		$filters[7]['search']['value'] = isset($teacherSubject) ? $teacherSubject : null;

        $data = $this->model_asesmen->getAllAsesmenKhusus($limit, $offset, $filters);
		
		// get data exam_student
		foreach ($data as $key => $value) {
			// cari exam_student untuk mengecek exam nya sudah di kerjakan atau belum
			$exam_student = $this->db->where('student_id', $value['student_id'])
							->where('exam_id', $value['exam_id'])
							->get('exam_student')->row_array();
			$data[$key]['exam_submit'] = ($exam_student) ? $exam_student['exam_submit'] : null; 
			$data[$key]['exam_total_nilai'] = ($exam_student) ? $exam_student['exam_total_nilai'] : null; 
		}

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => $this->model_asesmen->getAcountAllAsesmenKhusus($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/** 
	 * POST,
	 * return JSON
	 */
	public function save_draft(){
		$post = $this->input->post();

		$data = [
			'code' 			=> $post['code'],
			'class_id' 		=> $post['class_id'],
			'subject_id' 	=> $post['subject_id'],
			'title'			=> trim($post['title']),
			'description'	=> trim($post['desc']),
			'start_date'	=> $post['start_dt'],
			'end_date'		=> $post['end_dt'],
			'tipe'			=> $post['tipe'],
			'status'		=> $post['status'],
			'teacher_id'	=> $post['teacher_id'],
			'category_id'	=> $post['category_id']
		];

		// TIDAK JIKA ADA EXAM ID NYA LAKUKAN INSERT
		if(!$post['exam_id']){
			$insert = $this->db->insert('exam', $data);
			$insert_id = $this->db->insert_id();
	
			if($insert){
				// lakukan insert soal_exam
				foreach ($post['soal_ids'] as $key => $value) {
					$dataSoal = [
						'exam_id' => $insert_id,
						'soal_id' => $value,
						'no_urut' => $key+1,
						'bobot_nilai' => 1
					];
					$this->db->insert('soal_exam', $dataSoal);
				}

				$datas = [ 'success' => true,
							'exam_id'=> $insert_id,
							'message' => 'Data berhasil disimpan !!!' ];
			}else{
				$datas = [ 'success' => false,
							'message'	=> 'Data gagal disimpan !!!'];
			}
		}else{
			unset($data['code']);
			$update = $this->db->update('exam', $data, ['exam_id' => $post['exam_id']]);
			
			// delete soal_exam
			$this->db->delete('soal_exam', ['exam_id' => $post['exam_id']]);

			// lakukan insert soal_exam
			foreach ($post['soal_ids'] as $key => $value) {
				$dataSoal = [
					'exam_id' => $post['exam_id'],
					'soal_id' => $value,
					'no_urut' => $key+1,
					'bobot_nilai' => 1
				];
				$this->db->insert('soal_exam', $dataSoal);
			}

			if($update){
				$datas = [ 'success' => true,
							'exam_id'=> $post['exam_id'],
							'message' => 'Data berhasil disimpan !!!' ];
			}else{
				$datas = [ 'success' => false,
							'message'	=> 'Data gagal disimpan !!!'];
			}
		}
		
		echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/**
	 * GET
	 * Return JSON
	 */
	public function get_soal_by_exam_id(){
		$get = $this->input->get();
		
		$data = $this->db->where('se.exam_id', $get['exam_id'])
				->join('soal', 'soal.soal_id = se.soal_id', 'left')
				->order_by('grouping', 'ASC')
				->order_by('no_urut', 'ASC')
				->get('soal_exam se')->result_array();
		echo json_encode($data, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/**
	 * POST
	 * Return JSON
	 */
	// public function publish(){
	// $post = $this->input->post();

	// 	$data = [
	// 		'code' 			=> $post['code'],
	// 		'class_id' 		=> !empty($post['class_id']) ? $post['class_id'] : $post['select_kelas'],
	// 		'subject_id' 	=> $post['subject_id'],
	// 		'title'			=> trim($post['title']),
	// 		'description'	=> trim($post['desc']),
	// 		'start_date'	=> $post['start_dt'],
	// 		'end_date'		=> $post['end_dt'],
	// 		'tipe'			=> $post['tipe'],
	// 		'status'		=> $post['status'],
	// 		'teacher_id'	=> isset($post['teacher_id']) ? $post['teacher_id'] : null,
	// 		'student_id'	=> (isset($post['student_id']) && !empty($post['student_id'])) ? $post['student_id'] : null,
	// 		'category_id'	=> isset($post['category_id']) ? $post['category_id'] : null,
	// 	];

	// 	// TIDAK JIKA ADA EXAM ID NYA LAKUKAN INSERT
	// 	if(!$post['exam_id']){
	// 		$insert = $this->db->insert('exam', $data);
	// 		$insert_id = $this->db->insert_id();
	
	// 		if($insert){
	// 			// lakukan insert soal_exam
	// 				foreach ($post['soal_ids'] as $key => $value) {
	// 					$dataSoal = [
	// 						'exam_id' => $insert_id,
	// 						'soal_id' => $value,
	// 						'no_urut' => $key+1,
	// 						'bobot_nilai' => 1
	// 					];
	// 					$this->db->insert('soal_exam', $dataSoal);
	// 				}

	// 			// lakukan insert notif ke semua siswa
	// 				$students = $this->db->where('class_id', $data['class_id'])
	// 								->join('users u', 'u.username = s.nis', 'left')
	// 								->get('student s')->result_array();
	// 				foreach ($students as $key => $value) {
	// 					$data_notif = [
	// 						"type" 	=> "ASESMEN",
	// 						"title" => trim($post['title']),
	// 						"seen" 	=> false,
	// 						"user_id" => $value['userid'],
	// 						"created_at" => date('Y-m-d H:i:s'),
	// 						// "link"	=> "asesmen/do_exercise/$insert_id",
	// 						"link"	=> "asesmen",
	// 						"exam_id" => $insert_id
	// 					];
	// 					$this->db->insert('notif', $data_notif);
	// 				}

	// 			$datas = [ 'success' => true,
	// 						'exam_id'=> $insert_id,
	// 						'message' => 'Data berhasil disimpan !!!' ];
	// 		}else{
	// 			$datas = [ 'success' => false,
	// 						'message'	=> 'Data gagal disimpan !!!'];
	// 		}
	// 	}else{
	// 		unset($data['code']);
	// 		$update = $this->db->update('exam', $data, ['exam_id' => $post['exam_id']]);
			
	// 		// delete soal_exam
	// 		$this->db->delete('soal_exam', ['exam_id' => $post['exam_id']]);

	// 		// lakukan insert soal_exam
	// 			foreach ($post['soal_ids'] as $key => $value) {
	// 				$dataSoal = [
	// 					'exam_id' => $post['exam_id'],
	// 					'soal_id' => $value,
	// 					'no_urut' => $key+1,
	// 					'bobot_nilai' => 1
	// 				];
	// 				$this->db->insert('soal_exam', $dataSoal);
	// 			}
			
	// 		// lakukan insert notif ke semua siswa
	// 			$students = $this->db->where('class_id', $post['class_id'])
	// 						->join('users u', 'u.username = s.nis', 'left')
	// 						->get('student s')->result_array();
	// 			foreach ($students as $key => $value) {
	// 			$data_notif = [
	// 				"type" 	=> "ASESMEN",
	// 				"title" => trim($post['title']),
	// 				"seen" 	=> false,
	// 				"user_id" => $value['userid'],
	// 				"created_at" => date('Y-m-d H:i:s'),
	// 				"link"	=> "asesmen/do_exercise/".$post['exam_id'],
	// 				"exam_id" => $post['exam_id']
	// 			];
	// 			$this->db->insert('notif', $data_notif);
	// 			}

	// 		if($update){
	// 			$datas = [ 'success' => true,
	// 						'exam_id'=> $post['exam_id'],
	// 						'message' => 'Data berhasil disimpan !!!' ];
	// 		}else{
	// 			$datas = [ 'success' => false,
	// 						'message'	=> 'Data gagal disimpan !!!'];
	// 		}
	// 	}
		
	// 	echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	// }

	/**
	 * Publish new Assestment
	 *
	 * @return void
	 */
	public function save(): void {
		try
		{
			$judul = $this->input->post('a_title', TRUE);
			$start = $this->input->post('a_start', TRUE);
			$end = $this->input->post('a_end', TRUE);
			$deskripsi = $this->input->post('deskripsi', TRUE);
			$kategory = $this->input->post('select-category', TRUE);
			$kelas =  !empty(trim($this->input->post('class_id'))) ? 
						trim($this->input->post('class_id')) : 
						trim($this->input->post('select-kelas', TRUE));
			$mapel = $this->input->post('select-mapel', TRUE);
			$teacher_id = $this->input->post('teacher_id', TRUE) ?? NULL;
			$student_id = $this->input->post('student_id', TRUE) ?? NULL;
			$questions = $this->input->post('soal_id');
			$status = intval($this->input->post('status'));
			$is_update = $this->input->post('is_update');
			$examId = intval($this->input->post('exam_id'));
			$tipe = $this->input->post('tipe', TRUE) ?? 0;

			if(	empty($judul) || 
				empty($start) || 
				empty($end) || 
				empty($kategory) || 
				empty($kelas) || 
				empty($mapel) || 
				count($questions) == 0
				)
			{
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			if(strtotime($start) >= strtotime($end)){
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_date_start_end_mismatch'), 'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			if($is_update == 'on') 
			{
				if(empty($examId)) 
				{
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}
			}

			$startDate = new DateTime(str_replace('T', ' ', $start));
			$endDate = new DateTime(str_replace('T', ' ', $end));

			$data = [
				'code' 			=> strtotime('now'),
				'class_id' 		=> $kelas,
				'subject_id' 	=> $mapel,
				'title'			=> $judul,
				'description'	=> $deskripsi,
				'start_date'	=> $startDate->format('Y-m-d H:i:s'),
				'end_date'		=> $endDate->format('Y-m-d H:i:s'),
				'tipe'			=> $tipe,
				'status'		=> $status,
				'category_id'	=> $kategory,
				'student_id'	=> $student_id,
			];

			if($_SESSION['user_level'] === 4)
			{
				if(empty($this->input->post('student_id', TRUE)))
				{
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}

				$data['student_id'] = trim($this->input->post('student_id'));
				
			}


			if($_SESSION['user_level'] === 3)
			{
				if(empty($this->input->post('teacher_id', TRUE)))
				{
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}

				$data['teacher_id'] = trim($this->input->post('teacher_id'));
			}

			$this->db->trans_start();
			$exam_id = NULL;

			if(!empty($is_update) && $is_update == 'on') 
			{
				$this->db->update('exam', $data, ['exam_id' => $examId]);
				$exam_id = $examId;
			}
			else
			{
				$this->db->insert('exam', $data);
				$exam_id = $this->db->insert_id();
			}

			$this->db->where('exam_id', $exam_id)->delete('soal_exam');

			foreach($questions as $key => $val)
			{
				foreach($val as $k => $v) {
					$soal_exam = [
						'exam_id' 		=> $exam_id,
						'soal_id' 		=> $v,
						'no_urut' 		=> $k,
						'bobot_nilai'	=> 1,
						'grouping'		=> $key
					];

					$this->db->insert('soal_exam', $soal_exam);
				}
			}

			$this->db->trans_complete();

			if(!$this->db->trans_status())
			{
				$this->db->trans_rollback();
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
			}

			$this->db->trans_commit();
			
			// import push notif
			include_once APPPATH . 'libraries/Push_notif.php';
			$pushNotif = new Push_notif();
			
			$data_notif = [
				'exam_id' => $exam_id,
				'title' => 'Ada ujian yang akan Kamu kerjakan'
			];
			$new_exam = $pushNotif->new_exam($data_notif);
			http_response_code(200);
			$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'id' => $exam_id, 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			exit;

		}
		catch(Throwable $e)
		{
			log_message('error', $e->getMessage());
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
	}

	/**
	 * Publish new Asesmen Mandiri
	 * @return void
	 */

	public function save_mandiri():void{
		try {
			$judul = $this->input->post('a_title', TRUE);
			$deskripsi = $this->input->post('deskripsi', TRUE);
			$mapel = $this->input->post('select-mapel', TRUE);
			$student_id = $this->input->post('student_id', TRUE) ?? NULL;
			$questions = $this->input->post('soal_id[]') ?? [];
			$status = intval($this->input->post('status'));
			$is_update = $this->input->post('is_update');
			$examId = intval($this->input->post('exam_id'));
			$tipe = $this->input->post('tipe', TRUE) ?? 0;

			if(	empty($judul) || empty($mapel) || count($questions) == 0 ) {
				http_response_code(422);
				$msg = [
					'err_status' => 'error', 
					'message' => $this->lang->line('woow_is_required'),
					'token' => $this->security->get_csrf_hash()
				];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			if($is_update == 'on') {
				if(empty($examId)) {
					http_response_code(422);
					$msg = [
						'err_status' => 'error', 
						'message' => $this->lang->line('woow_is_required'),
						'token' => $this->security->get_csrf_hash()
					];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}
			}

			$data = [
				'code' 			=> strtotime('now'),
				'subject_id' 	=> $mapel,
				'title'			=> $judul,
				'start_date'	=> date('Y-m-d H:i:s'),
				'end_date'		=> date('Y-m-d H:i:s', time() + 86400),
				'description'	=> $deskripsi,
				'tipe'			=> $tipe,
				'status'		=> $status,
				'student_id'	=> $student_id,
			];

			$this->db->trans_start();
			$exam_id = NULL;

			if(!empty($is_update) && $is_update == 'on') {
				$this->db->update('exam', $data, ['exam_id' => $examId]);
				$exam_id = $examId;
			} else {
				$this->db->insert('exam', $data);
				$exam_id = $this->db->insert_id();
			}

			$this->db->where('exam_id', $exam_id)->delete('soal_exam');

			foreach($questions as $key => $val){
				// foreach($val as $k => $v) {
					$soal_exam = [
						'exam_id' 		=> $exam_id,
						'soal_id' 		=> (int)$val,
						'no_urut' 		=> $key+1,
						'bobot_nilai'	=> 1,
						'grouping'		=> 1 // pilihan ganda
					];
					$this->db->insert('soal_exam', $soal_exam);
				// }
			}

			$this->db->trans_complete();

			if(!$this->db->trans_status()){
				$this->db->trans_rollback();
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
			}

			$this->db->trans_commit();
			http_response_code(200);
			$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'id' => $exam_id, 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			exit;

		} catch(Throwable $e) {
			log_message('error', $e->getMessage());
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
	}

	/**
	 * GET
	 * Return JSON
	 */
	public function get_exam_student(){
		$draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_asesmen->getExamStudent($limit, $offset, $filters);

		$students = [];
		// AMBIL DATA SEMUA MURID DI KELAS EXAM
		if(!empty($filters[3]['search']['value'])){
			$exam = $this->db->where('exam_id', $filters[3]['search']['value'])->get('exam')->row_array();
			$students = $this->db->where('class_id', $exam['class_id'])->get('student')->result_array();

			foreach ($students as $key => $value) {
				foreach ($data as $key2 => $value2) {
					if($value['student_id'] == $value2['student_id']){
						$students[$key]['exam_answer'] = $value2;
					}else{
						$students[$key]['exam_answer'] = [];
					}
				}
			}
		}

        $datas = [
            'draw' => $draw,
            'data' => $students,
            'recordsTotal' => count($data),
            'recordsFiltered' => $this->model_asesmen->getCountExamStudent($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}


	public function do_exercise($id = ''){
		// $header['page_css'] = [
		// 	'https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.snow.css',
		// 	'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
		// 	'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
		// 	'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		// ];

		// page js
			// $data['page_js'] = 	[
			// 	['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'],
			// 	['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'],
			// 	['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'],
			// 	['path' => 'https://kit.fontawesome.com/b377b34fd7.js'],
			// 	['path' => 'https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.js'],
			// 	['path' => 'assets/js/storingAnswer.js', 'defer' => TRUE],
			// 	['path' => 'assets/js/_do_exercise.js', 'defer' => TRUE],
			// ];
		$student_id = $_SESSION['student_id'];
		
		$data['exam_id'] = $id;
		$data['exam'] = $this->db->where('exam_id', $id)
						->join('subject s', 's.subject_id = e.subject_id', 'left')
						->join('kelas k', 'k.class_id = e.class_id', 'left')
						->get('exam e')->row_array();

		$data['exam_answer'] = $this->db->where('exam_id', $id)->where('student_id', $student_id)->get('exam_answer')->num_rows();

		// $this->load->view('header', $header);
		$this->load->view('asesmen/do_exercise', $data);
		// $this->load->view('footer');
		// $this->template->load('template', 'asesmen/do_exercise', $data);get_soal_by_exam_id
	}

	/**
	 * Simpan Jawaban dari asesmen
	 *
	 * @return void
	 */
	public function save_answer(): void {
		$student_id = $_SESSION['student_id'];
		$class_id = $_SESSION['class_id'];

		$data = json_decode(file_get_contents('php://input'), true);
		$created_dt = date('Y-m-d H:i:s');

		$check = $this->db->get_where('exam_student', ['student_id' => $student_id, 'exam_id' => $data['exam_id']]);

		if($check->num_rows() > 0)
		{
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => 'Anda sudah mengerjakan ujian ini !!!'];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		$this->db->trans_start();
		foreach ($data['data'] as $value) {
			if(isset($value['soal_id'])){
				$soal = $this->db->where('soal_id', $value['soal_id'])->get('soal')->row_array();

				$dataInsert = [
					'student_id' 	=> $student_id,
					'exam_id'		=> $data['exam_id'],
					'class_id'		=> $class_id,
					'exam_answer' 	=> $value['jawaban'],
					'correct_answer'=> $soal['answer'],
					'exam_submit'	=> $created_dt,
					'soal_id'		=> $value['soal_id']
				]; 

				$this->db->insert('exam_answer', $dataInsert);
			}
		}

		$dataExam = [
			'student_id'	=> $student_id,
			'exam_id'		=> $data['exam_id'],
			'exam_submit' 	=> $created_dt
		];
		$this->db->insert('exam_student', $dataExam);
		$this->db->trans_complete();

		if(!$this->db->trans_status())
		{
			$this->db->trans_rollback();
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		$this->db->trans_commit();
		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'data' => $data];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}

	/**
	 * @param $id
	 * GET
	 * Return VIEW
	 */
	public function show_answer($id = ''){
		$header['add_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		// page js
			$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
			$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
			$data['page_js'][] = ['path' => 'https://code.highcharts.com/highcharts.js'];
			$data['page_js'][] = ['path' => 'https://code.highcharts.com/modules/exporting.js'];
			$data['page_js'][] = ['path' => 'https://code.highcharts.com/modules/accessibility.js'];
			$data['page_js'][] = ['path' => 'assets/js/_show_answer.js'];

		$exam_id 	= $this->input->get('id');
		$student_id = $this->input->get('student_id'); 
		
		$data['exam_id'] = $exam_id;
		$data['exam'] = $this->db->where('exam_id', $exam_id)
						->join('subject s', 's.subject_id = e.subject_id', 'left')
						->join('kelas k', 'k.class_id = e.class_id', 'left')
						->get('exam e')->row_array();


		$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : $student_id;

		$data['soal_exam'] = $this->db->where('se.exam_id', $exam_id)
						->join('soal s', 's.soal_id = se.soal_id', 'left')
						->order_by('grouping', 'ASC')
						->get('soal_exam se')->result_array();

		$hasSoalEssay = false;

		// looping soal_exam dan ambil jawaban siswa kemudian masukan kedalam array soal_exam
		foreach ($data['soal_exam'] as $key => $value) {
			$exam_answer = $this->db->where('exam_id', $exam_id)
							->where('student_id', $student_id)
							->where('soal_id', $value['soal_id'])
							->get('exam_answer')->result_array();

			// jika jenis soal adalah drag and drop
			if($value['type'] == 6){

				// looping jawaban siswa
				$jawaban_siswa = [];
				foreach(json_decode($exam_answer[0]['exam_answer']) as $key2 => $value2){
					$urutan = $value2->urutan;

					$jawaban_siswa[] = $this->db->where('urutan', $urutan)
						->where('soal_id', $value['soal_id'])
						->get('soal_dragdrop_question')->row_array();
				}

				$data['soal_exam'][$key]['drag_drop_answer'] = $jawaban_siswa;

			}

			
			$data['soal_exam'][$key]['exam_answer'] = $exam_answer;
			
			// cek apakah ada soal essay
			if($value['type'] == 2){
				$hasSoalEssay = true;
			}
		}

		// hitung total soal
		$total_soal = count($data['soal_exam']);
		$jawaban_benar = 0;
		$jawaban_salah = 0;
		$totalPointBenar = 0;
		$totalPointSalah = 0;

		foreach ($data['soal_exam'] as $key => $value) {
			$totalAnswer = count($value['exam_answer']); // total jawaban yang di jawab oleh siswa
			$benar = 0; // jawaban benar
			$salah = 0; // jawaban salah

			foreach($value['exam_answer'] as $key2 => $value2){
				if ($value2['result_answer']) {					
					$benar++;
				} else {
					$salah++;
				}

				// jika jenis soal essay maka bobot nilainya di ambil dari soal_exam
				if($value['type'] == 2){
					$totalPointBenar += $value2['result_point'];
				} else {
					// jika jenis soal selain essay, maka bobot nilainya di ambil dari soal_exam
					// jika jawaban nya benar
					if($value2['result_answer']){
						$totalPointBenar += $value['point'];
					} else {
						$totalPointSalah += $value['point'];
					}
				}
			}


			// jika total jawaban yang di jawab sama dengan jawaban benar untuk soal selain essay
			if($value['type'] != 2){
				if($totalAnswer == $benar){
					$data['soal_exam'][$key]['status'] = 'benar';
					$jawaban_benar++;
				}else{
					$data['soal_exam'][$key]['status'] = 'salah';
					$jawaban_salah++;
				}
			}
			
		}

		// jika tidak ada soal essay, maka total point benar di hitung dari persentase jawaban benar
		if(!$hasSoalEssay){
			$totalPointBenar = ($totalPointBenar / ($totalPointBenar + $totalPointSalah)) * 100;
			$totalPointSalah = 100 - $totalPointBenar;
		}

		$data['total_point'] = $totalPointBenar;
		$data['total_soal'] = $total_soal;
		$data['jawaban_benar'] = $jawaban_benar;
		$data['jawaban_salah'] = $jawaban_salah;
		$data['student_id'] = $student_id;

		$data['exam_student'] = $this->db->where('student_id', $student_id)->where('exam_id', $exam_id)->get('exam_student')->row_array();

		//  ============ section card header =================
		// User image
		$student = $this->db->where('student_id', $student_id)
			->join('kelas k', 'k.class_id = s.class_id', 'left')
			->get('student s')->row_array();

		// count percentage of correct answer
		// $correctPercentage = ($jawaban_benar / $total_soal) * 100;
		// percentage correct answer sekarang di hitung berdasarkan total point
		$correctPercentage = $totalPointBenar;

		$data['correctPercentage'] = round($correctPercentage, 2);

		// $wrongPercentage = ($jawaban_salah / $total_soal) * 100;
		$wrongPercentage = 100 - $data['correctPercentage'];
		$data['wrongPercentage'] = $wrongPercentage;

		$user = $this->db->where('username', $student['nis'])->get('users')->row_array();
		$data['student'] = $student;
		$data['user_image'] = $user['photo'];

		// ============ end section card header ==============
		
		$this->template->load('template', 'asesmen/show_answer', $data);
	}

	/**
	 * Post
	 * Return JSON
	 */
	public function get_exam_answer(){
		$get = $this->input->get();

		$draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_asesmen->getExamAnswer($limit, $offset, $filters);

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => $this->model_asesmen->getCountExamAnswer($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/**
	 * GET
	 * Return JSON
	 */
	public function get_skema_penilaian(){
		$draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_asesmen->getSkemaPenilaian($limit, $offset, $filters);

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => $this->model_asesmen->getCountSkemaPenilaian($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/**
	 * POST
	 * Return JSON
	 */
	public function save_score(){
		$post = $this->input->post();
		$exam_student_id 	= $post['exam_student_id'];
		$score 				= $post['score'];
		$notes 				= $post['notes'];

		$update = $this->db->update('exam_student', ['exam_total_nilai' => $score, 'notes' => $notes], ['es_id' => $exam_student_id]);
		if($update){
			// import push notif
			include_once APPPATH . 'libraries/Push_notif.php';
			$pushNotif = new Push_notif();

			$pushNotif->send_exam_score($exam_student_id);

			$datas = [
				'success' => true,
				'message' => 'Data berhasil di simpan!'
			];
		}else{
			$datas = [
				'success' => false,
				'message' => 'Data gagal di simpan!'
			];
		}
		echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}


	/**
	 * Delete, Post exam_id
	 * Return JSON
	 */
	public function delete(){
		$post = $this->input->post();

		$exam_id = $post['exam_id'];

		// jika asesmen khusus murid bisa hapus asesmen meskipun sudah di jawab
		$asesmenKhusus = $this->db->where('exam_id', $exam_id)->where('tipe', 1)->get('exam')->row_array();
		if($asesmenKhusus){
			$this->db->where('exam_id', $exam_id)->delete('exam_student');
			$this->db->where('exam_id', $exam_id)->delete('exam_answer');
		}
		// ====================================================================

		$delete = $this->db->delete('exam', ['exam_id' => $exam_id]);
		if($delete){
			$datas = [
				'success' => true,
				'message' => 'Data berhasil di hapus!'
			];
		}else{
			$datas = [
				'success' => false,
				'message' => 'Data gagal di hapus!'
			];
		}
		echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/** 
	 * POST
	 * Return JSON
	 */
	public function copy_asesmen(){
		$this->load->library('random_string');
		$randomString = new Random_string;

		$post = $this->input->post();
		$exam_id = $post['exam_id'];
		$exam = $this->db->where('exam_id', $exam_id)->get('exam')->row_array();

		unset($exam['code']);
		unset($exam['exam_id']);

		$exam['code'] = $randomString->generateRandomString(10);
		$exam['status'] = 0;

		$insert = $this->db->insert('exam', $exam);
		$insert = $this->db->insert_id();

		$soal_exam = $this->db->where('exam_id', $exam_id)->order_by('no_urut', 'ASC')->get('soal_exam')->result_array();
		foreach ($soal_exam as $key => $value) {
			$soal_exam[$key]['exam_id'] = $insert;
			$this->db->insert('soal_exam', $soal_exam[$key]);
		}

		if($insert){
			$datas = [
				'success' => true,
				'message' => 'Asesmen berhasil di gandakan!'
			];
		}else{
			$datas = [
				'success' => false,
				'message' => 'Asesmen gagal di gandakan!'
			];
		}
		echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/** 
	 * POST
	 * Return JSON
	 */
	public function generate_pertanyaan_mandiri(){
		$post = $this->input->post();
		$filters = $post['columns'];

		$data['data'] = $this->model_asesmen_mandiri->getAllSoal(null, null, $filters);
		$data['token'] = $this->security->get_csrf_hash();
		echo json_encode($data, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}


	/**
	 * Simapna data soal baru
	 *
	 * @return void
	 */
	public function save_soal() {
        $code = $this->input->post('a_soal_code', TRUE);
        //$no = $this->input->post('a_soal_no', TRUE);
        $mapel = $this->input->post('a_soal_subject', TRUE);
        //$class = $this->input->post('a_soal_class', TRUE);
        $soal = $this->input->post('a_soal_detail', TRUE);
        $type = $this->input->post('a_soal_type', TRUE);
        $jawaban = $this->input->post('a_soal_answer', TRUE);
        $pg = $this->input->post('pg');

        $_data = [];

        header('Content-Type: application/json');
        
        // empty validation
        if(empty($code) || empty($mapel) ||  empty($soal) || empty($jawaban) || empty($type))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        $checkCode = $this->db->get_where('soal', ['code' => $code])->num_rows();
        //$checkClassAndMapel = $this->db->get_where('soal', ['subject_id' => $mapel, 'class_id' => $class])->num_rows();

        if($checkCode > 0)
        {
            http_response_code(422);
            $m = [
                'err_status' => 'error', 
                'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
            ];
            echo json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }
        
        if(!empty($_FILES['a_soal_file']['name']))
        {
            $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
            
            if(!file_exists($_dir))
                @mkdir(FCPATH.$_dir, 0777, TRUE);
            
            // get type
            $ext = pathinfo(basename($_FILES['a_soal_file']['name']), PATHINFO_EXTENSION);
            // move upload file
            $file = move_uploaded_file($_FILES['a_soal_file']['tmp_name'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext);

            if(!$file)
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

            $_data['question_file'] = $_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext;
        }

        $call = [];

        if(isset($pg))
        {
            $_i = 0;
            foreach($pg as $P)
            {
                //if(!isset($P['key'])) continue;

                $call[] = $P;

                if(!empty($P['value']));
                {
                    $_data['choice_'.$P['key']] = $P['value'];

                }

                if(!empty($_FILES['pg']['name'][$_i]['file']))
                {
                    $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
					
					if(!file_exists($_dir))
                		@mkdir(FCPATH.$_dir, 0777, TRUE);
                    
					$ext = pathinfo(basename($_FILES['pg']['name'][$_i]['file']), PATHINFO_EXTENSION);
                    // move upload file
                    $file = move_uploaded_file($_FILES['pg']['tmp_name'][$_i]['file'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext);
                    $filename = $_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext;
                    $_data['choice_'.$P['key'].'_file'] = $filename;
                }

                $_i++;

            }
        }
        
        $_data['code'] = $code;
        $_data['subject_id'] = $mapel;
    //    $_data['class_level_id'] = $class;
        $_data['question'] = $soal;
        $_data['answer'] = $jawaban;
        $_data['type'] = $type;

        if(!$this->db->insert('soal', $_data))
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
        //if(issets())
    }

	/**
	 * Merubah soal yang existing
	 *
	 * @return void
	 */
    public function edit_soal() {
        $_id = $this->input->post('a_id');
        $code = $this->input->post('a_soal_code', TRUE);
        //$no = $this->input->post('a_soal_no', TRUE);
        $mapel = $this->input->post('a_soal_subject', TRUE);
    //    $class = $this->input->post('a_soal_class', TRUE);
        $soal = $this->input->post('a_soal_detail', TRUE);
        $type = $this->input->post('a_soal_type', TRUE);
        $jawaban = $this->input->post('a_soal_answer', TRUE);
        $pg = $this->input->post('pg');

        $_data = [];

        header('Content-Type: application/json');
        
        // empty validation
        if(empty($_id) || empty($code) || empty($mapel) ||  empty($soal) || empty($jawaban) || empty($type))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        
        
        if(!empty($_FILES['a_soal_file']['name']))
        {
            $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
            
            if(!file_exists($_dir))
                @mkdir(FCPATH.$_dir, 0777, TRUE);
            
            // get type
            $ext = pathinfo(basename($_FILES['a_soal_file']['name']), PATHINFO_EXTENSION);

            if(file_exists(FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext))
                unlink(FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext);
            // move upload file
            $file = move_uploaded_file($_FILES['a_soal_file']['tmp_name'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext);

            if(!$file)
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

            $_data['question_file'] = $_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext;
        }

        $call = [];

        if(isset($pg))
        {
            $_i = 0;
            foreach($pg as $P)
            {
                //if(!isset($P['key'])) continue;

                $call[] = $P;

                if(!empty($P['value']));
                {
                    $_data['choice_'.$P['key']] = $P['value'];

                }

                if(!empty($_FILES['pg']['name'][$_i]['file']))
                {
                    $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
                    $ext = pathinfo(basename($_FILES['pg']['name'][$_i]['file']), PATHINFO_EXTENSION);
                    // move upload file
                    $filename = $_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext;
                    if(file_exists(FCPATH.$filename))
                        unlink(FCPATH.$filename);
                    $file = move_uploaded_file($_FILES['pg']['tmp_name'][$_i]['file'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext);
                    $_data['choice_'.$P['key'].'_file'] = $filename;
                }

                $_i++;

            }
        }
        
        $_data['code'] = $code;
        $_data['subject_id'] = $mapel;
      //  $_data['class_level_id'] = $class;
        $_data['question'] = $soal;
        $_data['answer'] = $jawaban;
        $_data['type'] = $type;

        if(!$this->db->update('soal', $_data, ['soal_id' => $_id]))
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
        //if(issets())
    }

	public function add_question(){
		$data['page_css'] = [
			'https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.snow.css',
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/sweetalert2/dist/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
		];

		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => 'https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.js'];
		$data['page_js'][] = ['path' => $this->config->item('admin_url').'assets/new/libs/randomString.js'];
		$data['page_js'][] = ['path' => 'assets/js/_question_new.js', 'defer' => true];
		$this->template->load('template', 'asesmen/add_question', $data);
	}


	public function test_upload_pg(){
		$this->load->view('asesmen/test_upload_pg');
	}

	public function cek_pertanyaan_duplikat(){
		$post = $this->input->post();

		$pertanyaan = $post['pertanyaan'];
		$pertanyaan =  (array_chunk($pertanyaan, 100));
		$sekolah_id = $post['sekolah_id'];

		for($i = 0; $i < count($pertanyaan); $i++){
			$cek = $this->db->where_in('question', $pertanyaan[$i])
				->join('subject s', 's.subject_id = soal.subject_id', 'left')
				->where('s.sekolah_id', $sekolah_id)
				->get('soal')->result_array();

			if(!empty($cek)){
				header('Content-Type: application/json');
				echo json_encode([
					'success' => false, 
					'message' => "Pertanyaan \"".$cek[0]['question']."\" sudah ada di database",
					'token' => $this->security->get_csrf_hash()
				]);
				return;
			}
		}
		

		// $cek = $this->db->where_in('question', $pertanyaan)
		// 	->join('subject s', 's.subject_id = soal.subject_id', 'left')
		// 	->where('s.sekolah_id', $sekolah_id)
		// 	->get('soal')->row_array();

		// pertanyaan ada yang duplikat
		// response header json
		header('Content-Type: application/json');
		// if(!empty($cek)){
		// 	echo json_encode([
		// 		'success' => false, 
		// 		'message' => "Pertanyaan \"".$cek['question']."\" sudah ada di database",
		// 		'token' => $this->security->get_csrf_hash()
		// 	]);
		// } else{
			echo json_encode([
				'success' => true, 
				'message' => "Pertanyaan tidak ada yang duplikat",
				'token' => $this->security->get_csrf_hash()
			]);
		// }

	}

	public function cek_mapel(){
		$post = $this->input->post();

		$mapels = $post['mapel'];
		$sekolah_id = $post['sekolah_id'];

		foreach($mapels as $key => $value) {
			$cek = $this->db->where('lower(subject_name)', strtolower($value))
				->where('sekolah_id', $sekolah_id)
				->get('subject')->row_array();

			if(empty($cek)){
				header('Content-Type: application/json');
				echo json_encode([
					'success' => false, 
					'message' => "Mapel ".$value." tidak ada di database",
					'token' => $this->security->get_csrf_hash()
				]);
				return;
			}
		}

		header('Content-Type: application/json');
		echo json_encode([
			'success' => true, 
			'message' => "Mapel ada di database",
			'token' => $this->security->get_csrf_hash()
		]);
		
	}

	public function import_soal_pg(){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET');

		$data = file_get_contents('php://input');
		$soal = json_decode($data, true)['data'];
		$sekolah_id = json_decode($data, true)['sekolah_id'];

		// array length soal

		$n = 0;
		$loaded = 0;
		ob_start();

		foreach($soal as $key => $value) {
			// generate code 10 karakter using random string
			$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
			$subject = $this->db->where('lower(subject_name)', strtolower($value['Kategori Pembelajaran']))
				->where('sekolah_id', $sekolah_id)
				->get('subject')->row_array();

			$insert = [
				'code' 			=> $code,
				'subject_id' 	=> $subject['subject_id'],
				'question' 		=> $value['Pertanyaan'],
				'answer' 		=> $value['Jawaban Benar'],
				'type' 			=> 1,
				'choice_a' 		=> $value['Pilihan A'],
				'choice_b' 		=> $value['Pilihan B'],
				'choice_c' 		=> $value['Pilihan C'],
				'choice_d' 		=> $value['Pilihan D'],
			];

			if(!$this->db->insert('soal', $insert)){
				// header('Content-Type: application/json');
				// echo json_encode(['success' => false, 'message' => 'Data dengan pertanyaan '.$value['Pertanyaan'].' gagal di simpan, silahkan perbaiki data anda']);
				// return;
				$loaded += 1;
			} else {
				$loaded += 1;
			}
			
			// header('Content-Type: application/json');
			echo json_encode(['total' => count($soal), 'loaded' => $loaded]);
			ob_flush();
			$n++;

		}
		ob_end_flush();
	}

	function clear(){
		echo '
			<script>
				window.localStorage.removeItem("examId");
				window.localStorage.removeItem("remaining_time");
				window.localStorage.removeItem("soal");
				window.localStorage.removeItem("soal_master");
				window.localStorage.removeItem("start_time");

				window.location.href = "'.base_url('Asesmen').'";
			</script>
		';
	}
	
}
