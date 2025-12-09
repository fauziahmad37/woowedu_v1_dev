<?php

class Kategori_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all(?array $filter = NULL, ?int $limit=NULL, ?int $offset=NULL): array {

        if(!empty($filter[1]['search']['value']))
            $this->db->where('LOWER(category_name) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

        if(!empty($filter[2]['search']['value']))
            $this->db->where('parent_category='.$filter[2]['search']['value']);
        
        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);
            
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('categories');
        return $query->result_array();
    }

    public function count_all(?array $filter = NULL)
    {
        $this->db->where('deleted_at IS NULL');
        $query = $this->db->get('categories');
        return $query->num_rows();
    }

    public function get() 
    {

    }


}