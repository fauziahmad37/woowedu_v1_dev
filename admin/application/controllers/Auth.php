<?php defined('BASEPATH') OR exit('No direct script access allowed');

class auth extends CI_Controller 
{
	function __construct() {
		parent::__construct();
		$this->load->model('m_login');
		$this->load->model('m_users');
		$this->load->library('session');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
	}

	public function changePassword()
	{
		$userid		= $this->session->userdata['userid'];
		$username	= $this->session->userdata['username'];
		
		$pw_lama	= $this->input->post('password_lama');
		$hasil		= $this->m_login->loginCek($username)->row();
		
		if(hash_verified($pw_lama, $hasil->password)){
			$data = array(
				'userid'=>$userid,
				'password' => get_hash($this->input->post('repassword')),
			);
			$this->m_users->change_pw($data);
			echo "success";
		}else{
			echo "pw_salah";
		}
	}

	public function loginx() {
		header('Content-Type: application/json');

		$username = trim($this->input->post('username'));
		$password = trim($this->input->post('password'));

		if(empty($username)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_empty_username')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}
		if(empty($password)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_empty_password')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$getUser = $this->db->get_where('users', ['username' => $username]);
		$dt = $getUser->row();
		$bisaliat = [1,10, 11];

		if(!in_array($dt->user_level, $bisaliat))
		{
			http_response_code(403);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_unauthorized')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		if($getUser->num_rows() == 0) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_username_mismatch')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		if(password_verify($password, $dt->password) == FALSE) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_password_mismatch')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$this->session->set_userdata(
			array(
				'status_login' => 'y',
				'userid' => $dt->userid,
				'username' => $dt->username,
				'userpic' => $dt->photo,
				'user_level' => $dt->user_level, 
				'sekolah_id' => $dt->sekolah_id, 
				'user_token' => (empty($dt->users_token)) ? md5(uniqid($dt->username, true)) : $dt->users_token, 
				'logged_in' => true
			)
		);

		http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => 'Login Success','ulevel'=>$dt->user_level];
        echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
        exit;
	}

	public function login()
	{ 
		chek_session_login();
		$this->load->view('login/index');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth/login');
	}


	public function test() {
		//$this->db->update('users', ['password' => password_hash('admin', PASSWORD_DEFAULT)], ['userid' => 1]);
		$data = [
			'username'		=> 'naquib',
			'password'		=> password_hash('123456', PASSWORD_DEFAULT),
			'user_level'	=> 1
		];
		$this->db->insert('users', $data);
	}

}
