<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_login extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }



  function cekadmin($username, $pass)
  {
    $this->db->where('username', $username);
    $this->db->where('password', $pass);
    return $this->db->get('users');
  }

}
