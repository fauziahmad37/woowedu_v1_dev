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

	/**
	 * Get all class level by teacher 
	 *
	 * @param [type] $userid
	 * @return array
	 */
	public function get_all_class_teacher($userid): array {
		$query = "SELECT a.class_id, b.class_level_id, c.class_level_name
					FROM class_teacher a
					JOIN kelas b ON a.class_id=b.class_id 
					JOIN class_level c ON b.class_level_id=c.class_level_id
					WHERE a.teacher_id=?";

		$res = $this->db->query($query, [$userid]);
		return $res->result_array() ?? [];
	}

	/**
	 * Get All Class lvel by student
	 *
	 * @param [type] $userid
	 * @return array
	 */
	public function get_all_class_student($userid): array {
		$query = "SELECT a.class_id, a.class_level_id, b.class_level
					FROM student a
					JOIN kelas b ON a.class_id=b.class_id 
					JOIN class_level c ON b.class_level_id=c.class_level_id
					WHERE a.nis=?";

		$res = $this->db->query($query, [$userid]);
		return $res->row_array() ?? [];
	}

}