<?php

class Exam extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        check_Loggin();
        $this->load->model(['model_common', 'model_exam']); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
    }

    public function index() {
        $data['pageTitle']	= 'Data Soal';
		//$data['tableName']	= 'tbl_device';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/libs/daterangepicker-master/moment.min.js', 'defer' => true],
			['path' => 'assets/new/libs/daterangepicker-master/daterangepicker.js', 'defer' => true],
			['path' => 'assets/new/libs/randomString.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_ujian.js', 'defer' => true],
		]; 
		$data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
			'assets/new/libs/daterangepicker-master/daterangepicker.css',
			'assets/new/css/fpersonno.css'
		]; 
 
		$this->template->load('template', 'exam/index', $data);
    }

    public function student() {
        $data['pageTitle']	= 'Data Soal';
		//$data['tableName']	= 'tbl_device';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/libs/daterangepicker-master/moment.min.js', 'defer' => true],
			['path' => 'assets/new/libs/daterangepicker-master/daterangepicker.js', 'defer' => true],
			['path' => 'assets/new/libs/randomString.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_ujian_student.js', 'defer' => true],
		]; 
		$data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
			'assets/new/libs/daterangepicker-master/daterangepicker.css',
			'assets/new/css/fpersonno.css'
		]; 
 
		$this->template->load('template', 'exam/student', $data);
    }

    public function questions() {
        $data['pageTitle']	= 'Data Soal';
		//$data['tableName']	= 'tbl_device';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		// $data['page_js']	= [  
		// 	['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
		// 	['path' => 'assets/new/libs/daterangepicker-master/moment.min.js', 'defer' => true],
		// 	['path' => 'assets/new/libs/daterangepicker-master/daterangepicker.js', 'defer' => true],
		// 	['path' => 'assets/new/libs/randomString.js', 'defer' => true],
		// 	['path' => 'assets/new/js/pages/_ujian_student.js', 'defer' => true],
		// ]; 
		// $data['page_css']	= [  
		// 	'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
		// 	'assets/new/libs/daterangepicker-master/daterangepicker.css',
		// 	'assets/new/css/fpersonno.css'
		// ]; 
		
		if(!isset($_SESSION['ujian_student']))
		{
			echo '<script>';
			echo 'window.location.href = \''.base_url('exam/student').'\'';
			echo '</script>';
		}
			

		$kode_ujian = $this->input->get('kode_ujian');
		$no = intval($this->input->get('no'));
		
		$ujian = $this->model_exam->getByCode($kode_ujian);
		$questions = $this->model_exam->getAllSoalSelected($ujian['id_ujian']);

		$data['total_soal'] = $ujian['total_soal'];
		$data['durasi'] 	= $ujian['durasi'];
		$data['judul']		= $ujian['category_name'].' '.$ujian['subject_name'];
		$data['kelas']		= $ujian['class_name'];

		$data['soal'] = $questions[array_search($no, array_column($questions, 'no_urut'))];
		
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		

		$this->load->view('exam/exam_page', $data);
    }
}