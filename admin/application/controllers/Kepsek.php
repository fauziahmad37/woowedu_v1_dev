<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kepsek extends MY_Controller {

	public function __construct() {
		parent::__construct();
		// Load any required models, libraries, etc.
		check_loggin();
		$this->load->model('Kepsek_model');
		$this->load->model('model_common'); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
	}

	public function index() {
		$data['pageTitle'] = 'Kepala Sekolah';
		$data['tableName']	= 'teacher';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [
			['path' => 'assets/new/js/pages/_kepsek.js', 'defer' => true],
		]; 

		// Load a view
		$this->template->load('template','kepsek/index', $data);
	}

	
}
