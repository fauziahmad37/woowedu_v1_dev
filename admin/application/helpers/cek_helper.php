<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// function check_loggin() {
// 	$CI =& get_instance();
// 	$user = $CI->session->userdata('username');
// 	if (!isset($user)) { return false; } else { return true; }
// }

function check_Loggin()
{
    $CI= & get_instance();
    $session=$CI->session->userdata('status_login');
    $level = $CI->session->userdata('user_level');
    $bisaliat = [1, 10];
    if($session!='y' && !in_array(intval($level), $bisaliat))
    {
        redirect('auth/login');
    }
}

function chek_session_login()
{
    $CI= & get_instance();
    $session=$CI->session->userdata('status_login');
    if($session=='y')
    {
        redirect('dashboard');
    }
}

function check_auth($token) {

    $ci = &get_instance();

    if(empty($token))
        return false;

    $token = ltrim($token, 'Basic ');

    $auth = explode(':', base64_decode($token));

    if(empty($auth[0]))
        return false;

    $user = $ci->db->get_where('users', ['username' => $auth[0]])->row_array();

    if(!password_verify($auth[1], $user['password']))
        return false;

    return true;
}

function set_token($user_token) {
    $CI = &get_instance();

    $tok = explode('-', $user_token);
    $token_str = $tok[0].date('Y').$tok[1].date('m').$tok[2].date('d').$tok[3];

    return hash('sha256', $token_str);
}

function check_token($user_token) {
    $CI = &get_instance();

    $b64 = base64_decode($user_token);
    $token = explode(':', $b64);
    $user = $CI->db->get_where('users', ['users_token' => $token[0]])->row_array();

    if(empty($user['username']))
        return FALSE;

    $tok = explode('-', $user['users_token']);
    $token_str = $tok[0].date('Y').$tok[1].date('m').$tok[2].date('d').$tok[3];

    if($token[1] != hash('sha256', $token_str))
        return FALSE;

    return TRUE;
}