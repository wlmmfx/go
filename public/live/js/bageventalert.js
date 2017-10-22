var bagEventAlert = {
    alert: function (d, a) {
        var b = {
            popType: "alert",
            title: "提示",
            content: "<div class='layer_msg'><p>" + (d === undefined ? "" : d) + "</p>" + (this._defaults.btnContent ? "<button id='bagEventPopBtnSure' type='button'>确定</button>" : "") + "</div>",
            callback: function () {}
        };
        var c = $.extend({}, this._defaults, b, a);
        this._creatLayer(c);
        c.closeTimer != "0" ? this._closeLayer(c) : ""
    }, btnAlert: function (d, a) {
        var b = {
            popType: "alert",
            title: "提示",
            content: "<div class='layer_msg'><p>" + (d === undefined ? "" : d) + "</p><button id='bagEventPopBtnSure' type='button' class='button save'>确定</button></div>",
            callback: function () {}
        };
        var c = $.extend({}, this._defaults, b, a);
        this._creatLayer(c);
        c.closeTimer != "0" ? this._closeLayer(c) : ""
    }, confirm: function (e, a) {
        var c = {
            confirm: "确定",
            cancel: "取消"
        };
        if (a != undefined && a.buttonMsg) {
            c = a.buttonMsg
        }
        var d = {
            popType: "confirm",
            title: "请选择",
            content: "<div class='layer_msg'><p>" + (e === undefined ? "" : e) + "</p><button id='bagEventPopBtnSure' type='button' class='button save'>" + c.confirm + "</button><button id='bagEventAlertBtncancel' type='button' class='button cancel'>" + c.cancel + "</button></div>",
            cancel: function () {}, confirm: function () {}
        };
        var b = $.extend({}, this._defaults, d, a);
        this._creatLayer(b)
    }, closebagEventAlert: function () {
        this._closeLayer()
    }, _defaults: {
        icon: "",
        title: "",
        content: "",
        width: 350,
        height: 0,
        background: "#000",
        opacity: 0.5,
        duration: "slow",
        showTitle: true,
        escClose: true,
        popMaskClose: false,
        drag: true,
        dragOpacity: 1,
        popType: "alert",
        type: "info",
        closeTimer: 0,
        btnContent: false,
        repeatConfirm: 0
    }, _creatLayer: function (b) {
        var a = this;
        $(".popMask").empty().remove();
        $(".popMain").empty().remove();
        $("body").append("<div class='popMask'></div>");
        var f = $(".popMask");
        f.css({
            "background-color": b.background,
            filter: "alpha(opacity=" + b.opacity * 100 + ")",
            "-moz-opacity": b.opacity,
            opacity: b.opacity
        });
        b.popMaskClose && f.bind("click", function () {
            a._closeLayer()
        });
        b.escClose && $(document).bind("keyup", function (i) {
            try {
                i.keyCode == 27 && a._closeLayer()
            } catch (h) {
                a._closeLayer()
            }
        });
        f.fadeIn(b.duration);
        var c = "<div class='popMain'>";
        c += "<div class='popTitle'>" + (b.icon !== undefined && b.icon !== "" ? "<img class='icon' src='" + b.icon + "' />" : "") + "<span class='text'>" + b.title + "</span><span class='close'>&times;</span></div>";
        c += "<div class='popContent'>" + b.content + "</div>";
        c += "</div>";
        $("body").append(c);
        var d = $(".popMain");
        d.find(".layer_msg").addClass(b.type + "_icon");
        var e = $(".popTitle");
        var g = $(".popContent");
        b.showTitle ? e.show() : e.hide();
        b.width !== 0 && e.width(b.width);
        $(".popTitle .close").bind("click", function () {
            f.fadeOut(b.duration);
            d.fadeOut(b.duration);
            d.attr("isClose", "1");
            b.type == "container" && $(b.targetId).empty().append(b.content)
        });
        b.width !== 0 && g.width(b.width);
        b.height !== 0 && g.height(b.height);
        d.css({
            left: $(window).width() / 2 - d.width() / 2 - 32 + "px",
            top: $(window).height() / 2 - d.height() / 2 - 130 + "px"
        });
        $(window).resize(function () {
            d.css({
                left: $(window).width() / 2 - d.width() / 2 + "px",
                top: $(window).height() / 2 - d.height() / 2 + "px"
            })
        });
        b.drag && this._drag(b.dragOpacity);
        switch (b.popType) {
        case "alert":
            d.fadeIn(b.duration, function () {
                d.attr("style", d.attr("style").replace("FILTER:", ""))
            });
            $("#bagEventPopBtnSure").bind("click", function () {
                b.callback();
                a._closeLayer()
            });
            break;
        case "confirm":
            d.fadeIn(b.duration, function () {
                d.attr("style", d.attr("style").replace("FILTER:", ""))
            });
            $("#bagEventPopBtnSure").bind("click", function () {
                a._closeLayer();
                b.confirm()
            });
            $("#bagEventAlertBtncancel").bind("click", function () {
                b.cancel();
                a._closeLayer()
            });
            break;
        case "prompt":
            d.fadeIn(b.duration, function () {
                d.attr("style", d.attr("style").replace("FILTER:", ""))
            });
            $("#bagEventPopBtnSure").bind("click", function () {
                b.confirm($(".layer_msg input").val());
                a._closeLayer()
            });
            $("#bagEventAlertBtncancel").bind("click", function () {
                b.cancel();
                a._closeLayer()
            });
            break;
        default:
            break
        }
    }, _closeLayer: function (a) {
        if (a != undefined && a != null) {
            if (a.closeTimer !== "null" && a.closeTimer !== "" && a.closeTimer != "0") {
                setTimeout(function () {
                    $(".popTitle .close").triggerHandler("click")
                }, a.closeTimer)
            }
        } else {
            $(".popTitle .close").triggerHandler("click")
        } if (a != undefined && a != null && a.repeatConfirm == 1) {} else {
            $("#bagEventPopBtnSure").unbind("click");
            $("#bagEventAlertBtncancel").unbind("click")
        }
    }, _drag: function (f) {
        var c = false,
            a, e;
        $(".popTitle").bind("mousedown", function (b) {
            if ($(".popMain:visible").length > 0) {
                c = true;
                a = b.pageX - parseInt($(".popMain").css("left"), 10);
                e = b.pageY - parseInt($(".popMain").css("top"), 10);
                $(".popTitle").css({
                    cursor: "move"
                })
            }
        });
        $(document).bind("mousemove", function (d) {
            if (c && $(".popMain:visible").length > 0) {
                f != 1 && $(".popMain").fadeTo(0, f);
                var b = d.pageX - a;
                d = d.pageY - e;
                if (b < 0) {
                    b = 0
                }
                if (b > $(window).width() - $(".popMain").width()) {
                    b = $(window).width() - $(".popMain").width() - 2
                }
                if (d < 0) {
                    d = 0
                }
                if (d > $(window).height() - $(".popMain").height()) {
                    d = $(window).height() - $(".popMain").height() - 2
                }
                $(".popMain").css({
                    top: d,
                    left: b
                })
            }
        }).bind("mouseup", function () {
            if ($(".popMain:visible").length > 0) {
                c = false;
                f != 1 && $(".popMain").fadeTo(0, 1);
                $(".popTitle").css({
                    cursor: "auto"
                })
            }
        })
    }
};