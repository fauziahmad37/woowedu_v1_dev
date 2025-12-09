<?php

class Notif extends CI_Controller
{

	private $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->database();
	}

	// public function store_asesmen_standard($data)
	// {

	// 	$data_notif = [
	// 		'type' 		=> 'ASESMEN',
	// 		'title' 	=> $data['title'],
	// 		'seen' 		=> false,
	// 		'created_at' => date('Y-m-d H:i:s'),
	// 		'link'		=> 'asesmen',
	// 		'exam_id'	=> $data['id']
	// 	];

	// 	// store notif student
	// 	$students = $this->CI->db->where('class_id', $data['class_id'])->get('student')->result_array();
	// 	foreach ($students as $student) {
	// 		$user_id = $this->CI->db->where('username', $student['nis'])->get('users')->row_array();
	// 		if ($user_id) {
	// 			$data_notif['user_id'] = $user_id['userid'];

	// 			$this->CI->db->insert('notif', $data_notif);
	// 		}
	// 	}

	// 	$teacher = $this->CI->db->where('teacher_id', $data['teacher_id'])->get('teacher')->row_array();
	// 	$user_id = $this->CI->db->where('username', $teacher['nik'])->get('users')->row_array();
	// 	$data_notif['user_id'] = $user_id['userid'];
	// 	// store notif teacher
	// 	$this->CI->db->insert('notif', $data_notif);
	// }
}
