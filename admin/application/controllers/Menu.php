<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('model_users');
        $lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
    }

    public function getMenuTree() {
        $res = $this->model_users->get_menu();
        $arr = [];
        foreach($res as $r) {
            $arr[] = [
                'id'        => $r['id'],
                'text'      => $r['text'],
                'parent'    => $r['parent'] == 0 ? '#' : $r['parent']
            ];
        }
        header('Content-Type: appliation/json');
        echo json_encode($arr, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

    public function getTable() {
        // params
        $filter = $this->input->get('columns');
        $draw   = $this->input->get('draw');
        $limit  = $this->input->get('length');
        $offset = $this->input->get('start');
        $res    = $this->model_users->get_all($filter, $limit, $offset);

        $data = [
            'draw'              => $draw,
            'data'              => $res,
            'recordsTotal'      => $this->db->count_all_results('user_level'),
            'recordsFiltered'   => $this->model_users->count_all($filter)
        ];

        echo json_encode($data, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
        exit;
    }
    
    public function post() {
        header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if(empty($input['name']) || empty($input['menu_id'])) {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        if($this->db->get_where('user_level', ['user_level_name' => $input['name']])->num_rows() > 0) {
			http_response_code(422);
			$m = [
					'err_status' => 'error', 
					'message' => $this->lang->line('acc_ctrl_common').' '.$this->lang->line('woow_is_exists')
				];
			echo json_encode($m, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
			return;
		}

        $d = [
            'user_level_name' => $input['name']
        ];

        if(!$this->db->insert('user_level', $d)) {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }
        $id = $this->db->insert_id();
        $data = [];

        $this->db->delete('menu_level', ['menu_level_user_level' => $id]);
        foreach($input['menu_id'] as $menu) {
            $data[] = [
                'menu_level_user_level' => $id,
                'menu_level_menu'       => $menu
            ];
        }

        if(!$this->db->insert_batch('menu_level', $data)) {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
    }
    
    public function put() {
        header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if(empty($input['id']) || empty($input['name']) || empty($input['menu_id'])) {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_is_required')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        $d = [
            'user_level_name' => $input['name']
        ];

        if(!$this->db->update('user_level', $d, ['user_level_id' => $input['id']])) {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }
        $id = $input['id'];
        $data = [];

        $this->db->delete('menu_level', ['menu_level_user_level' => $id]);
        foreach($input['menu_id'] as $menu) {
            $data[] = [
                'menu_level_user_level' => $id,
                'menu_level_menu'       => $menu
            ];
        }

        if(!$this->db->insert_batch('menu_level', $data)) {
            http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
        }

        http_response_code(200);
		$msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_form_success')];
		exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
    }
    
    public function delete() {
        header('Content-Type: application/json');

        $_input = file_get_contents('php://input');
        $input  = json_decode($_input, TRUE);

        if($input['isBulk'] == 1)
            $this->db->where_in('user_level_id', $input['data']);
        else
            $this->db->where('user_level_id', $input['data']);

        if(!$this->db->delete('user_level')) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }
        if($input['isBulk'] == 1)
            $this->db->where_in('menu_level_user_level', $input['data']);
        else
            $this->db->where('menu_level_user_level', $input['data']);
        if(!$this->db->delete('menu_level')) {
            http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_delete_error')];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
            return;
        }

        http_response_code(200);
        $msg = ['err_status' => 'success', 'message' => $this->lang->line('woow_delete_success')];
        exit(json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT));
    }
}

