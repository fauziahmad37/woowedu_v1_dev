<?php

class Model_transaction_payment extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getAll($limit, $offset, $filter = []){
		$this->db->select('tp.transaction_number, tp.status, tp.field_table, bp.package_image, p.publisher_name, bp.package_name, tp.status, 
		tp.total_payment, tp.payment_link, tp.expiry_time, ci.item_id as bundling_package_id');

		if(isset($filter['field_table']))
			$this->db->where('tp.field_table', $filter['field_table']);

		if(isset($filter['user_id']))
			$this->db->where('c.user_id', $filter['user_id']);

		$this->db->from('transaction_payments tp');
		$this->db->join('checkouts c', 'c.id=(tp.field_id)::integer', 'left');
		$this->db->join('checkout_items ci', 'ci.checkout_id=c.id');
		$this->db->join('bundling_packages bp', 'bp.id=ci.item_id');
		$this->db->join('publishers p', 'p.id=bp.publisher_id');
		
		$this->db->order_by('tp.id', 'desc');
		return $this->db->get()->result_array();
	}

	public function countAll($filter = []){
		$this->db->select('count(*) as total');
		$this->db->from('transaction_payments tp');
		$this->db->join('checkouts c', 'c.id=(tp.field_id)::integer', 'left');
		$this->db->join('checkout_items ci', 'ci.checkout_id=c.id');
		$this->db->join('bundling_packages bp', 'bp.id=ci.item_id');
		$this->db->join('publishers p', 'p.id=bp.publisher_id');
		
		if(isset($filter['field_table']))
			$this->db->where('tp.field_table', $filter['field_table']);

		if(isset($filter['user_id']))
			$this->db->where('c.user_id', $filter['user_id']);

		return $this->db->get()->row_array()['total'];
	}
}
