<?php

class Model_materi extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get all data materi
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function getAll(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

		if(isset($filter[0]['search']['value']) && !empty($filter[0]['search']['value']))
			$this->db->where('s.sekolah_id', $filter[0]['search']['value']);

        if(isset($filter[1]['search']['value']) && !empty($filter[1]['search']['value']))
			$this->db->where('m.subject_id', $filter[1]['search']['value']);

        // if(isset($filter[1]['search']['value']) && !empty($filter[1]['search']['value']))
		// 	$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(isset($filter[2]['search']['value']) && !empty($filter[2]['search']['value']))
			$this->db->where('e.type', $filter[2]['search']['value']);

		if(isset($filter[3]['search']['value']) && !empty($filter[3]['search']['value']))
			$this->db->where('exam_id', $filter[3]['search']['value']);

		// if($filter[2]['search']['value'])
		// 	$this->db->where('parent_id', null, false);

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		$this->db->join('subject s', 's.subject_id = m.subject_id', 'left');
        $this->db->order_by('m.no_urut ', 'ASC');
        $get = $this->db->get('materi m');

        return $get->result_array() ?? [];
    }

	/**
     * get all subject (matapelajaran)
     *
     * @param array $filter
     * @return array
     */
    public function getAllSubject(array $filter = NULL): array {

        if(isset($filter[0]) && !empty($filter[0]))
			$this->db->where('s.subject_id', $filter[0]);

		$this->db->where('sekolah_id', $_SESSION['sekolah_id']);
        $this->db->order_by('s.subject_name ', 'ASC');
        $get = $this->db->get('subject s');

        return $get->result_array() ?? [];
    }

	 /**
     * count all data materi
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
	public function getCountAll(array $filter = NULL): int {

		if(isset($filter[0]['search']['value']) && !empty($filter[0]['search']['value']))
			$this->db->where('s.sekolah_id', $filter[0]['search']['value']);

        if(isset($filter[1]['search']['value']) && !empty($filter[1]['search']['value']))
			$this->db->where('m.subject_id', $filter[1]['search']['value']);

        // if(isset($filter[1]['search']['value']) && !empty($filter[1]['search']['value']))
		// 	$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(isset($filter[2]['search']['value']) && !empty($filter[2]['search']['value']))
			$this->db->where('e.type', $filter[2]['search']['value']);

		if(isset($filter[3]['search']['value']) && !empty($filter[3]['search']['value']))
			$this->db->where('exam_id', $filter[3]['search']['value']);

		// if($filter[2]['search']['value'])
		// 	$this->db->where('parent_id', null, false);

		$this->db->join('subject s', 's.subject_id = m.subject_id', 'left');
        $this->db->order_by('m.no_urut ', 'ASC');
        $get = $this->db->get('materi m');

        return $get->num_rows();
	}
}
