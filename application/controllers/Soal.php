<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Soal extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_soal_exam']);
		$this->load->helper(['assets', 'customstring', 'cek']);

		if (!isset($_SESSION['username'])) redirect('auth/login');

		$this->lang->load('upload', 'indonesia');
		$this->lang->load('message', 'indonesia');
	}

	function get_detail_soal(){
		$post = $this->input->post();
		$soal = $this->model_soal_exam->get_soal_by_id($post['soal_id']);
		
		if($soal){
			$res = [
				'status' => 'success',
				'data' => $soal
			];
		} else {
			$res = [
				'status' => 'error',
				'message' => 'Data tidak ditemukan'
			];
		}

		header('Content-Type: application/json');
		echo json_encode($res);
	}

	function get_soal_exam(){
		$post = $this->input->post();

		// untuk mengambil data soal exam
		$soal = $this->model_soal_exam->get_soal_exam_student($post['exam_id']);

		shuffle($soal);
		
		if($soal){
			$res = [
				'success' => true,
				'data' => $soal,
				'token' => $this->security->get_csrf_hash()
			];
		} else {
			$res = [
				'success' => false,
				'message' => 'Data tidak ditemukan',
				'token' => $this->security->get_csrf_hash()
			];
		}

		header('Content-Type: application/json');
		echo json_encode($res);
	}

}
