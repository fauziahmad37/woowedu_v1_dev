<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{

	protected $settings;

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
		
 
		
		$log = [
			'user' 	=> $_SESSION['username'] ?? '',
			'ipadd'	=> $_SERVER['REMOTE_ADDR'],
			'logdetail' => $this->router->fetch_method() .' : '.$this->router->fetch_class() 
		];
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