<?php
defined('BASEPATH') or exit('No direct script access allowed');

// import phpoffice
use PhpOffice\PhpSpreadsheet\IOFactory;
use Rakit\Validation\Validator;


class Book extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		check_Loggin();
		$this->load->model(['book_model', 'kategori_model', 'publisher_model', 'model_settings']);
		$this->load->library(['form_validation']);
		$this->load->helper('assets');
		$this->settings = $this->model_settings->get_settings();
	}

	/**
	 * View
	 *
	 * @return void
	 */
	public function index()
	{
		$data['page_css'] = [
			'assets/node_modules/@selectize/selectize/dist/css/selectize.bootstrap4.css',
			'assets/node_modules/jstree/dist/themes/default/style.min.css',
			'assets/new/css/fpersonno.css'
		];

		$data['page_js'] = [
			['path' => 'assets/node_modules/jstree/dist/jstree.min.js', 'defer' => true],
			['path' => 'assets/node_modules/@selectize/selectize/dist/js/selectize.min.js', 'defer' => true],
			['path' => 'assets/new/js/pages/book.js', 'defer' => true],
		];

		$this->template->load('template', 'book/index', $data);
	}
	
	/**
	 * Get details of an item
	 *
	 * @param int $id
	 * @return void
	 */
	public function show($id): void
	{
		$data['book'] = $this->book_model->get_one($id);
		echo $this->template->render('show', $data);
	}

	public function add() {

		$pageTitle = 'Tambah Buku';

		$data['page_css'] = [
			'assets/node_modules/@selectize/selectize/dist/css/selectize.bootstrap4.css',
			'assets/new/libs/dropdown-combotree/comboTreeStyle.css',
			'assets/new/css/fpersonno.css',
			'assets/new/css/add_book.css'
		];

		$data['page_js'] = [
			['path' => 'assets/node_modules/@selectize/selectize/dist/js/selectize.min.js', 'defer' => true],
			['path' => 'assets/new/libs/dropdown-combotree/comboTreePlugin.js', 'defer' => true],
			['path' => 'assets/new/js/pages/_add_book.js', 'defer' => true],
		];

		$data['publishers'] = $this->publisher_model->get_all();
		$data['categories'] = $this->kategori_model->get_all();

		$this->template->load('template', 'book/add', $data);
	}

	/**
	 * Get All Data
	 *
	 * @return void
	 */
	public function get_all(): void
	{
		$book = $this->db->get('books')->result_array();
		echo json_encode($book, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	/**
	 * Get All Data with Pagination
	 *
	 * @return void
	 */
	public function get_all_paginated(): void
	{
		$draw = $this->input->get('draw') ?? NULL;
		$limit = $this->input->get('length');
		$offset = $this->input->get('start');
		$filters = $this->input->get('columns');
		$data = $this->book_model->get_all($filters, $limit, $offset);

		$response = [
			'draw' => $draw,
			'data' => $data,
			'recordsTotal' => $this->db->count_all_results('ebooks'),
			'recordsFiltered' => $this->book_model->count_all($filters)
		];


		echo json_encode($response, JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG);
	}

	/**
	 * Store new data to database
	 *
	 * @return void
	 */
	// public function store(): void
	// {
	// 	$code 	   	= $this->input->post('book-code', TRUE);
	// 	$title 	   	= $this->input->post('book-title', TRUE);
	// 	$category  	= $this->input->post('book-category', TRUE);
	// 	$author	   	= $this->input->post('book-author', TRUE);
	// 	$publisher 	= $this->input->post('book-publisher', TRUE);
	// 	$year	   	= $this->input->post('book-year', TRUE) ?? NULL;
	// 	$isbn 	   	= $this->input->post('book-isbn', TRUE) ?? NULL;
	// 	$description = $this->input->post('book-description', TRUE);
	// 	$qty		= $this->input->post('book-qty', TRUE) ?? 0;
	// 	$img 	   	= $_FILES['book-image'];
	// 	$cover		= $_FILES['cover'];

	// 	$category_data = $this->kategori_model->get_all();
	// 	$publisher_data = $this->publisher_model->get_all();

	// 	// Validation
	// 	$this->form_validation->set_rules('book-code', 'Kode Buku', 'required|is_unique[books.book_code]');
	// 	$this->form_validation->set_rules('book-title', 'Judul', 'required|callback_is_new_book_unique['.$category.']', [
	// 		'is_new_book_unique' => '{field} sudah tersedia pada database'
	// 	]);
	// 	$this->form_validation->set_rules('book-category', 'Kategori', 'required|integer|in_list['.implode(',', array_column($category_data, 'id')).']');
	// 	$this->form_validation->set_rules('book-author', 'Penulis', 'required');
	// 	$this->form_validation->set_rules('book-publisher', 'Penerbit', 'required|integer|in_list['.implode(',', array_column($publisher_data, 'id')).']');
	// 	$this->form_validation->set_rules('book-year', 'Tahun Terbit', 'integer|exact_length[4]|greater_than[1900]');
	// 	$this->form_validation->set_rules('book-qty', 'Stok', 'integer|greater_than_equal_to[0]');
	// 	$this->form_validation->set_rules('book-isbn', 'ISBN');
	// 	$this->form_validation->set_rules('book-description', 'Uraian');

	// 	if(!$this->form_validation->run())
	// 	{
	// 		$resp = ['success' => false, 'errors' => $this->form_validation->error_array(), 'old' => $_POST];
	// 		$this->session->set_flashdata('error', $resp);
	// 		redirect($_SERVER['HTTP_REFERER']);
	// 	}

	// 	// Image
	// 	$filename = NULL;
	// 	if(intval($img['size']) > 0) {
			
	// 		$img_conf = [
	// 			'upload_path'	=> 'assets/img/books/',
	// 			'allowed_types'	=> 'jpg|png|jpeg',
	// 			'file_name'		=> str_replace(' ', '_', $title).'_'.$category.'.jpg',
	// 			'file_ext_tolower'	=> true,
	// 			'encrypt_name'	=> true
	// 		];

	// 		$this->load->library('upload', $img_conf);
	// 		if(!$this->upload->do_upload('book-image'))
	// 		{
	// 			$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
	// 			$this->session->set_flashdata('error', $resp);
	// 			redirect($_SERVER['HTTP_REFERER']);
	// 		}

	// 		$filename = $this->upload->data('full_path');
	// 		// resize
	// 		$config['image_library'] = 'gd2';
	// 		$config['source_image'] = FCPATH.'../'.$this->upload->data('full_path');
	// 		$config['create_thumb'] = TRUE;
	// 		$config['maintain_ratio'] = TRUE;
	// 		$config['width']         = 128 - 50;
	// 		$config['height']       = 165 - 50;

	// 		$this->load->library('image_lib', $config);
	// 		if(!$this->image_lib->resize())
	// 		{
	// 			$resp = ['success' => false, 'message' => $this->image_lib->display_errors(), 'old' => $_POST];
	// 			$this->session->set_flashdata('error', $resp);
	// 			redirect($_SERVER['HTTP_REFERER']);
	// 		}
	// 	}

	// 	$data = [
	// 		'book_code'		=> $code,
	// 		'title'			=> $title,
	// 		'author'		=> $author,
	// 		'isbn'			=> $isbn,
	// 		'publish_year'	=> $year,
	// 		'category_id'	=> $category,
	// 		'publisher_id'	=> $publisher,
	// 		'description'	=> $description,
	// 		'cover_img'		=> $filename,
	// 		'qty'			=> $qty
	// 	];

	// 	if(!$this->db->insert('ebook', $data))
	// 	{
	// 		$resp = ['success' => false, 'message' => 'Data gagal di simpan', 'old' => $_POST];
	// 		$this->session->set_flashdata('error', $resp);
	// 		redirect($_SERVER['HTTP_REFERER']);
	// 	}

	// 	// Success
	// 	$resp = ['success' => true, 'message' => 'Data berhasil di simpan', 'add_stock' => true, 'book_id' => $this->db->insert_id()];
	// 	$this->session->set_flashdata('success', $resp);
	// 	redirect($_SERVER['HTTP_REFERER']);
	// }

	/**
	 * Store new data to database
	 *
	 * @return void
	 */
	public function store(): void {
		try {

		$validate = new Validator();
		$this->load->library('upload');

		header('Content-Type: application/json');

		$validation = $validate->make($_POST + $_FILES, [
			'ebook-file'				=> 'required_if:rd-upload-type,file|uploaded_file|min:10KB',
			'ebook-link'				=> 'required_if:rd-upload-type,link|url',
			'book-code'					=> 'required',
			'book-title' 	 			=> 'required',
			'book-author' 	 			=> 'required',
			'book-publisher' 			=> 'required',
			'book-year'		 			=> 'required|date:Y',
			'book-isbn'		 			=> 'required',
			'book-pages'  				=> 'required|numeric',
			'book-description'  		=> 'required',
			'book-image'				=> 'required|uploaded_file:0,2MB,png,jpeg,jpg,webp',
			'cover'						=> 'array',
			'cover.*'					=> 'uploaded_file:0,2MB,png,jpeg,jpg,webp',
			'subscription'				=> 'array',
			'subscription.*.type'		=> 'required',
			'subscription.*.benefit'	=> 'required',
			'subscription.*.name'		=> 'required',
			'subscription.*.price'		=> 'required',
			'book-qty'					=> 'required|numeric',
			'start-time'				=> 'required|date:Y-m-d',
			'book-category'				=> 'required',
			'book-category-id'			=> 'required',
			'product-status'			=> 'required',

		]);
		$validation->setMessages([
			'required' 		=> 'Nilai tidak boleh kosong',
			'required_if' 	=> 'Nilai tidak boleh kosong',
			'url'	   		=> 'Format URL tidak valid' 
		]);

		$validation->validate();

		if($validation->fails())
		{
			http_response_code(422);
			$errors = $validation->errors();
			echo json_encode(['success' => FALSE, 'message' => $this->lang->line('woow_form_error'), 'errors' => $errors->toArray()], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
			return;
		}
		// echo json_encode($_POST + $_FILES);

		$rdbType 	 = $this->input->post('rd-upload-type', TRUE);
		$title 		 = $this->input->post('book-title', TRUE);
		$author	   	 = $this->input->post('book-author', TRUE);
		$publisher 	 = $this->input->post('book-publisher', TRUE);
		$year	   	 = $this->input->post('book-year', TRUE) ?? NULL;
		$isbn 	   	 = $this->input->post('book-isbn', TRUE) ?? NULL;
		$description = $this->input->post('book-description', TRUE);
		$qty		 = $this->input->post('book-qty', TRUE) ?? 0;
		$code		 = $this->input->post('book-code', TRUE);
		$pages		 = $this->input->post('book-pages', TRUE);
		$startTime	 = $this->input->post('start-time', TRUE);
		$activeStat  = $this->input->post('product-status', TRUE); 
		$subscribe	 = $this->input->post('subscription');
		$categoryId	 = $this->input->post('book-category-id', TRUE);
		$cover		 = $_FILES['book-image'];

		$filename = NULL;

		// ebook 
		switch($rdbType)
		{
			case 'file':
				$nFile = $_FILES['ebook-file'];
				
				$bookConf = [
					'upload_path'		=> $this->config->item('path_client').'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'ebooks'.DIRECTORY_SEPARATOR,
					'allowed_types'		=> 'pdf',
					'file_name'			=> $title.'.pdf',
					'file_ext_tolower'	=> true,
					'encrypt_name'		=> true
				];

				$this->upload->initialize($bookConf);
				
				if(!$this->upload->do_upload('ebook-file'))
				{
					http_response_code(422);
					echo json_encode(['success' => FALSE, 'message' => $this->upload->display_errors()], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
					return;
				}

				$filename = $this->upload->data('file_name');
				break;
			case 'link':
				$filename = $this->input->post('ebook-link', TRUE);
				break;
		}

		// first cover
		if(!empty($_FILES['book-image'])) {
			$img_conf = [
				'upload_path'		=> $this->config->item('path_client').'assets/images/ebooks/cover/',
				'allowed_types'		=> 'jpg|png|jpeg',
				'file_name'			=> md5($title.'_sampul').'.jpg',
				'file_ext_tolower'	=> true,
				'overwrite'			=> TRUE,
			];

			$this->upload->initialize($img_conf);
			if(!$this->upload->do_upload('book-image'))
			{
				http_response_code(422);
				$resp = ['success' => false, 'message' => $this->upload->display_errors()];
				echo json_encode($resp, JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG);
				return;
			}

			$coverUtama = $this->upload->data('file_name');
		}

		// seondary cover
		if(isset($_FILES['cover']) && count($_FILES['cover']['size']) > 0)
		{
			$covers = $_FILES['cover'];
			$covImages = [];
			for($i=0;$i<count($covers['size']);$i++)
			{
				if($covers['size'][$i] == 0) continue;

				$_FILES['cov']['name'] 		= $covers['name'][$i];
				$_FILES['cov']['type'] 		= $covers['type'][$i];
				$_FILES['cov']['tmp_name'] 	= $covers['tmp_name'][$i];
				$_FILES['cov']['error'] 	= $covers['error'][$i];
				$_FILES['cov']['size']		= $covers['size'][$i];

				$img_conf = [
					'upload_path'		=> $this->config->item('path_client').'assets/images/ebooks/cover/',
					'allowed_types'		=> ['jpg','png','jpeg','bmp'],
					'file_name'			=> md5($title.'_cover_'.($i + 1)).'.jpg',
					'file_ext_tolower'	=> true,
					'overwrite'			=> TRUE,
				];
				
				$this->upload->initialize($img_conf);
				if(!$this->upload->do_upload('cov'))
				{
					http_response_code(422);
					$resp = ['success' => false, 'message' => $this->upload->display_errors()];
					die(json_encode($resp, JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_TAG));
				}

				$covImages['cover_'.($i + 1)] = $this->upload->data('file_name');
			}
		}
		// main data
		$data = [
			'book_code'		=> $code,
			'title'			=> $title,
			'author'		=> $author,
			'isbn'			=> $isbn,
			'publish_year'	=> $year,
			'publisher_id'	=> $publisher,
			'description'	=> $description,
			'cover_img'		=> $coverUtama,
			'qty'			=> $qty,
			'total_pages'	=> $pages,
			'active_status' => $activeStat,
			'file_1'		=> $filename,
			'start_active'	=> (new DateTime($startTime))->format('Y-m-d')
		];
		$data = $data + $covImages;

		$this->db->trans_start();

		$this->db->insert('ebooks', $data);
		$insertedId = $this->db->insert_id();

		// categories
		$categories = explode(',', $categoryId);

		foreach($categories as $category)
			$this->db->insert('ebooks_categories', ['ebook_id' => $insertedId, 'category_id' => $category]);

		// subscription
		foreach($subscribe as $sb) {
			$in = [
				'ebook_id' 			=> $insertedId,
				'subscribe_periode' => $sb['type'],
				'name'				=> $sb['name'],
				'price'				=> $sb['price']
			];

			$this->db->insert('ebooks_subscribe_type', $in);
			$sb_type = $this->db->insert_id();
			$benefits = explode(',', $sb['benefit']);

			foreach($benefits as $benefit) 
				$this->db->insert('subscribe_type_benefit', ['subscribe_type' => $sb_type, 'benefit' => trim($benefit)]);
		}

		$this->db->trans_complete();

		if(!$this->db->trans_status())
		{
			http_response_code(422);
			$resp = ['success' => false, 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
			return;
		}

		$resp = ['success' => true, 'message' => $this->lang->line('woow_form_success')];
		echo json_encode($resp, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);

		}
		catch(Exception $e)
		{
			log_message('error', $e->__toString());

			http_response_code(500);
			$resp = ['success' => false, 'message' => $this->lang->line('woow_form_error')];
			die(json_encode($resp, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG));
		}

	}

	/**
	 * Edit Existing Data By ID
	 *
	 * @return void
	 */
	public function edit(): void {
		$id			= trim($this->input->post('book-id', TRUE));
		$code 	   	= trim($this->input->post('book-code', TRUE));
		$title 	   	= trim($this->input->post('book-title', TRUE));
		$category  	= trim($this->input->post('book-category', TRUE));
		$author	   	= trim($this->input->post('book-author', TRUE));
		$publisher 	= trim($this->input->post('book-publisher', TRUE));
		$year	   	= trim($this->input->post('book-year', TRUE)) ?? NULL;
		$isbn 	   	= trim($this->input->post('book-isbn', TRUE)) ?? NULL;
		$description = trim($this->input->post('book-description', TRUE));
		$qty		= trim($this->input->post('book-qty', TRUE)) ?? 0;
		$filename	= trim($this->input->post('book-img_name', TRUE));
		$img 	   	= $_FILES['book-image'];

		$category_data = $this->kategori_model->get_all();
		$publisher_data = $this->publisher_model->get_all();

		// Validation
		$this->form_validation->set_rules('book-id', 'ID', 'required|integer');
		$this->form_validation->set_rules('book-code', 'Kode Buku', 'required');
		$this->form_validation->set_rules('book-title', 'Judul', 'required|callback_is_edit_book_unique['.$category.'.'.$id.']', [
			'is_edit_book_unique' => '{field} sudah tersedia pada database'
		]);
		$this->form_validation->set_rules('book-category', 'Kategori', 'required|integer|in_list['.implode(',', array_column($category_data, 'id')).']');
		$this->form_validation->set_rules('book-author', 'Penulis', 'required');
		$this->form_validation->set_rules('book-publisher', 'Penerbit', 'required|integer|in_list['.implode(',', array_column($publisher_data, 'id')).']');
		$this->form_validation->set_rules('book-year', 'Tahun Terbit', 'integer|exact_length[4]|greater_than[1922]');
		$this->form_validation->set_rules('book-qty', 'Stok', 'integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('book-isbn', 'ISBN');
		$this->form_validation->set_rules('book-description', 'Uraian');

		if(!$this->form_validation->run())
		{
			$resp = ['success' => false, 'errors' => $this->form_validation->error_array(), 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Image
		if(intval($img['size']) > 0) {
			
			$img_conf = [
				'upload_path'	=> 'assets/img/books/',
				'allowed_types'	=> 'jpg|png|jpeg',
				'file_name'		=> str_replace(' ', '_', $title).'_'.$category.'.jpg',
				'file_ext_tolower'	=> true,
				'encrypt_name'	=> true
			];

			$this->load->library('upload', $img_conf);
			if(!$this->upload->do_upload('book-image'))
			{
				$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
				$this->session->set_flashdata('error', $resp);
				redirect($_SERVER['HTTP_REFERER']);
			}

			$filename = $this->upload->data('file_name');

			$config['image_library'] = 'gd2';
			$config['source_image'] = $this->upload->data('full_path');
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 128 - 50;
			$config['height']       = 165 - 50;

			$this->load->library('image_lib', $config);
			if(!$this->image_lib->resize())
			{
				$resp = ['success' => false, 'message' => $this->image_lib->display_errors(), 'old' => $_POST];
				$this->session->set_flashdata('error', $resp);
				redirect($_SERVER['HTTP_REFERER']);
			}
		}

		$data = [
			'book_code'		=> $code,
			'title'			=> $title,
			'author'		=> $author,
			'isbn'			=> $isbn,
			'publish_year'	=> $year,
			'category_id'	=> $category,
			'publisher_id'	=> $publisher,
			'description'	=> $description,
			'cover_img'		=> $filename,
			'qty'			=> $qty
		];

		if(!$this->db->update('ebooks', $data, ['id' => $id]))
		{
			$resp = ['success' => false, 'message' => 'Data gagal di ubah', 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		// Success
		$resp = ['success' => true, 'message' => 'Data berhasil di simpan'];
		$this->session->set_flashdata('success', $resp);
		redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * Delete a book
	 *
	 * @param mixed $id
	 * @return boolean
	 */
	public function erase($id): void
	{
		if(!$this->db->update('ebooks', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]))
        {
            $return = ['success' => false, 'message' =>  'Data Gagal Di Hapus'];
            $this->session->set_flashdata('error', $return);
            redirect($_SERVER['HTTP_REFERER']);
        }

        $return = ['success' => true, 'message' =>  'Data Berhasil Di Hapus'];
        $this->session->set_flashdata('success', $return);
        redirect($_SERVER['HTTP_REFERER']);
	}

	/**
	 * *********************************************************************************************
	 * 										CUSTOM VALIDATION
	 * *********************************************************************************************
	 */

	/**
	 * Custom validation for checking if book code is unique 
	*
	* @param string $str
	* @return void
	*/
	public function is_new_book_code_unique(string $str): void
	{

	}

	public function is_array(string $str): bool {
		return is_array($str) ?? FALSE;
	}

	 /**
	  * Custom check uniqueness of new book
	  *
	  * @param string  $str
	  * @param mixed $args2
	  * @return boolean
	  */
	public function is_new_book_unique($str, $args2): bool
	{
		return $this->db->get_where('ebooks', ['title' => $str, 'category_id' => $args2, 'deleted_at' => NULL])->num_rows() > 0 ? FALSE : TRUE;
	}

	/**
	 * Undocumented functionCustom check uniqueness of edited book
	 *
	 * @param string  $str
	 * @param mixed  $args2
	 * @return boolean
	 */
	public function is_edit_book_unique($str, $args2): bool
	{
		$args = explode('.', $args2);

		$this->db->where('ebooks.id <> '.$args[1])
				 ->where('title', $str)
				 ->where('category_id', $args[0])
				 ->where('deleted_at', NULL);
		$check = $this->db->get('ebooks');
		return $check->num_rows() > 0 ? FALSE : TRUE;
	}

	/**
	 * import book from excel
	 *
	 * @param string  $str
	 * @return boolean
	 */
	public function upload(): void {

		$config['upload_path'] = 'assets/files/uploads';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size'] = 10000;
		$config['file_name'] = 'book_'.time();

		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('file')) {
			$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
		}

		$upload_data = $this->upload->data();
		$filename = $upload_data['file_name'];

		$inputFileName = 'assets/files/uploads/'.$filename;
		try {
			$inputFileType = IOFactory::identify($inputFileName);
			$objReader = IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		$book_data = [];
		for ($row = 2; $row <= $highestRow; $row++) {
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

			$publisher_id = $this->db->get_where('publishers', [ 'publisher_name' => $rowData[0][3] ])->row_array();

			$book_data[] = [
				'cover_img' => $rowData[0][0],
				'title' => $rowData[0][1],
				'category_id' => $rowData[0][2],
				'publisher_id' => $publisher_id['id'],
				'author' => $rowData[0][4],
				'isbn' => $rowData[0][5],
				'publish_year' => $rowData[0][6],
				'qty' => $rowData[0][7],
				'description' => $rowData[0][8],
			];
		}

		$this->db->insert_batch('ebooks', $book_data);
		$resp = ['success' => true, 'message' => 'Data berhasil di simpan'];
		$this->session->set_flashdata('success', $resp);
		redirect($_SERVER['HTTP_REFERER']);

	}

	/**
	 * Import dataa from excel
	 *
	 * @return void
	 */
	public function import(): void {
		require_once APPPATH.'third_party'.DIRECTORY_SEPARATOR.'xlsx'.DIRECTORY_SEPARATOR.'SimpleXLSX.php';
		// Upload
		$config['upload_path'] = 'assets/files/uploads';
		$config['allowed_types'] = 'xlsx|xls';
		$config['max_size'] = 10000;
		$config['overwrite'] = TRUE;
		$config['file_name'] = 'book_'.time();

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('file'))
		{
			$resp = ['success' => false, 'message' => $this->upload->display_errors(), 'old' => $_POST];
			$this->session->set_flashdata('error', $resp);
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		$data = $this->upload->data();
		// Parse Excel
		if(!$xlsx = SimpleXLSX::parse($data['full_path']))
			throw new Exception(SimpleXLSX::parseError());
		$excel = $xlsx->rows(0);
		unset($excel[0]);
		// looping data

		$this->db->trans_start();
		foreach($excel as $x)
		{
			$category = $this->db->get_where('categories', ['category_name' => trim($x[2])]);
			$publisher = $this->db->get_where('publishers', ['publisher_name' => trim($x[3])]);

			if($category->num_rows() == 0) continue;
			if($publisher->num_rows() == 0) continue;

			$ls = [
				'book_code'		=> $x[0],
				'title'			=> $x[1],
				'category_id'	=> ($category->row_array())['id'],
				'publisher_id'	=> ($publisher->row_array())['id'],
				'author'		=> $x[4],
				'isbn'			=> $x[5],
				'publish_year'	=> $x[6],
				'qty'			=> $x[7],
				'rack_no'		=> $x[8],
				'cover_img'		=> $x[9],
				'description'	=> $x[10]
			];
			
			if($this->db->get_where('ebooks', ['book_code' => $x[0]])->num_rows() > 0)
				$this->db->update('ebooks', $ls, ['book_code' => $x[0]]);
			else
				$this->db->insert('ebooks', $ls);
		}
		$this->db->trans_complete();

		if($this->db->trans_status() == FALSE)
		{
			$this->session->set_flashdata('error', ['message' => 'Beberapa data gagal di input']);
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}

		@unlink($data['full_path']);

		$this->session->set_flashdata('success', ['message' => 'Beberapa data berhasil di input']);
		redirect($_SERVER['HTTP_REFERER']);
	}
	

}
