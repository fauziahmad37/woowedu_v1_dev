<?php
class Api_voucher extends MY_Controller
{
	// private $apiUrl = 'http://192.168.1.236:8086/v1/';
	// private $apiUrl = 'http://103.15.226.136:8087/v1/';

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	function getVoucherList()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'vouchers?status=1&page=1&per_page=100',
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

	function useVoucher()
	{
		$post = $this->input->post();
		$curl = curl_init();

		$data = [
			"checkout_id" => $post['checkout_id'],
			"voucher_code" => $post['voucher_code']
		];

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'checkout/update',
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
		$response = json_decode($response, true);

		$response['csrf_token'] = $this->security->get_csrf_hash(); // Update CSRF token in response

		curl_close($curl);
		echo json_encode($response);
	}

	function removeVoucher()
	{
		$post = $this->input->post();
		$curl = curl_init();

		$data = [
			"checkout_id" => $post['checkout_id'],
			"voucher_code" => $post['voucher_code']
		];

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->apiUrl . 'checkout/unreedem/voucher',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $this->session->userdata('jwt_token'),
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		$response = json_decode($response, true);
		$response['csrf_token'] = $this->security->get_csrf_hash(); // Update CSRF token in response

		curl_close($curl);
		echo json_encode($response);
	}
}
