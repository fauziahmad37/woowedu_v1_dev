<?php

class Soal extends MY_Controller {

    public function __construct() 
	{
		parent::__construct(); 
		$this->load->model(['model_common', 'model_soal', 'model_exam']);
		//$this->load->model(['Model_settings']);
		$this->load->library(['csrfsimple', 'curl']);
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);

        $headers = getallheaders();

        if($headers['X-Requested-With'] == 'XMLHttpRequest') 
        {
            if(empty($headers['X-Type-Token']))
            {
                http_response_code(401);
                $msg = ['err_status' => 'error', 'message' => 'Anda tidak memiliki akses'];
                die(json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
            }

            if(!check_token($headers['X-Type-Token'])) 
            {
                http_response_code(401);
                $msg = ['err_status' => 'error', 'message' => 'Anda tidak memiliki akses'];
                die(json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
            }
        }
        else
            check_Loggin();
	}

    public function getAll() {
        $draw   = $this->input->get('draw');
        $limit  = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_soal->getAll($filters, $limit, $offset);

        $_data = [
            'draw' => $draw,
            'data' => $data,
            // 'recordsTotal' => $this->db->count_all_results('soal'),
            'recordsTotal' => $this->model_soal->countAll($filters),
            'recordsFiltered' => $this->model_soal->countAll($filters)
        ];

        echo json_encode($_data, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    public function save() {
        $code = $this->input->post('a_soal_code', TRUE);
        //$no = $this->input->post('a_soal_no', TRUE);
        $mapel = $this->input->post('a_soal_subject', TRUE);
        //$class = $this->input->post('a_soal_class', TRUE);
        $soal = $this->input->post('a_soal_detail', TRUE);
        $type = $this->input->post('a_soal_type', TRUE);
        $jawaban = $this->input->post('a_soal_answer', TRUE);
        $pg = $this->input->post('pg');

        $_data = [];

        header('Content-Type: application/json');
        
        // empty validation
        if(empty($code) || empty($mapel) ||  empty($soal) || empty($jawaban) || empty($type))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        $checkCode = $this->db->get_where('soal', ['code' => $code])->num_rows();
        //$checkClassAndMapel = $this->db->get_where('soal', ['subject_id' => $mapel, 'class_id' => $class])->num_rows();

        if($checkCode > 0)
        {
            http_response_code(422);
            $m = [
                'err_status' => 'error', 
                'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
            ];
            echo json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }
        
        if(!empty($_FILES['a_soal_file']['name']))
        {
            $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
            
            if(!file_exists($_dir))
                @mkdir(FCPATH.$_dir, 0777, TRUE);
            
            // get type
            $ext = pathinfo(basename($_FILES['a_soal_file']['name']), PATHINFO_EXTENSION);
            // move upload file
            $file = move_uploaded_file($_FILES['a_soal_file']['tmp_name'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext);

            if(!$file)
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

            $_data['question_file'] = $_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext;
        }

        $call = [];

        if(isset($pg))
        {

            $val = array_column($pg, 'value');
            $img = array_column($_FILES['pg']['name'], 'file');

            if(count(array_filter($val)) === 0 && count(array_filter($img)) === 0)
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
                return;
            }

            $_i = 0;
            foreach($pg as $P)
            {
                //if(!isset($P['key'])) continue;

                $call[] = $P;

                if(!empty($P['value']));
                {
                    $_data['choice_'.$P['key']] = $P['value'];

                }

                if(!empty($_FILES['pg']['name'][$_i]['file']))
                {
                    $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
					
					if(!file_exists($_dir))
                		@mkdir(FCPATH.$_dir, 0777, TRUE);
                    
					$ext = pathinfo(basename($_FILES['pg']['name'][$_i]['file']), PATHINFO_EXTENSION);
                    // move upload file
                    $file = move_uploaded_file($_FILES['pg']['tmp_name'][$_i]['file'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext);
                    $filename = $_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext;
                    $_data['choice_'.$P['key'].'_file'] = $filename;
                }

                $_i++;

            }
        }
        
        $_data['code'] = $code;
        $_data['subject_id'] = $mapel;
    //    $_data['class_level_id'] = $class;
        $_data['question'] = $soal;
        $_data['answer'] = $jawaban;
        $_data['type'] = $type;

        if(!$this->db->insert('soal', $_data))
        {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
        //if(issets())
    }

    public function edit() {
        $_id = $this->input->post('a_id');
        $code = $this->input->post('a_soal_code', TRUE);
        //$no = $this->input->post('a_soal_no', TRUE);
        $mapel = $this->input->post('a_soal_subject', TRUE);
    //    $class = $this->input->post('a_soal_class', TRUE);
        $soal = $this->input->post('a_soal_detail', TRUE);
        $type = $this->input->post('a_soal_type', TRUE);
        $jawaban = $this->input->post('a_soal_answer', TRUE);
        $pg = $this->input->post('pg');

        $_data = [];

        header('Content-Type: application/json');
        
        // empty validation
        if(empty($_id) || empty($code) || empty($mapel) ||  empty($soal) || empty($jawaban) || empty($type))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        
        
        if(!empty($_FILES['a_soal_file']['name']))
        {
            $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
            
            if(!file_exists($_dir))
                @mkdir(FCPATH.$_dir, 0777, TRUE);
            
            // get type
            $ext = pathinfo(basename($_FILES['a_soal_file']['name']), PATHINFO_EXTENSION);

            if(file_exists(FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext))
                unlink(FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext);
            // move upload file
            $file = move_uploaded_file($_FILES['a_soal_file']['tmp_name'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext);

            if(!$file)
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

            $_data['question_file'] = $_dir.DIRECTORY_SEPARATOR.$code.'_file'.'.'.$ext;
        }

        $call = [];

        if(isset($pg))
        {
            $_i = 0;
            foreach($pg as $P)
            {
                //if(!isset($P['key'])) continue;

                $call[] = $P;

                if(!empty($P['value']));
                {
                    $_data['choice_'.$P['key']] = $P['value'];

                }

                if(!empty($_FILES['pg']['name'][$_i]['file']))
                {
                    $_dir = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.$code;
                    $ext = pathinfo(basename($_FILES['pg']['name'][$_i]['file']), PATHINFO_EXTENSION);
                    // move upload file
                    $filename = $_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext;
                    if(file_exists(FCPATH.$filename))
                        unlink(FCPATH.$filename);
                    $file = move_uploaded_file($_FILES['pg']['tmp_name'][$_i]['file'], FCPATH.$_dir.DIRECTORY_SEPARATOR.$code.'_'.$P['key'].'.'.$ext);
                    $_data['choice_'.$P['key'].'_file'] = $filename;
                }

                $_i++;

            }
        }
        
        $_data['code'] = $code;
        $_data['subject_id'] = $mapel;
      //  $_data['class_level_id'] = $class;
        $_data['question'] = $soal;
        $_data['answer'] = $jawaban;
        $_data['type'] = $type;

        if(!$this->db->update('soal', $_data, ['soal_id' => $_id]))
        {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
        //if(issets())
    }

    public function delete() {
		header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if($input['isBulk'] == 1)
            $this->db->where_in('soal_id', $input['data']);
        else
            $this->db->where('soal_id', $input['data']);

        if(!$this->db->delete('soal')) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success')];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
	}

    public function import() {
		$post = $this->input->post();
		$sekolahId = $post['sekolah_id'];
	
        require_once APPPATH.'third_party'.DIRECTORY_SEPARATOR.'xlsx'.DIRECTORY_SEPARATOR.'SimpleXLSX.php';
        $this->load->model('model_subject');

        $materi = $this->model_common->getMateriAll();
        $category = array(1=>'PS',2=>'PR',3=>'PH',4=>'PTS',5=>'PAS',6=>'REM');//$this->model_exam->getCategory();
				$tipe_soal = array(1=>'PG',2=>'Isian');
       // $kelas = $this->model_common->get_all_class_level();

        header('Access-Control-Allow-Origin: *');
				header('Access-Control-Allow-Methods: POST, GET');
        header('Content-Type: application/json');

        $metd = ['POST', 'GET'];
        if(!in_array($_SERVER['REQUEST_METHOD'], $metd)) {
            http_response_code(405);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
            echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
            return;
        }

        if(!is_dir(FCPATH.'assets/files/upload/soal/excel'))
            @mkdir(FCPATH.'assets/files/upload/soal/excel', 0777, TRUE);

		
        $config['upload_path'] = FCPATH.'assets/files/upload/soal/excel';
        $config['allowed_types'] = 'xlsx';
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('upload-file')) {
            http_response_code(422);
            $msg = ['err_status' => 'errorasdasd', 'message' => $this->upload->display_errors()];
            echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
            return;
        }
        $data = $this->upload->data();
        $xlsx = SimpleXLSX::parse($data['full_path']);
        $excelRows = $xlsx->rows();
        $n = 0; $prog = 0;
        unset($excelRows[0]); unset($excelRows[1]);
    
		// =========================== FILE SOAL ===========================
			$zip_name   = $_FILES['upload-zip']['name'];
			//$dir = 'assets/files/upload/soal/';
			$dir =  FCPATH.'assets/files/soal/'; 
			$filename = $dir.DIRECTORY_SEPARATOR.'tempzip';
			$ext = pathinfo(basename($zip_name), PATHINFO_EXTENSION);
			$move = move_uploaded_file($_FILES['upload-zip']['tmp_name'], $filename.'.'.$ext);
				
			if($move) {
				$zip = new ZipArchive;
				$res = $zip->open($filename.'.'.$ext);
				
				if ($res === TRUE) {
					$path_extract = FCPATH.'assets/files/soal/files/';
					$zip->extractTo($path_extract);
					$zip->close();
				}
			}
			
			unlink($filename.'.'.$ext);
		// =========================== FILE SOAL ===========================

        foreach($excelRows as $exc) 
        {
            if($exc[0] === '1') continue;

			// validasi row excel yang mandatory
            if(empty($exc[0]) || empty($exc[1]) || empty($exc[2]) || empty($exc[3]) || empty($exc[4]) || empty($exc[7]))
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
                exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
                break;
            }

            $_qFile =  'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.'files';

            if(!file_exists(FCPATH.$_qFile))
                @mkdir($_qFile, 0777, TRUE);
			
			// =========================== FILE SOAL ===========================
				$path_extract = FCPATH.'assets/files/soal/files/';
				$path_soal = FCPATH.'assets/files/soal/';

				// buat direktori soal menggunakan kode soal
				if(!file_exists($path_soal.$exc[0]))
					@mkdir($path_soal.$exc[0], 0777, TRUE);

				// pindahkan file soal ke direktori yang sudah dibuat
				if(!empty($exc[6])){
					$ext = pathinfo($path_soal.'files/'.$exc[6], PATHINFO_EXTENSION);
					$move = copy($path_extract.$exc[6], $path_soal.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_file.'.$ext);
					if($move)
						unlink($path_extract.$exc[6]);
				}


				// pindahkan file pilihan ganda ke direktori yang sudah dibuat
				if(!empty($exc[14])){
					$ext_a = pathinfo($path_extract.$exc[14], PATHINFO_EXTENSION);
					$move = copy($path_extract.$exc[14], $path_soal.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_a.'.$ext_a);
					if($move)
						unlink($path_extract.$exc[14]);
				}

				if(!empty($exc[15])){
					$ext_b = pathinfo($path_extract.$exc[15], PATHINFO_EXTENSION);
					$move = copy($path_extract.$exc[15], $path_soal.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_b.'.$ext_b);
					if($move)
						unlink($path_extract.$exc[15]);
				}

				if(!empty($exc[16])){
					$ext_c = pathinfo($path_extract.$exc[16], PATHINFO_EXTENSION);
					$move = copy($path_extract.$exc[16], $path_soal.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_c.'.$ext_c);
					if($move)
						unlink($path_extract.$exc[16]);
				}

				if(!empty($exc[17])){
					$ext_d = pathinfo($path_extract.$exc[17], PATHINFO_EXTENSION);
					$move = copy($path_extract.$exc[17], $path_soal.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_d.'.$ext_d);
					if($move)
						unlink($path_extract.$exc[17]);
				}

				if(!empty($exc[18])){
					$ext_e = pathinfo($path_extract.$exc[18], PATHINFO_EXTENSION);
					$move = copy($path_extract.$exc[18], $path_soal.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_e.'.$ext_e);
					if($move)
						unlink($path_extract.$exc[18]);
				}

				if(!empty($exc[19])){
					$ext_f = pathinfo($path_extract.$exc[19], PATHINFO_EXTENSION);
					$move = copy($path_extract.$exc[19], $path_soal.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_f.'.$ext_f);
					if($move)
						unlink($path_extract.$exc[19]);
				}

			// =========================== FILE SOAL ===========================
			

			// GET SUBJECT ID ============
			$filter[1] = $sekolahId;
			$filter[4]['search']['value'] = $exc[1];
			$subject = $this->model_subject->getAll($filter);
			
			if(!$subject) continue;
			
			$subjectId = array_column($subject, 'id_mapel')[0];
			// ===========================
			
            $nsd = [
                'code'              => $exc[0],
                // 'subject_id'     => $subject[array_search(trim($exc[1]), array_column($subject, 'nama_mapel'))]['id_mapel'], 
                'subject_id'        => $subjectId,
				'materi_id'        	=> $materi[array_search($exc[2], array_column($materi, 'title'))]['materi_id'], 
                'category'        	=> array_search($exc[3], $category), 
                'type'              => array_search($exc[4],$tipe_soal),
                'question'          => $exc[5],
                'question_file'     => !empty($exc[6]) ? 'assets/files/soal/'.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_file.'.$ext : NULL,
                'answer'            => $exc[7],
                'choice_a'          => !empty($exc[8]) ? $exc[8] : NULL,
                'choice_b'          => !empty($exc[9]) ? $exc[9] : NULL,
                'choice_c'          => !empty($exc[10]) ? $exc[10] : NULL,
                'choice_d'          => !empty($exc[11]) ? $exc[11] : NULL,
                'choice_e'          => !empty($exc[12]) ? $exc[12] : NULL,
                'choice_f'          => !empty($exc[13]) ? $exc[13] : NULL,
                'choice_a_file'     => !empty($exc[14]) ? 'assets/files/soal/'.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_a.'.$ext_a : NULL,
                'choice_b_file'     => !empty($exc[15]) ? 'assets/files/soal/'.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_b.'.$ext_b : NULL,
                'choice_c_file'     => !empty($exc[16]) ? 'assets/files/soal/'.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_c.'.$ext_c : NULL,
                'choice_d_file'     => !empty($exc[17]) ? 'assets/files/soal/'.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_d.'.$ext_d : NULL,
                'choice_e_file'     => !empty($exc[18]) ? 'assets/files/soal/'.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_e.'.$ext_e : NULL,
                'choice_f_file'     => !empty($exc[19]) ? 'assets/files/soal/'.$exc[0].DIRECTORY_SEPARATOR.$exc[0].'_f.'.$ext_f : NULL,
            ];
					//	var_dump($nsd);
            if($this->db->get_where('soal', ['code' => $exc[0]])->num_rows() > 0) {
         //   if($this->db->get_where('soal', ['question' => $exc[5]])->num_rows() > 0) {
                // $nsd['edit_at'] = date('Y-m-d H:i:s'); 
                // $nsd['edit_by'] = $this->session->userdata('username');
                if($this->db->update('soal', $nsd, ['code' => $exc[0]]))
                    $prog += 1;
            } else {
                // $nsd['create_by'] = $this->session->userdata('username');
                if($this->db->insert('soal', $nsd))
                    $prog += 1;
            }
            $_SESSION['UPLOAD_PROGRESS'] = ['total' => count($excelRows), 'prog' => $prog];
            ob_flush();
            $n++;
        }
        ob_end_clean();

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }
}
