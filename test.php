<?php

session_start();
echo json_encode($_SESSION['user']);
//require_once 'conf.php';
//$config->import('mailer');
//
//$db = 'sqlite';
//require_once("db/$db.php");
//
//echo $dao->getUserList(0);
