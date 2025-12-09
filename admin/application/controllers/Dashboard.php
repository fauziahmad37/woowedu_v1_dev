<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Cek apakah user sudah login
		check_Loggin();
		$this->load->model('model_users');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->model('model_common'); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$this->load->library('csrfsimple');
		$this->load->library('curl');
	}


	public function index()
	{
		//echo 'aa'.$this->m_transaction->weeks_in_month('03','2022').'=='.date('d', strtotime("first day of this month"));
		$data['pageTitle']	= 'Dashboard';
		$data['page_css']	= ['assets/css/_publisher_dashboard.css',];
		$param = array();

		if (isset($_GET['sdate'])) {
			$param['start_date'] = $_GET['sdate'];
		} else {
			$param['start_date'] = '01-' . date('m-Y');
		}

		$data['start_date']	= $param['start_date'];

		if (isset($_GET['edate'])) {
			$param['end_date'] = $_GET['edate'];
		} else {
			$param['end_date'] = date('d-m-Y');
		}

		$data['end_date']	= $param['end_date'];

		// jika user adalah publisher
		$userLevelId = $this->session->userdata('user_level');
		$userLevel = $this->db->get_where('user_level', ['user_level_id' => $userLevelId])->row();
		if (strtolower($userLevel->user_level_name) == 'publisher') {
			$this->template->load('template', 'dashboard/publisher_dashboard', $data);
			return;
		}

		$this->template->load('template', 'dashboard', $data);
	}


	public function error404()
	{
		$data['pageTitle']		= 'Error 404 - Page Not Found';
		$this->load->view('404.php', $data);
	}
}
