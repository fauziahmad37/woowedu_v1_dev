<?php
ini_set('memory_limit', -1);
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '500M');



class Materi extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $headers = getallheaders();
        if(!empty($headers['Authorization']))
        {
            $is_auth = check_auth($headers['Authorization']);
        }
        else
            check_Loggin();
        $this->load->model(['model_common', 'model_materi', 'model_subject']); 
		$this->load->helper('url');
		$this->load->helper(['slug', 'tgl']);
		$this->load->helper('assets');	
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
    }

    public function getAll() {
        $draw   = $this->input->get('draw');
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$filter = $this->input->get('columns');
        // data
        $record = $this->model_materi->getAll($filter, $limit, $offset);
        $data = [
            'draw'            => $draw,
            'data'            => $record,
            'recordTotal'     => $this->db->count_all_results('materi'),
            'recordsFiltered' =>  $this->model_materi->countAll($filter)
        ];

        echo json_encode($data, JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT);
        exit();
    }

    public function save() {
        
        $tema_title         = trim($this->input->post('a_materi_tema_title', TRUE));
        $sub_tema_title     = trim($this->input->post('a_materi_sub_tema_title', TRUE));
        $no_urut            = trim($this->input->post('a_materi_no_urut', TRUE));
        $title              = trim($this->input->post('a_materi_title', TRUE));
        $subject            = trim($this->input->post('a_materi_subject', TRUE));
        //$parent             = trim($this->input->post('a_materi_parent', TRUE));
        //$require            = trim($this->input->post('a_materi_require', TRUE));
       // $teacher      = trim($this->input->post('a_materi_teacher', TRUE));
        $ava_date           = date('Y-m-d');//trim($this->input->post('a_materi_date', TRUE));
        $description        = trim($this->input->post('a_materi_note', TRUE));
        $subject_name       = trim($this->input->post('a_materi_subject_text', TRUE));
        // video
        $video_name         = $_FILES['a_materi_video']['name'];
        $video_size         = $_FILES['a_materi_video']['size']; 
 
        header('Content-Type: application/json');

    //    if(empty($title) || empty($subject) || empty($teacher) || empty($ava_date) || empty($video_name)) 
        if(empty($title) || empty($subject) || empty($no_urut) || empty($tema_title) || empty($sub_tema_title)) 
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }
/*
        if(!validateDate($ava_date))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => 'Data yang di input tidak valid'];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }*/

        $this->db->where('LOWER(title) = ', strtolower(trim($title)));
        $_exists = $this->db->get_where('materi', ['subject_id' => $subject])->num_rows();

        if($_exists > 0)
        {
            http_response_code(422);
			$m = ['err_status' => 'error', 'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')];
			exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }

        // UPLOAD FILE
       // $dir = 'assets/files/upload/videos';
       $dir = '../assets/files/materi/'; 
          // $dir ='uploads/materi/';  

        // cek if dir is exists, if not then make the directory and set permission to be accessible
        if(!is_dir($dir))
            @mkdir($dir, 0777);
        // set name for the videofile
				
				$_n_subject_name =  str_replace(array(' ','"'),array("_","_"),strtolower($subject_name));
				$_n_title =  str_replace(array(' ','"'),array("_","_"),strtolower($title));
				
        $filename = $dir.DIRECTORY_SEPARATOR.$_n_subject_name.'-'.$_n_title;
        // get the extension
        $ext = pathinfo(basename($video_name), PATHINFO_EXTENSION);
        // now let's move the uploaded file to the directory
