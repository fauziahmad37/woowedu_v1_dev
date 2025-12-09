<?php
class m_users extends CI_Model{
	
	private $users = "users";

	function tampil_data_profile_log($userid)
	{
		$this->db->select('a.*, b.*');
		$this->db->from('users a');
		$this->db->join('user_level b', 'a.user_level = b.user_level_id', 'INNER');
		$this->db->where('a.userid', $userid);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}

	public function change_pw($data)
	{
		extract($data);
		$this->db->where('userid', $userid);
		$this->db->update('users', array('password' => $password));
	}

	public function getCountUsers()
	{
		$query = $this->db->query('SELECT COUNT(userid) AS cusers FROM users');
		return $query->row();
	}

	public function cek_pw($pw_lama)
	{
	$chek = $this->db->get_where('users',array('password'=> $pw_lama));
		if($chek->num_rows()>0){
			return 1;
		}
		else{
			return 0;
		}
	}

	public function get_comboRole(){
		$query = $this->db->query('SELECT * FROM user_level ORDER BY user_level_id ASC');
		return $query->result();
	}

	function tampil_data_profile()
	{
		$id = $this->session->userdata('userid');

		$this->db->select('a.*, b.*');
		$this->db->from('users a'); 
		$this->db->join('user_level b', 'a.user_level = b.user_level_id', 'INNER');
		$this->db->where('a.userid', $id);
		$query=$this->db->get();
		$data = $query->row();
		return $data;
	}

}