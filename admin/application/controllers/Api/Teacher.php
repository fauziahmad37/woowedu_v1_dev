<?php

class Teacher extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['model_common']);
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

		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
			return;
		}

		$draw   = $this->input->get('draw');
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$filter = $this->input->get('columns');
		$rec   = $this->model_common->get_all_teacher($filter, $limit, $offset);
		$count  = $this->model_common->count_all_teacher($filter);
		$countFilter = $this->model_common->count_all_teacher($filter);

		$datas =   array(
			"draw"			  => $draw,
			"recordsTotal"	  => $count,
			"recordsFiltered" => $countFilter,
			"data"			  => $rec
		);
		http_response_code(200);
		echo json_encode($datas, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
		exit();
	}



	public function set_relasi()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header('Content-Type: application/json');
		$_arr_kelas = isset($_POST['teacher_class'])? $this->input->post('teacher_class') : [];
		$_arr_subject = isset($_POST['teacher_subject'])? $this->input->post('teacher_subject') : [];
		$teacher_id = $this->input->post('a_teacher_id');

		$this->db->where('teacher_id', $teacher_id);
		$this->db->delete('class_teacher');
		$this->db->where('teacher_id', $teacher_id);
		$this->db->delete('subject_teacher');

		foreach ($_arr_kelas as $val) {
			$class_id = $val;
			$data_class = [
				'teacher_id'  => $teacher_id,
				'class_id'   => $class_id
			];
			$this->db->insert('class_teacher', $data_class);
		}

		foreach ($_arr_subject as $val) {
			$subject_id = $val;
			$data_subject = [
				'teacher_id'  => $teacher_id,
				'subject_id'   => $subject_id
			];
			$this->db->insert('subject_teacher', $data_subject);
		}

		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
		exit;
	}

	public function add_data()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST');
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
			return;
		}

		// params
		$sekolah_id   = trim($this->input->post('sekolah_id'));
		$teacher_name   = trim($this->input->post('teacher_name'));
		$nik   = trim($this->input->post('nik'));
		$gender   = trim($this->input->post('gender'));
		$address   = trim($this->input->post('address'));
		$phone   = trim($this->input->post('phone'));
		$email   = trim($this->input->post('email'));
		$token  = trim($this->input->post('xsrf_token'));
		//validation
		if ($this->csrfsimple->checkToken($token) === false) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
			return;
		}
		if (empty($teacher_name) || empty($nik)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
			return;
		}

		// insert action
		$data = [
			'teacher_name'  => $teacher_name,
			'nik' => $nik,
			'gender' => $gender,
			'address' => $address,
			'phone'   => $phone,
			'email'   => $email,
			'sekolah_id' => $sekolah_id
		];
		if (!$this->model_common->save_teacher($data)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
			return;
		}
		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
		exit;
	}

	public function edit_data()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: PUT');
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
			return;
		}
		$input = json_decode(file_get_contents('php://input'), TRUE);

		// params 
		$sekolah_id     = trim($input['sekolah_id']);
		$teacher_id     = trim($input['teacher_id']);
		$teacher_name   = trim($input['teacher_name']);
		$nik   = trim($input['nik']);
		$gender   = trim($input['gender']);
		$address   = trim($input['address']);
		$phone   = trim($input['phone']);
		$email   = trim($input['email']);
		$token  = trim($input['xsrf_token']);

		if ($this->csrfsimple->checkToken($token) === false) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
			return;
		}
		if (empty($teacher_id) || empty($teacher_name) || empty($nik)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
			return;
		}

		// update action
		$data = [
			'teacher_id'        => $teacher_id,
			'teacher_name'  => $teacher_name,
			'nik' => $nik,
			'gender' => $gender,
			'address' => $address,
			'phone'   => $phone,
			'email'   => $email,
			'sekolah_id'   => $sekolah_id
		];
		if (!$this->model_common->modify_teacher($data)) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
			return;
		}
		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
		exit;
	}




	public function delete_data()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: DELETE');
		header('Content-Type: application/json');

		$params = file_get_contents('php://input');
		$input = json_decode($params, TRUE);
		if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
			return;
		}
		if ($this->csrfsimple->checkToken($input['xsrf_token']) === false) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
			return;
		}
		if ($input['bulk'])
			$this->db->where_in('teacher_id', $input['data']);
		else
			$this->db->where('teacher_id', $input['data']);
		if (!$this->db->delete('teacher')) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
			return;
		}
		http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG);
		exit();
	}

	// import data
	public function import()
	{
		require_once APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'xlsx' . DIRECTORY_SEPARATOR . 'SimpleXLSX.php';
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET');
		$metd = ['POST', 'GET'];
		if (!in_array($_SERVER['REQUEST_METHOD'], $metd)) {
			http_response_code(405);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
			echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
			return;
		}

		$config['upload_path'] = FCPATH . 'assets/files/upload/teacher/';
		$config['allowed_types'] = 'xlsx';
		$config['overwrite'] = TRUE;
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('upload-file')) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->upload->display_errors()];
			echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
			return;
		}
		$data = $this->upload->data();
		$xlsx = SimpleXLSX::parse($data['full_path']);
		$excelRows = $xlsx->rows();
		$n = 0;
		$prog = 0;
		unset($excelRows[0]);
		ob_start();
		foreach ($excelRows as $exc) {
			if ($exc[0] === '1') continue;

			$sekolah = $this->db->where('lower(sekolah_nama)', strtolower($exc[5]))->get('sekolah')->row_array();
			$nsd = [
				'teacher_name'    => $exc[0],
				'nik'  =>  "$exc[1]",
				'address'  => $exc[2],
				'phone'   => "$exc[3]",
				'email'   => $exc[4],
				'sekolah_id' => $sekolah['sekolah_id'],
				'class_names' => $exc[6],
				'gender' => $exc[7],
			];
			if ($this->db->get_where('teacher', array('nik' => "$exc[1]"))->num_rows() > 0) {
				$nsd['edit_at'] = date('Y-m-d H:i:s');
				$nsd['edit_by'] = $this->session->userdata('username');
				unset($nsd['class_names']);
				if ($this->db->update('teacher', array('nik' => "$exc[1]"), $nsd))
					$prog += 1;
			} else {
				$nsd['create_by'] = $this->session->userdata('username');
				if ($this->model_common->save_teacher($nsd))
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
