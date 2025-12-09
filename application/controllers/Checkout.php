<?php

class Checkout extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_bundling_package');

		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	function serverKey(){
		return 'SB-Mid-server-Kk4WUNC1k7dZkgpJcX0WMdie';
	}


	/**
	 * Input dagt int check out table for view checkout
	 *
	 * @return void
	 */
	public function addItem()
	{
		try {
			$itemIds = $_POST['item_ids'];
			$itemType = $_POST['item_type'];


			// simpan ke tabel checkouts
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

			$transactionNumber = 'INV' . date('Ymd') . 'BP' . $insert;

			// update snap token kedalam tabel checkout
			// $getSnapToken = $this->getSnapToken($transactionNumber, $dataSave['gross_amount']);
			$paymentLink = $this->createPaymentLinkMidtrans($transactionNumber, $dataSave['gross_amount']);
			// lakukan insert juga kedalam tabel transaction payments untuk menyimpan no transaksinya yang nanti nya akan di gunakan untuk mengecek status transaksi menggunakan midtrans
			$dataTrxPayment = [
				'field_table' => $itemType,
				'field_id'	=> $insert,
				'transaction_number' => $transactionNumber,
				'total_payment' => $dataSave['gross_amount'],
				'payment_link'	=> $paymentLink->redirect_url,
			];
			$this->db->insert('transaction_payments', $dataTrxPayment);



			// $this->db->update('checkouts', ['snap_token' => $getSnapToken], ['id' => $insert]);

			// jika produk nya paket bundling maka simpan data bundling ke dalam checkout_items
			if ($itemType == 'bundle') {
				foreach ($itemIds as $val) {
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
		} catch (Exception $e) {
		}
	}

	public function createPaymentLinkMidtrans($trxNumber, $grossAmount)
	{
		// create curl resource 
		$curl = curl_init();

		$body =  [
			"transaction_details" => [
				"order_id" => $trxNumber,
				"gross_amount" => $grossAmount
			],
			// "customer_required" => false,
			"customer_details" => [
				"first_name" => $_SESSION['nama'],
				"email" => $_SESSION['email'],
			]
		];

		$payload = json_encode($body);

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://app.sandbox.midtrans.com/snap/v1/transactions',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_SSL_VERIFYHOST => false, // tanpa ssl
			CURLOPT_SSL_VERIFYPEER => false, // tanpa ssl
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
				'Content-Type: application/json',
				'Authorization: Basic ' . base64_encode('SB-Mid-server-Kk4WUNC1k7dZkgpJcX0WMdie:'),
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response);
	}

	public function getSnapToken($trxNumber, $totalAmount)
	{
		// Set your Merchant Server Key
		\Midtrans\Config::$serverKey = 'SB-Mid-server-Kk4WUNC1k7dZkgpJcX0WMdie';
		// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		\Midtrans\Config::$isProduction = false;
		// Set sanitization on (default)
		\Midtrans\Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		\Midtrans\Config::$is3ds = true;

		$params = array(
			'transaction_details' => array(
				'order_id' => $trxNumber,
				'gross_amount' => $totalAmount
			)
		);
		$snapToken = \Midtrans\Snap::getSnapToken($params); // bentuk output nya adalah token
		return $snapToken;
	}

	public function getPaymentStatusMidtrans()
	{
		if(isset($_GET['transaction_number'])) {
			$trxNumber = $_GET['transaction_number'];
		} else {
			$trxNumber = $_GET['order_id'];	
		}

		// create curl resource 
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.sandbox.midtrans.com/v2/' . $trxNumber . '/status',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => false, // tanpa ssl
			CURLOPT_SSL_VERIFYPEER => false, // tanpa ssl
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
		curl_close($curl);

		$z =  json_decode($response); // untuk memasukan payment link yang nantinya di gunakan untuk button lanjutkan pembayaran

		// update status transaksi 
		$data = json_decode($response, true);

		// cek status pembayaran di tabel transaksi payment
		$tp = $this->db->where('transaction_number', $trxNumber)->get('transaction_payments')->row_array();

		$z->payment_link = $tp['payment_link']; // masukan payment link

		// jika di tabel transaksi payment status nya null atau pending maka lakukan update
		if ($tp['status'] == null || $tp['status'] == 'pending') {
			$dataUpdate = [
				'status' => $data['transaction_status'],
				'payment_method' => $data['payment_type'],
				'transaction_time' => $data['transaction_time'],
				'expiry_time' => $data['expiry_time'],
				'settlement_time' => isset($data['settlement_time']) ? $data['settlement_time'] : null,
			];

			// start db execution
			$this->db->trans_start();

			$update = $this->db->update('transaction_payments', $dataUpdate, ['transaction_number' => $_GET['transaction_number']]);

			// jika user sudah melakukan pembayaran maka
			// lakukan insert data ke tabel ebook members
			// buat kondisi jika jenis transaksi merupakan paket bundling atau ebook biasa

			if (isset($data['settlement_time'])) {
				if ($tp['field_table'] == 'bundle') {
					$ebooks = $this->model_bundling_package->getEbooksByTrxNumber($_GET['transaction_number']);
				} else {
				}

				foreach ($ebooks as $value) {
					if($value['subscribe_periode'] == '1_month'){
						$durationTime = 30 * 24 * 60 * 60;
					} elseif($value['subscribe_periode'] == '3_month'){
						$durationTime = 90 * 24 * 60 * 60;
					} elseif($value['subscribe_periode'] == '6_month'){
						$durationTime = 180 * 24 * 60 * 60;
					} elseif($value['subscribe_periode'] == '12_month'){
						$durationTime = 365 * 24 * 60 * 60;
					} 

					$startActivation = strtotime($data['settlement_time']);
					$endActivation = $startActivation + $durationTime;

					$dataEbook = [
						'ebook_id' => $value['ebook_id'],
						'user_id' => $value['user_id'],
						'read_status' => 0,
						'start_activation' => date('Y-m-d H:i:s', $startActivation),
						'end_activation' => date('Y-m-d H:i:s', $endActivation),
					];
					$this->db->insert('ebook_members', $dataEbook);
				}
			}

			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return false;
			}
			$this->db->trans_commit();
		}

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($z);
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


		$co = $this->db->where('id', $id)->get('checkouts')->row_array();
		$trx = $this->db->where('field_id', $id)->get('transaction_payments')->row_array();

		$data['checkout_id'] = $co['id'];
		$data['payment_link'] = $trx['payment_link'];
		$data['transaction_number'] = $trx['transaction_number'];
		$data['snapToken'] = $co['snap_token'];
		$this->template->load('template', 'checkout/payment', $data);
	}

	public function statusPembayaran()
	{
		$data['page_css'] = [base_url('assets/css/_bundling_package.css')];

		$get = $this->input->get();
		$data['transaction_number'] = isset($get['transaction_number']) ? $get['transaction_number'] : $get['order_id'];

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
		$this->template->load('template', 'bundling_package/payment_status', $data);
	}

	public function cancelTransaction()
	{
		$trxNumber = $_GET['transaction_number'];
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.sandbox.midtrans.com/v2/'.$trxNumber.'/cancel',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_SSL_VERIFYHOST => false, // tanpa ssl
			CURLOPT_SSL_VERIFYPEER => false, // tanpa ssl
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json',
				'Content-Type: application/json',
				'Authorization: Basic ' . base64_encode($this->serverKey().':'),
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		header('Content-Type: application/json; charset=utf-8');
		echo $response;
	}
}
