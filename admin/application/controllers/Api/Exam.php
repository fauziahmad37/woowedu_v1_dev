<?php

class Exam extends MY_Controller {

    public function __construct() {
        parent::__construct();
        check_Loggin();
        $this->load->model(['model_common', 'model_exam']);
		$this->load->model(['Model_settings']);
		$this->load->library(['csrfsimple', 'curl']);
        $this->load->helper('url');
		$this->load->helper(['slug', 'tgl']);
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
    }

    public function getAll() {
        $draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns');

        $data = $this->model_exam->getAll($filters, $limit, $offset);

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('exam'),
            'recordsFiltered' => $this->model_exam->countAll($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }


    public function save() {
 
        $code = trim($this->input->post('a_exam_code', TRUE));
        $mapel = trim($this->input->post('a_exam_subject', TRUE));
        $category = trim($this->input->post('a_exam_category', TRUE));
        $start = trim($this->input->post('a_exam_start', TRUE));
        $end = trim($this->input->post('a_exam_end', TRUE));
        $duration = intval(trim($this->input->post('a_exam_duration')));

        header('Content-Type: application/json');

        if(empty($code)  || empty($mapel) ) 
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }
 

        if($this->db->get_where('exam', ['code' => $code])->num_rows() > 0)
        {
            http_response_code(422);
            $m = [
                'err_status' => 'error', 
                'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
            ];
            exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }

				/*
        $checkIntersect = $this->model_exam->getDateBetween($kelas, $mapel, $start, $end);

        if($checkIntersect->num_rows() > 0)
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => 'Periode ber-irisan dengan ujian lain !!!, harap merubah jangka waktu ujian'];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }*/
				
				
        // if duration is NULL or 0 then duration is interval hours between start_time and end_time
        $_durasi = empty($duration) ? (new DateTime($end))->diff(new DateTime($start))->format("%h") : $duration;

        $data = [
            'code'          => $code, 
            'subject_id'    => $mapel,
            'category_id'    => $category 
        ];

        if(!$this->db->insert('exam', $data))
        { 
            http_response_code(422);
			$msg = ['err_status' => 'errorss', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        $_id = $this->db->insert_id();

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success_exam'), 'exam_code' => $_id];
		echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
		exit;
    }

    public function edit() {
        $code = trim($this->input->post('a_exam_code', TRUE)); 
        $category = trim($this->input->post('a_exam_category'));
        $mapel = trim($this->input->post('a_exam_subject', TRUE));
        $start = trim($this->input->post('a_exam_start', TRUE));
        $end = trim($this->input->post('a_exam_end', TRUE));
        $duration = intval(trim($this->input->post('a_exam_duration')));
        $id = intval($this->input->post('a_id', TRUE));

        header('Content-Type: application/json');
        // empty validation
        if(empty($code) ||   empty($mapel) ) 
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }
 
        // check unique for id
        $this->db->where('exam_id <>', $id);
        if($this->db->get_where('exam', ['code' => $code])->num_rows() > 0)
        {
            http_response_code(422);
            $m = [
                'err_status' => 'error', 
                'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
            ];
            exit(json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG));
        }
        // check if date is overlaps
        /*$checkIntersect = $this->model_exam->getDateBetween($kelas, $mapel, $start, $end, $id);

        if($checkIntersect->num_rows() > 0)
        {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => 'Periode ber-irisan dengan periode ujian lain !!!, harap merubah periode ujian'];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
        }*/

         // if duration is NULL or 0 then duration is interval hours between start_time and end_time
         $_durasi = empty($duration) ? (new DateTime($end))->diff(new DateTime($start))->format("%h") : $duration;

        $data = [
            'code'          => $code,
            'class_id'      => $kelas,
            'subject_id'    => $mapel,
            'category_id'   => $category,
            'start_date'    => (new \DateTime($start))->format('Y-m-d H:i:s'),
            'end_date'      => (new \DateTime($end))->format('Y-m-d H:i:s'),
            'duration'      => $_durasi
        ];
        // update
        if(!$this->db->update('exam', $data, ['exam_id' => $id]))
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
            $this->db->where_in('exam_id', $input['data']);
        else
            $this->db->where('exam_id', $input['data']);

        if(!$this->db->delete('exam')) {
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

    public function getReserve() {

        $exam = $this->input->get('exam');
        $kelas = $this->input->get('kelas');
        $mapel = $this->input->get('mapel');

        $model = (!empty($kelas) && !empty($mapel)) ? $this->model_exam->getAllSoalReserve($exam, $kelas, $mapel) : [];

        echo json_encode(['data' => $model]);
    }

    public function getSelected() {

        $exam = $this->input->get('exam');
       
        $model = (!empty($exam)) ? $this->model_exam->getAllSoalSelected($exam) : [];

        echo json_encode(['data' => $model]);
    }

    /**
     * =====================================================================
     *                      INSERT SOAL
     * =====================================================================
     */

     public function insertSoal() {
        // input
        $input = file_get_contents('php://input');
        $params = json_decode($input, TRUE);

        header('Content-Type: application/json');
        // loop through $input
        $this->db->trans_start();
        if($this->db->delete('soal_exam', ['exam_id' => $params['exam']])) {};
        foreach($params['soal'] as $in)
        {       
            // delete by exam and soal
            $this->db->insert('soal_exam', ['soal_id' => $in['soal'], 'exam_id' => $params['exam'], 'no_urut' => $in['nomor'], 'bobot_nilai' => $in['bobot']]);

        }
        $this->db->trans_complete();

        if($this->db->trans_status() == FALSE)
        {
            $this->db->trans_rollback();
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error'), 'token' => $this->csrfsimple->genToken()];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }
        $this->db->trans_commit();
        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success'), 'token' => $this->csrfsimple->genToken()];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
        echo json_encode($params);
        
     }

    public function question($_id) {
        $redirect = NULL;
        $message = NULL;

        if(empty($_id)) {
            $redirect = $_SERVER['HTTP_REFERER'];
            $message = 'Ujian tidak boleh kosong';
        }
        $_get = $this->db->get_where('exam', ['exam_id' => $_id])->row_array();
        
        $start = (new DateTime($_get['start_date']));
        $end = (new DateTime($_get['end_date']));
        $today = new DateTime();

        if($today < $start)
        {
            $redirect = $_SERVER['HTTP_REFERER'];
            $message = 'Ujian belum dapat di akses';
        }
        elseif($end < $today)
        {
            $redirect = $_SERVER['HTTP_REFERER'];
            $message = 'Ujian sudah tidak dapat di akses';
        }
        else 
        {
            $redirect = base_url('exam/questions/?kode_ujian='.$_get['code'].'&no=1');
            $_SESSION['ujian_student'] = 'murid'; //student id
            $_SESSION['start_time'] = (new DateTime())->format('Y-m-d H:i:s');
        }

        $_SESSION['flash_message'] = $message;
        header('Location: '.$redirect);
        
    }


    public function submitSoal() {
        $answer     = $this->input->post('answer');
        $csrf_token = $this->input->post('csrf_token');
        $kode       = $this->input->post('kode_ujian');
        $soal       = $this->input->post('kode_soal');
        $no         = $this->input->post('nomor');
        $total      = $this->input->post('total_soal');

        if($this->csrfsimple->checkToken($csrf_token) === false) {
            $_SESSION['message'] = $this->lang->line('woow_csrf_token_false');
            $_SESSION['status']  = 'error'; 
        }
        elseif(empty($kode) || empty($no) || empty($soal))
        {
            $_SESSION['message'] = $this->lang->line('woow_is_required');
            $_SESSION['status']  = 'error'; 
        }
        else
        {
            $exam = $this->model_exam->getExamSoal($kode, $no);

            // student_id integer,
            // exam_id integer,
            // class_id integer,
            // exam_answer character varying(300),
            // correct_answer smallint,
            // exam_submit timestamp(6) without time zone,

            $data = [
                'student_id'    => $_SESSION['student_id'],
                'exam_id'       => $exam['exam_id'],
                'class_id'      => $exam['class_id'],
                'soal_id'       => $exam['soal_id'],
                'exam_answer'   => $answer,
                'correct_answer' => $exam['answer'],
                'exam_submit'   => (new DateTime())->format('Y-m-d H:i:s')
            ];

            if(!$this->db->insert('exam_answer', $data))
            {
                $_SESSION['message'] = $this->lang->line('woow_form_error');
                $_SESSION['status']  = 'error'; 
            }
        }
        
        $redirect = NULL;

        if($no == $total)
        {
            $redirect = base_url('exam/student');
        }
        else {
            $redirect = base_url('exam/questions/?kode_ujian='.$kode.'&no='.($no + 1));
        }

        header('Location: '.$redirect);

    }

    public function getAllAnsweredExam() {

        $_id = $this->input->get('exam_id');

        $answered = $this->model_exam->getAnsweredExam($_SESSION['student_id'], $_id);
        $answers = [];

        foreach($answered as $a)
        {
            unset($a['correct_answer']);
            unset($a['duration']);
            $answers[] = $a;
        }

        echo json_encode(['data' => $answers]);
    }

}