<?php

ini_set('memory_limit', '4000M');
use mikehaertl\wkhtmlto\Pdf;

class Subject extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        //check_Loggin();
        $this->load->model(['model_common', 'model_subject']); 
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
    }

    public function getAll() {
        $draw = $this->input->post('draw', TRUE);
        $limit = $this->input->post('length', TRUE);
        $offset = $this->input->post('start', TRUE);
        $filters = $this->input->post('columns');
		$filters[1] = isset($_GET['sekolah_id']) ? $_GET['sekolah_id'] : '';
		$filters[2] = isset($_GET['class_level_id']) ? $_GET['class_level_id'] : '';

        $data = $this->model_subject->getAll($filters, $limit, $offset);
        
        $resp = [
            'draw' => $draw,
            'data' => $data,
            // 'recordsTotal' => $this->db->count_all_results('subject'),
			'recordsTotal' => $this->model_subject->countAll($filters),
            'recordsFiltered' => $this->model_subject->countAll($filters)
        ];

        echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    public function save() {
        $code = trim($this->input->post('a_subject_code', TRUE));
        $name = trim($this->input->post('a_subject_name', TRUE));
        $class = trim($this->input->post('a_subject_class', TRUE));
        $sekolah_id = trim($this->input->post('sekolah_id', TRUE));

        // poster
        // $thumbnail_name   = $_FILES['thumb-file']['name'];
        // $thumbnail_size   = $_FILES['thumb-file']['size']; 
				
        // $poster_name   = $_FILES['detail-file']['name'];
        // $poster_size   = $_FILES['detail-file']['size']; 				
				
				
        header('Content-Type: application/json');

        if(empty($code) || empty($name) || empty($class))
        {
            http_response_code(422);
            $msg = ['err_status' => 'ersdsadror', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        $countNama = $this->db->get_where('subject', ['subject_name' => $name, 'sekolah_id' => $sekolah_id])->num_rows();
        $countCode = $this->db->get_where('subject', ['code' => $code, 'sekolah_id' => $sekolah_id])->num_rows();

        if($countCode > 0 || $countNama > 0)
        {
            http_response_code(422);
			$m = [
					'err_status' => 'error', 
					'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
				];
			exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }


        // UPLOAD FILE
 
    //    $dir1 = '../web/uploads/subject/thumbnail/';  
    //    $dir2 = '../web/uploads/subject/';  
	// 			if(!is_dir($dir1))  @mkdir($dir1, 0777); 
	// 			if(!is_dir($dir2))  @mkdir($dir2, 0777); 
				
    //     $filename1 = $dir1.DIRECTORY_SEPARATOR.str_replace(' ', '_', strtolower($name));  
    //     $filename2 = $dir2.DIRECTORY_SEPARATOR.str_replace(' ', '_', strtolower($name));  
        // $ext1 = pathinfo(basename($thumbnail_name), PATHINFO_EXTENSION); 
        // $ext2 = pathinfo(basename($poster_name), PATHINFO_EXTENSION); 
        // $move1 = move_uploaded_file($_FILES['thumb-file']['tmp_name'], FCPATH.$filename1.'.'.$ext1);
        // $move2 = move_uploaded_file($_FILES['detail-file']['tmp_name'], FCPATH.$filename2.'.'.$ext2);
 
				
        $data = [
            'code'              => $code,
            'subject_name'      => $name,
			'sekolah_id'		=> $sekolah_id,
            'class_level_id'    => $class,
            // 'thumbnail_pic'     => str_replace(' ', '_', strtolower($name)).'.'.$ext1,
            // 'detail_pic'        => str_replace(' ', '_', strtolower($name)).'.'.$ext2,
			'create_by'		=> $this->session->userdata('username')
        ];


        if(!$this->db->insert('subject', $data))
        {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        // $id = $this->m_common->getLastId('id', 'wahana');
        // $this->m_common->insertlog('Insert Wahana - ID : '.$id);

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }


    public function edit() {
        $id   = trim($this->input->post('a_id', TRUE));
        $code = trim($this->input->post('a_subject_code', TRUE));
        $name = trim($this->input->post('a_subject_name', TRUE));
        $class = trim($this->input->post('a_subject_class', TRUE));
		$sekolah_id = trim($this->input->post('sekolah_id', TRUE));

        $thumbnail_name   = isset($_FILES['thumb-file']['name']) ? $_FILES['thumb-file']['name'] : null;
        $thumbnail_size   = isset($_FILES['thumb-file']['size']) ? $_FILES['thumb-file']['size'] : null; 
				
        $poster_name   = isset($_FILES['detail-file']['name']) ? $_FILES['detail-file']['name'] : null;
        $poster_size   = isset($_FILES['detail-file']['size']) ? $_FILES['detail-file']['size'] : null; 		
				
				
        header('Content-Type: application/json');

        if(empty($id) || empty($code) || empty($name) || empty($class))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

		// code tidak boleh lebih dari 10 karakter
		if(strlen($code) > 10) {
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => 'Kode mapel tidak boleh lebih dari 10 karakter'];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

        $this->db->where('subject_id !=', $id);
        $countNama = $this->db->get_where('subject', ['subject_name' => $name, 'sekolah_id' => $sekolah_id])->num_rows();
        $this->db->where('subject_id !=', $id);
        $countCode = $this->db->get_where('subject', ['code' => $code, 'sekolah_id' => $sekolah_id])->num_rows();

        if($countCode > 0 || $countNama > 0)
        {
            http_response_code(422);
			$m = [
					'err_status' => 'error', 
					'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
				];
			exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }


        // UPLOAD FILE
 
       $dir1 = '../web/uploads/subject/thumbnail/';  
       $dir2 = '../web/uploads/subject/';  
				if(!is_dir($dir1))  @mkdir($dir1, 0777); 
				if(!is_dir($dir2))  @mkdir($dir2, 0777); 
				
        $filename1 = $dir1.DIRECTORY_SEPARATOR.str_replace(' ', '_', strtolower($name));  
        $filename2 = $dir2.DIRECTORY_SEPARATOR.str_replace(' ', '_', strtolower($name));  
        $ext1 = pathinfo(basename($thumbnail_name), PATHINFO_EXTENSION); 
        $ext2 = pathinfo(basename($poster_name), PATHINFO_EXTENSION); 
		if($thumbnail_name){
			$move1 = move_uploaded_file($_FILES['thumb-file']['tmp_name'], FCPATH.$filename1.'.'.$ext1);
			$move2 = move_uploaded_file($_FILES['detail-file']['tmp_name'], FCPATH.$filename2.'.'.$ext2);
		}
				
        $data = [
            'code'              => $code,
            'subject_name'      => $name,
            'class_level_id'    => $class,
            'thumbnail_pic'     => str_replace(' ', '_', strtolower($name)).'.'.$ext1,
            'detail_pic'        => str_replace(' ', '_', strtolower($name)).'.'.$ext2
        ];

        if(!$this->db->update('subject', $data, ['subject_id' => $id]))
        {

			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        // $id = $this->m_common->getLastId('id', 'wahana');
        // $this->m_common->insertlog('Insert Wahana - ID : '.$id);

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }

    public function delete() {
		header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if($input['isBulk'] == 1)
            $this->db->where_in('subject_id', $input['data']);
        else
            $this->db->where('subject_id', $input['data']);

        if(!$this->db->delete('subject')) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success')];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
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

        $config['upload_path'] = FCPATH.'assets/files/upload/subject';
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
			$sekolah = $this->db->where('lower(sekolah_nama)', strtolower(trim($exc[3])))->get('sekolah')->row_array();

            if($exc[0] === '1') continue;
            $nsd = [
                'code'          => $exc[0],
                'subject_name'  => $exc[1],
                // 'class_level_id'      => $this->model_subject->getIdByClassName(trim($exc[2])), // hide karna di add kelas UI tidak ada input nama kelas
				'class_level_id' => $exc[2],
				'sekolah_id' => $sekolah['sekolah_id'],
            ];
            if($this->db->get_where('subject', ['code' => $exc[0], 'sekolah_id' => $sekolah['sekolah_id']])->num_rows() > 0) {
                $nsd['edit_at'] = date('Y-m-d H:i:s');
                $nsd['edit_by'] = $this->session->userdata('username');

                if($this->db->update('subject', $nsd, ['code' => $exc[0], 'sekolah_id' => $sekolah['sekolah_id']]))
                    $prog += 1;
            } else {
                $nsd['create_by'] = $this->session->userdata('username');
                if($this->db->insert('subject', $nsd))
                    $prog += 1;
            }
            echo json_encode(['total' => count($excelRows), 'prog' => $prog]);
            ob_flush();
            $n++;
        }
        ob_end_clean();
    }
    // end import data

	 // import data subject teacher
	 public function importSubjectTeacher() {
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

        $config['upload_path'] = FCPATH.'assets/files/upload/subject';
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
			// ambil teacher id berdasarkan nik
			$teacher = $this->db->get_where('teacher', ['nik' => "$exc[0]" ])->row_array();
			$sekolah = $this->db->where('lower(sekolah_nama)', strtolower(trim($exc[3])))->get('sekolah')->row_array(); // get sekolah id

			// ubah kelas guru jadi array
			$mapelCodes = $exc[2];
			$mapelCodes = explode(',', $mapelCodes);

            if($exc[0] === '1') continue;

			// looping kode mapel
			foreach ($mapelCodes as $val) {
				// get subject id berdasarkan kode mapel
				$subject = $this->db->get_where('subject', ['lower(code)' => trim(strtolower($val)), 'sekolah_id' => $sekolah['sekolah_id'] ])->row_array();
				if(!$subject) continue;

				$nsd = [
					'teacher_id' => $teacher['teacher_id'],
					'subject_id'  => $subject['subject_id'],
					// 'class_level_id'      => $this->model_subject->getIdByClassName(trim($exc[2])), // hide karna di add kelas UI tidak ada input nama kelas
					// 'class_level_id' => $exc[2],
				];
				if($this->db->get_where('subject_teacher', $nsd)->num_rows() > 0) {
					$nsd['edit_at'] = date('Y-m-d H:i:s');
					$nsd['edit_by'] = $this->session->userdata('username');
	
					if($this->db->update('subject_teacher', $nsd, ['teacher_id' => $teacher['teacher_id'], 'subject_id'  => $subject['subject_id'],]))
						$prog += 1;
				} else {
					$nsd['create_by'] = $this->session->userdata('username');
					if($this->db->insert('subject_teacher', $nsd))
						$prog += 1;
				}
			}
			echo json_encode(['total' => count($excelRows), 'prog' => $prog]);
			ob_flush();
			$n++;
        }
        ob_end_clean();
    }
    // end import data subject teacher
}
