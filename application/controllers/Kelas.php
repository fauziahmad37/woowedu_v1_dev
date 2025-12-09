<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends MY_Controller {

	protected $settings;

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_settings', 'model_kelas']);
		$this->load->helper('assets_helper');
		
		if (!isset($_SESSION['username'])) redirect('auth/login');

		$this->settings = json_decode(json_encode($this->model_settings->get_settings()), TRUE);
	}

	/**
	 * return VIEW
	 */
	public function index(){
	}

	/**
	 * GET
	 * return JSON
	 */
	public function getAllClassTeacher() {
        $draw = $this->input->get('draw');
        $limit = $this->input->get('length');
        $offset = $this->input->get('start');
        $filters = $this->input->get('columns') ?? [];

        $data = $this->model_kelas->get_kelas_teacher($filters, $limit, $offset);

        $datas = [
            'draw' => $draw,
            'data' => $data,
            'recordsTotal' => $this->db->count_all_results('class_teacher'),
            'recordsFiltered' => $this->model_kelas->count_all_kelas_teacher($filters)
        ];

        echo json_encode($datas, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }

}
