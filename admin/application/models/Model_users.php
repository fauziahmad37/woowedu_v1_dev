<?php
  class Model_users extends CI_Model {

	public $table = 'users';

	public function get_profile()
	{
		if($this->session->userdata('userid') != ''){
			$id = $this->session->userdata('userid');
		}else{
			$id = $this->session->userdata('userid');
		}
		$this->db->select('*');
		$this->db->from('users a');
        $this->db->join('user_level b', 'a.user_level = b.user_level_id', 'INNER');
		$this->db->where('userid', $id);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}

	public function get_combo(){
		$query =  $this->db->get('users');
		return $query->result();
	}

	public function getUserID($username) {
		$this->db->select('userid');
		$this->db->from($this->table);
		$this->db->where('username', $username);
		$query=$this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row()->userid;
		}
		return false;
	}
	
 	function get_user_by_id($userid)
	{
	  $query = $this->db
		->where('userid', $userid)
		->get('users')->row();
	  return $query;			
	}

 
	public function cekAkun($username, $password)
	{

			$this->db->where('username', $username);
			$this->db->where('password', md5($password));
			$this->db->where('active', '1');

	  $query = $this->db->get($this->table)->row();

			if (!$query) return false;

	  $last_login = $this->update($query->userid, array('last_login' => date('Y-m-d H:i:s')));
	  
	  return $query;		
	}

	public function cekPasswordLama($userid, $password)
	{
			$this->db->where('userid', $userid);
			$this->db->where('password', md5($password));
			$this->db->where('active', '1');
			
	  $query = $this->db->get($this->table)->row();
	  
			if (!$query) return false;

	  $hash = $query->password;
	  
	  if (!password_verify($password, $hash)) return false;
	  
	  return $query;		
	}

	public function get()
	{
	  $query = $this->db->get($this->table);

	  return $query;
	}

	public function get_where($where)
	{
	  $query = $this->db
		->where($where)
		->get($this->table);

	  return $query;
	}

	public function insert($data)
	{
	  $query = $this->db->insert($this->table, $data);
	  return $query;
	}

	public function update($userid, $data)
	{
	  $query = $this->db
		->where('userid', $userid)
		->update($this->table, $data);
	  
	  return $query;
	}

	public function delete($userid)
	{
	  $query = $this->db
		->where('userid', $userid)
		->delete($this->table);

	  return $query;
	}
		
	function get_ulevel_by_id($id)
	{
	  $query = $this->db
		->where('user_level_id', $id)
		->get('user_level')->row();
	  return $query;			
	}		
   
	function get_ulevel_menu($id)
	{
	  $query = $this->db
		->where('menu_level_user_level', $id)
		->get('menu_level');
	  return $query;			
	}
	
	 

	public function get_menu() {
		$query = "SELECT a.menu_id as id, a.menu_name as \"text\", a.menu_link as link, a.menu_parent as parent, a.menu_icon as icon 
				  FROM menu a WHERE a.menu_status > 0";
		$res = $this->db->query($query);
		return $res->result_array();
	}

	public function get_all(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$query = "SELECT a.user_level_id as id, a.user_level_name as name, string_agg(b.menu_level_menu::text, ',') as menu
				  FROM user_level a LEFT JOIN menu_level b ON a.user_level_id=b.menu_level_user_level";
		if(!empty($filter)) {
			if(!empty($filter[2]['search']['value']))
				$query .= ' WHERE LOWER(a.user_level_name) LIKE \'%'.strtolower($filter[2]['search']['value']).'%\'';
		}
		$query .= " GROUP BY a.user_level_id, a.user_level_name ORDER BY user_level_id";
		if(!empty($limit))
			$query .= " LIMIT {$limit} OFFSET {$offset}";
		
		$res = $this->db->query($query);
		return $res->result_array();
	}

	public function count_all(array $filter = NULL) {
		$query = "SELECT COUNT(*) as total FROM user_level";
		if(!empty($filter)) {

		}
		//$query .= " ORDER BY user_level_id";
		//if(!empty($limit))
		//	$query .= " LIMIT {$limit} OFFSET {$offset}";
		$res = $this->db->query($query);
		return $res->row_array()['total'];
	}

	public function getMenuParents($id = 0) {
		$query = sprintf("
			WITH RECURSIVE menu_tree(menu_id, menu_parent) AS (
				SELECT a.menu_id, a.menu_parent FROM menu a WHERE a.menu_id IN (SELECT menu_level_menu FROM menu_level WHERE menu_level_user_level = %d)
				UNION ALL
				SELECT b.menu_id, b.menu_parent FROM menu b, menu_tree c WHERE b.menu_id = c.menu_parent
			)
			SELECT a.menu_id, a.menu_link, a.menu_name, a.menu_parent, a.menu_icon
			FROM menu a
			WHERE a.menu_id IN (SELECT menu_id FROM menu_tree) ORDER BY a.menu_id
		", (int) $id);

		$res = $this->db->query($query);
		return $res->result_array();
	}

	public function getUsers(array $filter = NULL, int $limit = NULL, int $offset = NULL) {
		$query = "SELECT a.userid, a.username, a.password, a.user_level, b.user_level_name, a.active, a.sekolah_id, a.date_limit, s.sekolah_nama, s.sekolah_kode
				  FROM users a 
				  LEFT JOIN user_level b ON a.user_level=b.user_level_id
				  LEFT JOIN sekolah s ON s.sekolah_id=a.sekolah_id";
		if(!empty($filter[2]['search']['value']) ||
			!empty($filter[5]['search']['value'])) {
			$query .= " WHERE";
			$i = 0;
			foreach($filter as $K => $f) {
				if(empty($f['search']['value'])) continue;
				if($i != 0)
					$query .= ' AND';
				if(!empty($f['search']['value'])) {
					if($K == 2)
						$query .= ' LOWER(a.username) LIKE \'%'.strtolower($f['search']['value']).'%\'';
					else if($K == 5)
						$query .= ' LOWER(b.user_level_name) LIKE \'%'.strtolower($f['search']['value']).'%\'';
				}	
				$i++;
			}
		}
		//if(!empty($filter[2]['search']['value']))
		//	$query .= ' LOWER(a.username) LIKE \'%'.strtolower($filter[2]['search']['value']).'%\'';
		//if(!empty($filter[5]['search']['value']))
		//	$query .= ' LOWER(b.user_level_name) LIKE \'%'.strtolower($filter[5]['search']['value']).'%\'';
		//
		$query .= " ORDER BY a.userid";
		if(!empty($limit))
			$query .= " LIMIT {$limit} OFFSET {$offset}";
		$res = $this->db->query($query);
		return $res->result_array();
	}

	public function countUsers(array $filter = NULL) {
		$query = "SELECT COUNT(*) as total FROM users a LEFT JOIN user_level b ON a.user_level=b.user_level_id ";
		if(!empty($filter[2]['search']['value']) ||
			!empty($filter[5]['search']['value'])) {
			$query .= " WHERE";
			$i = 0;
			foreach($filter as $K => $f) {
				if(empty($f['search']['value'])) continue;
				if($i != 0)
					$query .= ' AND';
				if(!empty($f['search']['value'])) {
					if($K == 2)
						$query .= ' LOWER(a.username) LIKE \'%'.strtolower($f['search']['value']).'%\'';
					else if($K == 5)
						$query .= ' LOWER(b.user_level_name) LIKE \'%'.strtolower($f['search']['value']).'%\'';
				}	
				$i++;
			}
		}
		//$query .= " ORDER BY a.userid";
		$res = $this->db->query($query);
		return $res->row_array()['total'];
	}
	 
}
