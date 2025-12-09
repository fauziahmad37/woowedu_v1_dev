<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_bundling extends CI_Model
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

	// Example function to insert data into a table
	public function insert($table, $data)
	{
		return $this->db->insert($table, $data);
	}

	// Example function to update data in a table
	public function update($table, $data, $where)
	{
		return $this->db->update($table, $data, $where);
	}

	// Example function to delete data from a table
	public function delete($table, $where)
	{
		return $this->db->delete($table, $where);
	}

	// Get All Bundling
	public function getAll($filter = [], $limit = 10, $offset = 0)
	{
		$this->db->select('b.id, b.package_name, b.price, b.stock, b.status, b.created_at, b.updated_at, b.package_image, b.start_date, b.end_date');
		$this->db->from('bundling_packages b');

		if (isset($filter[1]['search']) && !empty($filter[1]['search'])) {
			$this->db->where('LOWER(package_name) LIKE \'%' . trim(strtolower($filter[1]['search']['value'])) . '%\'', NULL, FALSE);
		}

		if (isset($filter['kategori'])) {
			$this->db->where_in('b.category_id', $filter['kategori']);
		}

		if (isset($filter[2]['order'])) {
			$this->db->order_by('b.price', $filter[2]['order']);
		}

		if (isset($filter[0]['search']) && !empty($filter[0]['search'])) {
			if ($filter[0]['search']['value'] == '') {
				$this->db->where_in('b.status', [0, 1]);
			} elseif ($filter[0]['search']['value'] == 1) {
				$this->db->where('b.status', 1);
			} elseif ($filter[0]['search']['value'] == 0) {
				$this->db->where('b.status', 0);
			}
		}

		$this->db->limit($limit, $offset);
		$query = $this->db->get();

		// var_dump($this->db->last_query()); die;

		return $query->result_array();
	}

	// Get Bundling Count

	public function countAll($filter = [])
	{
		$this->db->select('count(b.id) as total');
		$this->db->from('bundling_packages b');

		if (isset($filter[1]['search']) && !empty($filter[1]['search'])) {
			$this->db->where('LOWER(package_name) LIKE \'%' . trim(strtolower($filter[1]['search']['value'])) . '%\'', NULL, FALSE);
		}

		if (isset($filter['kategori'])) {
			$this->db->where_in('b.category_id', $filter['kategori']);
		}

		if (isset($filter[0]['status']) &&  ($filter[0]['status'] == 0 || $filter[0]['status'] == 1)) {
			$this->db->where('b.status', $filter[0]['status']);
		}

		$query = $this->db->get();

		return $query->row()->total;
	}

	public function getTotalBundlingTerjual($id)
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
