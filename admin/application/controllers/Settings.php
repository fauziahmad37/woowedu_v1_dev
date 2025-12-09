<?php
class settings extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Model_settings');
		check_loggin();
		$this->load->library('csrfsimple');
		$this->load->helper('assets');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
	}

	public function index()
	{
		$data['pageTitle']		= 'Pengaturan Umum';
		$data['tableName']		= 'settings';

		//if ($this->session->userdata('user_level') == '1') {
		$data['dt']				= $this->Model_settings->get_settings();
		$data['htm_non']				= $this->Model_settings->get_htm();
		$data['htm_rombongan']				= $this->Model_settings->get_htm(1);
		$data['htm_sekolah']				= $this->Model_settings->get_htm(2);
		$this->template->load('template', 'settings/index', $data);
		//}
	}



	public function menu()
	{
		$data['pageTitle']		= 'Menu Settings';
		$data['tableName']		= 'menu-settings';

		$data['page_js'] = [
			['path' => 'assets/node_modules/jstree/dist/jstree.min.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_menu.js', 'defer' => true]
		];
		$data['page_css'] = [
			'assets/fontawesome/css/font-awesome.min.css',
			'assets/node_modules/jstree/dist/themes/default/style.min.css',
			'assets/new/css/setting-area.css',
		];

		//if ($this->session->userdata('user_level') == '1') {
		//$data['dt']				= $this->Model_settings->get_settings();
		$this->template->load('template', 'settings/menu', $data);
		//}
	}

	public function users()
	{
		$data['pageTitle']		= 'Users Settings';
		$data['tableName']		= 'users-settings';

		$data['page_js'] = [
			['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_users.js', 'defer' => true]
		];
		$data['page_css'] = [
			'assets/fontawesome/css/font-awesome.min.css',
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
			'assets/new/libs/daterangepicker-master/daterangepicker.css',
			'assets/new/css/setting-area.css',
		];

		//if ($this->session->userdata('user_level') == '1') {
		//$data['dt']				= $this->Model_settings->get_settings();
		$this->template->load('template', 'settings/users', $data);
		//}
	}



	public function update_settings()
	{
		$form_general = $this->Model_settings->edit();
		$respon['massage'] = 'success';
	}

	public function update_jam()
	{
		$form_general = $this->Model_settings->editjam();
		$respon['massage'] = 'success';
	}

	public function update_link()
	{
		$form_sosial = $this->Model_settings->edit_link();
		$respon['massage'] = 'success';
	}


	public function auditrail()
	{
		$data['pageTitle']		= 'Auditrail';
		$data['tableName']		= 'menu-settings';
		$data['page_js'] = [
			['path' => 'assets/new/js/pages/_auditrail.js', 'defer' => true]
		];
		$this->template->load('template', 'settings/auditrail', $data);
	}



	public function  get_all_log()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
			return;
		}

		$draw   = $this->input->get('draw');
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$filter = $this->input->get('columns');
		$rec   = $this->Model_settings->get_all_log($filter, $limit, $offset);
		$count  = $this->Model_settings->count_all_log($filter);
		$countFilter = $this->Model_settings->count_all_log($filter);

		$datas =   array(
			"draw"			  => $draw,
			"recordsTotal"	  => $count,
			"recordsFiltered" => $countFilter,
			"data"			  => $rec
		);
		http_response_code(200);
		echo json_encode($datas, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
		exit();
	}
}
