<?php
/*
==================================================
buat prevent csrf token 
dan biar g ganggu form yang laen 
=================================================
*/

class Csrfsimple {
    private $ci;

    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->library('session');
    }

    public function genToken() {
        $_s = NULL;
        /// biar per user beda token
        if(isset($_SESSION['user_token']))
           $_s = $_SESSION['csrf_'.$_SESSION['user_token']] = bin2hex(random_bytes(32));
        else
            // klo user nya belum login
            $_s = $_SESSION['XsrfToken'] = bin2hex(random_bytes(32));
        return $_s;
    }

    public function checkToken($post) {
        $_s = NULL;
        if(isset($_SESSION['user_token']))
            $_s =  $_SESSION['csrf_'.$_SESSION['user_token']];
        else
            $_s = $_SESSION['XsrfToken'];

        if(!empty($_s) && $post == $_s) {
            if(isset($_SESSION['user_token']))
                $_SESSION['csrf_'.$_SESSION['user_token']] = NULL;
            else
                $_SESSION['XsrfToken'] = NULL;
            return true;
        }
        return false;
    }
}