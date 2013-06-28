<?php
require_once dirname(__FILE__) . '/common.inc.php';

if (isset($_SESSION['user'])) {
    $self = $_SESSION['user'];
} else {
    $self = null;
}

if ($self == null) {
    ?>
    <div id="pageHeader" class="header">
        <a class="button" id="hRegisterButton" href="javascript:void(0)">注册</a>
        <a class="button" id="hSignInButton" href="javascript:void(0)">登录</a>
    </div>
    <?php
} else {
    $userinfo = $self->username;
    if (isset($self->realname)) {
        $realname = $self->realname;
    }
    if (!empty($realname)) {
        $userinfo .= "[$realname]";
    }
    ?>
    <div class="header">
        <span><?php echo $userinfo ?></span>
        <a class="button" id="hSignOutButton" href="javascript:void(0)">注销</a>
    </div>
    <?php
}
?>
