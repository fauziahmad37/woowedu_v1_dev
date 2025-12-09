<?php

class Model_soal_exam extends CI_Model
{
	/**
	 * Buat ngambil soal ujian yang sudah di jawabs
	 *
	 * @param array $filter
	 * @return array
	 */
	public function get_soal($filter = [])
	{
		$this->db->select('se.*, s.answer as correct_answer, ea.exam_answer, ea.exam_submit, s.type');
		$this->db->where('se.exam_id', $filter['exam_id']);
		$this->db->join('exam_answer ea', 'ea.exam_id = se.exam_id and ea.student_id = ' . $filter['student_id'] . ' and se.soal_id = ea.soal_id', 'left');
		$this->db->join('soal s', 's.soal_id = se.soal_id');
		$this->db->from('soal_exam se');
		$query = $this->db->get();

		$res = $query->result_array();
		$_arr = [];
		foreach($res as $r) {
			if($r['type'] == 5) 
				$r['correct_answer'] = $this->db->get_where('soal_pairing_question', ['soal_id' => $r['soal_id']])->result_array();
			if($r['type'] == 6) 
				$r['correct_answer'] = $this->db->get_where('soal_dragdrop_question', ['soal_id' => $r['soal_id']])->result_array();

			$_arr[] = $r;
		}

		return $_arr;
	}

	
	/**
	 * Mengambil soal yang akan di ujikan, load pda awal masuk halaman ujian
	 *
	 * @param int $exam_id
	 * @return void
	 */
	public function get_soal_exam_student($exam_id): array {
		$this->db->select('s.*, s.answer as correct_answer')
			->where('exam_id', $exam_id)
			->join('soal s', 's.soal_id = se.soal_id', 'left');

		$results = $this->db->get('soal_exam se')->result_array();

		$_out = [];

		foreach($results as $r) {
			if($r['type'] == 5) 
				$r['correct_answer'] = $this->db->get_where('soal_pairing_question', ['soal_id' => $r['soal_id']])->result_array();
			if($r['type'] == 6) 
				$r['correct_answer'] = $this->db->get_where('soal_dragdrop_question', ['soal_id' => $r['soal_id']])->result_array();

			$_out[] = $r;
		}

		return $_out;
	}

	public function get_soal_by_id($id)
	{
		$this->db->where('soal_id', $id);
		$query = $this->db->get('soal');
		return $query->row_array();
	}
}
