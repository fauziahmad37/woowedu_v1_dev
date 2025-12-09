<?php

class Student extends MY_Controller {
	public function __construct() 
	{
		parent::__construct(); 
		$this->load->model(['model_common', 'model_student']);
		$this->load->model(['Model_settings']);
		$this->load->library(['csrfsimple', 'curl']);
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
	}

	public function get_all() 
	{
    	header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-Type: application/json');		
		
		if($_SERVER['REQUEST_METHOD'] !== 'GET') 
		{
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
				
		$draw   	 = $this->input->get('draw');
		$limit  	 = $this->input->get('length');
		$offset 	 = $this->input->get('start');
		$filter 	 = $this->input->get('columns'); 
		$rec    	 = $this->model_common->get_all_student($filter, $limit, $offset);
		$count  	 = $this->db->count_all_results('student');
    	$countFilter = $this->model_common->count_all_student($filter);
		
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
 
 
	public function add_data() 
	{
    	header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header('Content-Type: application/json');

		if($_SERVER['REQUEST_METHOD'] !== 'POST') {
				http_response_code(405);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
		}
		
		// params
		 
		$student_name   = trim($this->input->post('student_name')); 
		$nis   = trim($this->input->post('nis')); 
		$class_id   = trim($this->input->post('class_id'));  
		$address   = trim($this->input->post('address')); 
		$email = trim($this->input->post('email')); 
		$gender   = trim($this->input->post('gender'));
		$phone   = trim($this->input->post('phone'));  
		$parent_id = trim($this->input->post('parent_id'));
		// $parent_phone = trim($this->input->post('parent_phone'));
		// $parent_email = trim($this->input->post('parent_email'));
		$sekolah_id = $this->session->userdata('sekolah_id');			
		$token  = trim($this->input->post('xsrf_token'));
		//validation
		if($this->csrfsimple->checkToken($token) === false) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		if(empty($student_name) || empty($nis) || empty($parent_id)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}

		// check nis
		$cek = $this->db->get_where('student', ['nis' => $nis, 'sekolah_id' => $sekolah_id])->num_rows();
		if($cek > 0) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => 'nis sudah ada', 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
 
		// insert action
 
		
		$data = [ 
			'nis' => $nis,
			'student_name' => $student_name,
			'class_id' => $class_id, 
			'address' => $address,
			'phone' => $phone,
			'email' => $email,
			'gender' => $gender,
			'parent_id' => ($parent_id) ? $parent_id : null,
			// 'parent_phone' => $parent_phone,
			// 'parent_email' => $parent_email, 
			'sekolah_id' => $sekolah_id			
		];
		if(!$this->model_common->save_student($data)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}

	public function edit_data() 
	{
  	  	header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT');
		header('Content-Type: application/json');

		if($_SERVER['REQUEST_METHOD'] !== 'PUT') {
				http_response_code(405);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
				return;
		}
		$input = json_decode(file_get_contents('php://input'), TRUE);
		
		// params 
		$student_id     = trim($input['student_id']);   
		$student_name   = trim($input['student_name']); 
		$nis   = trim($input['nis']); 
		$class_id   = trim($input['class_id']);  
		$address   = trim($input['address']); 
		$phone   = trim($input['phone']);  
		$parent_id = trim($input['parent_id']);
		// $parent_phone = trim($input['parent_phone']);
		$email = trim($input['email']); 
		$sekolah_id = $this->session->userdata('sekolah_id');	 
		$token  = trim($input['xsrf_token']);

		if($this->csrfsimple->checkToken($token) === false) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		if(empty($student_id) || empty($student_name) || empty($nis)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		
		// update action
		$data = [
				'student_id'	=> $student_id, 
				'nis' 			=> $nis,
				'student_name'  => $student_name,
				'class_id' 		=> $class_id, 
				'address' 		=> $address,
				'phone' 		=> $phone,
				'email' 		=> $email,
				'parent_id'		=> ($parent_id) ? $parent_id : null,
				// 'parent_phone' 	=> $parent_phone,
				// 'parent_email' 	=> $parent_email, 
				'sekolah_id' 	=> $sekolah_id	
		];

		if(!$this->model_common->modify_student($data)) {
				http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}
		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
	}
 

	public function delete_data() {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: DELETE');
		header('Content-Type: application/json');

			$params = file_get_contents('php://input');
			$input = json_decode($params, TRUE);
			if($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
					http_response_code(405);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
					return;
			}
			if($this->csrfsimple->checkToken($input['xsrf_token']) === false) {
					http_response_code(422);
					$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
					echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
					return;
			}
			if($input['bulk'])
					$this->db->where_in('student_id', $input['data']);
			else
					$this->db->where('student_id', $input['data']);
			if(!$this->db->delete('student')) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
			}
			http_response_code(200);
			$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			exit();
	}

	// import data
	public function import() {
		require_once APPPATH.'third_party'.DIRECTORY_SEPARATOR.'xlsx'.DIRECTORY_SEPARATOR.'SimpleXLSX.php';
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET');
		$metd = ['POST', 'GET'];
		if(!in_array($_SERVER['REQUEST_METHOD'], $metd)) {
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
			return;
		}

		$config['upload_path'] = FCPATH.'assets/files/upload/teacher/';
		$config['allowed_types'] = 'xlsx';
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('upload-file')) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->upload->display_errors()];
			echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
			return;
		}
		$data = $this->upload->data();
		$xlsx = SimpleXLSX::parse($data['full_path']);
		$excelRows = $xlsx->rows();
		$n = 0; $prog = 0;
		unset($excelRows[0]);
		ob_start();
		foreach($excelRows as $exc) 
		{
			if($exc[0] === '1') continue;
			
			$sekolah 	= $this->db->where('lower(sekolah_nama)', strtolower($exc[7]))->get('sekolah')->row_array();
			$kelas 		= $this->db->where('lower(class_name)', strtolower($exc[2]))->where('sekolah_id', $sekolah['sekolah_id'])->get('kelas')->row_array();
			
			$nsd = [  
				'nis' => $exc[0],
				'student_name' => $exc[1],
				'class_id' => $kelas['class_id'],
				'address' => $exc[3],
				'phone' => "$exc[4]",
				'email' => $exc[5],
				'gender' => $exc[6],
				'sekolah_id' => $sekolah['sekolah_id']	
			];
			if($this->db->get_where('student', array('nis'=>$exc[1]))->num_rows() > 0) {
				$nsd['edit_at'] = date('Y-m-d H:i:s');
				$nsd['edit_by'] = $this->session->userdata('username');
				if($this->db->update('student', array('nis'=>$exc[1]), $nsd))
						$prog += 1;
			} else {
				$nsd['create_by'] = $this->session->userdata('username');
				if($this->model_common->save_student($nsd))
						$prog += 1;
			}
			echo json_encode(['total' => count($excelRows), 'prog' => $prog]);
			ob_flush();
			$n++;
		}
		ob_end_clean();
	}
	// end import data 
 
}
