<?php

class Model_bundling_package_book extends CI_Model {

	public function __construct(){
        parent::__construct();
    }

	public function getByIdBundle($id){
		$this->db->select('bp.*, publisher_name, cover_img, title, es.ebook_id');
		$this->db->from('bundling_packages bp');
		$this->db->join('bundling_package_books bpb', 'bpb.bundling_package_id=bp.id', 'left');
		$this->db->join('ebooks_subscribe_type es', 'es.id=bpb.ebook_subscribe_id');
		$this->db->join('ebooks e', 'e.id=es.ebook_id');
		$this->db->join('class_level cl', 'cl.class_level_id=e.class_level');
		$this->db->join('publishers p', 'p.id=e.publisher_id');
		$this->db->where('bp.id', $id);

		return $this->db->get()->result_array();
	}

}
