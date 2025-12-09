<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bundling extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Load any required models, libraries, etc.
		check_loggin();
		$this->load->model(['model_common','model_bundling', 'kategori_model']);
		$this->load->helper('url');
		$this->load->helper('slug');
		$this->load->helper('assets');
		$lang = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : config_item('language');
		$this->lang->load('message', $lang);
		$this->load->library('csrfsimple');
	}

	public function index()
	{
		$data['pageTitle'] = 'Paket Bundling';
		$data['tableName']	= 'bundling';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [
			['path' => 'assets/new/js/pages/_bundling.js', 'defer' => true],
		];

		// Load a view
		$this->template->load('template', 'bundling/index', $data);
	}

	public function create()
	{
		$data['pageTitle'] = 'Tambah Paket Bundling';
		$data['tableName']	= 'bundling';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [
			['path' => 'assets/new/libs/dropdown-combotree/comboTreePlugin.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_bundling.js', 'defer' => true],
		];

		$data['page_css'] = [
			'assets/new/libs/dropdown-combotree/comboTreeStyle.css',
		];

		// get publisher id
		$userId = $this->session->userdata('userid');
		$data['publisher_id'] = $this->db->get_where('users', ['userid' => $userId])->row()->publisher_id;

		$data['categories'] = $this->kategori_model->get_all();

		// Load a view
		$this->template->load('template', 'bundling/create', $data);
	}

	public function edit()
	{
		$id = $this->input->get('id');

		$data['pageTitle'] = 'Edit Paket Bundling';
		$data['tableName']	= 'bundling';
		$data['csrf_token']	= $this->csrfsimple->genToken();
		$data['page_js']	= [
			['path' => 'assets/new/libs/dropdown-combotree/comboTreePlugin.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_bundling.js', 'defer' => true],
		];

		$data['page_css'] = [
			'assets/new/libs/dropdown-combotree/comboTreeStyle.css',
		];
		
		// get publisher id
		$userId = $this->session->userdata('userid');
		$data['publisher_id'] = $this->db->get_where('users', ['userid' => $userId])->row()->publisher_id;

		// get data bundling
		$data['bundling'] = $this->db->get_where('bundling_packages', ['id' => $id])->row();

		// get data ebook
		$data['ebook'] = $this->db->select('*, es.price as normal_price')
			->where('bundling_package_id', $id)
			->join('bundling_packages bp', 'bp.id = bpb.bundling_package_id')
			->join('ebooks_subscribe_type es', 'es.id = bpb.ebook_subscribe_id')
			->join('ebooks e', 'e.id = es.ebook_id')
			->get('bundling_package_books bpb')->result_array();

		$data['total_harga_normal'] = array_sum(array_column($data['ebook'], 'normal_price'));
		$data['total_harga_paket'] = array_sum(array_column($data['ebook'], 'discount_price'));
		$data['total_diskon'] = ($data['total_harga_normal'] - $data['total_harga_paket']);

		// Load a view
		$this->template->load('template', 'bundling/create', $data);
	}

	public function update()
	{
		$post = $this->input->post();

		$package_name = $post['nama_paket'];
		$publisher_id = $post['publisher_id'];
		$id = $post['bundling_id'];

		// count array price
		$price = array_sum($post['harga_paket']);
		$description = $post['deskripsi_paket'];
		$start_date = $post['waktu_start'];
		$end_date = $post['waktu_end'];
		$stock = $post['kuota'];

		// duration days
		$duration = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);

		// upload image
		$config['upload_path']          = $this->config->item('path_client') . 'assets/images/bundlings/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 2048;
		$config['encrypt_name']         = TRUE;

		$this->load->library('upload', $config);

		// jika ada file yang diupload
		if (!empty($_FILES['foto_sampul']['name'])) {
			if ($this->upload->do_upload('foto_sampul')) {
				$cover_img = $this->upload->data('file_name');
			} else {
				$error = $this->upload->display_errors();
				$data = [
					'status' => 'error',
					'message' => $error
				];
				header('Content-Type: application/json');
				echo json_encode($data);
				return;
			}
		} else {
			$cover_img = $post['old_foto_sampul'];
		}

		$data = [
			'package_name' => $package_name,
			'publisher_id' => $publisher_id,
			'price' => $price,
			'description' => $description,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'duration_days' => $duration,
			'package_image' => $cover_img,
			'stock' => $stock,
		];

		$update = $this->model_bundling->update('bundling_packages', $data, ['id' => $id]);

		// delete bundling_package_books
		$this->db->delete('bundling_package_books', ['bundling_package_id' => $id]);

		// loop insert data to bundling_package_books
		foreach ($post['ebook_id'] as $key => $value) {
			$data = [
				'bundling_package_id' => $id,
				'ebook_subscribe_id' => $value,
				'normal_price' => $post['harga_normal'][$key],
				'discount_price' => $post['harga_paket'][$key],
			];

			$this->db->insert('bundling_package_books', $data);
		}

		//  response json header
		if ($update) {
			$data = [
				'status' => 'success',
				'message' => 'Data berhasil diupdate'
			];
		} else {
			$data = [
				'status' => 'error',
				'message' => 'Data gagal diupdate'
			];
		}

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	public function get_list_ebook()
	{
		$search = $this->input->get('search');
		$kategories = $this->input->get('kategori');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');

		if (!$limit) $limit = 1000;
		if (!$offset) $offset = 0;

		$publisher_id = $this->db->get_where('users', ['userid' => $this->session->userdata('userid')])->row()->publisher_id;

		$filter = [
			'search' => $search,
			'kategori' => array_filter(explode(',', $kategories)),
			'publisher_id' => (int)$publisher_id
		];

		$ebook = $this->model_bundling->get_ebook($filter, $limit, $offset);

		// create ebook variant
		foreach ($ebook as $key => $val) {
			$variant = $this->db->get_where('ebooks_subscribe_type', ['ebook_id' => $val['id']])->result_array();
			$ebook[$key]['variant'] = $variant;
		}

		$total = $this->model_bundling->get_ebook_count($search, $filter['kategori']);

		$datas =   array(
			"draw"			  => 1,
			"recordsTotal"	  => $total,
			"recordsFiltered" => $total,
			"data"			  => $ebook
		);

		http_response_code(200);
		echo json_encode($datas, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT);
		exit();
	}

	public function store()
	{
		$post = $this->input->post();

		$package_name = $post['nama_paket'];
		$publisher_id = $post['publisher_id'];

		// count array price
		$price = array_sum($post['harga_paket']);
		$description = $post['deskripsi_paket'];
		$start_date = $post['waktu_start'];
		$end_date = $post['waktu_end'];
		$stock = $post['kuota'];

		// duration days
		$duration = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);

		// upload image
		$config['upload_path']          = $this->config->item('path_client') . '\\assets\\images\\bundlings\\';
		$config['allowed_types']        = 'jpg|png';
		$config['max_size']             = 2048;
		$config['encrypt_name']         = TRUE;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('foto_sampul')) {
			$cover_img = $this->upload->data('file_name');
		} else {
			$error = $this->upload->display_errors();
			$data = [
				'status' => 'error',
				'message' => $error
			];
			header('Content-Type: application/json');
			echo json_encode($data);
			$cover_img = 'default.jpg';
		}


		$data = [
			'package_name' => $package_name,
			'publisher_id' => $publisher_id,
			'price' => $price,
			'description' => $description,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'duration_days' => $duration,
			'package_image' => $cover_img,
			'stock' => $stock,
			'status' => (time() > strtotime($start_date)) ? 1 : 0,
		];


		$insert = $this->model_bundling->insert('bundling_packages', $data);
		$last_id = $this->db->insert_id();

		// loop insert data to bundling_package_books
		foreach ($post['ebook_id'] as $key => $value) {
			$data = [
				'bundling_package_id' => $last_id,
				'ebook_subscribe_id' => $value,
				'normal_price' => $post['harga_normal'][$key],
				'discount_price' => $post['harga_paket'][$key],
			];

			$this->db->insert('bundling_package_books', $data);
		}

		//  response json header 
		if ($insert) {
			$data = [
				'status' => 'success',
				'message' => 'Data berhasil disimpan'
			];
		} else {
			$data = [
				'status' => 'error',
				'message' => 'Data gagal disimpan'
			];
		}

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	function getAll()
	{
		$draw = $this->input->get('draw', TRUE);
		$limit = $this->input->get('length', TRUE);
		$offset = $this->input->get('start', TRUE);
		$filters = $this->input->get('columns');

		$filters[2]['order'] = $this->input->get('order')[0]['dir'];

		if ($limit < 0) {
			$limit = 10;
		}

		$data = $this->model_bundling->getAll($filters, $limit, $offset);

		foreach ($data as $key => $val) {
			$totalTerjual = $this->model_bundling->getTotalBundlingTerjual($val['id']);
			$data[$key]['total_terjual'] = $totalTerjual[0]['total_terjual'];

			// kondisi switch status
			if(time() > strtotime($val['start_date'])){
				$data[$key]['switch_status'] = 'show';
			} else {
				$data[$key]['switch_status'] = 'hide';
			}
		}

		$resp = [
			'draw' => $draw,
			'data' => $data,
			'recordsTotal' => $this->model_bundling->countAll($filters),
			'recordsFiltered' => $this->model_bundling->countAll($filters)
		];

		echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	function activeProduct()
	{
		$id = $this->input->post('id');
		$status = $this->input->post('status');

		$data = [
			'status' => $status
		];

		$update = $this->db->update('bundling_packages', $data, ['id' => $id]);

		if ($update) {
			$data = [
				'status' => 'success',
				'message' => 'Data berhasil diupdate'
			];
		} else {
			$data = [
				'status' => 'error',
				'message' => 'Data gagal diupdate'
			];
		}

		header('Content-Type: application/json');
		echo json_encode($data);
	}
}
