<?php
require_once 'inc/common.inc.php';
$self = currentUser();

// 未登录用户不能查看信息
if (empty($self)) {
    redirect('index.php');
}
?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>用户信息</title>
        <link type="text/css" rel="stylesheet/less" href="style/def.less" />
        <script type="text/javascript" src="js/less-1.3.3.min.js"></script>
    </head>
    <body>
        <?php pageHeader(true); ?>
        <div>
            <label>用户名</label>
            <span id="username"></span>
        </div>
        <div>
            <label>姓名</label>
            <span id="realname"></span>
        </div>
        <div>
            <label>注册邮箱</label>
            <span id="email"></span>
        </div>
        <div>
            <span id="type"></span>
        </div>
    </body>
    <script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <script type="text/javascript">
<?php
$username = getParam('username', null);
// 只有管理员可以查看别人的信息，否则都只能查看自己的信息

if (empty($username) || $self->type == 0) {
    $user = $dao->getUser($self->username);
} else {
    $user = $dao->getUser($username);
}

putJson('user', $user);
?>

        $(function() {
            app.setTitle("用户 [{0}]".replace("{0}", user.username));

            var converter = {
                "email": function(dom, data) {
                    if (!data) {
                        return false;
                    }

                    $("<a>").attr("href", "emailto:" + data)
                            .text(data).appendTo(dom);

                    return false;
                },
                "type": function(dom, data) {
                    return data ? "管理员" : "普通用户";
                }
            };

            $.applyData(user, converter);
            app.endLoading();
        });
    </script>
</html>
