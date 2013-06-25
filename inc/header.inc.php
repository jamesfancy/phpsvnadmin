<?php
require_once dirname(__FILE__) . '/common.inc.php';

if (isset($_SESSION['user'])) {
    $self = $_SESSION['user'];
} else {
    $self = null;
}

if ($self == null) {
    ?>
    <div class="header">
        <a href="javascript:void(0)">登录</span>
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
        <a id="signOutButton" href="javascript:void(0)">注销</a>
    </div>
    <?php
}
?>
