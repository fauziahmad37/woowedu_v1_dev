<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('model_wishlist');
		$this->load->model('model_teacher');
		$this->load->model('model_ebook_member');
		$this->load->model('model_transaction_payment');
		
		$this->load->helper('assets');
		$this->load->library('session');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index()
	{
		$userId = $this->session->userdata('userid');
		$data['user_data'] = $this->user_model->get_user($userId);
		$username = $data['user_data']['username'];

		$data['menu'] = isset($_GET['menu']) ? $_GET['menu'] : '';
		$data['tab'] = isset($_GET['tab']) ? $_GET['tab'] : '';

		
		// JIKA USER LEVEL ORTU LAKUKAN QUERY UNTUK MENDAPATKAN DATA STUDENT YG TERTAUT
		if($data['user_data']['user_level'] == 5){
			$parentId = $this->db->where('username', $username)->get('parent')->row_array()['parent_id'];
			$students = $this->db->where('parent_id', $parentId)->get('student')->result_array();
			$data['students'] = $students;
		}

		// JIKA USER LEVEL MURID LAKUKAN QUERY UNTUK MENDAPATKAN DATA ORTU
		if($data['user_data']['user_level'] == 4){
			$parentId = $this->db->where('nis', $username)->get('student')->row_array()['parent_id'];
			if($parentId){
				$data['parent'] = $this->db->where('parent_id', $parentId)->get('parent')->row_array();
			}else{
				$data['parent'] = [];
			}
		}

		// JIKA USER LEVEL MURID LAKUKAN QUERY UNTUK MENDAPATKAN DATA PENGAJAR
		$data['teachers'] = [];
		if($data['user_data']['user_level'] == 4){
			$filter['class_id'] = $_SESSION['class_id'];
			$data['teachers'] = $this->model_teacher->getTeacherClass(100,0,$filter);
		}

		// AMBIL DATA EBOOK MEMBERS
		$filter['user_id'] = $_SESSION['userid'];
		$data['ebook_members'] = $this->model_ebook_member->getAll(1000, 0, $filter);
		
		$data['page_js'] = [
			['path' => base_url('assets/libs/splide/splide.min.js'), 'defer' => TRUE],
			['path' => base_url('assets/js/_user.js'), 'defer' => TRUE],
			['path' => base_url('assets/js/_user_cart.js'), 'defer' => TRUE],
			['path' => base_url('assets/js/_user_wishlist.js'), 'defer' => TRUE],
			['path' => 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js'],
			// ['path' => 'assets/libs/js-multiselect-dropdown-main/multi-select-dropdown.js', 'defer' => TRUE],
		];

		$data['page_css'] = [
			base_url('assets/libs/splide/splide.min.css'),
			base_url('assets/css/_user_cart.css'),
		];

		// get total pembelian
		$data['totalPembelian'] = $this->model_transaction_payment->countAll($filter);

		// GET DATA WISHLIST
		$ebookWishlist = $this->model_wishlist->get()->result_array();
		$bundlingWishlist = $this->model_wishlist->getBundling()->result_array();
		$data['wishlists'] = array_merge($ebookWishlist, $bundlingWishlist);

		// GET DATA HISTORY PAYMENT
		$data['transactionPayments'] = $this->model_transaction_payment->getAll(100, 0, $filter);

		$this->template->load('template', 'user/index', $data);
	}

	public function store(){
		// load file helper
		$this->load->helper('file');

		$post = $this->input->post();


		// set validation rules
		// if (empty($_FILES['userfile']['name'])){
		// 	$this->form_validation->set_rules('userfile', 'userfile', 'required');

		// 	if($this->form_validation->run() == false){
		// 		// validation fails
		// 		$resp = ['success' => false, 'message' => $this->form_validation->error_array(), 'old' => $post];
		// 		$this->session->set_flashdata('error', $resp);
		// 		redirect($_SERVER['HTTP_REFERER']);
		// 	}
		// }

		// upload images
		$config['upload_path']          = './assets/images/users/';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 2048;
		$config['encrypt_name']         = true;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('userfile')){
			// upload fails
			// $resp = ['success' => false, 'message' => $this->upload->display_errors()];
			// $this->session->set_flashdata('error', $resp);
			// echo json_encode($resp); die;
		}else{
			// upload success
			$upload_data = $this->upload->data();
			// resize image
			$config['image_library'] = 'gd2';
			$config['source_image'] = './assets/images/users/'.$upload_data['file_name'];
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = 300;
			$config['height']       = 300;

			$this->load->library('image_lib', $config);
			$this->image_lib->resize();
			// remove old image
			$old_image = $this->user_model->get_user($post['user_id'])['photo'];
			if($old_image != '' || $old_image != null){
				unlink('./assets/images/users/'.$old_image);
			}
			// update user data
			$data = [ 'photo' => $upload_data['file_name'] ];
		}
		
		if(isset($data['photo'])){
			// update user data
			$update = $this->user_model->update($data, $post['user_id']);
		}

		// UPDATE DATA BERDASARKAN USER LEVEL
		$user = $this->db->where('userid', $post['user_id'])->get('users')->row_array();
		if($user['user_level'] == 4){
			$update = $this->db->where('nis', $user['username'])->update('student', ['student_name' => $post['full_name'], 'email'=>$post['email'], 'phone'=>$post['phone'], 'address'=>$post['address'], 'edit_at'=>date('Y-m-d H:i:s')]);
		}elseif($user['user_level'] == 3 || $user['user_level'] == 6){
			$update = $this->db->where('nik', $user['username'])->update('teacher', ['teacher_name' => $post['full_name'], 'email'=>$post['email'], 'phone'=>$post['phone'], 'address'=>$post['address'], 'edit_at'=>date('Y-m-d H:i:s')]);
		}elseif($user['user_level'] == 5){
			$update = $this->db->where('username', $user['username'])->update('parent', ['name' => $post['full_name'], 'email'=>$post['email'], 'phone'=>$post['phone'], 'address'=>$post['address'], 'edit_at'=>date('Y-m-d H:i:s')]);
		}

		// update student data
		$res = ($update) ?  ['success' => true, 'message' => 'Data berhasil diubah.'] :  ['success' => false, 'message' => 'Data gagal diubah.'];
		// set success message
		$this->session->set_flashdata('success', $res);
		echo json_encode($res);
	}

	public function store_parent(){
		$post = $this->input->post();
		$data = [
			'email' 	=> $post['parent_email'],
			'phone' 	=> $post['parent_phone'],
			'edit_at'		=> date('Y-m-d H:i:s', time())
		];
		$username 	= $this->db->where('userid', $post['user_id'])->get('users')->row_array()['username'];
		$parentId 	= $this->db->where('nis', $username)->get('student', $data)->row_array()['parent_id'];
		$update		= $this->db->where('parent_id', $parentId)->update('parent', $data);

		$res['token'] = $this->security->get_csrf_hash();
		if($update){
			$res['success'] = true;
			$res['message'] = 'Data berhasil di simpan';
		} else {
			$res['success'] = false;
			$res['message'] = 'Data gagal di simpan';
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
	}

	public function change_password(){
		$post = $this->input->post();
		
		// set validation rules
		$this->form_validation->set_rules('old_password', 'Password Lama', 'required|callback_check_password' , ['check_password' => 'Password lama tidak sesuai.'] );
		$this->form_validation->set_rules('new_password', 'Password Baru', 'required');
		$this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]');

		// validate form input
		if($this->form_validation->run() == false){
			// validation fails
			$resp = [
				'success' => false, 
				'message' => $this->form_validation->error_array(), 
				'old' => $post,
				'token' => $this->security->get_csrf_hash()
			];
			$this->session->set_flashdata('error', $resp);

			// set response header json
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($resp);
		}else{
			// validation succeeds
			$data = [
				'password' => password_hash($post['new_password'], PASSWORD_DEFAULT)
			];

			// update user data
			$this->user_model->update($data, $post['user_id']);

			// set success message
			$resp = ['success' => true, 'message' => 'Password berhasil diubah.'];
			$this->session->set_flashdata('success', $resp);

			// set response header json
			header('Content-Type: application/json; charset=utf-8');
			$resp['token'] = $this->security->get_csrf_hash();
			echo json_encode($resp);
		}
	
	}

	public function check_password($str): bool{
		// $member = $this->user_model->get_user($this->session->userdata('userid'));
		$member = $this->db->where('userid', $this->session->userdata('userid'))->get('users')->row_array();

		if(isset($member) &&  password_verify($str, $member['password'])){
			return true;
		}else{
			return false;
		}
	} 

	public function store_tautan_anak(){
		$post = $this->input->post();
		$sekolahId = $_SESSION['sekolah_id'];

		// cek nis apakah ada di sekolah ini
		$cek = $this->db->get_where('student', ['nis' => $post['nis'], 'sekolah_id' => $sekolahId])->num_rows();

		if($cek == 0){
			$res['success'] = false;
			$res['message'] = 'NIS tidak di temukan!';
			$res['token'] = $this->security->get_csrf_hash();

			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($res);
			return;
		}

		$userId = $post['userId']; // user id ortu
		$userNameOrtu = $this->db->where('userid', $userId)->get('users')->row_array()['username']; // get username ortu
		$parentId = $this->db->where('username', $userNameOrtu)->get('parent')->row_array()['parent_id']; // get parent id

		$update = $this->db->where('nis', $post['nis'])->update('student', ['parent_id' => $parentId]); // update parent id di tabel student
		if($update){
			$students = $this->db->where('parent_id', $parentId)->get('student')->result_array();
			$res = [ 'success' => true, 'message' => 'Data berhasil di simpan', 'data' => $students ];
		}else{
			$res = [ 'success' => false, 'message' => 'Data gagal di simpan', 'data' => []];
		}
		header('Content-Type: application/json; charset=utf-8');
		$res['token'] = $this->security->get_csrf_hash();
		echo json_encode($res);
	}

	public function delete_tautan_anak(){
		$post = $this->input->post();

		$update = $this->db->where('nis', $post['nis'])->update('student', ['parent_id' => null]);

		$res['token'] = $this->security->get_csrf_hash();
		if($update){
			$res['success'] = true;
			$res['message'] = 'Data berhasil dihapus';
		} else {
			$res['success'] = false;
			$res['message'] = 'Data gagal dihapus';
		}
		
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($res);
	}

	public function change_theme(){
		$theme = $_GET['theme'];
		$userId = $_SESSION['userid'];
		
		$update = $this->db->update('users', ['themes'=>$theme], ['userid'=> $userId]);
		if($update){
			$_SESSION['themes'] = $theme;
			setcookie('themes', $theme, time() + (86400 * 30), "/"); // 86400 = 1 day
			$this->session->set_flashdata('change_theme_success', 'Tema berhasil diubah');
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function cancel_change(){
		$theme = $_GET['theme'];
		$userId = $_SESSION['userid'];
		
		$update = $this->db->update('users', ['themes'=>$theme], ['userid'=> $userId]);
		if($update){
			$_SESSION['themes'] = $theme;
			setcookie('themes', $theme, time() + (86400 * 30), "/"); // 86400 = 1 day
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function my_ebook_detail(){
		$get = $this->input->get();
		$data['ebook_id'] = $get['id'] ?? 0;
		$data['my_ebook_id'] = $get['my_ebook_id'] ?? 0;

		$data['page_css'] = [
			'assets/css/ebook.css',
			'assets/css/ebookDetail.css',
			'assets/libs/splide/splide.min.css',
		];

		$data['page_js'] = [
			['path' => base_url('assets/js/_my_ebook.js'), 'defer' => TRUE],
			['path' => 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js']
		];

		$this->template->load('template', 'user/my_ebook_detail', $data);
	}
}
