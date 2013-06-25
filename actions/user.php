<?php

define('DEFAULT_PASS_LEN', 10);

$keyName = 'username';
require_once 'action.inc.php';

function onMethod($method) {
    switch ($method) {
        case 'signin':
            return onSignIn();
        case 'signout':
            return onSignOut();
        case 'resetpass':
            return onResetPassword();
        case 'updatepass':
            return onUpdatePassword();
        case 'findpass':
            return onFindPassword();
        default:
            noMethod($method);
    }
}

function onSignIn() {
    global $key;
    $user = verifyPassword($key, getParam('password', null));
    if ($user == null) {
        throw new Exception('login failed', 1);
    }

    $signObject = (object) [
                'username' => $key,
                'type' => $user['type'],
                'signToken' => md5(rand()),
                'signTime' => time()
    ];
    $_SESSION['user'] = $signObject;

    return $signObject;
}

function onSignOut() {
    session_destroy();
    return true;
}

function onQuery($username) {
    global $dao;
    return $dao->getUser($username);
}

function onQueryList($page) {
    global $dao;
    return $dao->getUserList($page);
}

function onCreate() {
    $username = strtolower(trim(getParam('username', '')));
    if (empty($username)) {
        throw new Exception('expect username', 1);
    }

    $password = getParam('password', null);
    if (empty($password)) {
        $password = genPassword(0);
        $isRandomPassword = true;
    } else {
        $isRandomPassword = false;
    }

    $salt = md5(rand());
    $hash = md5($username . $password . $salt);

    global $dao;
    $dao->createUser($username, $salt, $hash);

    $result = new stdClass();
    $result->username = $username;
    if ($isRandomPassword) {
        $result->password = $password;
    }

    return $result;
}

function onUpdate($username) {
    $user = new stdClass();
    
    $user->username = $username;
    $user->realname = getParam('realname', null);
    $user->type = intval(getParam('type', 0));
    global $dao;

    $dao->updateUser($user);
    return $user;
}

function onDelete($username) {
    global $dao;
    return $dao->deleteUser($username);
}

function onFindPassword($username) {
    $email = trim(getParam('email', null));
    if (empty($email)) {
        throw new Exception('expect email', 1);
    }
    
    global $dao;
    $user = $dao->getUser($username);
    if (!isset($user)) {
        throw new Exception('user not exists', 2);
    }
    
    if (!isset($user['email'])) {
        throw new Exception('not register email address', 3);
    }
    
    if (!$user['verified']) {
        throw new Exception('not verified the email address', 4);
    }
    
    if (strcasecmp($email, $user['email']) != 0) {
        throw new Exception('email address is incorrect', 5);
    }
    
    // TODO 发送重设邮件，并登记重设密码的临时token，要设置过期时间
    return true;
}

function onResetPassword() {
    global $key;
    return updatePassword($key, getParam('password', null));
}

function onUpdatePassword() {
    global $key;
    $password = getParam('old_pass', null);
    if (verifyPassword($key, $password) == null) {
        throw new Exception('old password is not matched');
    }

    return updatePassword($key, getParam('password', null));
}

function updatePassword($username, $password) {
    global $dao;
    if (empty($password)) {
        $password = genPassword(0);
        $isRandomPassword = true;
    } else {
        $isRandomPassword = false;
    }

    $salt = md5(rand());
    $hash = hashPassword($username, $salt, $password);

    $result = new stdClass();
    $result->daoResult = $dao->changePassword($username, $salt, $hash);
    $result->username = $username;
    if ($isRandomPassword) {
        $result->password = $password;
    }
    return $result;
}

function verifyPassword($username, $password) {
    if (empty($password)) {
        return false;
    }

    global $dao;
    $user = $dao->getUserWithPassword($username);
    if ($user == null) {
        return false;
    }

    $hash = hashPassword($user['username'], $user['salt'], $password);
    if ($hash == $user['password']) {
        return $user;
    } else {
        return null;
    }
}

function hashPassword($username, $salt, $password) {
    return md5($username . $password . $salt);
}

function genPassword($length) {
    if ($length < 6) {
        $length = DEFAULT_PASS_LEN;
    }

    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $pass = '';
    for ($i = 0; $i < $length; $i++) {
        $pass .= $chars[rand(0, 52)];
    }
    return $pass;
}
