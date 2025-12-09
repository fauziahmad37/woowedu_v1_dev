<?php

class Model_ebook extends CI_Model {

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
        $kelas = NULL;

        $param = [];
        $query = "SELECT a.*, b.category_name, c.publisher_name FROM ebooks a, categories b, publishers c 
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

        if(!empty($filter['penerbit']))
        {
                $query .= " AND a.publisher_id=?";
                $param[] = $filter['penerbit'];
        }
            

        if($_SESSION['user_level'] == 3) {
            $kelas = array_column($_SESSION['class_level_id'], 'class_level_id');
            $query .= " AND a.class_level IN (".implode(",", $kelas).")";
        }
        if($_SESSION['user_level'] == 4) {
            $kelas =  $_SESSION['class_level_id'];
            $query .= " AND a.class_level=".$kelas;
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
        
        if(!empty($filter['title']))
        {
            $query .= " AND LOWER(a.title) LIKE ?";
            $param[] = '%'.strtolower(trim($filter['title'])).'%';
        }

        if(!empty($filter['category']))
        {
            $query .= " AND a.category_id=?";
            $param[] = $filter['category'];
        }

        if(!empty($filter['penerbit']))
        {
            $query .= " AND a.publisher_id=?";
            $param[] = $filter['penerbit'];
        }

        if($_SESSION['user_level'] == 3) {
            $kelas = array_column($_SESSION['class_level_id'], 'class_level_id');
            $query .= " AND a.class_level IN (".implode(",", $kelas).")";
        }
        if($_SESSION['user_level'] == 4) {
            $kelas =  $_SESSION['class_level_id'];
            $query .= " AND a.class_level=".$kelas;
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
        $get = $this->db->select('a.*, c.publisher_name')
                        ->where('a.id', $id)
                        ->or_where('a.book_code', $id)
                        ->join('publishers c', 'a.publisher_id=c.id')
                        ->get('ebooks a');

        return $get->row_array() ?? [];
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
     * Get read book history by person 
     *
     * @param string $userid
     * @return array
     */
    public function getHistoryByPerson(string $userid): array {
        $query = "SELECT DISTINCT a.book_id, b.book_code, b.title, b.file_1, b.cover_img
                    FROM read_log a, ebooks b WHERE a.book_id=b.id AND a.member_id=?";

        $res = $this->db->query($query, [$userid]);
        return $res->result_array() ?? [];
    }

    /**
     * Undocumented function
     *
     * @param  array $filter
     * @param  int $limit
     * @param  int $offset
     * @return array
     */
    public function getEbooks(array $filter=NULL, int $limit=NULL, int $offset=NULL, $categories=NULL): array {
        $query = "  SELECT DISTINCT a.* FROM ebooks a
                    JOIN ebooks_categories b ON a.id=b.ebook_id
                    JOIN categories c ON b.category_id=c.sid
                    WHERE 1=1";
        
        

        $res = $this->db->query($query);
        return $res->result_array();
    }

	public function getRekomendasi(int $limit = NULL, int $offset = NULL, array $filter = NULL){
		$this->db->select('ebooks.*, publishers.publisher_name');
		$this->db->from('ebooks');

		if(isset($filter['class_level'])){
			$this->db->where_in('class_level', $filter['class_level']);
		}

		if(isset($filter['baru_dirilis'])){
			$this->db->order_by('ebooks.id', 'DESC');
		}

		$this->db->join('publishers', 'ebooks.publisher_id=publishers.id');
		$this->db->limit($limit, $offset);

		return $this->db->get()->result_array();
	}

	public function terbanyakDibaca(){
		$this->db->select('count(rl.book_id), book_id, title, p.publisher_name, e.cover_img');
		$this->db->from('read_log rl');
		$this->db->join('ebooks e', 'e.id=rl.book_id', 'left');
		$this->db->join('publishers p', 'p.id=e.publisher_id', 'left');
		$this->db->group_by('book_id, title, p.publisher_name, e.cover_img');
		$this->db->order_by('count(rl.book_id) DESC');
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}
}
