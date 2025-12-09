<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->cekLogin();
		$this->load->model('model_users');
		$this->load->model('model_common');
		$this->load->helper('assets');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
	}

	public function index()
	{
		$data['pageTitle'] = 'Users';
		$data['users'] = $this->model_users->get()->result();
		$data['pageContent'] = $this->load->view('users/userList', $data, TRUE);
		$this->load->view('template/layout', $data);
	}

	function Manageuser()
	{
		if ($this->input->post('submit')) {

			$this->form_validation->set_rules('data[username]', 'User Name', 'required');
			if ($this->form_validation->run() === TRUE) {

				$data = $this->input->post('data');
				if ($data['password'] == '***')
					$data['password'] = $this->input->post('oldpass');
				else
					$data['password'] = md5($data['password']);
				$userid = $this->input->post('userid');
				if ($userid == '') {
					$query = $this->db->insert('users', $data);
					$this->model_common->insertlog('Tambah user ');

					if ($query) $message = array('status' => true, 'message' => 'Berhasil menambahkan user');
					else $message = array('status' => true, 'message' => 'Gagal menambahkan user');
					$this->session->set_flashdata('message', $message);
					redirect('users/manageuser', 'refresh');
				} else {
					$this->db->where('userid', $userid);
					$query = $this->db->update('users', $data);
					$this->model_common->insertlog('Update user ');

					if ($query) $message = array('status' => true, 'message' => 'Berhasil mengupdate user');
					else $message = array('status' => true, 'message' => 'Gagal mengupdate user');
					$this->session->set_flashdata('message', $message);
					redirect('users', 'refresh');
				}
			}
		}
		$data['pageTitle'] = 'Manage Users';
		$data['pageContent'] = $this->load->view('users/manageuser', $data, TRUE);
		$this->load->view('template/layout', $data);
	}

	function edituser($id)
	{

		$data['user'] = $this->model_users->get_user_by_id($id);
		$data['pageTitle'] = 'Edit User';
		$data['pageContent'] = $this->load->view('users/manageuser', $data, TRUE);
		$this->load->view('template/layout', $data);
	}

	function activation(){
		$userid = $this->input->post('userid');
		$expired_date = $this->input->post('expired_date');
		
		$data = [
			'date_limit' => $expired_date
		];

		$query = $this->db->update('users', $data, ['userid' => $userid]);

		// response header
		header('Content-Type: application/json');
		if($query) {
			$msg = ['err_status' => 'success', 'message' => 'Activation success'];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
		} else {
			$msg = ['err_status' => 'error', 'message' => 'Activation failed'];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
		}
	}

	function deleteuser($id)
	{

		$this->db->where('userid', $id);
		$query = $this->db->delete('users');
		//exit;
		$this->model_common->insertlog('Hapus user dengan ID : ' . $id);

		if ($query) $message = array('status' => true, 'message' => 'Berhasil menghapus user');
		else $message = array('status' => true, 'message' => 'Gagal menghapus user');

		$this->session->set_flashdata('message', $message);

		redirect('users', 'refresh');
	}

	function Manageulevel()
	{
		if ($this->input->post('submit')) {

			$this->form_validation->set_rules('user_level_name', 'User Level Name', 'required');
			if ($this->form_validation->run() === TRUE) {

				$data['user_level_name'] =  $this->input->post('user_level_name');
				$level_id = $this->input->post('level_id');
				$total_menu = $this->input->post('total_menu');

				if (empty($level_id)) {
					$query = $this->db->insert('user_level', $data);
					$level_id = $this->model_common->getLastId('user_level_id', 'user_level');



					$this->model_common->insertlog('Tambah User Level ');

					if ($query) $message = array('status' => true, 'message' => 'Berhasil menambahkan user level');
					else $message = array('status' => true, 'message' => 'Gagal menambahkan  user level');
					$this->session->set_flashdata('message', $message);
				} else {
					$this->db->where('user_level_id', $level_id);
					$query = $this->db->update('user_level', $data);

					$this->model_common->insertlog('Update User Level denga ID ' . $level_id);

					if ($query) $message = array('status' => true, 'message' => 'Berhasil mengupdate user level');
					else $message = array('status' => true, 'message' => 'Gagal mengupdate  user level');
					$this->session->set_flashdata('message', $message);
				}


				$query = $this->db->where('menu_level_user_level', $level_id)->delete('menu_level');

				for ($i = 0; $i < $total_menu; $i++) {
					if (!empty($this->input->post('menu_level_menu' . $i))) {
						$data_sub['menu_level_user_level'] = $level_id;
						$data_sub['menu_level_menu'] = $this->input->post('menu_level_menu' . $i);
						$query = $this->db->insert('menu_level', $data_sub);
					}
				}

				redirect('users/manageulevel', 'refresh');
			}
		}
		$level_id =  $_REQUEST['id'];
		if (!empty($level_id)) {
			$data['level_id'] = $level_id;
			$data['ulevel'] = $this->model_users->get_ulevel_by_id($level_id)->user_level_name;
			$menu = $this->model_users->get_ulevel_menu($level_id);
			foreach ($menu->result() as $obj) {
				//echo $obj->menu_level_menu;
				$_arr_menu[] = $obj->menu_level_menu;
			}
			$data['menu_level'] = $_arr_menu;
		}
		$data['pageTitle'] = 'Manage User Level';
		$data['pageContent'] = $this->load->view('users/manageulevel', $data, TRUE);
		$this->load->view('template/layout', $data);
	}

	function listulevel()
	{

		$data['pageTitle'] = 'List User Level';
		$data['pageContent'] = $this->load->view('users/listulevel', $data, TRUE);
		$this->load->view('template/layout', $data);
	}

	function tampil_data_ulevel()
	{
		$search = array();
		$filcoid = '';
		$columns = array(
			0 => 'user_level_id',
			1 => 'user_level_name'
		);

		//searching
		if (!empty($_POST['sName'])) $search['name'] = $_POST['sName'];



		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$order = $columns[$this->input->post('order')[0]['column']];
		$dir = $this->input->post('order')[0]['dir'];

		$totalData = $this->model_users->ulevel_count($filcoid);
		$totalFiltered = $totalData;

		if (empty($search)) {
			$posts = $this->model_users->ulevel_limit($filcoid, $limit, $start, $order, $dir);
		} else {
			$posts =  $this->model_users->ulevel_search($limit, $start, $search, $order, $dir);
			$totalFiltered = $this->model_users->ulevel_search_count($search);
		}


		$data = array();
		if (!empty($posts)) {

			foreach ($posts as $post) {
				$timeData['user_level_id'] = $post->user_level_id;
				$timeData['user_level_name'] = $post->user_level_name;
				$data[] = $timeData;
			}
			//var_dump($data);
		}

		$json_data = array(
			"draw"            => intval($this->input->post('draw')),
			"recordsTotal"    => intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"            => $data
		);

		echo json_encode($json_data);
	}

	function deleteulevel($id)
	{
		$query = $this->db->where('menu_level_user_level', $id)->delete('menu_level');
		$this->db->where('user_level_id', $id);
		$query = $this->db->delete('user_level');
		//exit;
		$this->model_common->insertlog('Hapus User Level dengan ID : ' . $id);

		if ($query) $message = array('status' => true, 'message' => 'Berhasil menghapus User Level');
		else $message = array('status' => true, 'message' => 'Gagal menghapus User Level');

		$this->session->set_flashdata('message', $message);

		redirect('users/listulevel', 'refresh');
	}

	public function updateUsername() {
		header('Content-Type: application/json');

		$id = trim($this->input->post('id_user'));
		$nama = trim($this->input->post('nama_user'));

		if(empty($id) || empty($nama)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$data = [
			'username' => $nama
		];

		if(!$this->db->update('users', $data, ['userid' => $id])) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_command_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
        echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
        exit;
	}

	public function changePassword() {
		header('Content-Type: application/json');

		$pass_lama = trim($this->input->post('password_lama'));
		$pass_baru = trim($this->input->post('password_baru'));
		$re_pass   = trim($this->input->post('repassword'));
		$userid    = trim($this->input->post('id_user'));

		if(empty($pass_lama) || empty($pass_baru) || empty($re_pass) || empty($userid)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$users = $this->db->get_where('users', ['userid' => $userid])->row_array();

		if(password_verify($pass_lama, $users['password']) == FALSE) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_oldpass_verify')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}
		if($pass_baru != $re_pass) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_match_pass')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$data = [
			'password' => password_hash($pass_baru, PASSWORD_DEFAULT)
		];
		if(!$this->db->update('users', $data, ['userid' => $userid])) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_command_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
        echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
        exit;

	}

	public function uploadPhoto() {
		header('Content-Type: application/json');

		/*

		if(password_verify($pass_lama, $users['password']) == FALSE) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_oldpass_verify')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}
		if($pass_baru != $re_pass) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_match_pass')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$data = [
			'password' => password_hash($pass_baru, PASSWORD_DEFAULT)
		];
		if(!$this->db->update('users', $data, ['userid' => $userid])) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_command_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
        echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
        exit;
		
		 [foto] => Array
        (
            [name] => admin_20200220065646.png
            [type] => image/png
            [tmp_name] => C:\Users\Naquib-Alatas\AppData\Local\Temp\phpC21A.tmp
            [error] => 0
            [size] => 39414
        )

		*/

		if(!is_dir(FCPATH.'assets/uploads/account'))
			@mkdir(FCPATH.'assets/uploads/account');

		$id = trim($this->input->post('id_user'));
		$foto = $_FILES['foto'];

		if(empty($foto['name'])) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}
		$path = FCPATH.'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'users';
		$ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
		$fname = $path.DIRECTORY_SEPARATOR.$id.'.'.$ext;
		if(file_exists($fname))
			unlink($fname);
		move_uploaded_file($foto['tmp_name'], $fname);

		//if($this->db->get_where('users', ['userid' => $id])->num_rows() > 0)
		$this->db->update('users', ['photo' => $id.'.'.$ext], ['userid' => $id]);
		http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
        echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
        exit;
	}

	/**
	 =========================================================
	 * 		UPDATE BY NAQUIB 2021 09 09
	 * 		NEW USERS FUNCTION
	 =========================================================
	 */

	public function getUserLevel() {
		$this->db->order_by('user_level_id');
        $data = $this->db->get('user_level')->result_array();

		header('Content-Type: application/json');
		echo json_encode($data, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		exit;
    }

	public function getTable() {
		$draw 	= $this->input->get('draw');
		$filter = $this->input->get('columns');
		$limit	= $this->input->get('length');
		$offset	= $this->input->get('start');

		$data 	= $this->model_users->getUsers($filter, $limit, $offset);
		$count	= $this->model_users->countUsers($filter);

		$resp = [
			'draw' 				=> $draw,
			'data'				=> $data,
			'recordsTotal'		=> $this->db->count_all_results('users'),
			'recordsFiltered'	=> $count
		];

		header('Content-Type: application/json');
		echo json_encode($resp, JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS);
		exit();
	}

	public function post() {
		$username  = trim($this->input->post('txt-username'));
		$password  = trim($this->input->post('txt-password'));
		$userlevel = trim($this->input->post('slc-userlevel'));
		$sekolah_id = trim($this->input->post('slc-sekolah'));
		$active    = trim($this->input->post('chk-active'));

		header('Content-Type: application/json');

		if(empty($username) || $password === '***' || empty($password) || empty($userlevel)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
		
		if($this->db->get_where('users', ['username' => $username])->num_rows() > 0) {
			http_response_code(422);
			$m = [
					'err_status' => 'error', 
					'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
				];
			echo json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			return;
		}

		$data = [
			'username'		=> $username,
			'password'		=> password_hash($password, PASSWORD_DEFAULT),
			'user_level'	=> $userlevel,
			'sekolah_id'	=> $sekolah_id,
			'active'		=> $active === 'on' ? 1 : 0
		];

		if(!$this->db->insert('users', $data)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}

	public function put() {
		$id		   = trim($this->input->post('userid'));
		$username  = trim($this->input->post('txt-username'));
		$password  = trim($this->input->post('txt-password'));
		$userlevel = trim($this->input->post('slc-userlevel'));
		$active    = trim($this->input->post('chk-active'));

		header('Content-Type: application/json');

		if(empty($username) || empty($password) || empty($userlevel)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		$data = [
			'username'		=> $username,
			'user_level'	=> $userlevel,
			'active'		=> $active === 'on' ? 1 : 0
		];

		if($password !== '***')
			$data['password'] = password_hash($password, PASSWORD_DEFAULT);

		if(!$this->db->update('users', $data, ['userid' => $id])) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}

	public function delete() {
		header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if($input['isBulk'] == 1)
            $this->db->where_in('userid', $input['data']);
        else
            $this->db->where('userid', $input['data']);

        if(!$this->db->delete('users')) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success')];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
	}

	public function updateStatus(){
		$post = json_decode(file_get_contents('php://input'), true);

		header('Content-Type: application/json');
		$data = [
			'active' => $post['active']
		];

		if(!$this->db->update('users', $data, ['userid' => $post['userid']])) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}

	
}
