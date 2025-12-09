<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{

	protected $settings;
	protected $apiUrl = 'http://103.15.226.136:8087/v1/';

	public function __construct()
	{
		parent::__construct();
		//settings
		$global_data['settings'] = $this->Model_settings->get_settings();
		$this->settings = $global_data['settings'];

		//menu
		// $global_data['menus'] = $this->m_menu->get_menus();
		// $this->menus = $global_data['menus'];

		$this->load->vars($global_data);
		$this->load->helper(['customstring', 'assets']);
 
		
		$log = [
			'user' 	=> $_SESSION['username'] ?? '',
			'ipadd'	=> $_SERVER['REMOTE_ADDR'],
			'logdetail' => $this->router->fetch_method() .' : '.$this->router->fetch_class(),
			'url'	=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'].'/'.$this->router->fetch_method().'/'.$this->uri->segment(3) : '',
		];

		// echo(json_encode($_SERVER));die;
		$this->db->insert('actionlog', $log);				
	}

	public function cekLogin()
	{
		if (!$this->session->userdata('username')) {
			redirect('auth/login');
		}
	}
	
	public function getUserData()
	{
		$userData = $this->session->userdata();
		return $userData;
	}

	public function isAdmin()
	{
		$userData = $this->getUserData();
		if ($userData['user_level'] !== 'administrator') show_404();
	}

}
