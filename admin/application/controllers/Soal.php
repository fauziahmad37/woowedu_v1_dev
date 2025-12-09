<?php

class Soal extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        check_Loggin();
        $this->load->model(['model_common', 'model_soal']); 
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
			// ['path' => 'assets/new/libs/tinymce/tinymce.min.js', 'defer' => true],
			//['path' => 'assets/new/libs/tinymce/jquery.tinymce.min.js', 'defer' => true],
			// ['path' => 'assets/new/libs/bootstrap4-datetimepicker/js/bootstrap4-datetimepicker.min.js', 'defer' => true],
			['path' => 'assets/new/libs/randomString.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_question.js', 'defer' => true],
		]; 
		$data['header_js'] = [
			['path' => 'assets/new/js/pages/inputElement.js', 'async' => true],
		];
		$data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
            'assets/new/libs/summernote/summernote-bs4.min.css',
			'assets/new/libs/bootstrap4-datetimepicker/css/bootstrap4-datetimepicker.min.css',
			'assets/new/css/fpersonno.css'
		];
		
		// jika super admin sekolah id kosongkan
		if($this->session->userdata('user_level') == 1)
			$data['sekolah_id'] = '';
		else
			$data['sekolah_id'] = $this->session->userdata('sekolah_id');
 
		$this->template->load('template', 'questions/index', $data);
    }

	public function forms()
	{
		$data['pageTitle']	= 'Data Soal';
		//$data['tableName']	= 'tbl_device';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js'],
			// ['path' => 'assets/new/libs/tinymce/tinymce.min.js'],
			// ['path' => 'assets/new/libs/tinymce/jquery.tinymce.min.js', 'defer' => true],
			// ['path' => 'assets/new/libs/bootstrap4-datetimepicker/js/bootstrap4-datetimepicker.min.js'],
			['path' => 'assets/new/libs/randomString.js'],
			['path' => 'assets/new/js/pages/_question_new.js', 'defer' => true],
		]; 
		// $data['header_js'] = [
		// 	['path' => 'assets/new/js/pages/inputElement.js', 'async' => true],
		// ];
		$data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
            'assets/new/libs/summernote/summernote-bs4.min.css',
			'assets/new/libs/bootstrap4-datetimepicker/css/bootstrap4-datetimepicker.min.css',
			'assets/new/css/fpersonno.css'
		]; 

		if(!empty($this->input->get('edit')) && $this->input->get('edit') == '1')
		{
			if(!empty($this->input->get('kode')))
			$data['soal'] = $this->model_soal->getByCode($this->input->get('kode'));
		}
 
		$this->template->load('template', 'questions/new', $data);
	}
}
