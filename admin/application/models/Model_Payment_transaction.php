<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_payment_transaction extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	// function to get all records from a table
	public function get_ebook($filter = [], $limit = 10, $offset = 0)
	{
		$this->db->select('e.id, e.title, e.author, e.publish_year, e.cover_img, e.category_id, e.price, e.qty, p.publisher_name');
		$this->db->from('ebooks e');
		// $this->db->join('categories c', 'e.category_id = (c.category_code)::text', 'left');
		$this->db->join('publishers p', 'e.publisher_id = p.id', 'left');
		$this->db->join('ebooks_categories c', 'c.ebook_id = e.id', 'left');

		if (!empty($filter['search'])) {
			$this->db->like('lower(e.title)', strtolower($filter['search']));
		}

		if (!empty($filter['kategori'])) {
			$this->db->where_in('c.category_id', $filter['kategori']);
		}

		if (!empty($filter['publisher_id'])) {
			$this->db->where('e.publisher_id', $filter['publisher_id']);
		}

		$this->db->group_by('e.id, e.title, e.author, e.publish_year, e.cover_img, e.category_id, e.price, e.qty, p.publisher_name');

		$this->db->limit($limit, $offset);
		$query = $this->db->get();

		return $query->result_array();
	}


	// function to get count of records from a table
	public function get_ebook_count($search = '', $kategori = [])
	{
		$this->db->select('count(e.id) as total');
		$this->db->from('ebooks e');
		$this->db->join('categories c', 'e.category_id = (c.id)::text', 'left');

		if ($search) {
			$this->db->like('lower(e.title)', strtolower($search));
		}

		if ($kategori) {
			$this->db->where_in('e.id', $kategori);
		}

		$query = $this->db->get();

		return $query->row()->total;
	}

	// Get All Transaction Payment
	public function getAll($filter = [], $limit = 10, $offset = 0, $order = [])
	{
		$this->db->select('tp.status, tp.transaction_number, tp.transaction_time, tp.settlement_time, tp.buyer_name, tp.field_table, 
			tp.field_id, tp.total_payment, c.tax, c.discount, c.biaya_admin');
		$this->db->from('transaction_payments tp');
		$this->db->join('checkouts c', 'c.id = (tp.field_id)::int', 'left');

		// filter jenis paket
		if(isset($filter[0]['search']['value']) && $filter[0]['search']['value'] != '') {
			$this->db->where('tp.field_table', $filter[0]['search']['value']);
		}

		// filter tanggal transaksi
		if(isset($filter[1]['search']['value']) && $filter[1]['search']['value'] != '') {
			$this->db->where('date(tp.settlement_time)', $filter[1]['search']['value']);
		}

		// filter status transaksi
		if(isset($filter[2]['search']['value']) && $filter[2]['search']['value'] != '') {
			$this->db->where('tp.status', $filter[2]['search']['value']);
		}

		if(isset($order[0]['column']) && $order[0]['column'] == 0) {
			$this->db->order_by('tp.total_payment', $order[0]['dir']);
		}

		$this->db->limit($limit, $offset);
		$query = $this->db->get();

		return $query->result_array();
	}

	// Get Total Payment Transaction

	public function countAll($filter = [])
	{
		$this->db->select('count(tp.id) as total');
		$this->db->from('transaction_payments tp');
		$this->db->join('checkouts c', 'c.id = (tp.field_id)::int', 'left');

		// filter jenis paket
		if(isset($filter[0]['search']['value']) && $filter[0]['search']['value'] != '') {
			$this->db->where('tp.field_table', $filter[0]['search']['value']);
		}

		// filter tanggal transaksi
		if(isset($filter[1]['search']['value']) && $filter[1]['search']['value'] != '') {
			$this->db->where('date(tp.settlement_time)', $filter[1]['search']['value']);
		}

		// filter status transaksi
		if(isset($filter[2]['search']['value']) && $filter[2]['search']['value'] != '') {
			$this->db->where('tp.status', $filter[2]['search']['value']);
		}

		$query = $this->db->get();

		return $query->row()->total;
	}

	public function getTotalPaymentTransaction($id)
	{
		return $this->db->select('count(*) as total_terjual')
			->join('checkouts c', 'c.id = (t.field_id)::int', 'left')
			->join('checkout_items ci', 'ci.checkout_id = c.id', 'left')
			->join('bundling_packages bp', 'bp.id = ci.item_id', 'left')
			->where('field_table', 'bundle')
			->where('t.status', 'settlement')
			->where('ci.item_id', $id)
			->get('transaction_payments t')->result_array();
	}
}
