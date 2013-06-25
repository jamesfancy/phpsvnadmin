$(function() {
    app.$();
    app.assignNames();

    app.$.username.enterAsTab(app.$.password);
    app.$.password.enter(function() {
        app.$.loginButton.click();
    });

    app.$.loginButton.on("click", function() {
        var me = $(this);
        me.disable();

        var data = $.extend({
            method: "signin"
        }, $(":input").serializeObject());

        app.json("actions/user.php", data).done(function(data) {
            console.log(data);
            if (data.code === 0) {
                $.redirect("user.php");
            } else {
                // TODO 这里有登录失败的相关提示
                alert("登录失败：" + data.message);
            }
        }).fail(function() {
            alert("登录失败");
        }).complete(function() {
            me.enable();
        });
    });

    app.$.username.focus();
});
