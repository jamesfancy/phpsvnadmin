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
            <div class="tab_panel" id="tabPanel">
                <a id="loginTab" class="button lfloat active" href="javascript:void(0)">登录</a>
                <a id="forgotTab" class="button rfloat" href="javascript:void(0)">忘记密码</a>
                <a id="registerTab" class="button rfloat" href="javascript:void(0)">注册</a>
            </div>
            <div class="index_panel" id="loginPanel">
                <div>
                    <label>用户名</label>
                    <input type="text" id="loginUser" />
                </div>
                <div>
                    <label>密码</label>
                    <input type="password" id="loginPass" />
                </div>
                <div>
                    <input type="button" id="loginButton" value="登录" />
                </div>
            </div>
            <div class="index_panel" id="registerPanel">
                <div>
                    <label>用户名</label>
                    <input type="text" id="regUser" />
                </div>
                <div>
                    <label>注册邮箱</label>
                    <input type="text" id="regEmail" />
                </div>
                <div>
                    <label>设置密码</label>
                    <input type="password" id="regPass" />
                </div>
                <div>
                    <input type="button" id="registerButton" value="注册" />
                </div>
            </div>
            <div class="index_panel" id="forgotPanel">
                <div>
                    <label>用户名或邮箱</label>
                    <input type="text" id="forgotUser" />
                </div>
                <div>
                    <input type="button" id="getPasswordButton" value="找回密码" />
                </div>
            </div>
        </div>
        <?php include 'inc/footer.inc.php' ?>
    </body>
    <?php include 'inc/endbody.inc.php' ?>
</html>
