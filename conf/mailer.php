<?php

// use the real phpmailer plugin path to instead of the default one
$phpmailer = 'phpmailer/class.phpmailer.php';

$config->mailer = [
    'from' => 'yours@domain.com',
    'fromName' => 'Your Name',
    'smtpHost' => 'stmp.domain.com',
    'smtpAuth' => true,
    'smtpUser' => 'sender username',
    'smtpPass' => 'sender password',
    'charset' => 'UTF-8'
];

require_once($phpmailer);
