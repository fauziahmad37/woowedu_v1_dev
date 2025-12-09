<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekolah extends MY_Controller{

  public function __construct()
  {
    parent::__construct();
		check_Loggin();
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
		$data['pageTitle']	= 'Data Sekolah';
		$data['tableName']	= 'sekolah';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/new/js/pages/_sekolah.js', 'defer' => true],
		]; 
 
		$this->template->load('template', 'sekolah/index', $data);
	}
   
}
