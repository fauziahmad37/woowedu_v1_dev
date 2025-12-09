<?php

class Model_sekolah extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
        $query = $this->compiledQuery();

		if(isset($filter[2]['search']['value']) && !empty($filter[2]['search']['value'])){
			$query .= " where lower(sekolah_nama) like lower('%".trim($filter[2]['search']['value'])."%')";
		}

        if(!empty($limit) && !is_null($offset))
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        $res = $this->db->query($query);
        return $res->result_array();
    }

    public function countAll(array $filter = NULL):int {
        $query = $this->compiledQuery();

		if(isset($filter[2]['search']['value']) && !empty($filter[2]['search']['value'])){
			$query .= " where lower(sekolah_nama) like lower('%".trim($filter[2]['search']['value'])."%')";
		}

        $res = $this->db->query($query);
        return $res->num_rows();
    }

    private function compiledQuery() {
 
				$query = "SELECT sekolah_id, sekolah_kode, sekolah_nama,  sekolah_alamat,sekolah_phone, kode_aktivasi 
                  FROM sekolah ";				

        return $query;
    }
}
