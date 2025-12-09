<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {
	
	public function page_missing()
	{
		$data = array(
			'heading' => 'Oops! Halaman / File tidak ditemukan.',
			'message' => 'Halaman / File yang Anda cari mungkin telah dihapus atau URL-nya salah.'
		);
		
		$this->load->view('errors/html/error_404', $data);
	}
}
