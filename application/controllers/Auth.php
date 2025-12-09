<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// $_SERVER["CI_ENV"] = 'production';

class auth extends CI_Controller 
{
	function __construct() {
		parent::__construct();
		$this->load->model('m_login');
		$this->load->model('m_users');
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
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_empty_username'), 'token' => $this->security->get_csrf_hash()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}
		if(empty($password)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_empty_password'), 'token' => $this->security->get_csrf_hash()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$getUser = $this->db->get_where('users', ['username' => $username]);

		if($getUser->num_rows() == 0) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_username_mismatch'), 'token' => $this->security->get_csrf_hash()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$dt = $getUser->row();

		if(!$dt->active) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_account_not_active'), 'token' => $this->security->get_csrf_hash()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			return;
		}

		if(password_verify($password, $dt->password) == FALSE) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_login_password_mismatch'), 'token' => $this->security->get_csrf_hash()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		// cek jika akun kadaluarsa
		if($dt->date_limit < date('Y-m-d H:i:s')){
			http_response_code(402);
            $msg = ['err_status' => 'error', 
				'message' => 'Pemberitahuan Akun Anda Telah Habis', 
				'username' => $dt->username];
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
				'logged_in' => true,
				'themes' => $dt->themes
			)
		);

		// ==================== GENERATE API TOKEN ================
		include_once APPPATH . 'libraries/Generate_auth.php';
		$auth = new Generate_auth();
		$data = [
			'id_user' => $dt->userid,
			'users_token' => $this->session->userdata('user_token')
		];
		$resAuth = $auth->generate($data);
		$resAuth = json_decode($resAuth, true)['data'];

		$jwtToken = $auth->generateToken($resAuth);
		$jwtToken = json_decode($jwtToken, true)['data'];
		$this->session->set_userdata('jwt_token', $jwtToken);

		// ==================== END GENERATE API TOKEN ================
		

		if($dt->user_level==3 || $dt->user_level==6){ //guru or kepsek
			$getTeacher = $this->db->get_where('teacher', ['nik' =>$username]);
			$dteacher = $getTeacher->row();
			$teacher_id = $dteacher->teacher_id;

			$this->session->unset_userdata('student_id');
			$this->session->unset_userdata('class_id');

			$this->session->set_userdata(array('teacher_id'=>$teacher_id));
			$this->session->set_userdata(array('nama'=>$dteacher->teacher_name));
			$this->session->set_userdata(array('email'=>$dteacher->email));
			$this->session->set_userdata(array('class_level_id' => $this->m_users->get_all_class_teacher($teacher_id)));
			
		}
		if($dt->user_level==4){  //siswa
			$this->db->select('k.class_id, k.class_level_id, student_id, student_name, s.email, s.parent_id');
			$this->db->from('kelas k');
			$this->db->join('student s', 's.class_id=k.class_id');
			$this->db->where('user_id', $dt->userid);
			$dstudent = $this->db->get()->row();

			// jika tidak ada maka lakukan pencarian berdasarkan nis, kondisi ini terjadi karna di pruction masih banyak
			// user_id yang kosong di tabel student.
			if(empty($dstudent)){
				$this->db->select('k.class_id, k.class_level_id, student_id, student_name, s.email, s.parent_id');
				$this->db->from('kelas k');
				$this->db->join('student s', 's.class_id=k.class_id');
				$this->db->where('nis', $dt->username);
				$dstudent = $this->db->get()->row();
			}

			$student_id = $dstudent->student_id;
			$class_id = $dstudent->class_id;
			$class_level_id = $dstudent->class_level_id;

			$this->session->unset_userdata('teacher_id');

			$this->session->set_userdata(array('student_id'=>$student_id));
			$this->session->set_userdata(array('class_id'=>$class_id));
			$this->session->set_userdata(array('class_level_id'=>$class_level_id));
			$this->session->set_userdata(array('nama'=>$dstudent->student_name));
			$this->session->set_userdata(array('email'=>$dstudent->email));

			// get parent user_id
			$parent = $this->db->get_where('parent', ['parent_id' => $dstudent->parent_id])->row();
			$user = $this->db->get_where('users', ['username' => $parent->username])->row();

			// insert notif
			$data_notif = [
				'type' => 'LOGIN',
				'title' => $dstudent->student_name.' baru saja login pukul '.date('H:i'),
				'seen' => false,
				'user_id' => $user->userid,
				'created_at' => date('Y-m-d H:i:s'),
				'link' => '#',
			];

			// $this->db->insert('notif', $data_notif);

			// import library push notif
			include_once APPPATH . 'libraries/Push_notif.php';

			$data_notif['username'] = $dt->username;
			$pushNotif = new Push_notif();
			$exc = $pushNotif->push($data_notif);
		}

		if($dt->user_level==5){ //ortu
			$dParent = $this->db->where('username', $username)->get('parent')->row();
			$dStudent = $this->db->where('parent_id', $dParent->parent_id)->get('student')->row();

			$this->session->set_userdata(array('class_id'=>$dStudent->class_id));
			$this->session->set_userdata(array('student_id'=>$dStudent->student_id));
			$this->session->set_userdata(array('nama' => $dParent->name));
			$this->session->set_userdata(array('email' => $dParent->email));
		}

		// cek jika akun segera kadaluarsa dalam 7 hari
		$remainingTime = strtotime($dt->date_limit) - time();
		if($remainingTime < 604800){
			$res = ['message' => 'Akun Anda Akan Segera Berakhir', 'date_limit' => $dt->date_limit];
			$this->session->set_flashdata('expired_soon', $res);
		}

		$_token = base64_encode($dt->username.':'.$password);

		http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => 'Login Success','ulevel'=>$dt->user_level, 'token' => $_token];
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
		$script = '<script>
					window.localStorage.removeItem(\'level\');
					window.localStorage.removeItem(\'token\');
				  </script>';
		echo $script;
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