//echo $_FILES['a_materi_video']['tmp_name'].'=='. FCPATH.$filename.'.'.$ext;
        $move = move_uploaded_file($_FILES['a_materi_video']['tmp_name'], FCPATH.$filename.'.'.$ext);
        if(!$move) 
        {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }


        $data = [
            'tema_title'        => $tema_title,  
            'sub_tema_title'    => $sub_tema_title,  
			'no_urut'           => $no_urut, 
            'title'             => $title,  
            'subject_id'        => $subject, 
            'available_date'    => $ava_date, 
            'materi_file'       => $_n_subject_name.'-'.$_n_title.'.'.$ext,
            'note'              => $description
        ];
        //if(!empty($parent)) $data['parent_id']=$parent;
        //if(!empty($require)) $data['materi_require']=$require; 

        if(!$this->db->insert('materi', $data))
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
    }

    public function edit() {
        $id           = trim($this->input->post('a_id', TRUE));
        $tema_title        = trim($this->input->post('a_materi_tema_title', TRUE));
        $sub_tema_title        = trim($this->input->post('a_materi_sub_tema_title', TRUE));	
        $no_urut        = trim($this->input->post('a_materi_no_urut', TRUE));			
        $title        = trim($this->input->post('a_materi_title', TRUE));
        $subject      = trim($this->input->post('a_materi_subject', TRUE));
        //$parent      = trim($this->input->post('a_materi_parent', TRUE));
        //$require      = trim($this->input->post('a_materi_require', TRUE));
        //$teacher      = trim($this->input->post('a_materi_teacher', TRUE));
        $ava_date     = trim($this->input->post('a_materi_date', TRUE));
        $description  = trim($this->input->post('a_materi_note', TRUE));
        $subject_name = trim($this->input->post('a_materi_subject_text', TRUE));
        // vidceo
        $video_name   = $_FILES['a_materi_video']['name'];
        $video_size   = $_FILES['a_materi_video']['size'];
        $data         = [];
        header('Content-Type: application/json');

        if(empty($title) || empty($subject) ) 
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }
/*
        if(!validateDate($ava_date))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => 'Data yang di input tidak valid'];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }*/

        $this->db->where('LOWER(title) = ', strtolower(trim($title)));
        $this->db->where('materi_id <> ', $id);
        $_exists = $this->db->get_where('materi', ['subject_id' => $subject])->num_rows();

        if($_exists > 0)
        {
            http_response_code(422);
			$m = ['err_status' => 'error', 'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')];
			exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }

        // UPLOAD FILE
        // cek if request has upload
        $file = NULL;
        if(!empty(trim($_FILES['a_materi_video']['name'])))
        {
            $dir = '../assets/files/materi/'; 
        
            // set name for the videofile
            $filename = $dir.DIRECTORY_SEPARATOR.str_replace([' ', ':'], '_', strtolower($subject_name)).'-'.str_replace([' ', ':'], '_', strtolower($title));
            // get the extension
            $ext = pathinfo(basename($video_name), PATHINFO_EXTENSION);
            // cek if file exists, if true then delete file
            if(file_exists(basename(FCPATH.$filename.'.'.$ext)))
                unlink(FCPATH.$filename.'.'.$ext);
            // now let's move the uploaded file to the directory
            $move = move_uploaded_file($_FILES['a_materi_video']['tmp_name'], FCPATH.$filename.'.'.$ext);
            if(!$move) 
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                return;
            }

            $file = basename($filename.'.'.$ext);
        }

        $data = [
            'tema_title'        => $tema_title,  
            'sub_tema_title'    => $sub_tema_title,  
			'no_urut'           => $no_urut, 
		    'title'             => $title, 
            'subject_id'        => $subject,
            'available_date'    => date('Y-m-d'),
            'note'              => $description
        ];

        if(!empty($file))
            $data['materi_file'] = $file;

			//	if(!empty($parent)) $data['parent_id']=$parent;
			//	if(!empty($require)) $data['materi_require']=$require;
        if(!$this->db->update('materi', $data, ['materi_id' => $id]))
        {

			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'ext' => $data];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }

    public function delete() {
		header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if($input['isBulk'] == 1)
            $this->db->where_in('materi_id', $input['data']);
        else
            $this->db->where('materi_id', $input['data']);

        if(!$this->db->delete('materi')) {
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
        $teacher = $this->model_common->get_all_teacher();
        $subject = $this->model_subject->getAll();

        header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET');
        $metd = ['POST', 'GET'];
        if(!in_array($_SERVER['REQUEST_METHOD'], $metd)) {
            http_response_code(405);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
            echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
            return;
        }

        if(!is_dir(FCPATH.'assets/files/upload/materi/excel'))
            @mkdir(FCPATH.'assets/files/upload/materi/excel', 0777);

        $config['upload_path'] = FCPATH.'assets/files/upload/materi/excel';

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
        $nsd = [];
        foreach($excelRows as $exc) 
        {
            if($exc[0] === '1') continue;
            $nsd[] = [
                'title'             => $exc[0],
                'subject_id'        => array_search($exc[1], array_column($subject, 'code')),
                'teacher_id'        => array_search($exc[2], array_column($teacher, 'nik')),
                'available_date'    => $exc[3],
                'note'              => $exc[4],
            ];
            // if($this->db->get_where('subject', $exc[0])->num_rows() > 0) {
            //     $nsd['edit_at'] = date('Y-m-d H:i:s');
            //     $nsd['edit_by'] = $this->session->userdata('username');
            //     if($this->db->update('subject', $exc[0], $nsd))
            //         $prog += 1;
            // } else {
            //     $nsd['create_by'] = $this->session->userdata('username');
            //     if($this->db->insert('subject', $nsd))
            //         $prog += 1;
            // }
            // echo json_encode(['total' => count($excelRows), 'prog' => $prog]);
            // ob_flush();
            // $n++;
        }
        ob_end_clean();

        // http_response_code(200);
		// $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
		// echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		// exit;
    }
    // check mport progrss
    public function importProgress() {
        json_encode($_SESSION['PHP_SESSION_UPLOAD_PROGRESS']);
    }
    // end import data



    public function importsoal() {
        require_once APPPATH.'third_party'.DIRECTORY_SEPARATOR.'xlsx'.DIRECTORY_SEPARATOR.'SimpleXLSX.php';
        $this->load->model('model_subject'); 
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


				$subject = $_POST['subject_id'];
				$materi = $_POST['materi_id'];
        $zip_name   = $_FILES['upload-soal-zip']['name']; 

				//$dir = 'assets/files/upload/soal/';    
				$dir =  '../web/uploads/soal/'; 
        $filename = $dir.DIRECTORY_SEPARATOR.'tempzip';    
        $ext = pathinfo(basename($zip_name), PATHINFO_EXTENSION);  
        $move = move_uploaded_file($_FILES['upload-soal-zip']['tmp_name'], FCPATH.$filename.'.'.$ext);
				if($move) { 
					$zip = new ZipArchive; 
					$res = $zip->open($filename.'.'.$ext);
					if ($res === TRUE) { 
					//	$zip->extractTo('assets/files/upload/soal/files/'); 
						$zip->extractTo('../web/uploads/soal/'); 
						$zip->close(); 
					} 
				} 
				unlink($filename.'.'.$ext);
        $config['upload_path'] = FCPATH.'assets/files/upload/soal/excel';
        $config['allowed_types'] = 'xlsx';
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('upload-soal-file')) {
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
       // echo json_encode($excelRows);

        foreach($excelRows as $exc) 
        {
            if($exc[0] === '1') continue;

            if(empty($exc[0]) || empty($exc[1]) || empty($exc[2]) || empty($exc[3]) || empty($exc[4]) || empty($exc[7]))
            {
                http_response_code(422);
                $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
                exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
                break;
            }

            $_qFile =  'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'soal'.DIRECTORY_SEPARATOR.'files';

            if(!empty($exc[6]) && !file_exists(FCPATH.$_qFile))
                @mkdir($_qFile, 0777, TRUE);

            $nsd = [
                'code'              => $exc[0],
                'subject_id'        => $subject, 
                'materi_id'        => $materi, 
                'category'        => array_search($exc[3], $category), 
                'type'              => array_search($exc[4],$tipe_soal),
                'question'          => $exc[5],
                'question_file'     => !empty($exc[6]) ? $exc[6] : NULL,
                'answer'            => $exc[7],
                'choice_a'          => !empty($exc[8]) ? $exc[8] : NULL,
                'choice_b'          => !empty($exc[9]) ? $exc[9] : NULL,
                'choice_c'          => !empty($exc[10]) ? $exc[10] : NULL,
                'choice_d'          => !empty($exc[11]) ? $exc[11] : NULL,
                'choice_e'          => !empty($exc[12]) ? $exc[12] : NULL,
                'choice_f'          => !empty($exc[13]) ? $exc[13] : NULL,
                'choice_a_file'     => !empty($exc[14]) ?  $exc[14] : NULL,
                'choice_b_file'     => !empty($exc[15]) ?  $exc[15] : NULL,
                'choice_c_file'     => !empty($exc[16]) ? $exc[16] : NULL,
                'choice_d_file'     => !empty($exc[17]) ?  $exc[17] : NULL,
                'choice_e_file'     => !empty($exc[18]) ?  $exc[18] : NULL,
                'choice_f_file'     => !empty($exc[19]) ?  $exc[19] : NULL
            ];
          //  if($this->db->get_where('soal', ['code' => $exc[0]])->num_rows() > 0) {
            if($this->db->get_where('soal', ['question' => $exc[5]])->num_rows() > 0) {
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

	public function getAllGlobal() {
        $draw   = $this->input->get('draw');
		$limit  = $this->input->get('length');
		$offset = $this->input->get('start');
		$filter = $this->input->get('columns');
        // data
        $record = $this->model_materi->getAllGlobal($filter, $limit, $offset);
        $data = [
            'draw'            => $draw,
            'data'            => $record,
            'recordTotal'     => $this->db->count_all_results('materi_global'),
            'recordsFiltered' =>  $this->model_materi->num_all_global($filter)
        ];

        echo json_encode($data, JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT);
        exit();
    }

	public function delete_global() {
		header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if($input['isBulk'] == 1)
            $this->db->where_in('materi_global_id', $input['data']);
        else
            $this->db->where('materi_global_id', $input['data']);

        if(!$this->db->delete('materi_global')) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success')];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
	}

	public function create_folder(){
		header('Content-Type: application/json');

		$post = $this->input->post();

		$data = [
			'title'				=> $post['folder_name'],
			'parent_id'			=> isset($post['materi_global_id']) ? $post['materi_global_id'] : null,
			'materi_type'		=> 0
		];

		$this->db->insert('materi_global', $data);

		http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success')];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
	}
}
