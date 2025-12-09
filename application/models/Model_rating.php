<?php

class Model_rating extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get All Comment By Ebook
     *
     * @param integer $ebook
     * @return array
     */
    public function getByEbook(int $ebook): object {
        $query = "SELECT a.book_id, a.member_id, a.rate, a.komentar, a.created_at, 
                         COALESCE(c.student_name, d.teacher_name, e.name, 'Anonymous') as  member_name,
                         CASE
                            WHEN (b.photo IS NOT NULL OR b.photo <> '') THEN '".base_url('assets/images/users/')."' || b.photo
                            ELSE '' 
                         END as photo
                  FROM ratings a
                  LEFT JOIN users b ON a.member_id=b.userid
                  LEFT JOIN student c ON b.username=c.nis AND a.member_role=4
                  LEFT JOIN teacher d ON b.username=d.nik AND a.member_role=3
                  LEFT JOIN parent e ON b.username=e.username AND a.member_role=5
                  WHERE a.book_id=?";

        $res = $this->db->query($query, [$ebook]);
        return $res ?? new stdClass();
    }
}