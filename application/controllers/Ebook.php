<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('zlib.output_compression', 'Off');
ini_set('memory_limit', '-1');
ini_set('memory_limit', '-1');


class Ebook extends MY_Controller {

	private $folderTarget;

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_ebook', 'model_settings', 'transaction_model', 'model_bundling_package']);
		
		if (!isset($_SESSION['username'])) redirect('auth/login');

		$this->settings = json_decode(json_encode($this->model_settings->get_settings()), TRUE);
		$this->folderTarget = 'assets'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'pdf_tmp'.DIRECTORY_SEPARATOR.$_SESSION['userid'];
	}

	// /**
	//  * Update Index
	//  *
	//  * @return void
	//  */
	// public function index(){

	// 	$header['add_js'] = [
	// 		'libs/htmx.min.js'
	// 	];

	// 	$header['page_css'] = [
	// 		'assets/css/ebook.css',
	// 	];

	// 	$header['pathjs'] = 'ebook/assets/index2_js';
	// 	$header['penerbit'] = $this->db->where('deleted_at is null')->get('publishers')->result_array(); 

	// 	$filter = [];

	// 	// jika user login sebagai guru
	// 	if($_SESSION['user_level'] == 3){
	// 		function classLevel($data){
	// 			return $data['class_level_id'];
	// 		}
	// 		$filter['class_level'] = array_map("classLevel", $_SESSION['class_level_id']);
	// 	}

	// 	// jika user login sebagai murid
	// 	if($_SESSION['user_level'] == 4){
	// 		$filter['class_level'][0] = $_SESSION['class_level_id'];
	// 	}

	// 	// jika user login sebagai kepala sekolah
	// 	if($_SESSION['user_level'] == 6){
	// 		unset($filter['class_level']);
	// 	}

	// 	$header['class_levels'] = (isset($filter['class_level']) && is_array($filter['class_level'])) ? $filter['class_level'] : [];

	// 	$header['bukuRekomendasi'] = $this->model_ebook->getRekomendasi(10, 0, $filter);

	// 	$filter = [];
	// 	$filter['baru_dirilis'] = true;
	// 	$header['baruDirilis'] = $this->model_ebook->getRekomendasi(10, 0, $filter);

	// 	$header['terbanyakDibaca'] = $this->model_ebook->terbanyakDibaca();
	// 	$header['bundlingPackages'] = $this->model_bundling_package->get(10);

	// 	$this->template->load('template', 'ebook/index2', $header);
	// }


	public function index() {
		$auth = $this->auth();
		$data['chip'] = $auth;

		$this->template->load('template', 'ebook/index_revamp', $data);
	}

	/**
	 * Detail of a book
	 *
	 * @param string $param
	 * @return void
	 */
	public function detail(string $param): void {

		$body['book'] = $this->model_ebook->get($param);
		// $body["similars"] = $this->model_ebook->getEbooks();
		// $body['history'] = $this->model_ebook->getHistoryByPerson($_SESSION['userid']);
		$body['page_css'] = [base_url('assets/css/ebookDetail.css')];
		$body['pathjs'] = 'ebook/assets/detail_js';
		
		$body['ebook_member'] = $this->db->get_where('ebook_members', ['user_id' => $_SESSION['userid'], 'ebook_id' => $param])->row_array();

		// print_r($body['book']);
		$this->template->load('template', 'ebook/detail', $body);
	}

		/**
	 * View for paket
	 *
	 * @return void
	 */
	public function paket(): void {
		try 
		{
			if(empty($this->input->get('book_no')))
			{
				http_response_code(404);
				die();
			}

			$body['page_js'] = [
				['path' => "https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js", 'defer' => TRUE],
				['path' => base_url('assets/js/ebookPaket.js'), 'defer' => TRUE]
			];
			$body['page_css'] = [
				base_url('assets/css/ebookPaket.css')
			];
			$this->template->load('template', 'ebook/paket', $body);
		}
		catch(Exception $e)
		{
			log_message('error', $e->__toString());
		}
	}

	/**
	 * Lists Books 
	 *
	 * @return void
	 */
	public function list(): void {

		$limit  = $this->input->get('count');
		$page 	= $this->input->get('page');
		$filter = $this->input->get('filter');
		$total	= $this->model_ebook->count_list($filter);
		$offset = $page == 1 ? 0 : ($page - 1) * $limit; 
		
		$data = $this->model_ebook->list($limit, $offset, $filter);

		$json = [
			'data' 		=> $data,
			'totalData' => $total,
		];

		header('X-Total-Count: '.$json['totalData']);
		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}



	public function test() {
		$_ebooks = $this->model_ebook->getEbooks();
		echo '<pre>';
		echo json_encode($_ebooks, JSON_PRETTY_PRINT);
		echo '</pre>';
	}

	/**
	 * Start read a book
	 *
	 * @return void
	 */
	public function open_book(): void {
		$id = $this->input->get('id');
		// only active member that can read the book
		if (!isset($_SESSION['logged_in']) && empty($_SESSION['username'])) {
			$data['heading'] = 'PERINGATAN';
			$data['message'] = '<p>Halaman hanya di peruntukan untuk anggota aktif. Silahkan login terlebih dahulu !!!' .
				'<br/> <a href="' . $_SERVER['HTTP_REFERER'] . '">Kembali</a></p>';
			$this->load->view('errors/html/error_general', $data);
			return;
		}
		// set transaction code
		$transcode = strtoupper(bin2hex(random_bytes(8)));
		// set cookie for reading time limit and idle time limit
		$cookie_option = [
			'expires'	=> strtotime('+' . $this->settings['limit_idle_value'] . ' ' . $this->settings['limit_idle_unit']),
			'path'		=> '/',
			'samesite'	=> 'Lax'
		];

		if (!isset($_COOKIE['read_book']))
			setcookie('read_book', base64_encode(json_encode(['key' => $transcode, 'expired' => date('Y-m-d H:i:s', $cookie_option['expires'])])), $cookie_option);

		// get latest transaction book
		// $latest_transaction = $this->transaction_model->get_latest_transaction($id, $_SESSION['userid']);

		$insert = [
			'trans_code' 	=> $transcode,
			'start_time' 	=> date('Y-m-d H:i:s.u'),
			'member_id' 	=> $_SESSION['userid'],
			'book_id'		=> $id,
		];

		// $book = $this->db->get_where('ebooks', ['id' => $id])->row_array();
		// $url = base_url('ebook/detail/'.$book['book_code']);
		$book = $this->model_ebook->get($id);
		$target = $this->folderTarget;

		if(!file_exists(FCPATH.$target))
			@mkdir(FCPATH.$target, 0777, true);
		$fileBook = str_replace('.pdf', '.wpdf', $book['file_1']);

		if(!file_exists(FCPATH.$target.DIRECTORY_SEPARATOR.$fileBook)) 
			@copy(FCPATH.'assets/files/ebooks/'.$book['file_1'], FCPATH.$target.DIRECTORY_SEPARATOR.$fileBook);

		$query['id'] = $book['book_code'];

		if(!empty($this->input->get('continue'))) {
			$lastRead = $this->transaction_model->get_latest_read_person($_SESSION['userid'], $book['id']);
			$query['lastPage'] = $lastRead['last_page'];
		}

		@chmod(FCPATH.$target.DIRECTORY_SEPARATOR.$fileBook, 0777);

		if($this->db->insert('read_log', $insert))
			$url = $book['from_api'] === 0 ? base_url('ebook/read_book?' . http_build_query($query)) : $book['file_1'];

		// jika buku dari API maka autoclose tab browser
		if($book['from_api'] == 1){
			echo '<script>'
					.'window.localStorage.removeItem("pdfjs.history");'				
					.'window.location.href="'.$url.'";'
					.'if(confirm("Anda akan di arahkan ke mode membaca ebook")) setTimeout(e=>{window.close()},10000);'
				.'</script>';
		}else{
			echo '<script>'
					.'window.localStorage.removeItem("pdfjs.history");'				
					.'window.location.href="'.$url.'";'
				.'</script>';
		}
		
	}

	/**
	 * Read a book
	 *
	 * @return void
	 */
	public function read_book(): void
	{
		
		$id = $this->input->get('id');

		if (!isset($_COOKIE['read_book'])) {
			echo '<script>';
			echo 'window.location.href="' . base_url('ebook/detail/') . $id . '"';
			echo '</script>';
			return;
		}

		$data['book'] = $this->model_ebook->get($id);
		$bookFile = str_replace('.pdf', '.wpdf', $data['book']['file_1']);
		if (empty($data['book']['file_1'])) {
			$_SESSION['error']['message'] = 'Buku tidak di temukan !!!';
			redirect(base_url('ebook/close_book?id='.$id));
			return;
		}

		$data['folderpath'] = $this->folderTarget;
		$data['setting'] = $this->settings;
		$this->load->view('ebook/read', $data);
	}

	/**
	 * Closing after read book
	 *
	 * @return void
	 */
	public function close_book(): void
	{
		$id = $this->input->get('id');
		$book = $this->model_ebook->get($id);

		$lastPage = $this->input->get('last_page') ?? 0;
		$cookie = json_decode(base64_decode($_COOKIE['read_book']), TRUE);

		$update = [
			'trans_code' => $cookie['key'],
			'book_id'  => $book['id'],
			'end_time' => date('Y-m-d H:i:s.u'),
			'last_page'	=> $lastPage
		];
		
		$this->db->update('read_log', $update, ['trans_code' => trim($cookie['key'])]);

		setcookie('read_book', NULL, time() - 1000);
		redirect('ebook/detail/' . $id);
	}


	public function history(){
		$username 	= $this->session->userdata('username');
		$user_level = $this->db->where('username', $username)->get('users')->row_array()['user_level'];
		$page 		= isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$limit 		= isset($_GET['limit']) ? (int)$_GET['limit'] : 3;
		$title		= $_GET['title'];
		$startDate	= $_GET['startDate'];
		$endDate	= $_GET['endDate'];

		$page = ($page - 1) * $limit;

		$data['user_level'] 	= $user_level;
		$data['news'] 			= $this->model_news->get_history($limit, $page, $title, $startDate, $endDate);
		$data['total_records'] 	= $this->model_news->get_total_history($title, $startDate, $endDate);
		$data['total_pages'] 	= ceil($data['total_records'] / $limit);

		// create json header	
		header('Content-Type: application/json');
		echo json_encode($data);
	}

	/**
	 * Save state of book
	 *
	 * @return void
	 */
	public function save_book(): void {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET,POST');

		$post = $_FILES['pdfFile'];
		$annotation = $this->input->post('annotation-info', TRUE);
		$id = $this->input->post('bookId', TRUE);
		$target = $this->folderTarget;
		

		if(empty($post['tmp_name']) || $post['size'] == 0)
		{
			http_response_code(422);
			echo json_encode(['success' => 'error', 'message' => 'Tidak ada berkas terunggah']);
			return;
		}
		
		if(file_exists($target.DIRECTORY_SEPARATOR.$post['name']))
			unlink($target.DIRECTORY_SEPARATOR.$post['name']);

		if(!move_uploaded_file($post['tmp_name'], $target.DIRECTORY_SEPARATOR.basename($post['name'], '.wpdf').'.wpdf')) {
			http_response_code(422);
			echo json_encode(['err_status' => 'error', 'message' => 'Tidak ada berkas terunggah']);
			return;
		}
		$timestamp = date('Y-m-d H:i:s');
		// save annotation history
		if(!empty($annotation)) {
			$input['annotation_text'] = $annotation;
		}
		$book = $this->model_ebook->get($id);
		$input['userid'] = $_SESSION['userid'];
		$input['ebook_id'] = $book['id'];
		$input['created_time'] = $timestamp;

		if(!$this->db->insert('annotation_history', $input))
		{
			http_response_code(422);
			$msg = ['err_status' => 'error', 'message' => $this->lang->line('woow_form_error')];
			echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT);
			return;
		}

		header('Content-Type: application/json');
		echo json_encode(['success' => true, 'message' => 'Data berhasil di simpan']);
	}

	/**
	 * Bookmark a page of a book
	 *
	 * @return void
	 */
	public function bookmark(): void {
		
		$pageIndex = $this->input->post('pageIndex', TRUE);
		$book = $this->input->post('bookId', TRUE);
		$page = $this->input->post('pageText', TRUE);
		$note = $this->input->post('note', TRUE);

		if(empty($pageIndex) || empty($page)) {
			http_response_code(422);
            $msg = ['err_status' => 'error', 'message' => 'Halaman gagal di tandai !!!'];
            echo json_encode($msg, JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_HEX_TAG);
            return;
		}

		$ebook = $this->model_ebook->get($book);

		$input = [
			'trans_code'	=> create_uuid4(),
			'userid' 		=> $_SESSION['userid'],
			'ebook_id'		=> $ebook['id'],
			'page_text' 	=> $page,
			'page_index' 	=> $pageIndex,
			'note'			=> $note,
			'created_time'	=> date('Y-m-d H:i:s')
		];

		if(!$this->db->insert('bookmark_book', $input)) {
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

	/**
	 * 
	 *
	 * @return void
	 */
	public function getSimilar() {
		try
		{
			$penerbit = $this->input->get("penerbit");
			$level = $this->input->get("level");

			$data = $this->db->get_where("ebooks", ["publisher_id" => $penerbit, "class_level" => $level])->result_array();

			header("Content-Type: application/json");
			echo json_encode(["count" => count($data), "data" => $data]);
		}
		catch(Exception $e)
		{
			log_message("error", $e->_toString());
		}
	}

	/**
	 * @return void
	 */
	public function getRekomendasi(){

		$data = $this->model_ebook->getRekomendasi();

		// $limit  = $this->input->get('count');
		// $page 	= $this->input->get('page');
		$limit  = 10;
		$page 	= 1;
		$filter = $this->input->get('filter');
		$offset = $page == 1 ? 0 : ($page - 1) * $limit; 
		
		$data = $this->model_ebook->list($limit, $offset, $filter);

		$json = [
			'data' => $data,
		];

		header('Content-Type: application/json');
		echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
	}

	public function auth(){

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://192.168.1.233:8086/v1/generate_auth',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
				"id_user": '.$_SESSION['userid'].',
				"users_token": "'.$_SESSION['user_token'].'"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);

		curl_close($curl);

		// $url = 'http://'.$_SERVER['HTTP_HOST'].':3001?chip='.$response['data'];

		// echo '<script>
		// 		window.localStorage.setItem("authToken", "'.$response['data'].'");
		// 		window.location.href = "'.$url.'";
		// 	</script>';

		return  $response;
	}
}
