<?php

use FontLib\Table\Type\head;

class Api_ebook extends MY_Controller
{
	// private $apiUrl = 'http://192.168.1.236:8086/v1/';
	// private $apiUrl = 'http://103.15.226.136:8087/v1/';
	

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('model_ebook');
	}

	/**
	 * Get all ebooks
	 * @return json
	 */
	function getAllEbooks(){
		$get = $this->input->get();
		$data['page'] = isset($get['page']) ? $get['page'] : 1;
		$data['per_page'] = isset($get['per_page']) ? $get['per_page'] : 10;

		if(isset($get['category'])){
			$data['category_id'] = $get['category'];
		}

		if(isset($get['publisher_id'])){
			$data['publisher_id'] = $get['publisher_id'];
		}

		$data['title'] = isset($get['title']) ? $get['title'] : '';
		$baseUrl = $this->apiUrl . 'ebooks?';
		$url = $baseUrl . http_build_query($data);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getAllCategories(){
		$get = $this->input->get();

		$data['category_name'] = isset($get['category_name']) ? $get['category_name'] : '';
		$data['page'] = isset($get['page']) ? $get['page'] : 1;
		$data['per_page'] = isset($get['per_page']) ? $get['per_page'] : 10;
		$data['with_children'] = isset($get['with_children']) ? $get['with_children'] : false;
		$data['only_main_category'] = isset($get['only_main_category']) ? $get['only_main_category'] : false;
		
		$baseUrl = $this->apiUrl . 'ebooks/categories?';
		$url = $baseUrl . http_build_query($data);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getAllPublisher(){

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'publisher',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getEbookDetail()
	{
		$get = $this->input->get();
		$ebook_id = isset($get['ebook_id']) ? $get['ebook_id'] : '';

		if (empty($ebook_id)) {
			echo json_encode(['error' => 'Ebook ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebooks/' . $ebook_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}


	function getPilihanKategoriMenarik(){
		$get = $this->input->get();
		$data['page'] = isset($get['page']) ? $get['page'] : 1;
		$data['per_page'] = isset($get['per_page']) ? $get['per_page'] : 10;

		$baseUrl = $this->apiUrl . 'ebooks/homepage/category?';
		$url = $baseUrl . http_build_query($data);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getPilihanKategoriMenarikById(){
		$get = $this->input->get();
		$id = isset($get['id']) ? (int)$get['id'] : 0;
		$page = isset($get['page']) ? $get['page'] : 1;
		$perPage = isset($get['per_page']) ? $get['per_page'] : 10;

		if (empty($id) || $id <= 0) {
			echo json_encode(['error' => 'ID is required and must be greater than 0']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebooks/homepage/category/detail/' . $id .'?page=' . $page . '&per_page=' . $perPage,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getEbookByNewest()
	{
		$get = $this->input->get();
		$page = isset($get['page']) ? $get['page'] : 1;
		$perPage = isset($get['per_page']) ? $get['per_page'] : 10;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebooks?order_by_newest=true&page=' . $page . '&per_page=' . $perPage,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getEbookByClassId(){
		$get = $this->input->get();
		$class_id = isset($get['class_id']) ? $get['class_id'] : '';
		$page = isset($get['page']) ? $get['page'] : 1;
		$perPage = isset($get['per_page']) ? $get['per_page'] : 10;

		if (empty($class_id)) {
			echo json_encode(['error' => 'Class ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebooks?class_level=' . $class_id . '&page=' . $page . '&per_page=' . $perPage,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getMostRead()
	{
		$get = $this->input->get();
		$page = isset($get['page']) ? $get['page'] : 1;
		$perPage = isset($get['per_page']) ? $get['per_page'] : 10;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebooks/most-read?page=' . $page . '&per_page=' . $perPage,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getRating()
	{
		$data = $this->input->get();
		$data['rating_level'] = isset($data['rating_level']) ? $data['rating_level'] : '';

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ratings?book_id=' . $data['book_id'] . '&rating_level=' . $data['rating_level'] . '&page=' . $data['page'] . '&per_page=' . $data['per_page'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getSimilarEbook()
	{
		$data = $this->input->get();
		$book_id = isset($data['book_id']) ? $data['book_id'] : '';

		if (empty($book_id)) {
			echo json_encode(['error' => 'Book ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebooks/' . $book_id . '/similar?page=' . $data['page'] . '&per_page=' . $data['per_page'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getBundlingPackages()
	{
		$data = $this->input->get();
		$data['page'] = isset($data['page']) ? $data['page'] : 1;
		$data['per_page'] = isset($data['per_page']) ? $data['per_page'] : 10;
		$data['order_by_newest'] = isset($data['order_by_newest']) ? $data['order_by_newest'] : 'true';
		$data['title'] = isset($data['title']) ? $data['title'] : '';

		if(isset($data['publisher_id']) && !empty($data['publisher_id'])){
			$data['id_publisher'] = $data['publisher_id'];
		}

		$baseUrl = $this->apiUrl . 'ebooks/bundling-packages?';
		$url = $baseUrl . http_build_query($data);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Accept: application/json'
			),
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			echo $response;
		}

		curl_close($curl);
	}

	function getDetailPaketBundling()
	{
		$data = $this->input->get();
		$package_id = isset($data['package_id']) ? $data['package_id'] : '';

		if (empty($package_id)) {
			echo json_encode(['error' => 'Package ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebooks/bundling-packages/' . $package_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Accept: application/json'
			),
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			echo $response;
		}

		curl_close($curl);
	}



	function checkout()
	{
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		$tax = isset($data['tax']) ? $data['tax'] : 0;
		$biaya_admin = isset($data['biaya_admin']) ? $data['biaya_admin'] : 0;
		$items = isset($data['items']) ? $data['items'] : [];

		if (empty($items)) {
			echo json_encode(['error' => 'Items are required']);
			return;
		}

		$itemsJson = json_encode($items);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'checkout',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
				"tax": ' . $tax . ',
				"biaya_admin": ' . $biaya_admin . ',
				"items": ' . $itemsJson . '
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getDetailCheckout()
	{
		$data = $this->input->get();
		$checkout_id = isset($data['checkout_id']) ? $data['checkout_id'] : '';

		if (empty($checkout_id)) {
			echo json_encode(['error' => 'Checkout ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'checkout/' . $checkout_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Accept: application/json'
			),
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			echo $response;
		}

		curl_close($curl);
	}

	function makePayment()
	{
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		$checkout_id = isset($data['checkout_id']) ? $data['checkout_id'] : '';
		$callback_url = isset($data['callback_url']) ? $data['callback_url'] : '';

		if (empty($checkout_id)) {
			echo json_encode(['error' => 'Checkout ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'transaction_payments/make_payment',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
			CURLOPT_POSTFIELDS => '{
				"checkout_id": ' . $checkout_id . ',
				"callback_url": "' . $callback_url . '"
			}',
		));

		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		if ($httpCode >= 400) {
			echo json_encode(['message' => json_decode($response, true)['message'], 'http_code' => $httpCode, 'status' => false, 'csrf_token' => $this->security->get_csrf_hash()]);
		} else {
			$responseData = json_decode($response, true);
			$responseData['csrf_token'] = $this->security->get_csrf_hash(); // Add CSRF token to the response
			$response = json_encode($responseData);
			echo $response;
		}

		curl_close($curl);
	}

	function getAllTransaction()
	{
		$data = $this->input->get();
		$data['page'] = isset($data['page']) ? $data['page'] : 1;
		$data['per_page'] = isset($data['per_page']) ? $data['per_page'] : 10;
		$data['status'] = isset($data['status']) ? $data['status'] : '';
		$data['start_date'] = isset($data['start_date']) ? $data['start_date'] : '';
		$data['end_date'] = isset($data['end_date']) ? $data['end_date'] : '';
		$baseUrl = $this->apiUrl . 'transaction_payments?';
		$url = $baseUrl . http_build_query($data);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Accept: application/json'
			),
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			echo $response;
		}

		curl_close($curl);
	}
	
	function updateTransactionStatus()
	{
		$data = $this->input->get();
		$transaction_number = isset($data['transaction_number']) ? $data['transaction_number'] : '';

		if (empty($transaction_number)) {
			echo json_encode(['error' => 'Transaction number is required']);
			return;
		}

		$data = [
			'status' => $data['status'],
			'payment_method' => $data['payment_method'],
			'settlement_time' => $data['settlement_time'],
		];

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'transaction_payments/' . $transaction_number,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'PATCH',
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getTransactionStatus()
	{
		$data = $this->input->get();
		$transaction_number = isset($data['transaction_number']) ? $data['transaction_number'] : '';
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'transaction_payments/status/' . $transaction_number,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getTransactionDetail()
	{
		$data = $this->input->get();
		$transaction_number = isset($data['transaction_number']) ? $data['transaction_number'] : '';

		if (empty($transaction_number)) {
			echo json_encode(['error' => 'Transaction number is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'transaction_payments/' . $transaction_number,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	function getEbookPaket()
	{
		$data = $this->input->get();
		$book_no = isset($data['ebook_id']) ? $data['ebook_id'] : '';
		$subscribe_period = isset($data['subscribe_period']) ? $data['subscribe_period'] : '';

		if (empty($book_no)) {
			echo json_encode(['error' => 'Book number is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ebook_subscribe?ebook_id=' . $book_no . '&subscribe_period=' . $subscribe_period,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Accept: application/json'
			),
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
			echo 'Curl error: ' . curl_error($curl);
		} else {
			echo $response;
		}

		curl_close($curl);
	}

	function saveEbookListGuru(){
		$post = $this->input->post();

		$teacher_id = isset($post['teacher_id']) ? $post['teacher_id'] : '';
		$title = isset($post['title']) ? $post['title'] : '';
		$description = isset($post['description']) ? $post['description'] : '';
		$ebooks = isset($post['ebooks']) ? json_decode($post['ebooks'], true) : [];

		if (empty($teacher_id) || empty($title) || empty($description) || empty($ebooks)) {
			echo json_encode(['error' => 'All fields are required']);
			return;
		}

		$data = [
			'teacher_id' => $teacher_id,
			'title' => $title,
			'description' => $description,
			'no_urut' => 0
		];

		$this->db->insert('ebook_teachers', $data);
		$insert = $this->db->insert_id();

		if($insert){
			foreach ($ebooks as $ebook) {
				$this->db->insert('ebook_teacher_details', [
					'ebook_id' => (int)$ebook,
					'ebook_teacher_id' => $insert
				]);
			}
			echo json_encode([
				'success' => true, 
				'message' => 'Data berhasil di simpan!',
				'csrf_token' => $this->security->get_csrf_hash()
			]);
		} else {
			echo json_encode([
				'success' => false,
				'message' => 'Data gagal di simpan!',
				'csrf_token' => $this->security->get_csrf_hash()
			]);
		}
	}

	function saveEbookTeacherDetail(){
		$post = $this->input->post();

		$ebook_teacher_id = isset($post['ebook_teacher_id']) ? $post['ebook_teacher_id'] : '';
		
		$ebooks = isset($post['ebooks']) ? json_decode($post['ebooks'], true) : [];

		if (empty($ebook_teacher_id) || empty($ebooks)) {
			echo json_encode(['error' => 'All fields are required']);
			return;
		}

		foreach ($ebooks as $ebook) {
			$this->db->insert('ebook_teacher_details', [
				'ebook_id' => (int)$ebook,
				'ebook_teacher_id' => $ebook_teacher_id
			]);
		}

		echo json_encode([
			'success' => true, 
			'message' => 'Data berhasil di simpan!',
			'csrf_token' => $this->security->get_csrf_hash()
		]);
	}

	function deleteAllEbookTeacher(){
		$post = $this->input->post();
		$ebook_teacher_id = isset($post['ebook_teacher_id']) ? $post['ebook_teacher_id'] : '';

		if (empty($ebook_teacher_id)) {
			echo json_encode(['error' => 'Ebook teacher ID is required']);
			return;
		}

		$this->db->where('id', $ebook_teacher_id);
		$this->db->delete('ebook_teachers');

		echo json_encode([
			'success' => true,
			'message' => 'All ebook details deleted successfully!',
			'csrf_token' => $this->security->get_csrf_hash()
		]);
	}

	function ebookListRekomendasiGuru()
	{
		$get = $this->input->get();
		$class_id = isset($get['class_id']) ? $get['class_id'] : '';

		if (empty($class_id)) {
			echo json_encode(['error' => 'Class ID is required']);
			return;
		}

		$data = $this->model_ebook->getEbookListRekomendasiGuru($class_id);
		foreach ($data as $key => $value) {
			$data[$key]['ebooks'] = $this->model_ebook->getDetailEbookListGuru($value['id']);
		}

		$res = [
			'success' => true,
			'message' => 'Data Berhasil di temukan',
			'data' => $data
		];

		header('Content-Type: application/json');
		echo json_encode($res);
	}
}
