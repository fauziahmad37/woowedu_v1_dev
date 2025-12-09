<?php

class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_user($user_id)
	{
		$userLevel = $this->get_level($user_id);
		$query = [];
		if($userLevel == 4){
			$this->db->where('u.userid', $user_id);
			$this->db->join('student s', 's.nis = u.username');
			$this->db->join('sekolah sc', 'sc.sekolah_id = u.sekolah_id', 'left');
			$query = $this->db->get('users u');
		}elseif($userLevel == 3 || $userLevel == 6){
			$this->db->where('u.userid', $user_id);
			$this->db->join('teacher t', 't.nik = u.username');
			$this->db->join('sekolah sc', 'sc.sekolah_id = u.sekolah_id', 'left');
			$query = $this->db->get('users u');
		}elseif($userLevel == 5){
			$this->db->where('u.userid', $user_id);
			$this->db->join('parent p', 'p.username = u.username');
			$this->db->join('sekolah sc', 'sc.sekolah_id = u.sekolah_id', 'left');
			$query = $this->db->get('users u');
		}
		
		return $query->row_array() ?? [];
	}

	public function update($data, $user_id){
		$this->db->where('userid', $user_id);
		return $this->db->update('users', $data);
	}

	public function get_level($user_id){
		$this->db->where('userid', $user_id);
		return $this->db->get('users')->row_array()['user_level'];
	}


}
