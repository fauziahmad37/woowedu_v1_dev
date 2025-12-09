<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sesi extends MY_Controller {

  public function __construct()
  {
    parent::__construct();
		$this->load->model('model_sesi');
		$this->load->helper('assets');
		if (!isset($_SESSION['username'])) redirect('auth/login');
	}
	
	public function index()
	{ 
	
		$saturday = strtotime('saturday this week');
		$sunday = strtotime('sunday this week');

		//echo date("d-m-Y",$sunday);
		//echo date("d-m-Y",$saturday);
	
		$data['jsdata'] = '';//$this->loaddata();

		$this->template->load('template', 'sesi/index', $data);
	}

	public function delete()
	{
		$post = $this->input->post();
		$delete = $this->db->where('sesi_id', $post['id'])->delete('sesi');

		$res = ($delete) ?  ['success'=>true, 'message'=>'Data berhasil dihapus!'] : ['success'=>false, 'message'=>'Data gagal dihapus!'];
		header('Content-Type: application/json');
		echo json_encode($res);		
	}
	
	public function sesidetail($id)
	{
		$user_level = $this->session->userdata('user_level');
		$filter = ['id' => $id];
		$data = $this->model_sesi->get_sesi($filter)->row_array();

		$buttonEdit = '<a href="sesi/create/'.$id.'" class="btn btn-success d-inline me-1 rounded-3"><i class="bi bi-pencil-square text-white"></i></a>';
		$buttonDelete = '<a class="btn btn-danger d-inline rounded-3" onclick="deleteSesi('.$id.')"><i class="bi bi-trash3-fill text-white"></i></a>';
		$buttonLihat = '<a href="sesi/lihat_sesi/'.$id.'" class="btn btn-primary d-inline me-1 rounded-3"><i class="bi bi-eye text-white"></i></a>';
		$buttonGroup = '';

		if($user_level == 3){
			// jika teacher_id sesi sama dengan teacher_id guru login maka munculkan tombol edit & delete
			if($_SESSION['teacher_id'] == $data['teacher_id']){
				$buttonGroup = $buttonLihat.$buttonEdit.$buttonDelete;
			}else{
			// jika teacher_id sesi tidak sama maka hanya munculkan button lihat
				$buttonGroup = $buttonLihat;
			}
		}

		$day = date('N', strtotime($data['sesi_date']));
		switch($day){
			case 1: 
				$day = 'Senin';
				break;
			case 2:
				$day = 'Selasa';
				break;
			case 3:
				$day = 'Rabu';
				break;
			case 4:
				$day = 'Kamis';
				break;
			case 5:
				$day = 'Jum\'at';
				break;
			case 6:
				$day = 'Sabtu';
				break;
			case 7:
				$day = 'Minggu';
				break;
		}
		
		$date = date('d M Y', strtotime($data['sesi_date']));

		$html = '<div class="card rounded-3">
					<h6 class="card-header bg-primary text-white rounded-top-3"><img src="assets/themes/space/icons/ic_sesi.svg" width="16" class="me-2">
						'.$day.', '.$date.', '.$data['sesi_jam_start'].'-'.$data['sesi_jam_end'].'
					</h6>
							
					<div class="card-body">
						<h5>'.$data['sesi_title'].'</h5>
						<div class="col">
							<span class="badge text-bg-primary">'.$data['class_name'].'</span>
							<span class="badge text-bg-primary">'.$data['teacher_name'].'</span>
						</div>

						<p class="mt-3">Catatan:</p>
						<p style="font-size: 11px;">'.$data['sesi_note'].'</p>
					</div>							
					<div class="card-footer d-flex justify-content-end">
						'.$buttonGroup.'
					</div>
				</div>';
		echo $html;
	}
		
 
	public function loaddata()
	{
		function classLevel($data){
			return $data['class_id'];
		}
		$classIds = array_map("classLevel", $_SESSION['class_level_id']);
		$params['sdate'] = $_GET['start'];
		$params['edate'] = $_GET['end'];
		$params['teacher_id'] = $this->session->userdata['teacher_id'];
		$params['class_ids'] = $classIds;
		$customerdata = $this->model_sesi->datasesi($params);
		$list = [];
		foreach($customerdata->result() as $data) {
			$tanggal =  $data->sesi_date;
			$color = (strtotime($data->sesi_date.'T'.$data->sesi_jam_start) > time()) ? '#0fff03' : '#808080';
			$list[] = array(
				'id'=>$data->sesi_id,
				'title'=>$data->sesi_title.' - '.$data->class_name.' ('.$data->subject_name.')',
				'start'=>$data->sesi_date.'T'.$data->sesi_jam_start,
				'end'=>$data->sesi_date.'T'.$data->sesi_jam_end,
				'color'=> $color
			);
		}
		if($list == null) $list[] = array(0);
		echo json_encode($list);			
		exit;
	}

	public function create($id = ''){
		// $this->session->userdata['teacher_id'];
		$post = $this->input->post();
		$data = [];

 
				
		if( isset($post['title'])){
			$teacher_id =  $this->session->userdata['teacher_id'];
			$data_save = [
				'sesi_title'	=> trim($post['title'], true), 
				'sesi_date'		=> $post['tanggal'], 
				'sesi_jam_start'=> $post['jamstart'], 
				'sesi_jam_end'	=> $post['jamend'],
				'subject_id'	=> $post['materi_id'],
				'class_id'		=> $post['class_id'],
				'teacher_id' 	=> $teacher_id,
				'sesi_note'		=> trim($post['keterangan'])
			];

			if($post['id'] == ''){
				// cek sesi di kelas yang sama atau kelas lain dengan guru yang sama
				// union 1 = cari waktu sesi yang ada di database yang berada di antara waktu inputan dan dengan kelas yang sama
				// union 2 = cari waktu inputan yang berada di antara waktu sesi yang ada di database dan dengan kelas yang sama
				// union 3 = cari waktu sesi yang ada di database yang berada di antara waktu inputan dan dengan guru yang sama
				// union 4 = cari waktu inputan yang berada di antara waktu sesi yang ada di database dan dengan guru yang sama
				// $cek = "select * from sesi s 
				// 			where ((concat_ws(' ', s.sesi_date, s.sesi_jam_start) between '".$post['tanggal'].' '.$post['jamstart']."' and '".$post['tanggal'].' '.$post['jamend']."') or 
				// 			(concat_ws(' ', s.sesi_date, s.sesi_jam_end) between '".$post['tanggal'].' '.$post['jamstart']."' and '".$post['tanggal'].' '.$post['jamend']."') or
				// 			( '".$post['tanggal'].' '.$post['jamstart']."' between  concat_ws(' ', s.sesi_date, s.sesi_jam_start)  and concat_ws(' ', s.sesi_date, s.sesi_jam_end) ) )
				// 			and class_id = ".$post['class_id']."
				// 		union 
				// 			select * from sesi s 
				// 			where 
				// 			(( '".$post['tanggal'].' '.$post['jamstart']."' between  concat_ws(' ', s.sesi_date, s.sesi_jam_start)  and concat_ws(' ', s.sesi_date, s.sesi_jam_end) ) or
				// 			( '".$post['tanggal'].' '.$post['jamend']."' between  concat_ws(' ', s.sesi_date, s.sesi_jam_start)  and concat_ws(' ', s.sesi_date, s.sesi_jam_end) ) )
				// 			and class_id = ".$post['class_id']."
				// 		union
				// 			select * from sesi s
				// 			where (concat_ws(' ', s.sesi_date, s.sesi_jam_start) between '".$post['tanggal'].' '.$post['jamstart']."' and '".$post['tanggal'].' '.$post['jamend']."' or 
				// 			concat_ws(' ', s.sesi_date, s.sesi_jam_end) between '".$post['tanggal'].' '.$post['jamstart']."' and '".$post['tanggal'].' '.$post['jamend']."') and teacher_id = $teacher_id
				// 		union 
				// 			select * from sesi s
				// 			where ('".$post['tanggal'].' '.$post['jamstart']."' between concat_ws(' ', s.sesi_date, s.sesi_jam_start)  and concat_ws(' ', s.sesi_date, s.sesi_jam_end) or 
				// 			'".$post['tanggal'].' '.$post['jamend']."' between concat_ws(' ', s.sesi_date, s.sesi_jam_start)  and concat_ws(' ', s.sesi_date, s.sesi_jam_end)) and teacher_id = $teacher_id; ";
				
				$cek = "WITH session_data AS (
								SELECT *
								FROM sesi s
								WHERE class_id = ".$post['class_id']." OR teacher_id = $teacher_id
							)
							SELECT *
							FROM session_data s
							WHERE (
								(concat_ws(' ', s.sesi_date, s.sesi_jam_start) BETWEEN '".$post['tanggal'].' '.$post['jamstart']."' AND '".$post['tanggal'].' '.$post['jamend']."') OR 
								(concat_ws(' ', s.sesi_date, s.sesi_jam_end) BETWEEN '".$post['tanggal'].' '.$post['jamstart']."' AND '".$post['tanggal'].' '.$post['jamend']."') OR 
								('".$post['tanggal'].' '.$post['jamstart']."' BETWEEN concat_ws(' ', s.sesi_date, s.sesi_jam_start) AND concat_ws(' ', s.sesi_date, s.sesi_jam_end)) OR
								('".$post['tanggal'].' '.$post['jamend']."' BETWEEN concat_ws(' ', s.sesi_date, s.sesi_jam_start) AND concat_ws(' ', s.sesi_date, s.sesi_jam_end))
							);";
											
				if($this->db->query($cek)->num_rows() > 0){
					$dataSesi = $this->db->query($cek)->row_array();
					$kelas = $this->db->where('class_id', $dataSesi['class_id'])->get('kelas')->row_array();
					$res = [
						'success'=>false, 
						'message'=>'Sesi sudah ada di jam tersebut sesi kelas '.$kelas['class_name'].' jam mulai '.date('d M Y', strtotime($dataSesi['sesi_date'])).' '.$dataSesi['sesi_jam_start'].' sd '.$dataSesi['sesi_jam_end'],
						'token' => $this->security->get_csrf_hash()
					];
					header('Content-Type: application/json');
					echo json_encode($res); 
					die;
				}

				$save = $this->db->insert('sesi', $data_save);
				$res = ($save) ?  ['success'=>true, 'message'=>'Data berhasil disimpan!'] : ['success'=>false, 'message'=>'Data gagal disimpan!'];

				// insert ke notif
				$data_notif = [
					"type" => 'SESI',
					"title" => $data_save['sesi_title'],
					"seen" => false,
					"user_id" => $this->session->userdata['userid'],
					"link" => 'sesi/lihat_sesi/'.$this->db->insert_id(),
					"created_at" => date('Y-m-d H:i:s'),
					"sesi_id" => $this->db->insert_id()
				];

				// $this->db->insert('notif', $data_notif);

				// import push notif
				include_once APPPATH . 'libraries/Push_notif.php';
				$pushNotif = new Push_notif();

				$pushNotif->send_sesi($data_notif);

			}else{
				$save = $this->db->where('sesi_id', $post['id'])->update('sesi', $data_save);
				$res =  ($save) ? ['success'=>true, 'message'=>'Data berhasil diupdate!'] :  ['success'=>false, 'message'=>'Data gagal diupdate!'];
			}

			header('Content-Type: application/json');
			echo json_encode($res); die;
		}

		$data['class_id'] = null; // inisiasi kelas id agar tidak error di js nya
		if($id != '') $data = $this->db->where('sesi_id', $id)->get('sesi')->row_array();

		$this->template->load('template', 'sesi/create', ['data' => $data]);
	}

	public function detail($id = ''){
		$data['data'] = $this->db->select('s.*, t.teacher_name, su.subject_name')
			->where('s.sesi_id', $id)
			->join('teacher t', 't.teacher_id=s.teacher_id', 'left')
			->join('materi m', 'm.materi_id=s.materi_id', 'left')
			->join('subject su', 'su.subject_id=m.subject_id', 'left')
			->get('sesi s')->row_array();

		$this->template->load('template', 'sesi/detail', $data);
	}	

	public function lihat_sesi($id = ''){
		$data['page_js'] = [
			['path' => 'assets/js/_sesi.js', 'defer' => false]
		];

		$filter = ['id' => $id];
		$data['sesi'] = $this->model_sesi->get_sesi($filter)->row_array();

		$filter = [];
		$filter['sesi_id'] = $id;
		$filter['student_id'] = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

		$data['absen'] = $this->model_sesi->get_absen_sesi($filter)->row_array();

		$this->template->load('template', 'sesi/lihat_sesi', $data);
	}

	public function getSesiByDate(){
		$teacher_id = $_SESSION['teacher_id'];
		$get = $this->input->get();

		// ambil sesi berdasarkan kelas dan guru yang login
		$sesi1 = $this->db->where('sesi_date', $get['date'])
					->where('sesi.teacher_id', $teacher_id)
					->join('kelas', 'kelas.class_id=sesi.class_id')
					->get('sesi')->result_array();

		// ambil sesi dari guru lain
		$sesi2 = $this->db->where('sesi_date', $get['date'])
					->where('sesi.class_id', $get['class_id'])
					->join('kelas', 'kelas.class_id=sesi.class_id')
					->get('sesi')->result_array();

		// gabungkan sesi dari guru yang login dan guru lain
		$sesi = array_merge($sesi1, $sesi2);

		$res = ['success'=>true, 'data'=>$sesi];
		header('Content-Type: application/json');
		echo json_encode($res);
	}

	public function gabung_sesi(){
		$sesi_id = $_POST['sesi_id'];
		$student_id = $_POST['student_id'];

		$data = [
			'sesi_id' => $sesi_id,
			'student_id' => $student_id,
			'sesi_start' => date('Y-m-d H:i:s'),
			'status' => 'hadir',
		];

		// cek absen sesi sudah ada apa belum
		$cek = $this->db->where('sesi_id', $sesi_id)->where('student_id', $student_id)->get('sesi_attendances');
		if($cek->num_rows() > 0){
			$res = $this->db->update('sesi_attendances', ['sesi_end' => date('Y-m-d H:i:s')], ['sesi_id' => $sesi_id, 'student_id' => $student_id]);
		}else{
			$res = $this->db->insert('sesi_attendances', $data);
		}
		
		if($res){
			$success = ['success'=>true, 'message'=>'Data berhasil di simpan!', 'token' => $this->security->get_csrf_hash()];
		}else{
			$success = ['success'=>false, 'message'=> 'Data gagal di simpan!', 'token' => $this->security->get_csrf_hash()];
		}
		
		header('Content-Type: application/json');
		echo json_encode($success);
	}
}
