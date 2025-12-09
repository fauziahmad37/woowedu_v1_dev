<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
		check_loggin();
		$this->load->model('model_common'); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
  }
	
 
	public function index()
	{
		$data['pageTitle']	= 'Data Tugas';
		//$data['tableName']	= 'tbl_device';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/libs/tinymce/tinymce.min.js', 'defer' => false],
			['path' => 'assets/new/libs/daterangepicker-master/moment.min.js', 'defer' => true],
			['path' => 'assets/new/libs/daterangepicker-master/daterangepicker.js', 'defer' => true],
			['path' => 'assets/new/libs/randomString.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_tugas.js', 'defer' => true],
		]; 
		$data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
			'assets/new/libs/daterangepicker-master/daterangepicker.css',
			'assets/new/css/fpersonno.css'
		]; 
 
		$this->template->load('template', 'task/index', $data);
	}
   
}
