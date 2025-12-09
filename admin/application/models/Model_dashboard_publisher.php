<?php
class Model_dashboard_publisher extends CI_Model{
	
    public function getAllTransactionBundling(): array {
		$this->db->select('est.ebook_id, count(*) as total');
		$this->db->where('tp.field_table', 'bundle');
		$this->db->join('checkouts c', 'c.id = cast(tp.field_id as integer)', 'left');
		$this->db->join('checkout_items a', 'c.id = a.checkout_id', 'left');
		$this->db->join('bundling_packages bp', 'bp.id = a.item_id', 'left');
		$this->db->join('bundling_package_books bpb', 'bp.id = bpb.bundling_package_id', 'left');
		$this->db->join('ebooks_subscribe_type est', 'est.id = bpb.ebook_subscribe_id', 'left');
		$this->db->group_by('est.ebook_id');
        $get = $this->db->get('transaction_payments tp');

        return $get->result_array() ?? [];
    }

	public function getAllTransactionEbook(): array {
		$this->db->select('est.ebook_id, count(*) as total');
		$this->db->where('tp.field_table', 'ebook');
		$this->db->join('checkouts c', 'c.id = cast(tp.field_id as integer)', 'left');
		$this->db->join('checkout_items a', 'c.id = a.checkout_id', 'left');
		$this->db->join('ebooks_subscribe_type est', 'est.id = a.item_id', 'left');
		$this->db->group_by('est.ebook_id');
		$get = $this->db->get('transaction_payments tp');

		return $get->result_array() ?? [];
	}
	
}
