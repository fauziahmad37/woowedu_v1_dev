<?php

class Kepsek extends MY_Controller
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
		$rec   = $this->model_common->get_all_kepsek($filter, $limit, $offset);
		$count  = $this->model_common->count_all_kepsek($filter);
		$countFilter = $this->model_common->count_all_kepsek($filter);

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

		// check nik
		$check_nik = $this->db->get_where('teacher', ['nik' => $nik])->num_rows();
		if ($check_nik > 0) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => 'Nik sudah ada!', 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
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

		if (!$this->model_common->save_kepsek($data)) {
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
		if (!$this->model_common->modify_kepsek($data)) {
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
}
