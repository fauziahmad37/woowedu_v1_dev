<?php

class Welcome extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        
    }

    public function index() {
        $data = [
            'username'  => 'admin',
            'password'  => password_hash('admin', PASSWORD_DEFAULT)
        ];

        $this->db->update('users', $data, ['username' => 'Admin']);
    }

    public function test() {
        echo base64_encode('HITGandaria8');
    }
}