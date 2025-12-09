<?php
class Api_wishlist extends MY_Controller
{
	// private $apiUrl = 'http://192.168.1.236:8086/v1/';
	// private $apiUrl = 'http://103.15.226.136:8087/v1/';

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	function checkWishlistByEbookId()
	{
		$get = $this->input->get();
		$ebook_id = isset($get['ebook_id']) ? $get['ebook_id'] : '';

		if (empty($ebook_id)) {
			echo json_encode(['error' => 'Ebook ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'wishlist/ebook/' . $ebook_id,
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

	function addToWishlist()
	{
		$post = $this->input->post();
		$ebook_id = isset($post['ebook_id']) ? $post['ebook_id'] : '';
		$item_type = isset($post['item_type']) ? $post['item_type'] : 'ebook';

		$dataPost = [
			'item_id' => (int)$ebook_id,
			'item_type' => $item_type,
			'liked' => 'Y',
		];

		if (empty($ebook_id)) {
			echo json_encode(['error' => 'Ebook ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'wishlist/upsert',
			CURLOPT_POSTFIELDS => json_encode($dataPost),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Content-Type: application/json',
				
			),
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response['csrf_token'] = $this->security->get_csrf_hash();

		curl_close($curl);
		echo json_encode($response);
	}

	function removeFromWishlist()
	{
		$post = $this->input->post();
		$ebook_id = isset($post['ebook_id']) ? $post['ebook_id'] : '';
		$item_type = isset($post['item_type']) ? $post['item_type'] : 'ebook';

		if (empty($ebook_id)) {
			echo json_encode(['error' => 'Ebook ID is required']);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'wishlist/remove',
			CURLOPT_POSTFIELDS => json_encode(['item_id' => (int)$ebook_id, 'item_type' => $item_type]),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Content-Type: application/json',
			),
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response['csrf_token'] = $this->security->get_csrf_hash();

		curl_close($curl);
		echo json_encode($response);
	}

	function getAllWishlist()
	{
		$get = $this->input->get();
		$page = isset($get['page']) ? $get['page'] : 1;
		$limit = isset($get['limit']) ? $get['limit'] : 100;
		$order = isset($get['order_by_newest']) ? $get['order_by_newest'] : false;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'wishlist?order_by_newest=' . ($order ? 'true' : 'false') . '&page=' . $page . '&per_page=' . $limit,
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
}
