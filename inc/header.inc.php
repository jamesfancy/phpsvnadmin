<?php
require_once dirname(__FILE__) . '/common.inc.php';

if (isset($_SESSION['user'])) {
    $self = $_SESSION['user'];
} else {
    $self = null;
}

global $appRoot;

$logo = "{$appRoot}style/img/logo.png";
$index = "{$appRoot}index.php";
$user = "{$appRoot}user.php";
?>

<div id = "pageHeader" class = "header">
    <img class="logo" src="<?php echo $logo ?>" />
    <span class="title">PHP SVN Admin</span>
    <?php if ($self == null) { ?>

        <a class="button" id="hRegisterButton" href="<?php echo $index ?>?reg">注册</a>
        <a class="button" id="hSignInButton" href="<?php echo $index ?>">登录</a>
        <?php
    } else {
        $username = $self->username;
        $realname = $self->realname;
        if (empty($realname)) {
            $realname = $username;
        }
        ?>

        <a class="button" id="hSignOutButton" href="javascript:void(0)">注销</a>
        <a class="button" id="hUserInfo" href="<?php echo $user ?>" title="<?php echo $username ?>"><?php echo $realname ?></a>
        <?php
    }
    ?>

</div>
