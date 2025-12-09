<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require FCPATH . 'vendor/autoload.php';
// require_once BASEPATH . 'core/CodeIgniter.php';

class BundlingPackage extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model(['model_ebook', 'model_settings', 'transaction_model', 'model_bundling_package', 'model_bundling_package_book']);

		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function index()
	{
	}

	public function detail($id = '')
	{
		$data['page_css'] = [base_url('assets/css/ebookDetail.css')];

		$data['packages'] = $this->model_bundling_package->get();
		$data['package'] = $this->model_bundling_package->getById($id);
		$data['package_items'] = $this->model_bundling_package_book->getByIdBundle($id);

		// echo(json_encode($data['data']));die;
		$data['wishlist'] = $this->db->get_where('wishlists', ['user_id' => $_SESSION['userid'], 'item_id'=>$id, 'item_type'=>'bundling'])->row_array();
		$data['cart'] = $this->db->get_where('shopping_cart', ['user_id' => $_SESSION['userid'], 'item_id'=>$id, 'item_type'=>'bundling'])->row_array();

		$this->template->load('template', 'bundling_package/detail', $data);
	}

	public function checkout($id = '')
	{
		$data['page_css'] = [base_url('assets/css/_bundling_package.css')];

		if (isset($_POST['simpan'])) {
			$dataSave = [
				'user_id' => $_SESSION['userid'],
				'tax'	=> (int)$_POST['tax'],
				'discount' => (int)$_POST['discount'],
				'total_price' => (float)trim(str_replace(['.', ' ', 'Rp'], '', $_POST['total_price'])),
				'voucher_id' => 0,
				'biaya_admin' => (int)$_POST['biaya_admin'],
				'gross_amount' => (float)trim(str_replace(['.', ' ', 'Rp'], '', $_POST['gross_amount'])),
			];

			$this->db->insert('checkouts', $dataSave);
			$insert = $this->db->insert_id();

			foreach ($_POST['item_ids'] as $val) {
				$bp = $this->db->get_where('bundling_packages', ['id' => $val])->row_array();
				$dataItems = [
					'checkout_id' => $insert,
					'item_id' => $val,
					'item_name' => $bp['package_name'],
					'item_price' => $bp['price'],
					'item_qty' => 1,
				];
				$this->db->insert('checkout_items', $dataItems);
			}

			if ($insert) {
				$res = [
					'success' => true,
					'message' => 'Data berhasil disimpan',
					'data' => ['id' => $insert]
				];
			} else {
				$res = [
					'success' => false,
					'message' => 'Data gagal disimpan',
					'data' => ''
				];
			}
			header("Content-Type: application/json");
			echo json_encode($res, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
			die();
		}

		$data['data'] = $this->db->select('bp.*, p.publisher_name')->where('bp.id', $id)
			->join('publishers p', 'p.id=bp.publisher_id', 'left')
			->get('bundling_packages bp')->row_array();

		$this->template->load('template', 'bundling_package/checkout', $data);
	}

	public function payment($id = '')
	{
		// function cek payment status berdasarkan nomor invoice
		// function cekPaymentStatus($trNumber)
		// {
		// 	// create curl resource 
		// 	$curl = curl_init();

		// 	curl_setopt_array($curl, array(
		// 		CURLOPT_URL => 'https://api.sandbox.midtrans.com/v2/' . $trNumber . '/status',
		// 		CURLOPT_RETURNTRANSFER => true,
		// 		CURLOPT_ENCODING => '',
		// 		CURLOPT_MAXREDIRS => 10,
		// 		CURLOPT_TIMEOUT => 0,
		// 		CURLOPT_FOLLOWLOCATION => true,
		// 		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		// 		CURLOPT_CUSTOMREQUEST => 'GET',
		// 		CURLOPT_HTTPHEADER => array(
		// 			'Accept: application/json',
		// 			'Content-Type: application/json',
		// 			'Authorization: Basic ' . base64_encode('SB-Mid-server-Kk4WUNC1k7dZkgpJcX0WMdie:'),
		// 		),
		// 	));

		// 	$response = curl_exec($curl);
		// 	curl_close($curl);
		// 	return json_decode($response);
		// }

		$data['page_css'] = [base_url('assets/css/_bundling_package.css')];

		$post = $this->input->post();
		// require_once 'vendor/midtrans/midtrans-php/Midtrans.php';
		// Set your Merchant Server Key
		// \Midtrans\Config::$serverKey = 'SB-Mid-server-Kk4WUNC1k7dZkgpJcX0WMdie';
		// // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		// \Midtrans\Config::$isProduction = false;
		// // Set sanitization on (default)
		// \Midtrans\Config::$isSanitized = true;
		// // Set 3DS transaction for credit card to true
		// \Midtrans\Config::$is3ds = true;

		// cek di transaction_payments sudah ada atau belum, jika belum ada maka insert transaksi baru

		// $co = $this->db->where('id', $id)
		// 	->get('checkouts c')->row_array();


		// $transactionNumber = 'INV' . date('Ymd') . 'BP' . $co['id'];

		// jika transaksi belum ada maka lakukan insert kedalam tabel transaction_payments
		// $cekPayment = $this->db->where('field_id', $id)->get('transaction_payments')->row_array();
		// if (!$cekPayment) {
		// 	$dataInsert = [
		// 		'field_table' => 'bundle',
		// 		'field_id' => $co['id'],
		// 		'transaction_number' => $transactionNumber,
		// 		'total_payment' => $co['gross_amount'],
		// 		'status' => 'pending'
		// 	];

		// 	$insert = $this->db->insert('transaction_payments', $dataInsert);

		// 	$params = array(
		// 		'transaction_details' => array(
		// 			'order_id' => $transactionNumber,
		// 			'gross_amount' => $co['gross_amount'],
		// 		)
		// 	);

		// 	$snapToken = \Midtrans\Snap::getSnapToken($params); // bentuk output nya adalah token
		// 	// update checkouts isi snap token nya
		// 	$this->db->update('checkouts', ['snap_token' => $snapToken], ['id' => $id]);

		// } else {
			
			// jika transaksi ada maka lakukan cek status payment 

		// }

		// $cek = cekPaymentStatus($transactionNumber);
		// if ($cek->status_code == '404') {
		// 	$dataInsert = [
		// 		'field_table' => 'bundle',
		// 		'field_id' => $co['id'],
		// 		'transaction_number' => $transactionNumber,
		// 		'total_payment' => $co['gross_amount'],
		// 		'status' => 'pending'
		// 	];

		// 	$insert = $this->db->insert('transaction_payments', $dataInsert);

		// 	$params = array(
		// 		'transaction_details' => array(
		// 			'order_id' => $transactionNumber,
		// 			'gross_amount' => $co['gross_amount'],
		// 		)
		// 	);

		// 	$snapToken = \Midtrans\Snap::getSnapToken($params); // bentuk output nya adalah token
		// 	// update checkouts isi snap token nya
		// 	$this->db->update('checkouts', ['snap_token' => $snapToken], ['id' => $id]);
		// } else {
		// 	$snapToken = $co['snap_token'];
		// }


		//INV/202410221654/BP/1

		// $snapToken = '46a59ab5-a730-4f61-91b2-50c8cf9c890a';



		// $data['transaction_number'] = $transactionNumber;
		// $data['snapToken'] = $snapToken;
		$this->template->load('template', 'bundling_package/payment', $data);
	}

	public function getTransactionStatus()
	{
		$transactionNumber = $_GET['transaction_number'];
		// create curl resource 
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.sandbox.midtrans.com/v2/' . $transactionNumber . '/status',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
				'Content-Type: application/json',
				'Authorization: Basic ' . base64_encode('SB-Mid-server-Kk4WUNC1k7dZkgpJcX0WMdie:'),
			),
		));

		$response = curl_exec($curl);
		$res = json_decode($response);
		// update transaksi status
		if ($res->status_code !== '404') {
			//jika transaksi status == settlement maka lakukan update transaction status dan item buku2 nya di insert kedalam tabel books_member
			if ($res->transaction_status == 'settlement') {
				// cek data order_id / transaction number di tabel transaction_payment jika status nya masih null 
				// maka lalukan update transaction_payments
				$tp = $this->db->where('transaction_number', $res->order_id)->get('transaction_payments')->row_array();
				$checkoutId = $tp['field_id'];

				if ($tp['status'] == null) {
					// lakukan update transaction_payments
					$this->db->update('transaction_payments', ['status' => $res->transaction_status, 'payment_method' => $res->payment_type], ['transaction_number' => $transactionNumber]);

					// lakukan insert buku bundling kedalam tabel member books
					$ck = $this->db->where('checkout_id', $checkoutId)
						->join('checkouts ck', 'ck.id=c.checkout_id', 'left')
						->join('bundling_packages bp', 'bp.id=c.item_id', 'left')
						->join('bundling_package_books bpb', 'bp.id=bpb.bundling_package_id', 'left')
						->get('checkout_items c')->result_array();

					foreach ($ck as $val) {
						$dataInsert = [
							'ebook_id' => $val['ebook_id'],
							'user_id' => $val['user_id'],
							'start_activation' => date('Y-m-d H:i:s'),
							'end_activation' => date('Y-m-d H:i:s', strtotime('+30 days')),
						];

						$this->db->insert('ebook_members', $dataInsert);
					}
				}
			}
		}

		curl_close($curl);
		header("Content-Type: application/json");
		echo json_encode($response, JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
	}

	public function statusPembayaran()
	{
		$data['page_css'] = [base_url('assets/css/_bundling_package.css')];

		// $data['data'] = (json_decode($_GET['data']));
		// if($data['data']->transaction_status == 'settlement'){
		// 	// cek data order_id / transaction number di tabel transaction_payment
		// 	$tp = $this->db->where('transaction_number', $data['data']->order_id)->get('transaction_payments')->row_array();
		// 	if($tp){
		// 		// jika 
		// 		if($tp['status'] == 'null' || $tp['status'] == 'pending'){
		// 			var_dump($tp->row_array());
		// 			die;
		// 		}
		// 	}
		// }

		// $this->template->load('template', 'bundling_package/payment_status', $data);
		$this->template->load('template', 'bundling_package/payment_status');
	}
}
