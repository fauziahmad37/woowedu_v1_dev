<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_news');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index(){
		$data = [];
		$this->template->load('template', 'news/index', $data);
	}

	public function create($id = ''){
		$data = [];
		if($id != '') $data = $this->db->where('id', $id)->get('news')->row_array();

		$this->template->load('template', 'news/create', ['data'=>$data]);
	}

	public function save(){
		$this->load->helper('file');

		$post = $this->input->post();
		$data = [];

		$this->form_validation->set_rules('title', 'Judul Pengumuman', 'required|min_length[3]|max_length[250]', ['min_length' => '%s Tidak boleh kurang dari 3 karakter']);
		$this->form_validation->set_rules('isi', 'Isi Pengumuman', 'required|min_length[10]',['min_length' => '%s Isi Pengumuman tidak boleh kurang dari 10 karakter']);

		// jika inputan salah (tidak lolo validasi)
		if(!$this->form_validation->run())
		{
			$resp = ['success' => false, 'errors' => $this->form_validation->error_array(), 'old' => $_POST];
			$resp['token'] = $this->security->get_csrf_hash();
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		// jika ada file yang di kirim
		if(isset($_FILES['lampiran']) && $_FILES['lampiran']['name']){
			$dir = './assets/images/news/';

			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}

			$config['upload_path'] = $dir;
			$config['allowed_types']        = 'jpg|jpeg|png|webp';
			$config['max_size']             = 2000;
			$config['encrypt_name']         = true;

			$this->load->library('upload', $config);

			if(!$this->upload->do_upload('lampiran')){
				// upload fails
				$resp = [
					'error' => true,
					'message' => $this->upload->display_errors()
				];

				$resp['token'] = $this->security->get_csrf_hash();
				$this->session->set_flashdata('error', $resp);
				redirect($_SERVER['HTTP_REFERER']);
			}
			
			// upload success
			$upload_data = $this->upload->data();
		}

		if( isset($post['id'])){
			
			$data_save = [
				'user_id' 	=> $post['user_id'],
				'judul'		=> strip_tags($post['title'], true), 
				'isi'		=> strip_tags($post['isi']),
				'tanggal'	=> date('Y-m-d H:i:00', time()),
				'image' 	=> isset($upload_data['file_name']) ? $upload_data['file_name'] : null,
				'sekolah_id'=> $_SESSION['sekolah_id']
			];

			if($post['id'] == ''){
				// cek new untuk validasi duplikat input berkali2
				$cek = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->where('tanggal', date('Y-m-d H:i:s', time()))->get('news')->num_rows();
				if($cek == 0){
					// Disable debug db supaya errornya gak keliatan di layar user
					$db_debug = $this->db->db_debug; //save setting
					$this->db->db_debug = FALSE; //disable debugging for queries
					
					$save = $this->db->insert('news', $data_save); // run query
					
					//check for errors, etc
					$this->db->db_debug = $db_debug; //restore setting
					
					$res = ($save) ?  ['success'=>true, 'message'=>'Data berhasil disimpan!'] : ['success'=>false, 'message'=>'Data gagal disimpan!'];
					$res['token'] = $this->security->get_csrf_hash();
					// Success simpan
					$this->session->set_flashdata('success', $res);
					
					// insert ke notif
					$data_notif = [
						'user_id' 	=> $post['user_id'],
						'type' 		=> 'NEWS',
						'title' 	=> $data_save['judul'],
						'seen' 		=> false,
						'created_at'=> date('Y-m-d H:i:s'),
						'link'		=> 'news/detail/'.$this->db->insert_id(),
						'news_id'	=> $this->db->insert_id(),
					];
					// get all user
					// $users = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->get('users')->result_array();
					// foreach($users as $user){
						// $data_notif['user_id'] = $user['userid'];
						// $this->db->insert('notif', $data_notif);
					// }
					
					// import push notif
					include_once APPPATH . 'libraries/Push_notif.php';
					$pushNotif = new Push_notif();
					$data_notif['sekolah_id'] = $_SESSION['sekolah_id'];

					$pushNotif->send_news($data_notif);
					
					$this->template->load('template','news/index');
				}else{
					$res = ['success'=>false, 'message'=>'Data sudah ada!'];
					$res['token'] = $this->security->get_csrf_hash();
					// gagal simpan
					$this->session->set_flashdata('success', $res);
					$this->template->load('template','news/index');
				}
				
			}else{
				// jika update tidak melampirkan file
				if(!isset($upload_data['file_name'])) unset($data_save['image']);
				$save = $this->db->where('id', $post['id'])->update('news', $data_save);
				// Success update
				$res =  ($save) ? ['success'=>true, 'message'=>'Data berhasil diupdate!'] :  ['success'=>false, 'message'=>'Data gagal diupdate!'];
				// buat res dengan token
				$res['token'] = $this->security->get_csrf_hash();

				$this->session->set_flashdata('success', $res);
				return redirect('news');
			}

		}
	}

	public function history(){
		$username 	= $this->session->userdata('username');
		$user_level = $this->db->where('username', $username)->get('users')->row_array()['user_level'];
		$page 		= isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit 		= isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
		$title		= $_GET['title'];
		$startDate	= (isset($_GET['startDate'])) ? $_GET['startDate'] : null;
		$endDate	= (isset($_GET['endDate'])) ? $_GET['endDate'] : null;

		$page = ($page - 1) * $limit;

		$data['user_level'] 	= $user_level;
		$data['news'] 			= $this->model_news->get_history($limit, $page, $title, $startDate, $endDate);
		$data['total_records'] 	= $this->model_news->get_total_history($title, $startDate, $endDate);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function detail($id = ''){
		if($id == '') redirect('news');

		$data['data'] = $this->db->where('id', $id)->get('news')->row_array();

		$this->template->load('template', 'news/detail', $data);
	}

	public function delete(){
		$post = $this->input->post();

		$news = $this->db->where('id', $post['id'])->get('news')->row_array();
		if($news['image']){
			if(file_exists('./assets/images/news/'.$news['image'])){
				unlink('./assets/images/news/'.$news['image']);
			}
		}

		$delete = $this->db->where('id', $post['id'])->delete('news');

		$res = ($delete) ?  ['success'=>true, 'message'=>'Data berhasil dihapus!', 'token' => $this->security->get_csrf_hash()] : ['success'=>false, 'message'=>'Data gagal dihapus!'];
		header('Content-Type: application/json');
		echo json_encode($res);
	}

}
