<?php

class Orangtua extends MY_Controller {

    public function __construct() {
        parent::__construct();

		check_Loggin();
		$this->load->model(['model_common', 'model_parent']); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
    }

    public function index() {
        $data['pageTitle']	= 'Data Orang Tua';
		$data['tableName']	= 'Parents';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [  
            ['path' => 'assets/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_parent.js', 'defer' => true]
		]; 
        $data['page_css']	= [  
			'assets/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
			'assets/new/css/fpersonno.css'
		]; 
 
		$this->template->load('template', 'parent/index', $data);
    }

    /**
     * List all parents data
     *
     * @return void
     */
    public function list(): void {
        $draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');
        $data = $this->model_parent->get_all($limit, $offset, $filters);
        $count = $this->model_parent->count_all($filters);

        $json = [
            'draw' => $draw,
            'data' => $data, 
            // 'recordsTotal' => $this->db->count_all_results('parent'),
            'recordsTotal' => $count,
            'recordsFiltered' => $count
        ];

        header('Content-Type: application/json');
        echo json_encode($json, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    /**
     * Store new data in database
     *
     * @return void
     */
    public function store(): void {
		$post = $this->input->post();
        $username = $this->input->post('a_username', TRUE);
        $name = $this->input->post('a_full_name', TRUE);
        $address = $this->input->post('a_address', TRUE);
        $phone = $this->input->post('a_phone', TRUE);
        $email = $this->input->post('a_email', TRUE);
        $gender = $this->input->post('a_gender', TRUE);
        $children = $this->input->post('a_children', TRUE);

        header('Content-Type: application/json');

        if(empty($username) || empty($name))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

		$children = explode(',', $children);
        $data = [
            'username' => $username, 
            'name'     => $name,
            'address'  => $address,
            'phone'     => $phone,
            'email'     => $email,
			'gender'	=> $gender,
			'sekolah_id'	=> $_SESSION['sekolah_id'],
			'children' => $children
        ];

        

        // $this->db->trans_start();
        // $this->db->insert('parent', $data);
        // $id = $this->db->insert_id();
        
		// insert to user
		$this->model_common->save_parent($data);


        // foreach($children as $child)
        //     $this->db->update('student', ['parent_id' => $id], ['student_id' => intval($child)]);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        $this->db->trans_commit();
        
        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken(), 'POST' => $post];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;

    }

    /**
     * Edit An Exitsting data in database
     *
     * @return void
     */
    public function edit(): void {
        $id = $this->input->post('a_parent_id', TRUE);
        $username = $this->input->post('a_username', TRUE);
        $name = $this->input->post('a_full_name', TRUE);
        $address = $this->input->post('a_address', TRUE);
        $phone = $this->input->post('a_phone', TRUE);
        $email = $this->input->post('a_email', TRUE);
        $children = $this->input->post('a_children', TRUE);

        header('Content-Type: application/json');

        if(empty($username) || empty($name))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required'), 'token' => $this->csrfsimple->genToken()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        $data = [
            'username' => $username, 
            'name'     => $name,
            'address'  => $address,
            'phone'     => $phone,
            'email'     => $email,
            'sekolah_id'	=> $_SESSION['sekolah_id'],
        ];

        $children = explode(',', $children);

        $this->db->trans_start();
        $this->db->update('parent', $data, ['parent_id' => intval($id)]);
        
        foreach($children as $child)
            $this->db->update('student', ['parent_id' => $id], ['student_id' => intval($child)]);
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        $this->db->trans_commit();
        
        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken(), 'POST' => $this->input->post()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }

    /**
     * Delete One Or More Data in Database
     *
     * @return void
     */
    public function delete(): void {
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
			// if($this->csrfsimple->checkToken($input['xsrf_token']) === false) {
			// 		http_response_code(422);
			// 		$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_csrf_token_false'), 'token' => $this->csrfsimple->genToken()];
			// 		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			// 		return;
			// }

            if($this->db->get_where('student', ['parent_id' => $input['data']])->num_rows() > 0)
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

			if($input['bulk'])
					$this->db->where_in('parent_id', $input['data']);
			else
					$this->db->where('parent_id', $input['data']);
			if(!$this->db->delete('parent')) {
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

		$config['upload_path'] = FCPATH.'assets/files/upload/parent';
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

			$sekolah = $this->db->where('lower(sekolah_nama)', strtolower($exc[6]))->get('sekolah')->row_array();
			$nsd = [
				'username' => $exc[0],
				'name'  =>  "$exc[1]",
				'address'  => $exc[2],
				'phone'   => $exc[3],
				'email'   => $exc[4],
				'gender'   => "$exc[5]",
				'sekolah_id' => $sekolah['sekolah_id'],
			];
			if($this->db->get_where('parent', array('username'=>"$exc[0]"))->num_rows() > 0) {
				// update data ortu
				$nsd['edit_at'] = date('Y-m-d H:i:s');
				$nsd['edit_by'] = $this->session->userdata('username');
				if($this->db->update('parent', $nsd, array('username'=>"$exc[0]")))
						$prog += 1;

				// update parent murid
				// $student = $this->db->where('nis', $exc[1])->get('student')->row_array();
				// $parent = $this->db->where('username', "$exc[0]")->get('parent')->row_array();
				// if($student) $this->db->update('student', ['parent_id' => $parent['parent_id']], ['nis'=>$exc[1]]);
			} else {
				// insert data ortu
				$nsd['create_by'] = $this->session->userdata('username');
				if($this->model_common->save_parent($nsd))
						$prog += 1;

				// update parent murid
				// $student = $this->db->where('nis', $exc[1])->get('student')->row_array();
				// $parent = $this->db->where('username', "$exc[0]")->get('parent')->row_array();
				// if($student) $this->db->update('student', ['parent_id' => $parent['parent_id']], ['nis'=>$exc[1]]);
			}
			echo json_encode(['total' => count($excelRows), 'prog' => $prog]);
			ob_flush();
			$n++;
		}
		ob_end_clean();
	}
	// end import data 
}
