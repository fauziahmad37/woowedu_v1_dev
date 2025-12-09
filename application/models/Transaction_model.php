<?php

class Transaction_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

	/**
	 * Query for count transaction book per daily basis
	 *
	 * @return array
	 */
	public function count_read_daily(): array {
		$query = "SELECT COUNT(a.book_id), a.trans_timestamp, b.title 
				  FROM transactions a, books b
				  WHERE a.book_id=b.id 
				  GROUP BY  b.title, a.trans_timestamp 
				  HAVING a.trans_timestamp = CURRENT_DATE";
		$res = $this->db0->query($query);
		return $res->result_array();
	}

	public function avg_read_time() {
		$query = "SELECT ";		
	}

	// check_borrowed

	/**
	 * Query for check if book is borrowed
	 * 
	 * @param int $book_id
	 * @param int $user_id
	 * 
	 * @return array
	 */
	 
	public function check_borrowed(int $book_id, int $user_id): array {
		$this->db->from('transactions');
		$this->db->where('book_id', $book_id);
		$this->db->where('member_id', $user_id);
		$this->db->where('actual_return', null);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);

		$res = $this->db->get();

		if($res->num_rows() == 0) {
			return [];
		}

		return $res->row_array();
	}

	/**
	 * Query for get user borrowed book
	 * 
	 * @param int $user_id
	 * @return array
	 */
	 
	public function get_user_borrowed_book(int $user_id, int $limit=NULL, int $offset=NULL): array {
		$this->db->from('transactions a')
				 ->join('books b', 'a.book_id=b.id')
				 ->where('a.member_id', $user_id)
				 ->where('a.actual_return', null);

		$res = $this->db->get();
		return $res->result_array();
	}

	/**
	 * Query for get user borrowed book
	 * 
	 * @param int $user_id
	 * @return array
	 */
	 
	public function count_user_borrowed_book(int $user_id): array {
		$this->db->from('transactions');
		$this->db->where('member_id', $user_id);
		$this->db->where('actual_return', null);

		$res = $this->db->get();
		return $res->num_rows();
	}

	/**
	 * Query for get get_latest_transaction
	 * 
	 * @param int $book_id
	 * @param int $member_id
	 * 
	 * @return array
	 */

	public function get_latest_transaction(int $book_id, int $member_id): array {
		$this->db->from('transactions');
		$this->db->where('book_id', $book_id);
		$this->db->where('userid', $member_id);
		$this->db->where('actual_return', null);
		$this->db->order_by('id', 'DESC');
		$this->db->limit(1);

		$res = $this->db->get();

		if($res->num_rows() == 0) {
			return [];
		}
		
		return $res->row_array();
	}

	/**
	 * Query for get get_users_loan
	 *
	 * @param int $member_id
	 * @param array $filter
	 *  
	 * @return array
	 */
	 
	public function get_users_loan(int $member_id, $filter): array {
		$this->db->select('books.*, publisher_name, category_name');
		$this->db->from('transactions');
		$this->db->join('books', 'transactions.book_id = books.id');
		$this->db->join('publishers', 'books.publisher_id = publishers.id');
		$this->db->join('categories', 'books.category_id = categories.id');
		$this->db->where('member_id', $member_id);
		$this->db->where('actual_return', null);

		// sort by
		if ($filter['sort_by'] == 'title-asc')
			$this->db->order_by('books.title', 'ASC');

		if ($filter['sort_by'] == 'title-desc')
			$this->db->order_by('books.title', 'DESC');
		
		// filter limit offset
		$this->db->limit($filter['limit'], $filter['offset']);

		$res = $this->db->get();

		if($res->num_rows() == 0) {
			return [];
		}

		return $res->result_array();
	}

	/** 
	 * Query for get get_users_loan_count
	 * 
	 * @param int $member_id
	 * @return int
	 */

	public function get_users_loan_count(int $member_id): int {
		$this->db->from('transactions');
		$this->db->where('member_id', $member_id);
		$this->db->where('actual_return', null);

		$res = $this->db->get();
		return $res->num_rows();
	}

	//=====================================================================================================

	/**
	 * Get members read By Category 
	 *
	 * @param string $type
	 * @param DateTime $value
	 * @return array
	 */
	public function get_by_category(string $type, DateTime $value): array {
		$res = [];

		switch($type)
		{
			case 'daily':
				$res = $this->get_by_category_daily($value);
				break;
			case 'monthly':
				$res = $this->get_by_category_monthly($value);
				break;
		}

		return $res;
	}

	/**
	 * Get Member By Book Categories Daily 
	 *
	 * @param DateTime $value
	 * @return array
	 */
	private function get_by_category_daily(DateTime $value): array {

		$query = "SELECT COUNT(a.member_id) as \"count\", b.category_id, c.category_name
				  FROM read_log a, books b, categories c, members d 
				  WHERE a.book_id=b.id AND b.category_id=c.id AND a.member_id=d.id AND a.start_time::date=?
				  GROUP BY b.category_id, c.category_name
				  ORDER BY \"count\" DESC LIMIT 5";
		$res = $this->db->query($query, [$value->format('Y-m-d')]);
		return $res->result_array();
	}

	/**
	 * Get members read by categories monthly
	 *
	 * @param DateTime $value
	 * @return array
	 */
	private function get_by_category_monthly(DateTime $value, int $limit = 5): array {
		$query = "SELECT COUNT(a.member_id) \"count\", b.category_id, c.category_name
				  FROM read_log a, books b, categories c, members d 
				  WHERE a.book_id=b.id AND b.category_id=c.id AND a.member_id=d.id AND date_part('month', a.start_time)=?
				  GROUP BY b.category_id, c.category_name
				  ORDER BY \"count\" DESC
				  LIMIT {$limit}";
		$res = $this->db->query($query, [$value->format('n')]);
		return $res->result_array();
	}


	//===================================================================================================================

	/**
	 * Get all read members by grade
	 *
	 * @param string $type
	 * @param DateTime $value
	 * @return array
	 */
	public function get_by_grade(string $type, DateTime $value): array {
		$res = [];

		switch($type)
		{
			case 'daily':
				$res = $this->get_by_grade_daily($value);
				break;
			case 'monthly':
				$res = $this->get_by_grade_monthly($value);
				break;
		}

		return $res;
	}

	/**
	 * Get all read members by grade daily
	 *
	 * @param DateTime $value
	 * @return array
	 */
	private function get_by_grade_daily(DateTime $value): array {
		$query = "SELECT COUNT(a.member_id) as \"count\", b.kelas
				  FROM read_log a, members b 
				  WHERE a.member_id=b.id AND a.start_time::date=?
				  GROUP BY b.kelas
				  ORDER BY \"count\" DESC
				  LIMIT 5";
		$res = $this->db->query($query, [$value->format('Y-m-d')]);
		return $res->result_array();
	}

	private function get_by_grade_monthly(DateTime $value): array {
		$query = "SELECT COUNT(a.member_id) as \"count\", b.kelas
				  FROM read_log a, members b 
				  WHERE a.member_id=b.id AND date_part('month', a.start_time)=?
				  GROUP BY b.kelas
				  ORDER BY \"count\" DESC
				  LIMIT 5";
		$res = $this->db->query($query, [$value->format('n')]);
		return $res->result_array();
	}

	//====================================================================================================

	/**
	 * Count average member's time reading 
	 *
	 * @return array|null
	 */
	public function get_avg_read_member(int $limit = 5): ?array {
		$query = "SELECT AVG(a.end_time - a.start_time) AS avg_duration, b.member_name 
				  FROM read_log a
				  JOIN members b ON a.member_id=b.id
				  GROUP BY b.member_name
				  ORDER BY avg_duration DESC
				  LIMIT {$limit}";

		$res = $this->db->query($query);
		return $res->result_array();
	}

	/**
	 * Undocumented function
	 *
	 * @return array|null
	 */
	public function get_avg_person_by_day(): ?array {
		$query = "SELECT AVG(m.member_by_date) AS avg_calc, EXTRACT(dow from m.tanggal::DATE) AS minggu
					FROM (
						SELECT COUNT(a.member_id) AS member_by_date, l.tanggal 
						FROM (
							SELECT k.date AS tanggal 
							FROM GENERATE_SERIES((CURRENT_DATE - INTERVAL '3 MONTHS')::date, CURRENT_DATE, '1 day') k
						) l
						LEFT JOIN read_log a ON a.start_time::DATE=l.tanggal
						GROUP BY l.tanggal
						ORDER BY l.tanggal DESC
					) m
					GROUP BY minggu
					ORDER BY minggu";
		$res = $this->db->query($query);
		return $res->result_array();
	}

	public function get_latest_read_person(string $userid, int $book_id): ?array {
		$query = "SELECT a.member_id, a.book_id, a.last_page, MAX(a.end_time) as last_read_id
					FROM read_log a 
					WHERE a.member_id=? AND a.book_id=?
					GROUP BY a.member_id, a.book_id, a.last_page, a.end_time
					HAVING a.end_time=MAX(a.end_time)
					ORDER BY a.end_time DESC
					LIMIT 1
					";
		$res = $this->db->query($query, [$userid, $book_id]);
		return $res->row_array() ?? [];
	}
}
