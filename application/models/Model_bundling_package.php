<?php

class Model_bundling_package extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	public function get($limit=0, $offset=0, $filter=[]){
		$this->db->select('bp.*, p.publisher_name');
		$this->db->from('bundling_packages bp');
		$this->db->join('publishers p', 'p.id=bp.publisher_id');

		$this->db->where('status', 1);
		
		if($limit)
			$this->db->limit($limit, $offset);

		return $this->db->get()->result_array();
	}

	public function getById($id){
		$this->db->select('bp.*, p.publisher_name');
		$this->db->from('bundling_packages bp');
		$this->db->join('publishers p', 'p.id=bp.publisher_id');

		$this->db->where('bp.id', $id);

		return $this->db->get()->row_array();
	}

	public function getEbooksByTrxNumber($trxNumber){
		$this->db->select('est.ebook_id, c.user_id, bp.duration_days, est.subscribe_periode');
		$this->db->from('transaction_payments tp');
		
		$this->db->join('checkouts c', 'c.id=(tp.field_id)::integer');
		$this->db->join('checkout_items ci', 'ci.checkout_id=c.id');
		$this->db->join('bundling_packages bp', 'bp.id=ci.item_id');
		$this->db->join('bundling_package_books bpb', 'bpb.bundling_package_id=bp.id');
		$this->db->join('ebooks_subscribe_type est', 'est.id=bpb.ebook_subscribe_id');

		$this->db->where('tp.transaction_number', $trxNumber);

		return $this->db->get()->result_array();
	}

}
