<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subject extends MY_Controller{

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
		$data['pageTitle']	= 'Data Pelajaran';
		//$data['tableName']	= 'tbl_device';
		$data['data_class']	= $this->model_common->get_class_level();
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_subject.js', 'defer' => true],
		]; 
		$data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
		]; 
 
		$this->template->load('template', 'subject/index', $data);
	}
   
}
