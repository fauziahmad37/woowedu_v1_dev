<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends MY_Controller{

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
		$this->load->library('curl');
  }
	
 
	public function index()
	{
		
		$filter[0]['data'] = 'sekolah_id';
		$filter[0]['search']['value'] = isset($_SESSION['sekolah_id']) ? $_SESSION['sekolah_id'] : '';
		
		$response_class = ['data' => $this->model_common->get_all_class($filter), 'count' => $this->model_common->count_all_class()];
 
		// if($response['ErrStatus'] === 'error') {
		// 		http_response_code(422);
		// 		$msg = ['err_status' => 'error', 'message' => $response['Message'], 'token' => $this->csrfsimple->genToken()];
		// 		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
		// 		return;
		// }
						
		$data['pageTitle']	= 'Data Siswa';
		$data['data_class']	= $response_class['data'];
		$data['tableName']	= 'siswa';
		$data['csrf_token']	= $this->csrfsimple->genToken();

		$data['page_css'] = [
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
		];

		$data['page_js']	= [  
			['path' => 'assets/new/js/pages/_student.js', 'defer' => true],
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
		]; 
 
		$this->template->load('template', 'student/index', $data);
	}
	
 
   
}
