<?php

class Model_asesmen_mandiri extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get All Books Data
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function list(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {
        $param = [];
        $query = "SELECT a.*, b.category_name FROM ebooks a, categories b, publishers c 
                  WHERE 
                        a.category_id::text=b.category_code AND
                        a.publisher_id=c.id";
        
        if(!empty($filter['title']))
        {
            $query .= " AND LOWER(a.title) LIKE ?";
            $param[] = '%'.strtolower(trim($filter['title'])).'%';
        }

        if(!empty($filter['category']))
        {
            $query .= " AND b.category_code=?";
            $param[] = $filter['category'];
        }
            

        if(!empty($limit) && !is_null($offset))
            $query .= " LIMIT {$limit} OFFSET {$offset}";

        $get = $this->db->query($query, $param);
        return $get->result_array() ?? [];
    }

    /**
     * Count All List 
     *
     * @return void
     */
    public function count_list(array $filter = NULL): int {
        $param = [];
        $query = "SELECT a.*, b.category_name FROM ebooks a, categories b, publishers c 
                  WHERE 
                        a.category_id::text=b.category_code AND
                        a.publisher_id=c.id";
        
        if(!empty($param['title']))
        {
            $query .= " AND a.title LIKE ?";
            $param[] = '%'.$filter['title'].'%';
        }

        if(!empty($filter['category']))
        {
            $query .= " AND a.category_id=?";
            $param[] = $filter['category'];
        }

        $get = $this->db->query($query, $param);
        return $get->num_rows() ?? 0;
    }

    /**
     * get by id
     *
     * @return array
     */
    public function get($id): array {
        $get = $this->db->select('a.*, b.category_name, c.publisher_name')
                        ->where('a.id', $id)
                        ->join('categories b', 'a.category_id=b.category_code')
                        ->join('publishers c', 'a.publisher_id=c.id')
                        ->get('ebooks a');

        return $get->row_array();
    }

    /**
     * Get a book dta by book code
     *
     * @param string $code
     * @return array
     */
    public function getByCode(string $code): array {
        $get = $this->db->select('a.*, b.category_name, c.publisher_name')
                        ->where('a.book_code', $code)
                        ->join('categories b', 'a.category_id=b.category_code')
                        ->join('publishers c', 'a.publisher_id=c.id')
                        ->get('ebooks a');
        return $get->row_array();

    }

   /**
     * get all data soal
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function getAllSoal(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

		if(!empty($filter[0]['search']['value']))
			$this->db->where('s.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(tema_title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('s.type', $filter[2]['search']['value']);

		if(!empty($filter[3]['search']['value']))
			// $this->db->where('su.sekolah_id', $filter[3]['search']['value']);
			
		if(!empty($filter[4]['search']['value']))
			$this->db->where('s.materi_id', $filter[4]['search']['value']);

		if(!empty($filter[5]['search']['value']))
			$this->db->where_not_in('s.soal_id', $filter[5]['search']['value']);
		// if(!empty($filter[1]['search']['value']))
		// 	$this->db->where('parent_id', $filter[1]['search']['value']);

		// if($filter[2]['search']['value'])
		// 	$this->db->where('parent_id', null, false);

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		// $this->db->join('materi m', 'm.materi_id = s.materi_id', 'left');
		$this->db->where('sekolah_id', $_SESSION['sekolah_id']);
		$this->db->join('subject su', 'su.subject_id = s.subject_id', 'left');
        $this->db->order_by('s.soal_id ', 'DESC');
        $get = $this->db->get('soal s');

        return $get->result_array() ?? [];
    }

	 /**
     * count all data soal
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
	public function getCountAllSoal(array $filter = NULL): int {

        if(!empty($filter[0]['search']['value']))
			$this->db->where('s.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(tema_title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('s.type', $filter[2]['search']['value']);
		// if(!empty($filter[1]['search']['value']))
		// 	$this->db->where('parent_id', $filter[1]['search']['value']);

		// if($filter[2]['search']['value'])
		// 	$this->db->where('parent_id', null, false);

		$this->db->join('materi m', 'm.materi_id = s.materi_id', 'left');
        $this->db->order_by('s.soal_id ', 'DESC');
        $get = $this->db->get('soal s');

        return $get->num_rows();
	}

	/**
     * get all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function getAllAsesmen(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

		if(!empty($filter[0]['search']['value']))
			$this->db->where('e.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('e.type', $filter[2]['search']['value']);

		if(!empty($filter[3]['search']['value']))
			$this->db->where('teacher_id', $filter[3]['search']['value']);

		if(!empty($filter[4]['search']['value'])){
			$this->db->where('e.class_id', $filter[4]['search']['value']);
			$this->db->where('e.status', 1);
		}
		
		if(!empty($filter[5]['search']['value']) || is_numeric($filter[5]['search']['value']))
			$this->db->where('e.tipe', $filter[5]['search']['value']);

		// if(!empty($filter[6]['search']['value']))
		// 	$this->db->where('e.student_id', $filter[6]['search']['value']);

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		$this->db->join('subject s', 's.subject_id = e.subject_id', 'left');
		$this->db->join('kelas c', 'c.class_id = e.class_id', 'left');
        $this->db->order_by('e.exam_id ', 'DESC');
        $get = $this->db->get('exam e');

        return $get->result_array() ?? [];
    }

	 /**
     * count all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
	public function getAcountAllAsesmen(array $filter = NULL): int {

        if(!empty($filter[0]['search']['value']))
			$this->db->where('e.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('e.type', $filter[2]['search']['value']);

		if(!empty($filter[3]['search']['value']))
			$this->db->where('teacher_id', $filter[3]['search']['value']);

		if(!empty($filter[4]['search']['value']))
			$this->db->where('e.class_id', $filter[4]['search']['value']);
		
		if(!empty($filter[5]['search']['value']) || is_numeric($filter[5]['search']['value']))
			$this->db->where('e.tipe', $filter[5]['search']['value']);

		if(!empty($filter[6]['search']['value']))
			$this->db->where('e.student_id', $filter[6]['search']['value']);

		$this->db->join('subject s', 's.subject_id = e.subject_id', 'left');
		$this->db->join('kelas c', 'c.class_id = e.class_id', 'left');
        $this->db->order_by('e.exam_id ', 'DESC');
        $get = $this->db->get('exam e');

        return $get->num_rows();
	}

	/**
     * get all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function getExamStudent(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

		if(!empty($filter[0]['search']['value']))
			$this->db->where('e.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('e.type', $filter[2]['search']['value']);

		if(!empty($filter[3]['search']['value']))
			$this->db->where('exam_id', $filter[3]['search']['value']);

		// if($filter[2]['search']['value'])
		// 	$this->db->where('parent_id', null, false);

        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		$this->db->join('student s', 's.student_id = e.student_id', 'left');
        $this->db->order_by('e.exam_id ', 'DESC');
        $get = $this->db->get('exam_student e');

        return $get->result_array() ?? [];
    }

	 /**
     * count all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
	public function getCountExamStudent(array $filter = NULL): int {

        if(!empty($filter[0]['search']['value']))
			$this->db->where('e.subject_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('e.type', $filter[2]['search']['value']);
		
		if(!empty($filter[3]['search']['value']))
			$this->db->where('exam_id', $filter[3]['search']['value']);

		// if($filter[2]['search']['value'])
		// 	$this->db->where('parent_id', null, false);

		$this->db->join('student s', 's.student_id = e.student_id', 'left');
        $this->db->order_by('e.exam_id ', 'DESC');
        $get = $this->db->get('exam_student e');

        return $get->num_rows();
	}

	/**
     * get all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function getExamAnswer(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

		if(!empty($filter[0]['search']['value']))
			$this->db->where('e.student_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('e.exam_id', $filter[2]['search']['value']);


        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		$this->db->join('soal s', 's.soal_id = e.soal_id', 'left');
        $this->db->order_by('e.exam_id ', 'DESC');
        $get = $this->db->get('exam_answer e');

        return $get->result_array() ?? [];
    }

	 /**
     * count all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
	public function getCountExamAnswer(array $filter = NULL): int {

        if(!empty($filter[0]['search']['value']))
			$this->db->where('e.student_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('e.exam_id', $filter[2]['search']['value']);
		


		$this->db->join('soal s', 's.soal_id = e.soal_id', 'left');
        $this->db->order_by('e.exam_id ', 'DESC');
        $get = $this->db->get('exam_answer e');

        return $get->num_rows();
	}

	/**
     * get all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
    public function getSkemaPenilaian(int $limit = NULL, int $offset = NULL, array $filter = NULL): array {

		if(!empty($filter[0]['search']['value']))
			$this->db->where('se.student_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('se.exam_id', $filter[2]['search']['value']);


        if(!empty($limit) && !is_null($offset))
            $this->db->limit($limit, $offset);

		$this->db->join('soal s', 's.soal_id = se.soal_id', 'left');
        $this->db->order_by('se.no_urut ', 'ASC');
        $get = $this->db->get('soal_exam se');

        return $get->result_array() ?? [];
    }

	 /**
     * count all data asesmen
     *
     * @param int $limit
     * @param int $offset
     * @param array $filter
     * @return array
     */
	public function getCountSkemaPenilaian(array $filter = NULL): int {

        if(!empty($filter[0]['search']['value']))
			$this->db->where('se.student_id', $filter[0]['search']['value']);

        if(!empty($filter[1]['search']['value']))
			$this->db->where('LOWER(title) LIKE \'%'.trim(strtolower($filter[1]['search']['value'])).'%\'', NULL, FALSE);

		if(!empty($filter[2]['search']['value']))
			$this->db->where('se.exam_id', $filter[2]['search']['value']);
		


		$this->db->join('soal s', 's.soal_id = se.soal_id', 'left');
        $get = $this->db->get('soal_exam se');

        return $get->num_rows();
	}
}
