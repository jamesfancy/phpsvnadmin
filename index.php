<?php
require_once 'inc/common.inc.php';
$self = currentUser();
?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>登录</title>
        <?php include 'inc/inhead.inc.php' ?>
    </head>
    <body>
        <?php pageHeader(false); ?>
        <div class="index_main_panel" id="mainPanel">
            <div class="tab_panel">
                <a class="button rfloat" href="javascript:void(0)">忘记密码</a>
                <a class="button rfloat" href="javascript:void(0)">注册</a>
                <a class="button lfloat" href="javascript:void(0)">登录</a>
            </div>
            <div class="login_panel" id="loginPanel">
                <div class="top_spliter"></div>
                <div>
                    <label>用户名</label>
                    <input type="text" id="username" />
                </div>
                <div>
                    <label>密码</label>
                    <input type="password" id="password" />
                </div>
                <div>
                    <input type="button" id="loginButton" value="登录" />
                </div>
            </div>
        </div>
    </body>
    <?php include 'inc/endbody.inc.php' ?>
</html>
