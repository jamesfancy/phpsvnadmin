<?php

define('DEFAULT_PASS_LEN', 10);
define('SALT_LEN', 16);

$keyName = 'username';
require_once 'action.inc.php';

function onMethod($method) {
    $map = [
        'signin' => 'onSignIn',
        'signout' => 'onSignOut',
        'signup' => 'onCreate',
        'resetpass' => 'onResetPassword',
        'updatepass' => 'onUpdatePassword',
        'findpass' => 'onFindPassword'
    ];

    return onMethodMap($method, $map);
}

function onSignIn() {
    global $key;
    $user = verifyPassword($key, getParam('password', null));
    if ($user == null) {
        throw new Exception('login failed', 1);
    }

    $signObject = (object) [
                'username' => $key,
                'realname' => $user['realname'],
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
    $user = getParamObject([
        'username', 'password', 'realname',
        'email', 'type'
    ]);

    // validate username
    if ($user->username != null) {
        $user->username = strtolower(trim($user->username));
    }

    if (empty($user->username)) {
        throw new Exception('expect username', 1);
    }

    if (empty($user->password)) {
        $user->password = genPassword();
        $user->literalPassword = $user->password;
    }

    $user->salt = genSalt();
    $user->password = hashPassword($user->username, $user->password, $user->salt);

    global $dao;
    $dao->createUser($user);

    // create json result model
    $result = new stdClass();
    $result->username = $user->username;
    if (isset($user->literalPassword)) {
        $result->password = $user->literalPassword;
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

function onFindPassword() {
    global $key;
    $username = $key;
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
        $password = genPassword();
        $isRandomPassword = true;
    } else {
        $isRandomPassword = false;
    }

    $salt = genSalt();
    $hash = hashPassword($username, $password, $salt);

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

    $hash = hashPassword($user['username'], $password, $user['salt']);
    if ($hash == $user['password']) {
        return $user;
    } else {
        return null;
    }
}

function hashPassword($username, $password, $salt) {
    return md5(md5($username) . md5($password) . $salt);
}

function genSalt() {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $salt = '';
    for ($i = 0; $i < SALT_LEN; $i++) {
        $salt .= $chars[rand(0, 52)];
    }
    return md5($salt);
}

function genPassword($length = 0) {
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
