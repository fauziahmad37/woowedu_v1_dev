<?php
class Model_wishlist extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function get(){
		$this->db->select("e.*, p.publisher_name, 'ebook' as item_type");
		$this->db->from('wishlists w');
		$this->db->join('ebooks e', "w.item_id=e.id", 'left');
		$this->db->join('publishers p', 'p.id=e.publisher_id', 'left');
		$this->db->where('w.user_id', $_SESSION['userid']);
		$this->db->where('w.liked', 'Y');
		$this->db->where('w.item_type', 'ebook');
		$this->db->order_by('w.created_at', 'DESC');
		return $this->db->get();
	}

	public function getBundling(){
		$this->db->select("bp.package_image as cover_img, bp.package_name as title, bp.id as id, p.publisher_name, 'bundling' as item_type");
		$this->db->from('wishlists w');
		$this->db->join('bundling_packages bp', "w.item_id=bp.id", 'left');
		$this->db->join('publishers p', 'p.id=bp.publisher_id', 'left');
		$this->db->where('w.user_id', $_SESSION['userid']);
		$this->db->where('w.liked', 'Y');
		$this->db->where('w.item_type', 'bundling');
		$this->db->order_by('w.created_at', 'DESC');
		return $this->db->get();
	}
}
