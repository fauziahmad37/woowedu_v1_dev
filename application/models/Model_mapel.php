<?php

class Model_mapel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * get all data
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function get_all(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

        if(!empty($filter[0]['search']['value']))
            $this->db->where('a.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
            $this->db->where('a.teacher_id', $filter[1]['search']['value']);

		if(!empty($filter[2]['search']['value']))
            $this->db->where('b.class_level_id', $filter[2]['search']['value']);

		if(!empty($filter[3]['search']['value']))
			$this->db->where('mk.class_id', $filter[3]['search']['value']);

		if(!empty($filter[4]['search']['value']))
			$this->db->where_in('mk.class_id', $filter[4]['search']['value']);

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		$this->db->where('b.sekolah_id', $this->session->userdata('sekolah_id'));

        $this->db->select('a.*, b.subject_name, t.teacher_name');
		$this->db->join('materi a', 'a.materi_id = mk.materi_id');
        $this->db->join('subject b', 'a.subject_id=b.subject_id');
        $this->db->join('teacher t', 't.teacher_id=a.teacher_id', 'left');
		$this->db->order_by('a.materi_id', 'DESC');
        $get = $this->db->get('materi_kelas mk');

        return $get->result_array() ?? [];
    }

    /**
     * Num rows with filter
     *
     * @param array $filter
     * @return integer
     */
    public function num_all(array $filter = NULL): int {
		if(!empty($filter[0]['search']['value']))
            $this->db->where('a.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
            $this->db->where('a.teacher_id', $filter[1]['search']['value']);

		if(!empty($filter[2]['search']['value']))
            $this->db->where('b.class_level_id', $filter[2]['search']['value']);
		
		if(!empty($filter[3]['search']['value']))
			$this->db->where('mk.class_id', $filter[3]['search']['value']);

		if(!empty($filter[4]['search']['value']))
			$this->db->where_in('mk.class_id', $filter[4]['search']['value']);
        
        $this->db->select('a.*, b.subject_name,  t.teacher_name');
		$this->db->join('materi a', 'a.materi_id = mk.materi_id');
		$this->db->join('subject b', 'a.subject_id=b.subject_id');
		$this->db->join('teacher t', 't.teacher_id=a.teacher_id', 'left');
        $get = $this->db->get('materi_kelas mk');

        return $get->num_rows() ?? 0;
    }

	 /**
     * get all data
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function get_all_materi_sekolah(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

        if(!empty($filter[0]['search']['value']))
            $this->db->where('a.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
            $this->db->where('a.teacher_id', $filter[1]['search']['value']);

		if(!empty($filter[2]['search']['value']))
            $this->db->where('b.class_level_id', $filter[2]['search']['value']);

		// filter seluruh teacher id yang role nya kepala sekolah
		if(!empty($filter[3]['search']['value']))
			$this->db->where_in('a.teacher_id', $filter[3]['search']['value']);
		

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		$this->db->where('b.sekolah_id', $this->session->userdata('sekolah_id'));

        $this->db->select('a.*, b.subject_name, t.teacher_name');
        $this->db->join('subject b', 'a.subject_id=b.subject_id');
        $this->db->join('teacher t', 't.teacher_id=a.teacher_id', 'left');
		$this->db->order_by('a.materi_id', 'DESC');
        $get = $this->db->get('materi a');

        return $get->result_array() ?? [];
    }

    /**
     * Num rows with filter
     *
     * @param array $filter
     * @return integer
     */
    public function num_all_materi_sekolah(array $filter = NULL): int {
		if(!empty($filter[0]['search']['value']))
            $this->db->where('a.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
            $this->db->where('a.teacher_id', $filter[1]['search']['value']);

		if(!empty($filter[2]['search']['value']))
            $this->db->where('b.class_level_id', $filter[2]['search']['value']);

		// filter seluruh teacher id yang role nya kepala sekolah
		if(!empty($filter[3]['search']['value']))
			$this->db->where_in('a.teacher_id', $filter[3]['search']['value']);
		
		$this->db->where('b.sekolah_id', $this->session->userdata('sekolah_id'));
        $this->db->select('a.*, b.subject_name,  t.teacher_name');
		$this->db->join('subject b', 'a.subject_id=b.subject_id');
		$this->db->join('teacher t', 't.teacher_id=a.teacher_id', 'left');
        $get = $this->db->get('materi a');

        return $get->num_rows() ?? 0;
    }

    /**
     * get all data
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function get_all_materi_saya(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

        if(!empty($filter[0]['search']['value']))
            $this->db->where('a.teacher_id', $filter[0]['search']['value']);

		if(!empty($filter[1]['search']['value']))
            $this->db->where('a.subject_id', $filter[1]['search']['value']);

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

        $this->db->select('a.*, b.subject_name')
                 ->join('subject b', 'a.subject_id=b.subject_id')
                 ->order_by('a.materi_id', 'DESC');
        $get = $this->db->get('materi a');

        return $get->result_array() ?? [];
    }

	/**
     * Num rows with filter
     *
     * @param array $filter
     * @return integer
     */
    public function num_all_materi_saya(array $filter = NULL): int {
		if(!empty($filter[0]['search']['value']))
            $this->db->where('a.teacher_id', $filter[0]['search']['value']);

        
        $this->db->select('a.*, b.subject_name')
                 ->join('subject b', 'a.subject_id=b.subject_id');
        $get = $this->db->get('materi a');

        return $get->num_rows() ?? 0;
    }

	/**
     * get all data
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function get_all_tutorial(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

        if(!empty($filter[0]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[0]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[1]['search']['value']))
			$this->db->where('parent_id', $filter[1]['search']['value']);

		if($filter[2]['search']['value'])
			$this->db->where('parent_id', null, false);

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

        $this->db->order_by('a.materi_global_id ', 'DESC');
        $get = $this->db->get('materi_global a');

        return $get->result_array() ?? [];
    }

	/**
     * Num rows with filter
     *
     * @param array $filter
     * @return integer
     */
    public function num_all_tutorial(array $filter = NULL): int {
		if(!empty($filter[0]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[0]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[1]['search']['value']))
			$this->db->where('parent_id', $filter[1]['search']['value']);

		if($filter[2]['search']['value'])
			$this->db->where('parent_id', null, false);

        $get = $this->db->get('materi_global a');

        return $get->num_rows() ?? 0;
    }
}
