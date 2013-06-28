$(function() {
    app.$();
    app.assignNames();

    app.$.loginUser.placeholder("username").enterAsTab(app.$.password);
    app.$.loginPass.placeholder("password").enter(function() {
        app.$.loginButton.click();
    });

    app.$.regUser.placeholder("username").enterAsTab(app.$.regEmail);
    app.$.regEmail.placeholder("email").enterAsTab(app.$.regPass);
    app.$.regPass.placeholder("password").enter(function() {
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
    
    app.$.registerButton.on("click", function() {
        console.log("register");
    });
    
    app.$.getPasswordButton.on("click", function() {
        console.log("getPassword");
    });
});
