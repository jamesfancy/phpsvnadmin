(function(window, $) {
    $.extend({
        reload: function() {
            window.location.reload();
        },
        redirect: function(url, params) {
            if ($.isPlainObject(params)) {
                params = $.param(params);
            } else if (typeof params !== "string") {
                params = "";
            }

            if (!!params) {
                url += ((!!url.match(/\?/)) ? "?" : "&") + params;
            }
            window.location.href = url;
        },
        applyData: function() {
            $.fn.applyData.apply($("body"), arguments);
        }
    });

    $.fn.extend({
        placeholder: function(text) {
            if (typeof text === "undefined") {
                return this.attr("placeholder") || "";
            } else {
                return this.attr("placeholder", text || "");
            }
        },
        enterAsTab: function(selector) {
            var target = selector;
            if (typeof target === "string") {
                target = $(target);
            }

            this.off("keypress.app");

            if (!target) {
                return;
            }

            return this.on("keypress.app", function(event) {
                if (event.altKey || event.ctrlKey || event.shiftKey) {
                    return;
                }

                if (event.keyCode === 13) {
                    target.focus();
                    event.preventDefault();
                }
            });
        },
        enter: function(callback) {
            this.off("keypress.app");

            if (!$.isFunction(callback)) {
                return;
            }

            return this.on("keypress.app", function(event) {
                if (event.altKey || event.ctrlKey || event.shiftKey) {
                    return;
                }

                if (event.keyCode === 13) {
                    callback();
                }
            });
        },
        enable: function() {
            return this.attr("disabled", false);
        },
        disable: function() {
            return this.attr("disabled", true);
        },
        serializeObject: function() {
            "use strict";

            var result = {};
            var extend = function(i, element) {
                var node = result[element.name];

                if ('undefined' !== typeof node && node !== null) {
                    if ($.isArray(node)) {
                        node.push(element.value);
                    } else {
                        result[element.name] = [node, element.value];
                    }
                } else {
                    result[element.name] = element.value;
                }
            };

            $.each(this.serializeArray(), extend);
            return result;
        },
        applyData: function(data, converter) {
            if (!data) {
                return;
            }

            var isFunc = false;
            if ($.isFunction(converter)) {
                isFunc = true;
            } else if (!$.isPlainObject(converter)) {
                converter = null;
            }

            converter = converter || {};
            for (var key in data) {
                var dom = this.find("#" + key);
                if (dom.length === 0) {
                    continue;
                }

                var d = (function() {
                    if (isFunc) {
                        return converter(key, dom, data[key]);
                    } else {
                        var f = converter[key];
                        if ($.isFunction(f)) {
                            return f(dom, data[key]);
                        }
                    }
                })();
                
                if (d === false) {
                    continue;
                }

                if (d === undefined) {
                    d = data[key];
                }

                if (dom.is(":input")) {
                    dom.val(d);
                } else {
                    dom.text(d);
                }
            }
        }
    });
})(window, jQuery);

(function(window, $) {
    var app = {
        $: (function($) {
            var me = function(arg) {
                var map = {};

                var selector = null;
                if (typeof arg === "string" && !!arg) {
                    selector = $(arg);
                } else if (typeof arg === "object" && arg instanceof jQuery) {
                    selector = arg;
                }

                if ($.isArray(arg)) {
                    var idList = arg;
                    for (var i = 0; i < idList.length; i++) {
                        map[idList[i]] = $("#" + idList[i]);
                    }
                } else {
                    if (!selector) {
                        selector = $(document);
                    }

                    selector.find("[id]").each(function() {
                        var o = $(this);
                        map[o.attr("id")] = o;
                    });
                }

                $.extend(me, map);
            };

            return me;
        })($),
        json: function(url, data, options) {
            options = $.extend({
                type: "POST",
                dataType: "json"
            }, options);

            if (!!data) {
                options.data = $.extend(options.data || {}, data);
            }

            // DEBUG
            // return $.ajax(url, options);
            return $.ajax(url, options).done(function(data) {
                console.log("JSON: ", data);
            }).fail(function(xhr) {
                if (!!xhr) {
                    console.log("JSON Error: ", xhr.responseText);
                } else {
                    console.log("JSON Error: ", arguments);
                }
            });
        },
        assignNames: function() {
            $(":input:not([name])").filter("[id]").each(function() {
                var me = $(this);
                me.attr("name", me.attr("id"));
            });
        },
        setTitle: function(title) {
            document.title = title;
        },
        endLoading: function(selector) {
            var target;
            if (typeof selector === "object") {
                if (selector instanceof jQuery) {
                    target = selector;
                }
            } else if (typeof selector === "string") {
                target = $(selector);
            } else {
                target = $(".init_loading");
            }

            target.hide();
        }
    };
    window.app = app;
})(window, jQuery);

jQuery(function() {
    // 为Title添加后缀
    (function($) {
        var title = document.title || "";
        if (!title.match(/J\.Fan SvnAdmin$/)) {
            document.title = (!title
                    ? "J.Fan SvnAdmin"
                    : title + " - J.Fan SvnAdmin");
        }
    })(jQuery);
    
    // 初始化Header
    (function($) {
        var pageHeader = $("#pageHeader");
    })(jQuery);

    // 注销按钮
    $("#signOutButton").on("click", function() {
        if (!confirm("是否真要注销")) {
            return;
        }

        app.json("actions/user.php", {
            method: "signout"
        }).done(function() {
            $.redirect("index.php");
        });
    });
});
