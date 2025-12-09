<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends MY_Controller{

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
		$data['pageTitle']	= 'Data Kelas';
		$data['tableName']	= 'kelas';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
			['path' => 'assets/new/js/pages/_kelas.js', 'defer' => true],
		]; 
 
		$this->template->load('template', 'kelas/index', $data);
	}
   
}
