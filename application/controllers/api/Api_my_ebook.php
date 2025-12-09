<?php
class Api_my_ebook extends MY_Controller
{
	// private $apiUrl = 'http://192.168.1.236:8086/v1/';
	// private $apiUrl = 'http://103.15.226.136:8087/v1/';

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	function getMyEbook()
	{
		$get = $this->input->get();
		$data['read_status'] = isset($get['read_status']) ? $get['read_status'] : '';
		$data['category_ids'] = isset($get['category_id']) ? $get['category_id'] : '';
		$data['created_at'] = isset($get['tanggal_awal']) ? $get['tanggal_awal'] : '';
		// $data['tanggal_akhir'] = isset($get['tanggal_akhir']) ? $get['tanggal_akhir'] : '';
		$data['page'] = isset($get['page']) ? $get['page'] : 1;
		$data['limit'] = isset($get['limit']) ? $get['limit'] : 10;

		if ($data['read_status'] === '0') $data['read_status'] = 0;
		if ($data['read_status'] === '1') $data['read_status'] = 1;
		if ($data['read_status'] === '2') $data['read_status'] = 2;
		if ($data['read_status'] === '') unset($data['read_status']);

		if (!$data['created_at']) unset($data['created_at']);
		if (!$data['category_ids']) unset($data['category_ids']);

		$baseUrl = $this->apiUrl . 'my_ebook/my-ebooks?';
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

	function getDetailMyEbook()
	{
		$get = $this->input->get();
		$ebook_id = isset($get['ebook_id']) ? $get['ebook_id'] : '';

		if (empty($ebook_id)) {
			echo json_encode(['error' => 'Ebook ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'my_ebook/my-ebooks/' . $ebook_id,
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

	function giveRating()
	{
		$post = $this->input->post();
		$ebook_id = isset($post['ebook_id']) ? $post['ebook_id'] : '';
		$rating = isset($post['rating']) ? $post['rating'] : '';
		$review = isset($post['review']) ? $post['review'] : '';

		if (empty($ebook_id) || empty($rating)) {
			echo json_encode(['error' => 'Ebook ID and rating are required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'ratings/upsert',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode(['book_id' => $ebook_id, 'rate' => $rating, 'review' => $review]),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);

		$response['csrf_token'] = $this->security->get_csrf_hash();

		curl_close($curl);
		echo json_encode($response);
	}

	function updateReadStatus()
	{
		$get = $this->input->get();
		$ebook_id = isset($get['ebook_id']) ? $get['ebook_id'] : '';
		$data['read_status'] = isset($get['read_status']) ? $get['read_status'] : '';

		$baseUrl = $this->apiUrl . 'my_ebook/my-ebooks/' . $ebook_id . '/read/status?';
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
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token')
			),
		));

		$response = curl_exec($curl);
		echo $response;
	}

	function updateLastPage()
	{
		$get = $this->input->get();
		$ebook_id = isset($get['ebook_id']) ? $get['ebook_id'] : '';
		$data['status'] = isset($get['last_page']) ? $get['last_page'] : '';

		$baseUrl = $this->apiUrl . 'my_ebook/my-ebooks/' . $ebook_id . '/last_page?';
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
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
			),
		));

		$response = curl_exec($curl);
		echo $response;
	}
}
