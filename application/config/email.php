<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol']    = 'smtp';
$config['smtp_host']   = 'ssl://smtp.googlemail.com';
$config['smtp_port']   = 465;
$config['smtp_user']   = 'email_anda@gmail.com'; // TODO: Ganti dengan email aktif
$config['smtp_pass']   = 'app_password_anda';    // TODO: Ganti dengan App Password Gmail (bukan password login biasa)
$config['smtp_crypto'] = 'ssl';
$config['mailtype']    = 'html';
$config['charset']     = 'utf-8';
$config['newline']     = "\r\n";
$config['wordwrap']    = TRUE;
