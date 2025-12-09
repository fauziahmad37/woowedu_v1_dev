<?php

use Svg\Tag\Path;

// import library mpdf
use Dompdf\Dompdf;

defined('BASEPATH') or exit('No direct script access allowed');

class Asesmen_standard extends CI_Controller
{

	private $settings;
	private $_soaldir;

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_asesmen', 'model_settings', 'model_subject_teacher', 'model_class_teacher', 'model_subject', 'model_student', 'model_exam_student']);
		$this->load->helper(['assets', 'customstring', 'cek']);

		if (!isset($_SESSION['username'])) redirect('auth/login');

		$this->settings = json_decode(json_encode($this->model_settings->get_settings()), TRUE);
		$this->_soaldir = $this->config->item('admin_path').'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal';

		$this->lang->load('upload', 'indonesia');
		$this->lang->load('message', 'indonesia');
	}

	function create($id = null)
	{
		// PAGE CSS
		$data['page_css'] = [
			'https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css',
			base_url()."assets/css/_create_asesmen_standard.css",
		];

		// PAGE JS
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => $this->config->item('admin_url') . 'assets/new/libs/randomString.js'];
		$data['page_js'][] = ['path' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/sortablejs/Sortable.min.js'];
		$data['page_js'][] = ['path' => 'assets/js/asesmen_revamp/_create_multiple_choice.js'];
		$data['page_js'][] = ['path' => 'assets/js/asesmen_revamp/_create_asesmen_standard.js'];


		// $data['page_js'][] = ['path' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'];
		// $data['page_js'][] = ['path' => 'assets/js/asesmen_revamp/_create_asesmen_standard.js'];
		// $data['page_js'][] = ['path' => 'assets/js/_create_asesmen_standar.js'];
		// $data['page_js'][] = ['path' => 'assets/js/_question_new.js', 'defer' => true];
		// $data['page_js'][] = ['path' => 'admin/assets/new/libs/datatables.net-buttons-bs4/js/select.bootstrap4.min.js'];
		// $data['page_js'][] = ['path' => 'admin/assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true];

		$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : null;
		$data['teacher_id'] = $teacher_id;

		if ($id) {
			$data['exam'] = $this->db->where('exam_id', $id)->get('exam')->row_array();
		}

		if (!is_null($teacher_id)) {
			$data['mapels'] = $this->model_subject_teacher->get($teacher_id);
		} else {
			$classLevelId[] = $_SESSION['class_level_id'];
			$data['mapels'] = $this->model_subject->get_by_class_level($classLevelId);
		}

		$data['categories'] = $this->db->get('exam_category')->result_array();
		$data['classes'] = $this->model_class_teacher->get($teacher_id);

		$data['is_update'] = FALSE;
		if (!empty($id))
			$data['is_update'] = TRUE;

		$this->template->load('template', 'asesmen_standard/create', $data);
	}

	function edit($id = '')
	{
		if (empty($id)) {
			redirect('asesmen_standard/create');
		}

		// PAGE CSS
		$data['page_css'] = [
			'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/_create_asesmen_standard.css',
		];

		// PAGE JS
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'];
		$data['page_js'][] = ['path' => 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'];
		$data['page_js'][] = ['path' => 'assets/node_modules/sweetalert2/dist/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'https://kit.fontawesome.com/b377b34fd7.js'];
		$data['page_js'][] = ['path' => $this->config->item('admin_url') . 'assets/new/libs/randomString.js'];
		$data['page_js'][] = ['path' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'];
		$data['page_js'][] = ['path' => 'assets/js/asesmen_revamp/_create_asesmen_standard.js'];

		$teacher_id = isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : null;
		$data['teacher_id'] = $teacher_id;

		if ($id) {
			$data['exam'] = $this->db->where('exam_id', $id)->get('exam')->row_array();
		}

		if (!is_null($teacher_id)) {
			$data['mapels'] = $this->model_subject_teacher->get($teacher_id);
		} else {
			$classLevelId = $_SESSION['class_level_id'];
			$data['mapels'] = $this->db->where('class_level_id', $classLevelId)->get('subject')->result_array();
		}

		$data['categories'] = $this->db->get('exam_category')->result_array();
		$data['classes'] = $this->model_class_teacher->get($teacher_id);
		$data['is_update'] = FALSE;
		if (!empty($id))
			$data['is_update'] = TRUE;

		// get question group by type/jenis soal
		$data['questions'] = $this->db->select('exam_id, soal.type')
			->join('soal', 'soal.soal_id = soal_exam.soal_id')
			->where('exam_id', $id)
			->group_by('type, exam_id')
			->get('soal_exam')->result_array();

		// get question detail
		foreach ($data['questions'] as $key => $question) {
			$data['questions'][$key]['detail'] = $this->db
				->join('soal', 'soal.soal_id = soal_exam.soal_id')
				->where('exam_id', $question['exam_id'])
				->where('soal.type', $question['type'])
				->get('soal_exam')->result_array();
		}

		$this->template->load('template', 'asesmen_standard/create', $data);
	}

	function get_question_bank()
	{
		$draw = $this->input->get('draw');
		$limit = $this->input->get('length');
		$offset = $this->input->get('start');
		$filters = $this->input->get('columns');

		$data = $this->model_asesmen->getAllSoal($limit, $offset, $filters);

		$datas = [
			'draw' => $draw,
			'data' => $data,
			// 'recordsTotal' => $this->db->count_all_results('soal'),
			'recordsTotal' => $this->model_asesmen->getCountAllSoal($filters),
			'recordsFiltered' => $this->model_asesmen->getCountAllSoal($filters)
		];

		echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	function get_student_collect(){
		$draw = $this->input->post('draw');
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$filters = $this->input->post('columns');

		$filters['class_id'] = $this->input->post('class_id');
		$filters['exam_id'] = $this->input->post('exam_id');
		$filters['namaSiswa'] = $filters[2]['search']['value'];
		$filters['status'] = $filters[4]['search']['value'];

		$data = $this->model_student->getAll($limit, $offset, $filters);

		// looping untuk mendapatkan exam student yang sudah mengumpulkan
		foreach ($data as $key => $student) {
			$exam_student = $this->model_exam_student->get_exam_by_student_id($student['student_id'], $_POST['exam_id']);
			
			$status = '';
			$examSubmit = '-';
			$examScore = '-';
			$exam_student_id = '';
			
			// jika exam student nya ada maka buat status
			if($exam_student){
				$exam_student_id = $exam_student['es_id'];
				$examSubmit = $exam_student['exam_submit'];

				// jika exam total nilai ada maka sudah dinilai
				if($exam_student['exam_total_nilai']){
					$status = 'Sudah Dinilai';
					$examScore = $exam_student['exam_total_nilai'];
				} else {
					$status = 'Menunggu Penilaian';
				}
			}else{
				$status = 'Belum Mengumpulkan';
			}

			$data[$key]['status'] = $status;
			$data[$key]['exam_submit'] = $examSubmit;
			$data[$key]['exam_score'] = $examScore;
			$data[$key]['exam_student_id'] = $exam_student_id;
			$data[$key]['exam_id'] = $_POST['exam_id'];
		}

		// jika filter status nya ada
		if($filters['status']){
			// filter data berdasarkan status
			foreach ($data as $key => $student) {
				if(strtolower($student['status']) !== strtolower($filters['status'])){
					unset($data[$key]);
				}
			}

			$data = array_values($data);
		}

		$datas = [
			'draw' => $draw,
			'data' => $data,
			'recordsTotal' => $this->model_student->getAllCount($filters),
			'recordsFiltered' => $this->model_student->getAllCount($filters),
		];

		header('Content-Type: application/json');
		echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);

	}

	function download_student_collect_exam(){
		$exam_id = $this->input->get('exam_id');

		$exam = $this->db->where('exam_id', $exam_id)->get('exam')->row_array(); // get detail exam

		$filters['class_id'] = $exam['class_id'];
		$data = $this->model_student->getAll(null, null, $filters); // get all student in class

		// looping untuk mendapatkan exam student yang sudah mengumpulkan
		foreach ($data as $key => $student) {
			$exam_student = $this->model_exam_student->get_exam_by_student_id($student['student_id'], $exam_id);
			
			$status = '';
			$examSubmit = '-';
			$examScore = '-';
			
			// jika exam student nya ada maka buat status
			if($exam_student){
				$examSubmit = $exam_student['exam_submit'];

				// jika exam total nilai ada maka sudah dinilai
				if($exam_student['exam_total_nilai']){
					$status = 'Sudah Dinilai';
					$examScore = $exam_student['exam_total_nilai'];
				} else {
					$status = 'Menunggu Penilaian';
				}
			}else{
				$status = 'Belum Mengumpulkan';
			}

			$data[$key]['status'] = $status;
			$data[$key]['exam_submit'] = $examSubmit;
			$data[$key]['exam_score'] = $examScore;
			$data[$key]['exam_id'] = $exam_id;
		}

		$html = $this->load->view('asesmen_standard/download_student_collect_exam', ['data' => $data], true);

		$dompdf = new Dompdf();

		$options = $dompdf->getOptions();
		$options->set('isRemoteEnabled', true);
		$dompdf->setOptions($options);

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream('soal_' . $exam['code'] . '.pdf', array('Attachment' => 0));
	}

	function create_fill_the_blank()
	{
		$data = [];

		$this->load->view('asesmen_standard/header');
		$this->load->view('asesmen_standard/create_fill_the_blank', $data);
		$this->load->view('asesmen_standard/footer');
	}

	function save_fill_the_blank()
	{
		$post = $this->input->post();

		// generate random string 10 character
		$code = $this->code();
		$data = [];

		// save image soal
		if (isset($_FILES['image'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_soal.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['question_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban
		if (isset($_FILES['imageJawabanBenar'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanBenar'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_benar.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_correct_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban salah
		if (isset($_FILES['imageJawabanSalah'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanSalah'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_salah.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_wrong_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		$data['code'] = $code;
		$data['type'] = $post['type'];
		$data['subject_id'] = $post['mapel'];
		$data['question'] = trim(preg_replace("/\r|\n/", "", $post['soal']));
		$data['answer'] = trim(preg_replace("/\r|\n/", "", $post['jawaban']));

		if($post['responseJawabanCheck'] == 'true'){
			$data['response_correct_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanBenar']));
			$data['response_wrong_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanSalah']));

			if($post['deleteImageJawabanBenar'] == 'true'){
				$data['response_correct_answer_file'] = '';
			}

			if($post['deleteImageJawabanSalah'] == 'true'){
				$data['response_wrong_answer_file'] = '';
			}
		}

		if($post['jawabanAlternatifCheck'] == 'true'){
			$data['alternative_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanAlternatif']));
		}

		$data['variation_answer'] = trim(preg_replace("/\r|\n/", "", $post['variasiJawaban']));
		$data['point'] = (int)$post['point'];

		$this->db->insert('soal', $data);
		$id = $this->db->insert_id();

		if ($id) {
			$data['id'] = $id;
			$res = [
				'success' => true,
				'message' => 'Soal berhasil disimpan',
				'data' => $data
			];
		} else {
			$res = [
				'success' => false,
				'message' => 'Soal gagal disimpan'
			];
		}

		$res['csrf_token'] = $this->security->get_csrf_hash();

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function save_pilihan_ganda()
	{
		$post = $this->input->post();

		// generate random string 10 character
		$code = $this->code();
		$data = [];

		// save image soal
		if (isset($_FILES['image'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_soal.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['question_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban
		if (isset($_FILES['imageJawabanBenar'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanBenar'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_benar.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_correct_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban salah
		if (isset($_FILES['imageJawabanSalah'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanSalah'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_salah.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_wrong_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// item pilihan ganda / multiple choice
		$choices = [];
		$multipleChoice = json_decode($post['multipleChoice'], true);
		foreach ($multipleChoice as $key => $value) {
			// save image jawaban from post data image base64
			if (isset($value['image'])) {
				$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
				// convert base64 to image
				list($type, $value['image']) = explode(';', $value['image']);
				list(, $value['image'])      = explode(',', $value['image']);
				$dataImage = base64_decode($value['image']);
				$ext = 'jpg';
				$filename = $code . '_jawaban_' . $key . '.' . $ext;
				$destination = $_dir . '/' . $filename;

				if (!file_exists($_dir)) {
					mkdir($_dir, 0777, true);
				}

				file_put_contents($destination, $dataImage);

				$choices[$key]['image'] = 'assets/files/soal/' . $code . '/' . $filename;
			}

			// save jawaban benar
			if ($key == 0 && $value['trueChoice'] == true) $data['answer'] = 'A';
			if ($key == 1 && $value['trueChoice'] == true) $data['answer'] = 'B';
			if ($key == 2 && $value['trueChoice'] == true) $data['answer'] = 'C';
			if ($key == 3 && $value['trueChoice'] == true) $data['answer'] = 'D';
			if ($key == 4 && $value['trueChoice'] == true) $data['answer'] = 'E';
		}

		// data pilihan ganda
		$data['choice_a'] = isset($multipleChoice[0]['choice']) ? $multipleChoice[0]['choice'] : '';
		$data['choice_b'] = isset($multipleChoice[1]['choice']) ? $multipleChoice[1]['choice'] : '';
		$data['choice_c'] = isset($multipleChoice[2]['choice']) ? $multipleChoice[2]['choice'] : '';
		$data['choice_d'] = isset($multipleChoice[3]['choice']) ? $multipleChoice[3]['choice'] : '';
		$data['choice_e'] = isset($multipleChoice[4]['choice']) ? $multipleChoice[4]['choice'] : '';

		$data['choice_a_file'] = isset($choices[0]['image']) ? $choices[0]['image'] : '';
		$data['choice_b_file'] = isset($choices[1]['image']) ? $choices[1]['image'] : '';
		$data['choice_c_file'] = isset($choices[2]['image']) ? $choices[2]['image'] : '';
		$data['choice_d_file'] = isset($choices[3]['image']) ? $choices[3]['image'] : '';
		$data['choice_e_file'] = isset($choices[4]['image']) ? $choices[4]['image'] : '';

		$data['code'] = $code;
		$data['type'] = $post['type'];
		$data['subject_id'] = $post['mapel'];
		$data['question'] = trim(preg_replace("/\r|\n/", "", $post['soal']));

		$data['response_correct_answer'] = trim($post['responseJawabanBenar']);
		$data['response_wrong_answer'] = trim($post['responseJawabanSalah']);
	
		$data['point'] = (int)$post['point'];

		$this->db->insert('soal', $data);
		$id = $this->db->insert_id();

		if ($id) {
			$data['id'] = $id;
			$res = [
				'success' => true,
				'message' => 'Soal berhasil disimpan',
				'data' => $data,
				'csrf_token' => $this->security->get_csrf_hash()
			];
		} else {
			$res = [
				'success' => false,
				'message' => 'Soal gagal disimpan',
				'csrf_token' => $this->security->get_csrf_hash()
			];
		}

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function save_true_or_false(){
		$post = $this->input->post();

		// generate random string 10 character
		$code = $this->code();
		$data = [];

		// save image soal
		if (isset($_FILES['image'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_soal.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['question_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban
		if (isset($_FILES['imageJawabanBenar'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanBenar'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_benar.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_correct_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban salah
		if (isset($_FILES['imageJawabanSalah'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanSalah'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_salah.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_wrong_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		$data['code'] = $code;
		$data['type'] = $post['type'];
		$data['subject_id'] = $post['mapel'];
		$data['question'] = trim(preg_replace("/\r|\n/", "", $post['soal']));
		$data['answer'] = trim(preg_replace("/\r|\n/", "", $post['jawaban']));
		$data['response_correct_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanBenar']));
		$data['response_wrong_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanSalah']));
		$data['point'] = (int)$post['point'];

		$this->db->insert('soal', $data);
		$id = $this->db->insert_id();

		if ($id) {
			$data['id'] = $id;
			$res = [
				'success' => true,
				'message' => 'Soal berhasil disimpan',
				'data' => $data,
				'csrf_token' => $this->security->get_csrf_hash()
			];
		} else {
			$res = [
				'success' => false,
				'message' => 'Soal gagal disimpan',
				'csrf_token' => $this->security->get_csrf_hash()
			];
		}

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function update_true_or_false(){
		$post = $this->input->post();
		
		$data = [];

		// get soal
		$soal = $this->db->where('soal_id', $post['true_or_false_id'])->get('soal')->row_array();
		$code = $soal['code'];	

		// save image soal
		if (isset($_FILES['image'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_soal_'. time() .'.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['question_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban
		if (isset($_FILES['imageJawabanBenar'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanBenar'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_benar.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_correct_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		// save image jawaban salah
		if (isset($_FILES['imageJawabanSalah'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanSalah'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_salah.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_wrong_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		}

		if($post['removeImage'] == 'true') $data['question_file'] = '';

		$data['code'] = $code;
		$data['subject_id'] = $post['mapel'];
		$data['question'] = trim(preg_replace("/\r|\n/", "", $post['soal']));
		$data['answer'] = trim(preg_replace("/\r|\n/", "", $post['jawaban']));
		$data['response_correct_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanBenar']));
		$data['response_wrong_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanSalah']));
		$data['point'] = (int)$post['point'];

		$this->db->where('soal_id', $post['true_or_false_id'])->update('soal', $data);

		$data['id'] = $post['true_or_false_id'];

		$data['question_file'] = isset($data['question_file']) ? $data['question_file'] : $soal['question_file'];

		$res = [
			'success' => true,
			'message' => 'Soal berhasil diupdate',
			'data' => $data,
			'csrf_token' => $this->security->get_csrf_hash()
		];

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function update_pilihan_ganda()
	{
		$post = $this->input->post();

		$question_id = $post['pilihan_ganda_id'];

		// get soal code
		$soal = $this->db->where('soal_id', $question_id)->get('soal')->row();
		$code = $soal->code;

		$data = [];

		// save image soal
		if (isset($_FILES['image'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_soal.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['question_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		} else {
			if ($post['removeImage'] == 'true') {
				$data['question_file'] = '';
			} else {
				$data['question_file'] = $soal->question_file;
			}
		}

		// save image jawaban
		if (isset($_FILES['imageJawabanBenar'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanBenar'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_benar.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_correct_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		} else {
			$data['response_correct_answer_file'] = $soal->response_correct_answer_file;
		}

		// save image jawaban salah
		if (isset($_FILES['imageJawabanSalah'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanSalah'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_salah.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_wrong_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		} else {
			$data['response_wrong_answer_file'] = $soal->response_wrong_answer_file;
		}

		// item pilihan ganda / multiple choice
		$choices = [];
		$multipleChoice = json_decode($post['multipleChoice'], true);
		foreach ($multipleChoice as $key => $value) {
			// save jawaban benar
			if ($key == 0 && $value['trueChoice'] == true) $data['answer'] = 'A';
			if ($key == 1 && $value['trueChoice'] == true) $data['answer'] = 'B';
			if ($key == 2 && $value['trueChoice'] == true) $data['answer'] = 'C';
			if ($key == 3 && $value['trueChoice'] == true) $data['answer'] = 'D';
			if ($key == 4 && $value['trueChoice'] == true) $data['answer'] = 'E';

			// save image jawaban from post data image base64
			if (isset($value['image'])) {
				// jika gambar bukan base64
				if (strpos($value['image'], 'data:image') === false) {
					if ($key == 0) {
						$choices[$key]['image'] = str_replace($this->config->item('admin_url'), '', $value['image']);
					} else if ($key == 1) {
						$choices[$key]['image'] = str_replace($this->config->item('admin_url'), '', $value['image']);
					} else if ($key == 2) {
						$choices[$key]['image'] = str_replace($this->config->item('admin_url'), '', $value['image']);
					} else if ($key == 3) {
						$choices[$key]['image'] = str_replace($this->config->item('admin_url'), '', $value['image']);
					} else if ($key == 4) {
						$choices[$key]['image'] = str_replace($this->config->item('admin_url'), '', $value['image']);
					}

					continue;
				}

				$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
				// convert base64 to image
				list($type, $value['image']) = explode(';', $value['image']);
				list(, $value['image'])      = explode(',', $value['image']);
				$dataImage = base64_decode($value['image']);
				$ext = 'jpg';
				$filename = $code . '_jawaban_' . $key . '_'. time(). '.' . $ext;
				$destination = $_dir . '/' . $filename;

				if (!file_exists($_dir)) {
					mkdir($_dir, 0777, true);
				}

				file_put_contents($destination, $dataImage);

				$choices[$key]['image'] = 'assets/files/soal/' . $code . '/' . $filename;
			}
		}

		// data pilihan ganda
		$data['choice_a'] = isset($multipleChoice[0]['choice']) ? $multipleChoice[0]['choice'] : '';
		$data['choice_b'] = isset($multipleChoice[1]['choice']) ? $multipleChoice[1]['choice'] : '';
		$data['choice_c'] = isset($multipleChoice[2]['choice']) ? $multipleChoice[2]['choice'] : '';
		$data['choice_d'] = isset($multipleChoice[3]['choice']) ? $multipleChoice[3]['choice'] : '';
		$data['choice_e'] = isset($multipleChoice[4]['choice']) ? $multipleChoice[4]['choice'] : '';

		$data['choice_a_file'] = isset($choices[0]['image']) ? $choices[0]['image'] : '';
		$data['choice_b_file'] = isset($choices[1]['image']) ? $choices[1]['image'] : '';
		$data['choice_c_file'] = isset($choices[2]['image']) ? $choices[2]['image'] : '';
		$data['choice_d_file'] = isset($choices[3]['image']) ? $choices[3]['image'] : '';
		$data['choice_e_file'] = isset($choices[4]['image']) ? $choices[4]['image'] : '';

		$data['code'] = $code;
		$data['type'] = $post['type'];
		$data['subject_id'] = $post['mapel'];
		$data['question'] = trim(preg_replace("/\r|\n/", "", $post['soal']));

		$data['response_correct_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanBenar']));
		$data['response_wrong_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanSalah']));

		$data['point'] = (int)$post['point'];

		$this->db->where('soal_id', $question_id)->update('soal', $data);

		// get soal after update
		$soal = $this->db->where('soal_id', $question_id)->get('soal')->row_array();

		$soal['id'] = $question_id;
		$res = [
			'success' => true,
			'message' => 'Soal berhasil diupdate',
			'data' => $soal,
			'csrf_token' => $this->security->get_csrf_hash()
		];

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function delete_response_jawaban_pilihan_ganda(){
		$post = $this->input->post();

		$soal_id = $post['soal_id'];

		$data = [
			'response_correct_answer' => null,
			'response_wrong_answer' => null,
			'response_correct_answer_file' => null,
			'response_wrong_answer_file' => null,
		];

		$this->db->where('soal_id', $soal_id)->update('soal', $data);

		$res = [
			'success' => true,
			'message' => 'Berhasil menghapus jawaban'
		];

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function update_fill_the_blank()
	{
		$post = $this->input->post();

		$question_id = $post['fiil_the_blank_id'];

		// get soal code
		$soal = $this->db->where('soal_id', $question_id)->get('soal')->row();
		$code = $soal->code;

		$data = [];

		// save image soal
		if (isset($_FILES['image'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['image'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_soal_' . time() . '.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['question_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		} else {
			$data['question_file'] = $soal->question_file;
		}

		// save image jawaban
		if (isset($_FILES['imageJawabanBenar'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanBenar'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_benar.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_correct_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		} else {
			$data['response_correct_answer_file'] = $soal->response_correct_answer_file;
		}

		// save image jawaban salah
		if (isset($_FILES['imageJawabanSalah'])) {
			$_dir = $this->config->item('admin_path') . 'assets/files/soal/' . $code;
			$image = $_FILES['imageJawabanSalah'];
			$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
			$filename = $code . '_jawaban_salah.' . $ext;
			$destination = $_dir . '/' . $filename;

			if (!file_exists($_dir)) {
				mkdir($_dir, 0777, true);
			}

			move_uploaded_file($image['tmp_name'], $destination);

			$data['response_wrong_answer_file'] = 'assets/files/soal/' . $code . '/' . $filename;
		} else {
			$data['response_wrong_answer_file'] = $soal->response_wrong_answer_file;
		}

		$data['code'] = $code;
		$data['type'] = $post['type'];
		$data['subject_id'] = $post['mapel'];
		$data['question'] = trim(preg_replace("/\r|\n/", "", $post['soal']));
		$data['answer'] = trim(preg_replace("/\r|\n/", "", $post['jawaban']));

		// jika user menghapus image jawaban benar
		if($post['deleteImageJawabanBenar'] == 'true'){
			$data['response_correct_answer_file'] = '';
		}

		// jika user menghapus image jawaban salah
		if($post['deleteImageJawabanSalah'] == 'true'){
			$data['response_wrong_answer_file'] = '';
		}

		if($post['responseJawabanBenar'] == 'Respon Jawaban Benar...'){
			$data['response_correct_answer'] = '';
		}else{
			$data['response_correct_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanBenar']));
		}

		if($post['responseJawabanSalah'] == 'Respon Jawaban Salah...'){
			$data['response_wrong_answer'] = '';
		}else{
			$data['response_wrong_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanSalah']));
		}

		$data['alternative_answer'] = trim(preg_replace("/\r|\n/", "", $post['responseJawabanAlternatif']));
		$data['variation_answer'] = trim(preg_replace("/\r|\n/", "", $post['variasiJawaban']));
		$data['point'] = (int)$post['point'];

		$this->db->where('soal_id', $question_id)->update('soal', $data);

		$data['id'] = $question_id;
		$res = [
			'success' => true,
			'message' => 'Soal berhasil diupdate',
			'data' => $data
		];

		$res['csrf_token'] = $this->security->get_csrf_hash();

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function get_question_fill_the_blank()
	{
		$question_id = $_GET['question_id'];

		$question = $this->db->where('soal_id', $question_id)->get('soal')->row_array();

		$res = [
			'success' => true,
			'data' => $question
		];

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	/**
	 * **************************************************
	 * 			START BLOCK PAIRING QUESTION 
	 * **************************************************
	 */	

	/**
	 * Page for Pairing Question
	 *
	 * @return void
	 */
	public function create_pairing_question(): void {
		try
		{
			$question = $this->input->post('question-text', TRUE);
			$mapel = $this->input->post('mapel', TRUE);
			$kelas = $this->input->post('kelas', TRUE);
			$imgAdds = $_FILES['file-add'];
			$answerKeys = $this->input->post('input-key');
			$answerValues = $this->input->post('input-value');
			$imgAsKey = $this->input->post('image-match');
			$config = $this->input->post('config');
			$questionImages = $_FILES['q-image'];
			$rand = bin2hex(random_bytes(5));

			if(empty($question) || empty($mapel) || empty($kelas))
			{
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}
			
			$inputQuestion = [
				'code'			=> $rand,
				'question' 		=> $question,
				'type'			=> 5,
				'subject_id'	=> $mapel,
				'class_id'		=> $kelas,
				'point'			=> $config['scores']
			];

			if(!empty($imgAdds['name']))
			{
				$files = $this->_soaldir.DIRECTORY_SEPARATOR.$rand;

				if(!file_exists($files))
					@mkdir($files, 0777, TRUE);

				$pathinfo = pathinfo($imgAdds['name']);
				$filename = md5($imgAdds['name']).'.'.$pathinfo['extension'];

				if(!move_uploaded_file($imgAdds['tmp_name'], $files.DIRECTORY_SEPARATOR.$filename)) {
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_unable_to_write_file'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}

				$inputQuestion['question_file'] = 'assets/files/soal/'.$rand.'/'.$filename;
			}

			$this->db->trans_start();


			$this->db->insert('soal', $inputQuestion);
			$id = $this->db->insert_id();

			for($i=0;$i<count($answerValues);$i++) {
				$insert = [
					'soal_id'		=> $id,
					'answer_key' 	=> $answerKeys[$i],
					'answer_value' 	=> $answerValues[$i],
					'urutan'		=> $i + 1 
				];

				if($imgAsKey[$i] == 'on') {
					$insert['has_image'] = 1;



					if($questionImages['size'][$i] == 0 || empty($questionImages['name'][$i])) {
						http_response_code(422);
						$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_no_file_selected'), 'token' =>  $this->security->get_csrf_hash()];
						echo json_encode($msg);
						die();
					}

					$files = $this->_soaldir.DIRECTORY_SEPARATOR.$rand;

					if(!file_exists($files))
						mkdir($files, 0777, TRUE);

					$pinfo = pathinfo($questionImages['name'][$i]);
					$filename = $rand.'_'.($i + 1).'.'.$pinfo['extension'];
					$file = $files.DIRECTORY_SEPARATOR.$filename;

					if(!move_uploaded_file($questionImages['tmp_name'][$i], $file)) {
						http_response_code(422);
						$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_unable_to_write_file'), 'token' => $this->security->get_csrf_hash()];
						echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
						die();
					}

					$insert['answer_key'] = 'assets/files/soal/'.$rand.'/'.$filename;
				}

				$this->db->insert('soal_pairing_question', $insert);
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

			$output['err_status'] = 'success';
			$output['message'] = $this->lang->line('woow_form_success');
			$output['token'] = $this->security->get_csrf_hash();
			$output['soal_id'] = $id;

			echo json_encode($output);
		}
		catch(Exception $e)
		{
			log_message('error', $e->__toString());
		}
	}

	/**
	 * Get Pairing Question By Id
	 *
	 * @return void
	 */
	public function get_pairing_question_by_id(): void {
		$_id = $this->input->get('question_id');
		$question = $this->db->get_where('soal', ['soal_id' => $_id])->row_array();
		$answers = $this->db->get_where('soal_pairing_question', ['soal_id' => $_id])->result_array();

		if(!isset($question))
		{
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		$question['answer'] = $answers;

		echo json_encode(['data' => $question], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
	}


	/**
	 * **************************************************
	 * 			 END BLOCK PAIRING QUESTION 
	 * **************************************************
	 */


	/**
	 * **************************************************
	 * 		  START BLOCK DRAG AND DROP QUESTION 
	 * **************************************************
	 */	

	 /**
	  * Isert new question 
	  *
	  * @return void
	  */
	public function create_dragdrop_question(): void {

		$mapel = $this->input->post('mapel');
		$kelas = $this->input->post('kelas');
		$question = $this->input->post('question', TRUE);
		$code = bin2hex(random_bytes(4));
		$addFile = $_FILES['file-add'];
		$correctAnswers = $this->input->post('answer');
		$falseAnswers = $this->input->post('falseAnswer');
		$funfactFiles = $this->input->post('funfactFile');
		$funfactText = $this->input->post('funfactText');
		$config = $this->input->post('config');

		header('Content-Type: application/json');

		if(empty($question) || empty($correctAnswers) || empty($config['score']) || empty($kelas) || empty($mapel)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			return;
		}

		$insert['code'] = $code;
		$insert['question'] = $question;
		$insert['point'] = $config['score'];
		$insert['subject_id'] = $mapel;
		$insert['class_id'] = $kelas;
		$insert['type'] = 6;

		// Additional File
		if(!empty($addFile['name']))
		{
			$_dir = $this->_soaldir.DIRECTORY_SEPARATOR.$code;

			if($addFile['size'] == 0)
			{
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_no_file_selected'), 'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}

			if(!file_exists($_dir)) 
				mkdir($_dir, 0777, TRUE);

			$pathinfo = pathinfo($addFile['name']);
			$filename = sha1($pathinfo['filename']).'.'.$pathinfo['extension'];

			if(!move_uploaded_file($addFile['tmp_name'], $_dir.DIRECTORY_SEPARATOR.$filename)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_unable_to_write_file'), 'token' => $this->security->get_csrf_hash()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}
			
			$insert['question_file'] = 'assets/files/soal/'.$code.'/'.$filename;
		}

		if($config['funfact'] == 1) {
			if(!empty($funfactFiles['correct']['name']))
			{
				$img = $funfactFiles['correct'];
				$_dir = $this->_soaldir.DIRECTORY_SEPARATOR.$code;

				if($img['size'] == 0)
				{
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_no_file_selected'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}

				if(!file_exists($_dir)) 
					mkdir($_dir, 0777, TRUE);

				$pathinfo = pathinfo($img['name']);
				$filename = sha1($pathinfo['filename']).'_response_benar.'.$pathinfo['extension'];

				if(!move_uploaded_file($img['tmp_name'], $_dir.DIRECTORY_SEPARATOR.$filename)) {
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_unable_to_write_file'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}

				$insert['response_correct_answer_file'] = 'assets/files/soal/'.$code.'/'.$filename;
			}
			
			if(!empty($funfactFiles['false']['name']))
			{
				$img = $funfactFiles['false'];
				$_dir = $this->_soaldir.DIRECTORY_SEPARATOR.$code;

				if($img['size'] == 0)
				{
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_no_file_selected'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}

				if(!file_exists($_dir)) 
					mkdir($_dir, 0777, TRUE);

				$pathinfo = pathinfo($img['name']);
				$filename = sha1($pathinfo['filename']).'_response_salah.'.$pathinfo['extension'];

				if(!move_uploaded_file($img['tmp_name'], $_dir.DIRECTORY_SEPARATOR.$filename)) {
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('upload_unable_to_write_file'), 'token' => $this->security->get_csrf_hash()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
				}

				$insert['response_wrong_answer_file'] = 'assets/files/soal/'.$code.'/'.$filename;
			}
			$insert['response_correct_answer'] = $funfactText['correct'];
			$insert['response_wrong_answer'] = $funfactText['false'];
		}

		$this->db->trans_start();

		$this->db->insert('soal', $insert);
		$_id = $this->db->insert_id();
		unset($insert);

		$i=0;
		foreach($correctAnswers as $ca) {
			$insert['soal_id'] = $_id;
			$insert['answer_correct'] = $ca['text'];
			$insert['words_count'] = $ca['wordsCount'];

			if($config['falseAnswer'] == 1)
			{
				if(empty($falseAnswers[$i]['text'])) {
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->security->get_csrf_hash()];
					die(json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
					break;
					return;
				}

				

				$insert['answer_false'] = $falseAnswers[$i]['text'];
			}

			$insert['urutan'] = $i + 1;
			$this->db->insert('soal_dragdrop_question', $insert);
			$i++;
		}

		$this->db->trans_complete();

		if(!$this->db->trans_status()) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		$output['err_status'] = 'success';
		$output['message'] = $this->lang->line('woow_form_success');	
		$output['token'] = $this->security->get_csrf_hash();
		$output['id'] = $_id;

		echo json_encode($output, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
	}

	/**
	 * get question by id
	 *
	 * @param [type] $id
	 * @return void
	 */
	public function get_one_dragdrop_question($id): void {
		$item = $this->db->get_where('soal', ['soal_id' => $id])->row_array();
		$answers = $this->db->get_where('soal_dragdrop_question', ['soal_id' => $id])->result_array();

		$item['answer'] = $answers;

		header('Content-Type: application/json');
		echo json_encode(['data' => $item]);
	}
	/**
	 * **************************************************
	 * 		 	END BLOCK DRAG AND DROP QUESTION 
	 * **************************************************
	 */	

	function code($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	function save_draft()
	{
		$post = $this->input->post();

		// load library notif
		$this->load->library('notif');
		$notif = new Notif();

		if (!$post['exam_id']) {

			$code = $this->code(); // generate random string 10 character

			$data = [
				'code' => $code,
				'teacher_id' => $post['teacher_id'],
				'subject_id' => $post['subject_id'],
				'class_id' => $post['class_id'],
				'category_id' => $post['category_id'],
				'title' => $post['title'],
				'description' => $post['description'],
				'start_date' => $post['start_date'],
				'end_date' => $post['end_date'],
				'duration' => $post['duration'],
				'tipe'	=> 0, // 0 = asesmen standard, 1 = asesmen mandiri
				'status' => ($post['is_publish'] == 'true') ? 1 : 0, // 0 = draft, 1 = publish
			];

			$this->db->insert('exam', $data);
			$id = $this->db->insert_id();

			// save soal exam
			foreach ($post['questions'] as $group) {
				foreach ($group as $key => $question) {
					$data_soal = [
						'exam_id' => $id,
						'soal_id' => $question['soal_id'],
						'no_urut' => $key + 1,
						'bobot_nilai' => $question['point'],
						'grouping' => $question['type']
					];

					$this->db->insert('soal_exam', $data_soal);
				}
			}

			if ($id) {
				// jika asesmen di publish maka insert ke tabel notif
				$post['id'] = $id;
				// $notif->store_asesmen_standard($post);

				// import push notif
				include_once APPPATH . 'libraries/Push_notif.php';
				$pushNotif = new Push_notif();

				$data_notif = [
					'exam_id' => $id,
					'title' => 'Ada ujian yang akan Kamu kerjakan'
				];
				$new_exam = $pushNotif->new_exam($data_notif);

				$data['exam_id'] = $id;
				$res = [
					'success' => true,
					'message' => 'Asesmen berhasil disimpan',
					'data' => $data
				];
			} else {
				$res = [
					'success' => false,
					'message' => 'Asesmen gagal disimpan'
				];
			}

			header('Content-Type: application/json');
			echo json_encode($res);
		} else {

			$exam_id = $post['exam_id'];

			$data = [
				'teacher_id' => $post['teacher_id'],
				'subject_id' => $post['subject_id'],
				'class_id' => $post['class_id'],
				'category_id' => $post['category_id'],
				'title' => $post['title'],
				'description' => $post['description'],
				'start_date' => $post['start_date'],
				'end_date' => $post['end_date'],
				'duration' => $post['duration'],
				'tipe'	=> 0, // 0 = asesmen standard, 1 = asesmen mandiri
				'status' => ($post['is_publish'] == 'true') ? 1 : 0, // 0 = draft, 1 = publish
			];

			$this->db->where('exam_id', $exam_id)->update('exam', $data);

			// delete soal exam
			$this->db->where('exam_id', $exam_id)->delete('soal_exam');

			// save soal exam
			foreach ($post['questions'] as $group) {
				foreach ($group as $key => $question) {
					$data_soal = [
						'exam_id' => $exam_id,
						'soal_id' => $question['soal_id'],
						'no_urut' => $key + 1,
						'bobot_nilai' => $question['point'],
						'grouping' => $question['type']
					];

					$this->db->insert('soal_exam', $data_soal);
				}
			}

			if ($exam_id) {
				$data['exam_id'] = $exam_id;
				$res = [
					'success' => true,
					'message' => 'Asesmen berhasil diupdate',
					'data' => $data
				];
			} else {
				$res = [
					'success' => false,
					'message' => 'Asesmen gagal diupdate'
				];
			}

			header('Content-Type: application/json');
			echo json_encode($res);
		}
	}

	function download_soal_to_pdf($id = '')
	{
		if (empty($id)) redirect('asesmen_standard');

		$exam = $this->db->where('exam_id', $id)->get('exam')->row_array();

		$group_soal = $this->db->select('exam_id, grouping')
			->where('exam_id', $id)
			->group_by('grouping, exam_id')
			->get('soal_exam')->result_array();

		foreach ($group_soal as $key => $val) {
			$soal = $this->db->where('exam_id', $id)
				->where('grouping', $val['grouping'])
				->join('soal', 'soal.soal_id = soal_exam.soal_id')
				->get('soal_exam')->result_array();

			$jenis_soal = $soal[0]['type'];
			if ($jenis_soal == 1) $group_soal[$key]['jenis_soal'] = 'Pilihan Ganda';
			if ($jenis_soal == 2) $group_soal[$key]['jenis_soal'] = 'Esai';
			if ($jenis_soal == 3) $group_soal[$key]['jenis_soal'] = 'Benar atau Salah';
			if ($jenis_soal == 4) $group_soal[$key]['jenis_soal'] = 'Isi Yang Kosong';
			if ($jenis_soal == 5) $group_soal[$key]['jenis_soal'] = 'Menjodohkan';
			if ($jenis_soal == 6) $group_soal[$key]['jenis_soal'] = 'Seret Lepas';

			$group_soal[$key]['soal'] = $soal;
		}

		$data = [		
			'exam' => $exam,
			'group_soal' => $group_soal,
		];

		$html = $this->load->view('asesmen_standard/soal_pdf', $data, true);

		$dompdf = new Dompdf();

		$options = $dompdf->getOptions();
		$options->set('isRemoteEnabled', true);
		$dompdf->setOptions($options);

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream('soal_' . $exam['code'] . '.pdf', array('Attachment' => 0));
	}

	function download_kunci_jawaban_asesmen_standard($id = '')
	{
		if (empty($id)) redirect('asesmen_standard');

		$exam = $this->db->where('exam_id', $id)->get('exam')->row_array();

		$group_soal = $this->db->select('exam_id, grouping')
			->where('exam_id', $id)
			->group_by('grouping, exam_id')
			->get('soal_exam')->result_array();

		foreach ($group_soal as $key => $val) {
			$soal = $this->db->where('exam_id', $id)
				->where('grouping', $val['grouping'])
				->join('soal', 'soal.soal_id = soal_exam.soal_id')
				->get('soal_exam')->result_array();

			$jenis_soal = $soal[0]['type'];
			if ($jenis_soal == 1) $group_soal[$key]['jenis_soal'] = 'Pilihan Ganda';
			if ($jenis_soal == 2) $group_soal[$key]['jenis_soal'] = 'Esai';
			if ($jenis_soal == 3) $group_soal[$key]['jenis_soal'] = 'Benar atau Salah';
			if ($jenis_soal == 4) $group_soal[$key]['jenis_soal'] = 'Isi Yang Kosong';
			if ($jenis_soal == 5) $group_soal[$key]['jenis_soal'] = 'Menjodohkan';
			if ($jenis_soal == 6) $group_soal[$key]['jenis_soal'] = 'Seret Lepas';

			$group_soal[$key]['soal'] = $soal;
		}

		$data = [		
			'exam' => $exam,
			'group_soal' => $group_soal,
		];

		$html = $this->load->view('asesmen_standard/kunci_jawaban_pdf', $data, true);

		$dompdf = new Dompdf();

		$options = $dompdf->getOptions();
		$options->set('isRemoteEnabled', true);
		$dompdf->setOptions($options);

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream('soal_' . $exam['code'] . '.pdf', array('Attachment' => 0));
	}

	function download_report_student(){
		$id = $this->input->get('exam_id');
		$student_id = $this->input->get('student_id');

		$exam = $this->db->where('exam_id', $id)->get('exam')->row_array();

		$group_soal = $this->db->select('exam_id, grouping')
			->where('exam_id', $id)
			->group_by('grouping, exam_id')
			->get('soal_exam')->result_array();

		// kelompokan soal berdasarkan jenis nya
		foreach ($group_soal as $key => $val) {
			$soal = $this->db->where('exam_id', $id)
				->where('grouping', $val['grouping'])
				->join('soal', 'soal.soal_id = soal_exam.soal_id')
				->get('soal_exam')->result_array();

			$jenis_soal = $soal[0]['type'];
			if ($jenis_soal == 1) $group_soal[$key]['jenis_soal'] = 'Pilihan Ganda';
			if ($jenis_soal == 2) $group_soal[$key]['jenis_soal'] = 'Esai';
			if ($jenis_soal == 3) $group_soal[$key]['jenis_soal'] = 'Benar atau Salah';
			if ($jenis_soal == 4) $group_soal[$key]['jenis_soal'] = 'Isi Yang Kosong';
			if ($jenis_soal == 5) $group_soal[$key]['jenis_soal'] = 'Menjodohkan';
			if ($jenis_soal == 6) $group_soal[$key]['jenis_soal'] = 'Seret Lepas';

			$group_soal[$key]['soal'] = $soal;

			// ambil jawaban siswa
			foreach($soal as $key2 => $val2){
				$jawaban = $this->db->where('exam_id', $id)
					->where('student_id', $student_id)
					->where('soal_id', $val2['soal_id'])
					->get('exam_answer')->row_array();

				if($jawaban){
					$group_soal[$key]['soal'][$key2]['exam_answer'] = $jawaban['exam_answer'];
					$group_soal[$key]['soal'][$key2]['correct_answer'] = $jawaban['correct_answer'];
					$group_soal[$key]['soal'][$key2]['result_answer'] = $jawaban['result_answer'];
				}
			}
		}

		$data = [		
			'exam' => $exam,
			'group_soal' => $group_soal,
		];

		$html = $this->load->view('asesmen_standard/download_report_student', $data, true);
		// echo $html;die;


		$dompdf = new Dompdf();

		$options = $dompdf->getOptions();
		$options->set('isRemoteEnabled', true);
		$dompdf->setOptions($options);

		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream('soal_' . $exam['code'] . '.pdf', array('Attachment' => 0));
	}

	function do_exercise($id = ''){
		if (empty($id)) redirect('asesmen_standard');

		$data['exam'] = $this->db->where('exam_id', $id)
			->join('subject s', 's.subject_id = e.subject_id')
			->join('kelas k', 'k.class_id = e.class_id')
			->get('exam e')->row_array();

		$this->load->view('asesmen_standard/student/do_exercise_new', $data);
	}

	function get_soal_exam(){
		$post = $this->input->post();

		$filter = [];
		$filter['exam_id'] = $post['exam_id'];
		$filter['student_id'] = $post['student_id'];

		$this->load->model('model_soal_exam');

		$soal = $this->model_soal_exam->get_soal($filter);

		header('Content-Type: application/json');
		$res = [
			'success' => true,
			'data' => $soal,
			"token" => $this->security->get_csrf_hash()
		];
		echo json_encode($res);
	}

	function finish_exam(){
		$post = $this->input->post();

		$exam_id = $post['exam_id'];
		$soal = json_decode($post['soal'], true);

		// time exam
		$time = $post['time']; // in seconds
		$hours = floor($time / 3600);
		$minutes = floor(($time / 60) % 60);
		$seconds = $time % 60;
		$time = $hours . ':' . $minutes . ':' . $seconds;

		// jika time nya minus
		if($seconds < 0){
			$time = '00:00:00';
		}

		$data = [
			'exam_id' => $exam_id,
			'student_id' => $post['student_id'],
			'exam_submit' => date('Y-m-d H:i:s'),
			'completion_time' => $time,
		];

		// cek dulu sudah submit atau belum
		$cek = $this->db->where('exam_id', $exam_id)
			->where('student_id', $post['student_id'])
			->get('exam_student')->row_array();

		if($cek){
			$res = [
				'success' => true,
				'message' => 'Asesmen sudah disubmit'
			];

			header('Content-Type: application/json');
			echo json_encode($res);
			return;
		}

		$this->db->insert('exam_student', $data);
		$id = $this->db->insert_id();

		// save soal exam
		foreach ($soal as $key => $question) {
			$data_soal = [
				'student_id' => $post['student_id'],
				'exam_id' => $exam_id,
				'class_id' => $post['class_id'],
				'exam_answer' => is_array($question['exam_answer']) ? json_encode($question['exam_answer']) : $question['exam_answer'],
				'correct_answer' => is_array($question['correct_answer']) ? json_encode($question['correct_answer']) : $question['correct_answer'],
				'exam_submit' => $data['exam_submit'],
				'soal_id' => $question['soal_id'],
				'result_answer' => $question['result_answer'],
			];

			$this->db->insert('exam_answer', $data_soal);
		}

		$res = [
			'success' => true,
			'message' => 'Asesmen berhasil disubmit',
			'data' => $data
		];

		header('Content-Type: application/json');
		echo json_encode($res);
	}

}
