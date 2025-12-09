<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wishlist extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	function __construct()
	{
		parent::__construct();
		$this->load->model(['model_kelas', 'model_ebook', 'model_wishlist', 'model_task']);
		$this->load->helper('assets');

		if (!isset($_SESSION['username'])) redirect('auth/login');
	}

	public function get(): void
	{
		try {
			$user = $_SESSION['userid'];
			$book = $this->input->get("item_id", TRUE);

			$get = $this->db->get_where("wishlists", ['user_id' => $user, "item_id" => $book]);

			$isLiked = FALSE;
			if ($get->num_rows() > 0) {
				$isLiked = TRUE;
			}

			echo json_encode(['isLiked' => $isLiked], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
		} catch (Exception $e) {
			log_message("error", $e->_toString());
		}
	}

	public function post(): void
	{
		$user = $_SESSION['userid'];
		$book = ($_POST['item_type'] == 'bundling') ? $_POST['item_id'] : $this->input->post("ebook_id", TRUE);

		header("Content-Type: application/json");

		// ambil data buku / paket bundling
		if ($_POST['item_type'] == 'bundling') {
			$get = $this->db->get_where("wishlists", ['user_id' => $user, "item_id" => $book, 'item_type' => 'bundling']);
		} else {
			$get = $this->db->get_where("wishlists", ['user_id' => $user, "item_id" => $book, 'item_type' => 'ebook']);
		}

		$ret = FALSE;
		$limit = FALSE;
		if ($get->num_rows() > 0) {
			$this->db->delete("wishlists", ['user_id' => $user, "item_id" => $book, 'item_type' => ($_POST['item_type'] == 'bundling') ? $_POST['item_type'] : 'ebook']);
			$ret = FALSE;
			$message = 'Berhasil di hapus dari wishlist';
		} else {
			// cek total wishlist. jika lebih dari 10 item tidak boleh menambahkan wishlist lagi
			$cek = $this->db->get_where('wishlists', ['user_id' => $user])->num_rows();
			if($cek >= 10){
				$message = 'Silahkan atur ulang daftar wishlist yang kamu miliki untuk menambahkan yang baru';

				echo json_encode(['isLiked' => $ret, 'limit' => true, 'message' => $message, "csrf_name" => $this->security->get_csrf_token_name(), "csrf_token" => $this->security->get_csrf_hash()], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
				exit;
			}

			$this->db->insert("wishlists", ['user_id' => $user, "item_id" => $book, 'item_type' => ($_POST['item_type'] == 'bundling') ? $_POST['item_type'] : 'ebook']);
			$ret = TRUE;
			$message = 'Berhasil di tambahkan ke wishlist';
		}

		echo json_encode(['isLiked' => $ret, 'limit' => $limit, 'message' => $message, "csrf_name" => $this->security->get_csrf_token_name(), "csrf_token" => $this->security->get_csrf_hash()], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
	}

	public function getAll()
	{
		// ambil berdasarkan publiher
		$data = $this->model_wishlist->get()->result_array();

		header("Content-Type: application/json");
		echo json_encode(['data' => $data], JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
	}
}
