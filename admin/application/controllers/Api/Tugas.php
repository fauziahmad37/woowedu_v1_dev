<?php

class Tugas extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $headers = getallheaders();

        if(!empty($headers['Authorization']))
        {
            $is_auth = check_auth($headers['Authorization']);

            if(!$is_auth) {
                http_response_code(403);
                $msg = ['err_status' => 'error', 'message' => 'Anda tidak dapat mengakses halaman ini !!!'];
                echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            }
        }
        else
            check_Loggin();
        $this->load->model(['model_common', 'model_tugas', 'model_materi']);
		$this->load->model(['Model_settings']);
		$this->load->library(['csrfsimple', 'curl']);
        $this->load->helper('url');
		$this->load->helper(['slug', 'tgl']);
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
    }


    public function getAll() {
        $draw   = $this->input->get('draw');
        $limit  = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $id = $this->input->get('teacher') ?? NULL;

        $data = $this->model_tugas->getAll($filters, $limit, $offset, $id);

        $_data = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('task'),
            'recordsFiltered' => $this->model_tugas->countAll($filters, $id)
        ];

        echo json_encode($_data, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    public function save() {
        $code    = $this->input->post('a_tugas_code', TRUE);
        //$no = $this->input->post('a_soal_no', TRUE);
        $materi  = $this->input->post('a_tugas_materi', TRUE);
        $class   = $this->input->post('a_tugas_class', TRUE);
        $guru    = $this->input->post('a_tugas_guru', TRUE);
        $materiText  = $this->input->post('a_tugas_materi_text', TRUE);
        $classText   = $this->input->post('a_tugas_class_text', TRUE);
        $guruText    = $this->input->post('a_tugas_guru_text', TRUE);

        $start   = $this->input->post('a_tugas_start', TRUE);
        $end     = $this->input->post('a_tugas_end', TRUE);
        $periode = $this->input->post('a_tugas_periode', TRUE);
        $detail  = $this->input->post('a_tugas_detail', TRUE);

        $_data = [];

        header('Content-Type: application/json');

        // empty validation
        if(empty($code) || empty($materi) || empty($class) || empty($start) || empty($end) || empty($detail) || empty($guru))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }

        $checkCode = $this->db->get_where('task', ['code' => $code])->num_rows();
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

         // date validation
         if(!validateDate($start, 'Y-m-d H:i:s') || !validateDate($end, 'Y-m-d H:i:s'))
         {
             http_response_code(422);
             $msg = ['err_status' => 'error', 'message' => 'Data yang di input tidak valid'];
             echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
             return;
         }

        if(!empty($_FILES['a_tugas_file']['name']))
        {
            $dir = 'assets/files/tugas/'.$classText;

            if(!file_exists(FCPATH.$dir))
                @mkdir(FCPATH.$dir, 0777, TRUE);

              // get type
              $ext = pathinfo(basename($_FILES['a_tugas_file']['name']), PATHINFO_EXTENSION);
              // move upload file
              $filename = $dir.DIRECTORY_SEPARATOR.$classText.'_'.$materiText.'.'.$ext;
              $file = move_uploaded_file($_FILES['a_tugas_file']['tmp_name'], FCPATH.$filename);
  
              if(!$file)
              {
                  http_response_code(422);
                  $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
                  echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                  return;
              }

            $_data['task_file'] = $filename;
        }

        $_data['materi_id'] = $materi;
        $_data['class_id'] = $class;
        $_data['teacher_id'] = $guru;
        $_data['code'] = $code;
        $_data['note'] = $detail;
        $_data['available_date'] = $start;
        $_data['due_date'] = $end;

        if(!$this->db->insert('task', $_data))
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
        $_id     = $this->input->post('a_id', TRUE);
        $code    = $this->input->post('a_tugas_code', TRUE);
        //$no = $this->input->post('a_soal_no', TRUE);
        $materi  = $this->input->post('a_tugas_materi', TRUE);
        $class   = $this->input->post('a_tugas_class', TRUE);
        $guru    = $this->input->post('a_tugas_guru', TRUE);
        $materiText  = $this->input->post('a_tugas_materi_text', TRUE);
        $classText   = $this->input->post('a_tugas_class_text', TRUE);
        $guruText    = $this->input->post('a_tugas_guru_text', TRUE);

        $start   = $this->input->post('a_tugas_start', TRUE);
        $end     = $this->input->post('a_tugas_end', TRUE);
        $periode = $this->input->post('a_tugas_periode', TRUE);
        $detail  = $this->input->post('a_tugas_detail', TRUE);

        $_data = [];

        header('Content-Type: application/json');

        // empty validation
        if(empty($code) || empty($materi) || empty($class) || empty($start) || empty($end) || empty($periode) || empty($detail) || empty($guru) || empty($_id))
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }


        $this->db->where('task_id <>', $_id);
        $checkCode = $this->db->get_where('task', ['code' => $code])->num_rows();
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

         // date validation
         if(!validateDate($start, 'Y-m-d H:i:s') || !validateDate($end, 'Y-m-d H:i:s'))
         {
             http_response_code(422);
             $msg = ['err_status' => 'error', 'message' => 'Data yang di input tidak valid'];
             echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
             return;
         }

        if(!empty($_FILES['a_tugas_file']['name']))
        {
            $dir = 'assets/files/tugas/'.$classText;

            if(!file_exists(FCPATH.$dir))
                @mkdir(FCPATH.$dir, 0777, TRUE);

          

              // get type
              $ext = pathinfo(basename($_FILES['a_tugas_file']['name']), PATHINFO_EXTENSION);
              // move upload file
              $filename = $dir.DIRECTORY_SEPARATOR.$classText.'_'.$materiText.'.'.$ext;

              if(file_exists(FCPATH.$filename))
                unlink(FCPATH.$filename);

              $file = move_uploaded_file($_FILES['a_tugas_file']['tmp_name'], FCPATH.$filename);
  
              if(!$file)
              {
                  http_response_code(422);
                  $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
                  echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
                  return;
              }

            $_data['task_file'] = $filename;
        }

        $_data['materi_id'] = $materi;
        $_data['class_id'] = $class;
        $_data['teacher_id'] = $guru;
        $_data['code'] = $code;
        $_data['note'] = $detail;
        $_data['available_date'] = $start;
        $_data['due_date'] = $end;

        if(!$this->db->update('task', $_data, ['task_id' => $_id]))
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

    public function delete() {
		header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if($input['isBulk'] == 1)
            $this->db->where_in('task_id', $input['data']);
        else
            $this->db->where('task_id', $input['data']);

        if(!$this->db->delete('task')) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success')];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
	}

    public function getAllCategories() {
        $model = $this->model_exam->getCategory();
        echo json_encode($model, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
        exit();
    }


    // import data
    public function import() {
        require_once APPPATH.'third_party'.DIRECTORY_SEPARATOR.'xlsx'.DIRECTORY_SEPARATOR.'SimpleXLSX.php';
        $teacher = $this->model_common->get_all_teacher();
        $materi = $this->model_materi->getAll();
        $kelas = $this->model_common->get_all_class();

        header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET');
        $metd = ['POST', 'GET'];
        if(!in_array($_SERVER['REQUEST_METHOD'], $metd)) {
            http_response_code(405);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_mismatch_method')];
            echo json_encode($msg, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_APOS);
            return;
        }

        if(!is_dir(FCPATH.'assets/files/upload/tugas/excel'))
            @mkdir(FCPATH.'assets/files/upload/tugas/excel', 0777);

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
        foreach($excelRows as $exc) 
        {
            if($exc[0] === '1') continue;
            $nsd = [
                'code'              => $exc[0],
                'materi_id'         => $materi[array_search($exc[1], array_column($subject, 'materi_'))]['id_mapel'],
                'teacher_id'        => $teacher[array_search($exc[2], array_column($teacher, 'nik'))]['teacher_id'],
                'available_date'    => $exc[3],
                'note'              => $exc[4],
            ];
            if($this->db->get_where('materi', ['title' => $exc[0], 'subject_id' => $nsd['subject_id'], 'teacher_id' => $nsd['teacher_id']])->num_rows() > 0) {
                // $nsd['edit_at'] = date('Y-m-d H:i:s'); 
                // $nsd['edit_by'] = $this->session->userdata('username');
                if($this->db->update('subject', $exc[0], $nsd))
                    $prog += 1;
            } else {
                // $nsd['create_by'] = $this->session->userdata('username');
                if($this->db->insert('materi', $nsd))
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