<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends MY_Controller{

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
		$data['pageTitle']	= 'Data Guru';
		$data['tableName']	= 'teacher';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/new/js/pages/_teacher.js', 'defer' => true],
		]; 
 
		$this->template->load('template', 'teacher/index', $data);
	}
	
	public function relasi()
	{ 
		$this->load->view( 'teacher/relasi',true );
	}	
   
}
