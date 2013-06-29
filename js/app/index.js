$(function() {
    app.$();
    app.assignNames();

    app.$.loginUser.placeholder("username").enterAsTab(app.$.password);
    app.$.loginPass.placeholder("password").enter(function() {
        app.$.loginButton.click();
    });

    app.$.regUser.placeholder("username").enterAsTab(app.$.regPass);
    app.$.regEmail.placeholder("password").enterAsTab(app.$.regEmail);
    app.$.regPass.placeholder("email").enter(function() {
        app.$.registerButton.click();
    });

    app.$.forgotUser.placeholder("username or email").enter(function() {
        app.$.getPasswordButton.click();
    });

    // intialize tab
    (function(app, $) {
        $.fn.tab = function() {
            var tab = this;
            app.$.tabPanel.find("a").removeClass("active");
            tab.addClass("active");

            $(".index_panel").hide();
            tab.data("panel").show();
            tab.trigger("tab");
        };

        app.$.loginTab.data("panel", app.$.loginPanel).on("tab", function() {
            app.$.loginUser.focus();
        });

        app.$.registerTab.data("panel", app.$.registerPanel).on("tab", function() {
            app.$.regUser.focus();
        });

        app.$.forgotTab.data("panel", app.$.forgotPanel).on("tab", function() {
            app.$.forgotUser.focus();
        });

        app.$.tabPanel.on("click", "a", function() {
            $(this).tab();
        });

        // active a tab
        var url = window.location.href;
        var m = url.match(/\?(.*)/);
        if (!!m && m[1] === "reg") {
            app.$.registerTab.tab();
        } else {
            app.$.loginTab.tab();
        }
    })(app, $);

    app.$.loginButton.on("click", function() {
        var me = $(this);
        me.disable();

        app.$.loginForm.json().done(function(data) {
            if (data.code === 0) {
                $.redirect("user.php");
            } else {
                alert("登录失败：" + data.message);
            }
        }).fail(function() {
            alert("登录失败");
        }).complete(function() {
            me.enable();
        });
    });

    app.$.registerButton.on("click", function() {
        var me = $(this);
        me.disable();

        app.$.registerForm.json().done(function(data) {
            if (data.code === 0) {
                console.log("register successful");
            } else {
                alert("注册用户失败：" + data.message);
            }
        }).fail(function() {
            alert("注册用户失败");
        }).complete(function() {
            me.enable();
        });
    });

    app.$.getPasswordButton.on("click", function() {
        var me = $(this);
        me.disable();

        app.$.forgotForm.json().done(function(data) {
            if (data.code === 0) {
                console.log("find password success");
            } else {
                alert("找回密码失败：" + data.message);
            }
        }).fail(function() {
            alert("找回密码失败");
        }).complete(function() {
            me.enable();
        });
    });
});
