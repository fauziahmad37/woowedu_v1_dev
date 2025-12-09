<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('assets');
		$this->load->library('session');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){
		$data['page_js'] = [
			['path' => base_url('assets/js/_voucher.js'), 'defer' => TRUE]
		];

		$this->template->load('template', 'voucher/index', $data);
	}

}
