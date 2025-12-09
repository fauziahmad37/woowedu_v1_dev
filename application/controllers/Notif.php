<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notif extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_notif');
	}

	private function cari_task_notif(){
		$session = $this->session->userdata();

		// CARI DATA Task JIKA TIDAK ADA DI TABEL NOTIF MAKA LAKUKAN INSERT
		$filter = ['sekolah_id' => $session['sekolah_id']];

		// jika user login sebagai murid
		if(isset($session['student_id']))
			$filter['class_id'] = $session['class_id'];

		// jika user login sebagai guru
		if(isset($session['teacher_id']))
			$filter['teacher_id'] = $session['teacher_id'];

		// get data sesi selama 30 hari kebelakang
		$tasks = $this->model_notif->get_task($filter)->result_array();
		
		foreach ($tasks as $task) {
			//get data notif, jika tidak ada di tabel notif maka lakukan insert notif baru
			$filter = ['type' => 'TASK', 'task_id' => $task['task_id'], 'user_id' => $session['userid']];
			$notif = $this->model_notif->get_notif($filter)->row_array();
			if(!$notif){
				$data = [
					'type' 		=> 'TASK',
					'title' 	=> $task['note'],
					'seen' 		=> false,
					'user_id' 	=> $session['userid'],
					'created_at' => $task['available_date'],
					'link'		=> 'task/detail/'.$task['task_id'],
					'task_id'	=> $task['task_id']
				];
				$this->db->insert('notif', $data);
			}
		}
	}

	private function cari_news_notif(){
		$session = $this->session->userdata();

		// CARI DATA NEWS JIKA TIDAK ADA DI TABEL NOTIF MAKA LAKUKAN INSERT
		$beritas = $this->db->where('DATE(tanggal) >=', date("Y-m-d", strtotime("-1 months")))
					->where('sekolah_id', $_SESSION['sekolah_id'])
					->get('news')->result_array();
		foreach ($beritas as $news) {
			$notif = $this->db->where('type', 'NEWS')->where('news_id', $news['id'])->where('user_id', $session['userid'])->get('notif')->row_array();
			if(!$notif){
				$data = [
					'type' 		=> 'NEWS',
					'title' 	=> $news['judul'],
					'seen' 		=> false,
					'user_id' 	=> $session['userid'],
					'created_at' => $news['tanggal'],
					'link'		=> 'news/detail/'.$news['id'],
					'news_id'	=> $news['id']
				];
				$this->db->insert('notif', $data);
			}
		} 
	}

	private function cari_sesi_notif(){
		$session = $this->session->userdata();

		// CARI DATA SESI JIKA TIDAK ADA DI TABEL NOTIF MAKA LAKUKAN INSERT
		$filter = ['sekolah_id' => $session['sekolah_id']];

		// jika user login sebagai murid
		if(isset($session['student_id']))
			$filter['class_id'] = $session['class_id'];

		// jika user login sebagai guru
		if(isset($session['teacher_id']))
			$filter['teacher_id'] = $session['teacher_id'];

		// get data sesi selama 30 hari kebelakang
		$sesies = $this->model_notif->get_sesi($filter)->result_array();

		foreach ($sesies as $sesi) {
			//get data notif, jika tidak ada di tabel notif maka lakukan insert notif baru
			$filter = ['type' => 'SESI', 'sesi_id' => $sesi['sesi_id'], 'user_id' => $session['userid']];
			$notif = $this->model_notif->get_notif($filter)->row_array();
			if(!$notif){
				$data = [
					'type' 		=> 'SESI',
					'title' 	=> $sesi['sesi_title'],
					'seen' 		=> false,
					'user_id' 	=> $session['userid'],
					'created_at' => $sesi['sesi_date'].' '.$sesi['sesi_jam_start'],
					'link'		=> 'sesi/lihat_sesi/'.$sesi['sesi_id'],
					'sesi_id'	=> $sesi['sesi_id']
				];
				$this->db->insert('notif', $data);
			}
		} 
	}

	// public function sync_notif(){
	// 	$session = $this->session->userdata();

	// 	if($session['user_level'] == 4 || $session['user_level'] == 5){
	// 		// $this->cari_task_notif();
	// 		// $this->cari_news_notif();
	// 		// $this->cari_sesi_notif();
	// 	}

	// 	if($session['user_level'] == 3 || $session['user_level'] == 6){
	// 		// $this->cari_news_notif();
	// 	}

	// 	if($session['user_level'] == 3){
	// 		// $this->cari_sesi_notif();
	// 	}
	// }

	public function notif(){
		$user_id = $this->session->userdata('userid'); 
		// $notif = $this->db->where('user_id', $user_id)->where('seen', false)->get('notif')->num_rows();
		include_once APPPATH . 'libraries/Push_notif.php';
		$pushNotif = new Push_notif();
		$data_notif['user_id'] = $user_id;

		$notif = $pushNotif->get_all_notification($data_notif);

		$data = (json_decode($notif, true));
		$notif = $data['data']['total_unseen'];

		$response = [ 'success' => true, 'total' => $notif ];

		header('Content-Type: application/json');
		echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}

	public function notif_data(){
		$user_id = $this->session->userdata('userid'); 
		// $notif = $this->db->where('user_id', $user_id)
		// 			->where('u.sekolah_id', $_SESSION['sekolah_id'])
		// 			->join('users u', 'u.userid = notif.user_id', 'left')
		// 			->limit('100')->order_by('created_at', 'DESC')
		// 			->get('notif')->result_array();

		

		// import push notif
		include_once APPPATH . 'libraries/Push_notif.php';
		$pushNotif = new Push_notif();
		$data_notif['user_id'] = $user_id;

		$notif = $pushNotif->get_all_notification($data_notif);

		$data = (json_decode($notif, true));
		$notif = $data['data']['notif_data'];

		$response = [ 'success' => true, 'data' => $notif ];

		header('Content-Type: application/json');
		echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}

	public function notif_update(){
		$get = $this->input->get();
		$notif_id = $get['notif_id'];

		$update = $this->db->where('notif_id', $notif_id)->update('notif', ['seen'=> true]);

		if($update){
			$response = [ 'success' => true, 'data' => 'data berhasil diupdate'];
		}else{
			$response = [ 'success' => false, 'data' => 'data gagal diupdate'];
		}


		header('Content-Type: application/json');
		echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}

}
