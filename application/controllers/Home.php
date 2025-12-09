<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('model_task');
		$this->load->model('model_news');
		$this->load->model('model_student');
		$this->load->model('model_teacher');
		$this->load->library('session');
		$this->load->helper('assets');

		if (!isset($_SESSION['username'])) redirect('auth/login');

		// $this->sync_notif();
	}

	/**
	 * View
	 *
	 * @return void
	 */
	public function index()
	{
		$class_id 				= $this->session->userdata('class_id');
		// $user 					= $this->db->where('username', $class_id)->get('users')->row_array();

		$filters['sekolah_id'] = $_SESSION['sekolah_id'];
		
		// jika login sebagi Murid
			if(isset($_SESSION['student_id'])){
				$filters['class_id'] = $class_id;
			}

		// jika login sebagai Guru
			if(isset($_SESSION['teacher_id'])){
				$filters['teacher_id'] = $_SESSION['teacher_id'];
			}

		// ############### Start Chart Siswa di tiap Kelas ############### 
			$kelas = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->order_by('class_level_id', 'ASC')->get('kelas')->result_array();
			$dataCartKelas = [];
			foreach ($kelas as $key => $value) {
				$rowStudent = $this->db->where('class_id', $value['class_id'])->get('student')->num_rows();
				$dataCartKelas[$key][] = $value['class_name'];
				$dataCartKelas[$key][] = ($rowStudent) ? $rowStudent : 0;
			}

			$data['dataCartKelas'] = $dataCartKelas;

		// ############### End Chart Siswa di tiap Kelas ###############
		// ############### Start Chart Guru Aktif / Tidak Aktif ###############
			$teachersStatusChart = [];
			// $teachersStatus = $this->db->select("case 
			// 				when status = 0 then 'Tidak Aktif' 
			// 				when status = 1 then 'Aktif' end as status, count(status) as total")
			// 			->where('sekolah_id', $_SESSION['sekolah_id'])
			// 			->group_by('status')
			// 			->get('teacher t')->result_array();
			$teachersStatus = $this->db->query("select 'aktif' as status, count(*) as total from teacher t 
				where sekolah_id = ".$_SESSION['sekolah_id']." and status = 1
				union
				select 'tidak aktif' as status, count(*) as total from teacher t 
				where sekolah_id = ".$_SESSION['sekolah_id']." and status = 0;")->result_array();

			foreach ($teachersStatus as $key => $value) {
				$teachersStatusChart[$key][] = $value['status'];
				$teachersStatusChart[$key][] = $value['total'];
			}

		// ############### End Chart Guru Aktif / Tidak Aktif ###############


		$data['student_class'] 	= $kelas;
		$data['teacher_status_chart'] = $teachersStatusChart;

		$data['tasks'] 			= $this->model_task->get_tasks(3, 0, $filters);
		$data['news']			= $this->model_news->get_news();

		// =========================== TASK DEADLINE ===========================
			// JIKA LOGIN SEBAGAI GURU
			if(isset($_SESSION['teacher_id'])){
				
				// ambil data yang belum melewati deadline
				$deadlines = $this->db->where('t.teacher_id', $_SESSION['teacher_id'])
										->where('due_date >=', 'now()')
										->join('kelas k', 'k.class_id = t.class_id', 'left')
										->join('subject s', 's.subject_id = t.subject_id', 'left')
										->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left')
										->get('task t')->result_array();

				//looping untuk ambil data yang due_date nya tinggal 1 hari
				$d = [];
				foreach ($deadlines as $key => $val) {
					if( (strtotime($val['due_date'])-time()) <= 86400  ){
						$d[] = $val;
					}
				}

				$data['deadline'] = $d;
			}

			// JIKA LOGIN SEBAGAI MURID
			if(isset($_SESSION['student_id'])){
				// ambil data yang belum melewati deadline
				$deadlines = $this->db->where('t.class_id', $_SESSION['class_id'])
										->where('due_date >=', date('Y-m-d H:i:s'))
										->join('subject s', 's.subject_id = t.subject_id', 'left')
										->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left')
										->get('task t')->result_array();

				//looping untuk ambil data yang due_date nya tinggal 1 hari
				$d = [];
				foreach ($deadlines as $key => $val) {
					if( (strtotime($val['due_date'])-time()) <= 86400  ){
						$d[] = $val;
					}
				}

				// looping lagi untuk mencari tugas deadline yang belum di jawab
				$e = [];
				foreach($d as $dealine){
					$cekAnswer = $this->db->where('task_id', $dealine['task_id'])->where('student_id', $_SESSION['student_id'])->get('task_student')->num_rows();
					if($cekAnswer == 0){
						$e[] = $dealine; 
					}
				}

				$data['deadline'] = $e;
			}

			// JIKA LOGIN SEBAGAI ORANG TUA
			if(isset($_SESSION['user_level']) && $_SESSION['user_level'] == 5){
				// get id parent
				$parentId = $this->db->where('username', $_SESSION['username'])->get('parent')->row_array()['parent_id'];

				// get data kelas anak
				$students = $this->db->where('parent_id', $parentId)->get('student')->result_array();
				$studentClass = array_map(function($student){
					return $student['class_id'];
				}, $students);

				// check if $studentClass is empty atau belum ada tautan anak
				if(empty($studentClass)){
					$studentClass = [0];
				}

				// ambil data yang belum melewati deadline
				$deadlines = $this->db->where_in('t.class_id', $studentClass)
										->where('due_date >=', date('Y-m-d H:i:s'))
										->join('subject s', 's.subject_id = t.subject_id', 'left')
										->join('teacher tc', 'tc.teacher_id = t.teacher_id', 'left')
										->join('kelas c', 'c.class_id = t.class_id', 'left')
										->get('task t')->result_array();

				//looping untuk ambil data yang due_date nya tinggal 1 hari
				$d = [];
				foreach ($deadlines as $key => $val) {
					if( (strtotime($val['due_date'])-time()) <= 86400  ){
						$d[] = $val;
					}
				}

				// looping lagi untuk mencari tugas deadline yang belum di jawab
				$e = [];
				foreach($d as $dealine){
					$cekAnswer = $this->db->where('task_id', $dealine['task_id'])
						->where('student_id', $_SESSION['student_id'])
						->get('task_student')->num_rows();

					if($cekAnswer == 0){
						$e[] = $dealine; 
					}
				}

				$data['deadline'] = $e;
			}

		$data['pathjs'] = 'home/js';

		$data['page_js'][] = ['path' => 'assets/libs/sweetalert2/sweetalert2.min.js'];
		$data['page_js'][] = ['path' => 'assets/libs/amcharts5/index.js'];
		$data['page_js'][] = ['path' => 'assets/libs/amcharts5/xy.js'];
		$data['page_js'][] = ['path' => 'assets/libs/amcharts5/themes/Animated.js'];
		$data['page_js'][] = ['path' => 'assets/libs/amcharts5/percent.js'];

		$data['page_css'] = [
			'assets/libs/sweetalert2/sweetalert2.min.css',
			'assets/node_modules/pagination-system/dist/pagination-system.min.css',
			'assets/css/home.css'
		];

		$data['total_siswa'] = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->where('user_level', 4)->get('users')->num_rows();
		$data['total_siswa_aktif'] = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->where('user_level', 4)->where('active', 1)->get('users')->num_rows();
		$data['total_siswa_tidak_aktif'] = $this->db->where('sekolah_id', $_SESSION['sekolah_id'])->where('user_level', 4)->where_in('active', [0, null])->get('users')->num_rows();

		
		$this->template->load('template', 'home/index', $data);
	}

	private function cari_task_notif(){
		$session = $this->session->userdata();

		// CARI DATA TUGAS JIKA TIDAK ADA DI TABEL NOTIF MAKA LAKUKAN INSERT
		$tasks = $this->db->where('DATE(available_date) >=', date("Y-m-d", strtotime("-1 months")))->get('task')->result_array();
		
		############################## INI DI GUNAKAN JIKA MENGGUNAKAN CONTENT JSON ##############################
		
		foreach ($tasks as $task) {
			$notif = $this->db->where('type', 'TASK')->where('task_id', $task['task_id'])->where('user_id', $session['userid'])->get('notif')->row_array();
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
		$beritas = $this->db->where('DATE(tanggal) >=', date("Y-m-d", strtotime("-1 months")))->get('news')->result_array();
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
		$sesies = $this->db->where('DATE(sesi_date) >=', date("Y-m-d", strtotime("-1 months")))->get('sesi')->result_array();
		foreach ($sesies as $sesi) {
			$notif = $this->db->where('type', 'SESI')->where('sesi_id', $sesi['sesi_id'])->where('user_id', $session['userid'])->get('notif')->row_array();
			if(!$notif){
				$data = [
					'type' 		=> 'SESI',
					'title' 	=> $sesi['sesi_title'],
					'seen' 		=> false,
					'user_id' 	=> $session['userid'],
					'created_at' => $sesi['sesi_date'].' '.$sesi['sesi_jam_start'],
					'link'		=> 'sesi/detail/'.$sesi['sesi_id'],
					'sesi_id'	=> $sesi['sesi_id']
				];
				$this->db->insert('notif', $data);
			}
		} 
	}

	public function sync_notif(){
		$session = $this->session->userdata();

		if($session['user_level'] == 4 || $session['user_level'] == 5){
			$this->cari_task_notif();
			$this->cari_news_notif();
			$this->cari_sesi_notif();
		}

		if($session['user_level'] == 3 || $session['user_level'] == 6){
			$this->cari_news_notif();
		}

		if($session['user_level'] == 3){
			$this->cari_sesi_notif();
		}
	}

	public function notif(){
		$user_id = $this->session->userdata('userid'); 
		$notif = $this->db->where('user_id', $user_id)->where('seen', false)->get('notif')->num_rows();

		$response = [ 'success' => true, 'total' => $notif ];

		header('Content-Type: application/json');
		echo json_encode($response, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}

	public function notif_data(){
		$user_id = $this->session->userdata('userid'); 
		$notif = $this->db->where('user_id', $user_id)->limit('100')->order_by('created_at', 'DESC')->get('notif')->result_array();

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
