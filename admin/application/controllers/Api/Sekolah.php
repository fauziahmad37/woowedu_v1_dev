<?php

ini_set('memory_limit', '4000M'); 

class Sekolah extends MY_Controller {

    public function __construct()
    {
			parent::__construct();
			check_Loggin();
			$this->load->model(['model_common', 'model_sekolah']); 
			$this->load->helper('url');
			$this->load->helper('slug');
			$this->load->helper('assets');	
			$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
			$this->lang->load('message', $lang);
			$this->load->library('csrfsimple');
    }

    public function getAll() {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET');
		header('Content-Type: application/json');		

        $draw = $this->input->post('draw', TRUE);
        $limit = $this->input->post('length', TRUE);
        $offset = $this->input->post('start', TRUE);
        $filters = $this->input->post('columns');

        $data = $this->model_sekolah->getAll($filters, $limit, $offset);
        
        $resp = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('sekolah'),
            'recordsFiltered' => $this->model_sekolah->countAll($filters)
        ];

		echo json_encode($resp, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit();
        // echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

	public function add_data(){
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
		 
		$sekolah_nama   = trim($this->input->post('sekolah_nama')); 
		$sekolah_kode   = trim($this->input->post('sekolah_kode')); 
		$sekolah_alamat = trim($this->input->post('sekolah_alamat')); 
		$sekolah_phone = trim($this->input->post('sekolah_phone')); 
		// $kode_aktivasi = trim($this->input->post('kode_aktivasi')); 
		$kode_aktivasi = $this->generateRandomString();
		$token  = trim($this->input->post('xsrf_token'));
		//validation
		if($this->csrfsimple->checkToken($token) === false) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		if(empty($sekolah_nama)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
 
		// insert action
		$data = [ 
				'sekolah_kode'  	 => $sekolah_kode,
				'sekolah_nama'  	 => $sekolah_nama,
				'sekolah_alamat'  	 => $sekolah_alamat,
				'sekolah_phone'  	 => $sekolah_phone,
				'kode_aktivasi'  	 => $kode_aktivasi,
		];
		if(!$this->model_common->save_sekolah($data)) {
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
		$sekolah_id     = trim($input['sekolah_id']);
		$sekolah_nama   = trim($input['sekolah_nama']); 
		$sekolah_kode  = trim($input['sekolah_kode']);
		$sekolah_alamat  = trim($input['sekolah_alamat']);
		$sekolah_phone  = trim($input['sekolah_phone']);
		$token  = trim($input['xsrf_token']);

		if($this->csrfsimple->checkToken($token) === false) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		if(empty($sekolah_nama)) {
				http_response_code(422);
				$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
				echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
				return;
		}
		
		// update action
		$data = [
				'sekolah_id'    	=> $sekolah_id,
				'sekolah_nama'    	=> $sekolah_nama,
				'sekolah_kode'  	=> $sekolah_kode,
				'sekolah_alamat'    => $sekolah_alamat,
				'sekolah_phone'		=> $sekolah_phone,
		];
		if(!$this->model_common->modify_sekolah($data)) {
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
					$this->db->where_in('sekolah_id', $input['data']);
			else
					$this->db->where('sekolah_id', $input['data']);
			if(!$this->db->delete('sekolah')) {
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
 
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
	
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
	
		return $randomString;
	}
}
