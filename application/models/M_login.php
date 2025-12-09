<?php 
class m_login extends CI_Model{

	private $_table = "users";
	
	function login($username, $password)
	{
		return $this->db->get_where('users',array('username' => $username,'password' => $password));
	}

	function loginCek($username)
	{
		return $this->db->get_where('users', array('username' => $username));
	}

	function passwordCek($password)
	{
		return $this->db->get_where('users', array('password' => $password));
	}
	 
	function tampildata()
	{
		return $this->db->get('users');
	}

	public function userdata($username)
	{
		$this->db->select('a.*, b.*');
		$this->db->from('users a'); 
		$this->db->join('user_level b', 'a.user_level = b.user_level_id', 'INNER');
		$this->db->where('a.username', $username);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}

	public function proses_login($username)
	{
		$this->db->select('a.*, b.*');
		$this->db->from('users a'); 
		$this->db->join('user_level b', 'a.user_level = b.user_level_id', 'INNER');
		$this->db->where('a.username', $username);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}
	
	function get_one($id)
	{
		$param  =   array('userid'=>$id);
		return $this->db->get_where('users', $param);
	}

	public function last_lg($log)
	{  
		extract($log);
		$this->db->where('userid', $userid);
		$this->db->update('users', array('last_login' => $last_login));
	}

	// public function last_lo($log)
	// {  
	// 	extract($log);
	// 	$this->db->where('userid', $userid);
	// 	$this->db->update('users', array('last_logout' => $last_logout));
	// }
}