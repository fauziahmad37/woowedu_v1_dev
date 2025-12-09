<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('model_task');
		$this->load->model(['model_kelas', 'model_ebook']);
		$this->load->helper('assets');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index() {
		$data['mapelop'] = $this->model_task->get_mapel();
		$data['page_js'] = [
			['path' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11', 'defer' => TRUE],
			// ['path' => 'https://pagination.js.org/dist/2.6.0/pagination.js', 'defer' => TRUE],
			['path' => 	html_escape('assets/node_modules/pdfjs-dist/build/pdf.min.js')],
			['path' => 	html_escape('assets/node_modules/pdfjs-dist/web/pdf_viewer.js'), 'defer' => TRUE],
			['path' => 'assets/js/task_index.js', 'defer' => TRUE],
		];
		$data['page_css'] = [
			base_url('assets/css/tugas.css')
		];

		// JIKA LOGIN SEBAGAI GURU
		if(isset($_SESSION['teacher_id'])){
			$data['total_task'] = $this->db->where('teacher_id', $_SESSION['teacher_id'])
									->where('date(due_date) >=', date('Y-m-d'))
									->order_by('available_date')
									->get('task')->num_rows();
			
			// ambil data yang belum melewati deadline
			$deadlines = $this->db->where('teacher_id', $_SESSION['teacher_id'])
									->where('due_date >=', 'now()')
									->get('task')->result_array();

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
			$data['total_task'] = $this->db->select('t.*')
									->from('task t')
									->join('task_student ts', 'ts.task_id=t.task_id and ts.student_id='.$_SESSION['student_id'], 'left')
									->where('class_id', $_SESSION['class_id'])
									->where('date(due_date) >=', date('Y-m-d'))	
									->where('ts.student_id is null')
									->order_by('available_date')
									->get()->num_rows();
			
			// ambil data yang belum melewati deadline
			$deadlines = $this->db->where('class_id', $_SESSION['class_id'])
									->where('due_date >=', 'now()')
									->get('task')->result_array();

			//looping untuk ambil data yang due_date nya tinggal 1 hari
			$d = [];
			foreach ($deadlines as $key => $val) {
				if( (strtotime($val['due_date'])-time()) <= 86400  ){
					$d[] = $val;
				}
			}

			$data['deadline'] = $d;
		
		}

		$this->template->load('template', 'task/index', $data);
	}
	
	public function getlist()
	{
		$username 	= $this->session->userdata('username');
		$user_level 				= $this->session->userdata('user_level');
		$teacher_id 				= $this->session->userdata('teacher_id');
		$page 		= isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit 		= isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
		$mapel		= $_GET['mapel'];
		$startDate	= $_GET['startDate'];
		$endDate	= $_GET['endDate'];

		$page = ($page - 1) * $limit;

		$data['user_level'] 	= $user_level;
		
		if($user_level == 3 ){ // jika user login Guru
			$data['task'] 			= $this->model_task->get_teacher_task($limit, $page, $mapel, $startDate, $endDate);
			$data['total_records'] 	= $this->model_task->get_teacher_total_task($mapel, $startDate, $endDate);			
		}elseif($user_level == 4 ){	// jika user login Murid
			$tasks 					= $this->model_task->get_student_task($limit, $page, $mapel, $startDate, $endDate);
			foreach ($tasks as $key => $value) {
				$tasks[$key]['answer'] = $this->db->where('student_id', $_SESSION['student_id'])
											->where('task_id', $value['task_id'])
											->get('task_student')->row_array();
			}

			$data['task'] 			= $tasks;
			$data['total_records'] 	= $this->model_task->get_student_total_task($mapel, $startDate, $endDate);		
		}
		

		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);		
	}

	public function getAll() {
        $draw   = $this->input->get('draw');
        $limit  = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_task->get_tasks($limit, $offset, $filters);

        $_data = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('task'),
            'recordsFiltered' => $this->model_task->count_get_tasks($filters)
        ];

        echo json_encode($_data, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

	public function get_all_kelas(){
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-Type: application/json');		
		
		if($_SERVER['REQUEST_METHOD'] !== 'GET'){
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
				
		$draw   	 = $this->input->get('draw');
		$limit  	 = $this->input->get('length');
		$offset 	 = $this->input->get('start');
		$filter 	 = $this->input->get('columns');
		$rec    	 = $this->model_kelas->get_kelas_teacher();
		$count  	 = $this->db->count_all_results('class_teacher');
    	$countFilter = $this->model_kelas->count_all_kelas_teacher();
		
		$datas =   array(
			"draw"			  => $draw,
			"recordsTotal"	  => $count,
			"recordsFiltered" => $countFilter,
			"data"			  => $rec
		);

		http_response_code(200);
		header("Content-Type: application/json");
		echo json_encode($datas, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit();
	}

	public function detail($id = ''){
		$data['page_css'] = [
			'assets/css/_task.css',
		];

		$get = $this->input->get();
		$ts_id = isset($get['ts_id']) ? $get['ts_id'] : '';
		
		if(!$id) redirect('dashboard');

		$data['task'] = $this->model_task->get_tasks_detail($id);
		$user_level = $this->session->userdata('user_level');
		
		if($data['task']['tipe'] === 2)
			$data['ebook'] = '';

		if($user_level == 4 && !$ts_id){ // jika user login sebagai murid dan ts_id nya kosong	
			$student = $this->db->where('nis', $this->session->userdata('username'))->get('student')->row_array();
			$data['task_student'] = $this->db->where('student_id', $student['student_id'])->where('task_id', $id)->order_by('ts_id', 'desc')->get('task_student')->row_array();
		}

		if($user_level == 5 && !$ts_id){ // jika user login sebagai orang tua murid dan ts_id nya kosong	
			$parent = $this->db->where('username', $this->session->userdata('username'))->get('parent')->row_array();
			$student = $this->db->where('parent_id', $parent['parent_id'])->get('student')->result_array();

			$student_ids = [];
			foreach ($student as $key => $value) {
				$student_ids[] = $value['student_id'];
			}

			$task_student = $this->db->where_in('student_id', $student_ids)->where('task_id', $id)->order_by('ts_id', 'desc')->get('task_student')->row_array();
			$data['task_student'] = $task_student;
		}

		if($user_level == 3 && $ts_id){ // jika user login sebagai guru dan ts_id ada	
			$student = $this->db->where('nis', $this->session->userdata('username'))->get('student')->row_array();
			$data['task_student'] = $this->db->where('ts_id', $ts_id)->get('task_student')->row_array();
		}

		if($user_level == 3 && !$ts_id){ // jika login sebagai guru dan ts_id nya kosong
			// get data semua siswa yang ada di kelas tugas tersebut
			$data_siswa_kelas = $this->model_task->get_all_siswa_task($id);
			
			// looping untuk mencari siswa yang sudah mengerjakan tugas
			foreach ($data_siswa_kelas as $key => $val) {
				$data_siswa_kelas[$key]['detail_jawaban'] = $this->db->where('task_id', $id)->where('student_id', $val['student_id'])->get('task_student')->row_array();
			}
			$data['data_siswa_kelas'] = $data_siswa_kelas; 
			
			$this->template->load('template', 'task/detail_tugas_guru', $data);
		} else {

			$this->template->load('template', 'task/detail', $data);	
		}
		
	}

	public function store_file(){
		$this->load->helper('file');
		$post = $this->input->post();
		$student = $this->db->where('nis', $this->session->userdata('username'))->get('student')->row_array();

		// jika ada file yang di kirim
		if($_FILES['formFile']['name']){
			$dir = './assets/files/student_task/'.$student['class_id'];

			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}

			$config['upload_path'] = $dir;
			$config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|docx|doc|xls|xlsx|mp4';
			$config['max_size']             = 30000;
			$config['encrypt_name']         = true;

			$this->load->library('upload', $config);

			if(!$this->upload->do_upload('formFile')){
				// upload fails
				$resp = [
					'success' => false, 
					'message' => json_encode($this->upload->display_errors()) 
				];
				$this->session->set_flashdata('simpan', $resp);
				redirect(base_url('task/detail/'.$post['task_id']));
			}
			
			// upload success
			$upload_data = $this->upload->data();
		} 
			
		
		// insert task student
		$data = [
			'student_id' 	=> $student['student_id'],
			'task_id' 		=> $post['task_id'],
			'task_note'		=> $post['task_note'],
			'task_file'		=> isset($upload_data['file_name']) ? $upload_data['file_name'] : null,
			'task_submit'	=> date('Y-m-d H:i:s',time())
		];

		$check_task_answer = $this->db->where('student_id', $student['student_id'])->where('task_id', $post['task_id'])->get('task_student')->row_array();
		if($check_task_answer){
			$result = $this->db->where('student_id', $student['student_id'])->where('task_id', $post['task_id'])->update('task_student', $data);
		}else{
			$result = $this->db->insert('task_student', $data);
		}
		
		$resp = ($result) ? ['success'=>true, 'message'=>'Data berhasil disimpan'] : ['success'=>false, 'message'=>'Data gagal disimpan'];

		$this->session->set_flashdata('simpan', $resp);
		redirect(base_url('task/detail/'.$post['task_id']));
		
	}
	
	public function save() {
		$this->load->helper('file');
		$post = $this->input->post(); 
		$teacher_id = $this->session->userdata('teacher_id');
		$tanggal_start = (new DateTime($post['tanggal_start'].' '.$post['jamstart']))->format('Y-m-d H:i');
		$tanggal_end = (new DateTime($post['tanggal_end'].' '.$post['jamend']))->format('Y-m-d H:i');

		if(empty(trim($post['title'])) || empty(trim($post['keterangan'])) || count($post['pilih_kelas']) == 0 || empty($tanggal_start) || empty($tanggal_end)) {
			$Msg = 	[
						'success'=> false, 
						'message'=> $this->lang->line('woow_is_required'),
						'old' => $post
					];
			$this->session->set_flashdata('simpan', $Msg);
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		$dir = './assets/files/teacher_task/'.$teacher_id;

		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}

		$config['upload_path'] = $dir;
		$config['allowed_types']        = 'gif|jpg|jpeg|png|pdf|docx|doc|xls|xlsx|ppt|pptx|mp4';
		$config['max_size']             = 100000; // 100 MB
		$config['encrypt_name']         = true;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if($post['tipeTugas'] !== 'ebook') {

			if(!empty($_FILES['lampiran']['name'])){ // jika lampiran nya tidak kosong

				if(!$this->upload->do_upload('lampiran')){ // jika gagal di upload
					// log_message('error', $this->upload->display_errors()); die;
					// upload fails
					// $resp = [
					// 	'success' => false, 
					// 	'message' => json_encode($this->upload->display_errors()),
					// 	'old' => $_POST
					// ];
					// $this->session->set_flashdata('simpan', $resp);

					$resp['success'] = false;
					$resp['message'] = $this->upload->display_errors();

					// response header json
					header('Content-Type: application/json');
					$resp['token'] = $this->security->get_csrf_hash();
					echo json_encode($resp);
					return;
				}else{


				// upload success
				$upload_data = $this->upload->data();
				}
			}
			
		}	

		$tipe = [ 'regular' => 1, 'ebook' => 2 ];

		$pages = []; $book = [];

		if($post['tipeTugas'] === 'ebook') {
			$pages = explode(',', $post['pages']);
			$book = $this->model_ebook->get($post['ebook_code']);
		}
		

		// insert task
		if(isset($post['id']) && empty($post['id'])){
			$this->db->trans_start();

			foreach ($post['pilih_kelas'] as $key => $value) {
				$data = [
					'teacher_id' 	=> $teacher_id,
					'available_date'=> $tanggal_start,
					'due_date' 		=> $tanggal_end,
					'title'			=> trim($post['title']),
					'note'			=> trim($post['keterangan']),
					'subject_id'	=> $post['select_mapel'],
					'class_id'		=> $value,
					'task_file'		=> isset($upload_data['file_name']) ? $upload_data['file_name'] : '',
					'tipe'			=> !empty($post['tipeTugas']) ? $tipe[trim($post['tipeTugas'])] : 1 
				];
				
				$this->db->insert('task', $data);

				$id = $this->db->insert_id();

				if($post['tipeTugas'] === 'ebook')
					foreach($pages as $page)
						$this->db->insert('task_ebook', ['task_id' => $id, 'ebook_id' => $book['id'], 'page_index' => $page ]);

				// ============= INSERT NOTIF start ==========
				$data_notif = [
					'type' => 'TASK',
					'title' => 'Ada tugas baru untuk Kamu',
					'created_at' => date('Y-m-d H:i:s'),
					'link' => 'task/detail/'.$id,
					'task_id' => $id,
					'seen' => false,
					'class_ids' => json_encode($data['class_id']),
				];

				// $students = $this->db->where('class_id', $value)->get('student')->result_array();
				// foreach ($students as $key => $student) {
				// 	$user = $this->db->where('username', $student['nis'])->get('users')->row_array();
				// 	$data_notif['user_id'] = $user['userid'];
				// 	$this->db->insert('notif', $data_notif);
				// }

				// import push notif
				include_once APPPATH . 'libraries/Push_notif.php';
				$pushNotif = new Push_notif();

				$pushNotif->send_task($data_notif);

				// ============= INSERT NOTIF END ==========
			}

			$this->db->trans_complete();

			$resp = [
				'success' => true, 
				'message' => 'data berhasil di simpan',
			];
			$this->session->set_flashdata('simpan', $resp);

		} else {
			$this->db->trans_start();

			foreach ($post['pilih_kelas'] as $key => $value) {
				$data = [
					'teacher_id' 	=> $teacher_id,
					'available_date'=> $post['tanggal_start'].' '.$post['jamstart'],
					'due_date' 		=> $post['tanggal_end'].' '.$post['jamend'],
					'title'			=> $post['title'],
					'note'			=> $post['keterangan'],
					'subject_id'	=> $post['select_mapel'],
					'class_id'		=> $value,
					'task_file'		=> isset($upload_data['file_name']) ? $upload_data['file_name'] : '',
					'tipe'			=> !empty($post['tipeTugas']) ? $tipe[trim($post['tipeTugas'])] : 1 
				];
				
				$update = $this->db->update('task', $data, ['task_id' => $post['id']]);

				$id = $this->db->insert_id();

				if($post['tipeTugas'] === 'ebook')
					foreach($pages as $page)
						$this->db->insert('task_ebook', ['task_id' => $id, 'ebook_id' => $book['id'], 'page_index' => $page ]);
					
			}

			$this->db->trans_complete();
		}
			

		if($this->db->trans_status()){
			$resp = ['success'=>true, 'message'=>'Data berhasil disimpan'];
		}else{
			$resp = ['success'=>false, 'message'=>'Data gagal disimpan'];
		}

		$this->session->set_flashdata('simpan', $resp);
		// redirect(base_url('task'));

		// response header json
		header('Content-Type: application/json');
		$resp['token'] = $this->security->get_csrf_hash();
		echo json_encode($resp);
	}
	
	public function save_nilai(){
 
		$post = $this->input->post(); 
  
		$data = [ 
			'task_nilai'=> $post['task_nilai'],
			'task_comment_nilai'=> $post['task_comment_nilai'] 
		];
		$this->db->where('ts_id', $post['ts_id']);
		$update = $this->db->update('task_student', $data);
		if($update){
			$resp = ['success'=>true, 'message'=>'Data berhasil disimpan'];
		}else{
			$resp = ['success'=>false, 'message'=>'Data gagal disimpan'];
		}

		// insert to notif
		$task_student = $this->db->where('ts_id', $post['ts_id'])->get('task_student')->row_array();
		$task = $this->db->where('task_id', $task_student['task_id'])
					->join('subject s', 's.subject_id = t.subject_id', 'left')
					->get('task t')->row_array();

		$data_notif = [
			'type' => 'TASK',
			'title' => 'Nilaimu untuk tugas ' . $task['subject_name'] . ' telah keluar. Bagus sekali, terus semangat!',
			'seen' => false,
			'created_at' => date('Y-m-d H:i:s'),
			'link' => 'task/detail/'.$task_student['task_id'],
			'task_id' => $task_student['task_id'],
		];

		// get user id student
		// $student = $this->db->where('student_id', $task_student['student_id'])->get('student')->row_array();
		// $user = $this->db->where('username', $student['nis'])->get('users')->row_array();

		// insert notif berdasarkan user id murid
		$data_notif['ts_id'] = $post['ts_id'];
		// $this->db->insert('notif', $data_notif);

		// import push notif
		include_once APPPATH . 'libraries/Push_notif.php';
		$pushNotif = new Push_notif();

		$pushNotif->send_nilai_tugas_murid($data_notif);


		// insert notif berdasarkan user id orang tua murid
		// $parent = $this->db->where('parent_id', $student['parent_id'])->get('parent')->row_array();
		// $user = $this->db->where('username', $parent['username'])->get('users')->row_array();

		$data_notif['title'] = 'Nilai tugas '.$task['subject_name'].' anak Anda sudah tersedia.';
		$pushNotif->send_nilai_tugas_parent($data_notif);
		// $data_notif['user_id'] = $user['userid'];
		// $this->db->insert('notif', $data_notif);

		echo json_encode($resp);
		 
	}	
	 
	
	public function create($id = ''){

		$teacher_id = $_SESSION['teacher_id'];
		$data['mapelop'] = $this->model_task->get_mapel();
		$data['class_teachers'] = $this->db->where('teacher_id', $teacher_id)
									->join('kelas c', 'c.class_id = ct.class_id', 'left')
									->get('class_teacher ct')->result_array();
 
		if($id != '') $data['data'] = $this->db->where('task_id', $id)->get('task')->row_array();

		if(!empty($this->input->get('type')) && $this->input->get('type') == 'ebook')
		{
			$pages = $this->input->get('pages');
			$bookCode = $this->input->get('code');
			$data['bookinfo'] = $this->db->get_where('ebooks', ['book_code' => $bookCode])->row_array();
			$data['bookinfo']['pages'] = implode(',', $pages);
		}

		$data['page_css'] = [
								"https://cdn.quilljs.com/1.3.6/quill.snow.css",
								"https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
		];
		
		$data['pathjs'] = 'task/assets/js_create';

		$this->template->load('template', 'task/create', $data);
	}	

	public function delete($id = ''){
		if($_SERVER['REQUEST_METHOD'] !== 'DELETE'){
			$res = ['success'=>false, 'message'=>'Request method not alowed!'];
			header('Content-Type: application/json');
			echo json_encode($res); 
			die;
		}

		$delete = $this->db->where('task_id', $id)->delete('task');

		$res = ($delete) ?  ['success'=>true, 'message'=>'Data berhasil dihapus!'] : ['success'=>false, 'message'=>'Data gagal dihapus!'];
		header('Content-Type: application/json');
		echo json_encode($res);
	}

	public function ebookTask() {

		$bookCode = $this->input->post('book_code', TRUE);
		$title = $this->input->post('title', TRUE);
		$pages = $this->input->post('pages');

		$this->form_validation->set_rules('book_code', 'Kode Buku', 'required', [
			'required' => '%s wajib terisi !!!'
		]);
		$this->form_validation->set_rules('title', 'Judul', 'required', [
			'required' => '%s wajib terisi !!!'
		]);
		$this->form_validation->set_rules('pages', 'Halaman', 'is_array', [
			'is_array' => '%s harus berupa data larik'
		]);

		if(!$this->form_validation->run())
		{
			$this->session->set_flashdata('errors', $this->form_validation->error_array());
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		$params = [
			'type' => 'ebook',
			'code' => $bookCode,
			'pages' => $pages
		];

		redirect(base_url('task/create?'.http_build_query($params)));
	}

}
