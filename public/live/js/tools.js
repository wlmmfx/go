/*!
 * 工具类
 *
 */
var change_random_code = function(e, d) {
    var b = new Date();
    var c, a;
    if (emptyStr(d)) {
        d = "code_img"
    }
    c = $("." + d + "").attr("src");
    if ((a = c.lastIndexOf("?")) != -1) {
        c = c.substr(0, a)
    }
    if (emptyStr(e)) {
        $("." + d + "").attr("src", c + "?d=" + b.getTime())
    } else {
        $("." + d + "").attr("src", c + "?code=" + e + "&d=" + b.getTime())
    }
    b = null,
    c = null,
    a = null;
    $(".code_img").val("")
};
var emptyStr = function(a) {
    if (a == undefined || a == null || a.length < 1) {
        return true
    } else {
        return false
    }
};
var priceRegex = function(b) {
    if (b == undefined || b == null || b.length < 1) {
        return true
    } else {
        var a = /^\d{1,10}$|^\d{1,10}\.\d{0,1}\w?$/;
        if (!a.test(b)) {
            return true
        } else {
            return false
        }
    }
};
var wholeNumberRegex = function(b) {
    if (b == undefined || b == null || b.length < 1) {
        return true
    } else {
        var a = /^\+?[1-9][0-9]*$/;
        if (!a.test(b)) {
            return true
        } else {
            return false
        }
    }
};
function common_upload_file(c, f, a, g, h,l,j,k) {
    var i = $("#" + f);
    if (i.val() == "") {
        return
    }
    var b = document.getElementById("upload_file_form");
    b.action = c;
    b.method = "post";
    b.target = "upload_file_iframe";
    b.innerHTML = "";
    create_form_util(b, "hidden", "callback_name", a);
    create_form_util(b, "hidden", "name", f);
    create_form_util(b, "hidden", "_csrf_token", l);
    create_form_util(b, "hidden", "id", k);

    create_form_util(b, "hidden", "token", j);
    if (g) {
        create_form_util(b, "hidden", "args", g)
    }
    if (h) {
        create_form_util(b, "hidden", "type", h)
    }
    var d = i.next();
    var e = i.parent();
    b.appendChild(document.getElementById(f));
    b.submit();
    if (d.length > 0) {
        i.insertBefore(d)
    } else {
        i.appendTo(e)
    }
}
function create_form_util(e, d, c, a) {
    var b = document.createElement("input");
    b.type = d;
    if (c && c != "") {
        b.name = c
    }
    if (a && a != "") {
        b.value = a
    }
    e.appendChild(b)
}
function setAuthCountdown(f, d, c) {
    var e = this,
    b = $(f);
    d = "undefined" != typeof d ? d: 60;
    if ("undefined" == typeof c) {
        c = function() {}
    }
    this.setAuthCountdown.time = d;
    "undefined" != typeof this.setAuthCountdown.timer && clearInterval(this.setAuthCountdown.timer);
    this.setAuthCountdown.timer = setInterval(function() {
        var a = e.setAuthCountdown.time;
        b.html(a),
        0 >= a && (c(), clearInterval(e.setAuthCountdown.timer));
        e.setAuthCountdown.time--
    },
    1000)
}
var alertWarningTips = function(b, a) {
    bagEventAlert.alert(b, {
        type: "info",
        closeTimer: 4000
    });
    if (!emptyStr(a)) {
        $("html,body").animate({
            scrollTop: $("[name='" + a + "']").offset().top - 150
        },
        1000);
        if ("parentIFrame" in window) {
            console.log("has parentIFrame");
            parentIFrame.scrollTo(0, $("[name='" + a + "']").offset().top - 150)
        }
    }
};
var alertWarningTipsLong = function(a, b) {
    bagEventAlert.alert(a, {
        type: "info",
        closeTimer: b
    })
};
var alertSuccessTips = function(a) {
    bagEventAlert.alert(a, {
        type: "right",
        closeTimer: 2500
    })
};
var alertTips = function(b, a) {
    alert(b);
    if (!emptyStr(a)) {
        $("html,body").animate({
            scrollTop: $("[name='" + a + "']").offset().top - 150
        },
        1000);
        if ("parentIFrame" in window) {
            console.log("has parentIFrame");
            parentIFrame.scrollTo(0, $("[name='" + a + "']").offset().top - 150)
        }
    }
};
function redirectEmail(a) {
    $t = a.split("@")[1];
    $t = $t.toLowerCase();
    if ($t == "163.com") {
        return "mail.163.com"
    } else {
        if ($t == "vip.163.com") {
            return "vip.163.com"
        } else {
            if ($t == "126.com") {
                return "mail.126.com"
            } else {
                if ($t == "qq.com" || $t == "vip.qq.com" || $t == "foxmail.com") {
                    return "mail.qq.com"
                } else {
                    if ($t == "gmail.com") {
                        return "mail.google.com"
                    } else {
                        if ($t == "sohu.com") {
                            return "mail.sohu.com"
                        } else {
                            if ($t == "tom.com") {
                                return "mail.tom.com"
                            } else {
                                if ($t == "vip.sina.com") {
                                    return "vip.sina.com"
                                } else {
                                    if ($t == "sina.com.cn" || $t == "sina.com") {
                                        return "mail.sina.com.cn"
                                    } else {
                                        if ($t == "tom.com") {
                                            return "mail.tom.com"
                                        } else {
                                            if ($t == "yahoo.com.cn" || $t == "yahoo.cn") {
                                                return "mail.cn.yahoo.com"
                                            } else {
                                                if ($t == "tom.com") {
                                                    return "mail.tom.com"
                                                } else {
                                                    if ($t == "yeah.net") {
                                                        return "www.yeah.net"
                                                    } else {
                                                        if ($t == "21cn.com") {
                                                            return "mail.21cn.com"
                                                        } else {
                                                            if ($t == "hotmail.com") {
                                                                return "www.hotmail.com"
                                                            } else {
                                                                if ($t == "sogou.com") {
                                                                    return "mail.sogou.com"
                                                                } else {
                                                                    if ($t == "188.com") {
                                                                        return "www.188.com"
                                                                    } else {
                                                                        if ($t == "139.com") {
                                                                            return "mail.10086.cn"
                                                                        } else {
                                                                            if ($t == "189.cn") {
                                                                                return "webmail15.189.cn/webmail"
                                                                            } else {
                                                                                if ($t == "wo.com.cn") {
                                                                                    return "mail.wo.com.cn/smsmail"
                                                                                } else {
                                                                                    if ($t == "139.com") {
                                                                                        return "mail.10086.cn"
                                                                                    } else {
                                                                                        if ($t == "outlook.com" || $t == "hotmail.com") {
                                                                                            return "login.live.com"
                                                                                        } else {
                                                                                            return ""
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
var randomArrayIndex = function(c) {
    var b = 0,
    a = c.length;
    return parseInt(Math.random() * a + b, 10)
};
var randomIconColor = function() {
    var a = ["#7362b2", "#24acd8", "#46b9cb", "#66b969", "#f381c5", "#ff2d4a", "#ffa300", "#61d032"];
    return a[this.randomArrayIndex(a)]
};
var displayUserIcon = function(c, d, b) {
    if (d != "" && d.indexOf("user_img.png") == -1) {
        $(".avator").html('<img src="' + _SYS_CONFIG.imgDomain + d + '-userAvatar">')
    } else {
        var a = emptyStr(b) ? "bg": b;
        $(".avator").html('<span class="' + a + '" style="background:' + renderIconColor(c.substr(0, 1).toUpperCase()) + '">' + c.substr(0, 1).toUpperCase() + "</span>")
    }
};
var weixinBadgeDisplayUserIcon = function(d, e, c, b) {
    if (e != "" && e != null) {
        $("#avator" + c).html('<img src="' + e + '">')
    } else {
        var a = emptyStr(b) ? "bg": b;
        $("#avator" + c).html('<span class="' + a + '" style="background:' + renderIconColor(d.substr(0, 1).toUpperCase()) + '">' + d.substr(0, 1).toUpperCase() + "</span>")
    }
};
var payServiceFunction = function(a) {
    if (!a) {
        a = {}
    }
    $.ajax({
        type: "POST",
        url: "/action/service/submitSingleServiceOrder",
        data: a,
        dataType: "json",
        async: false,
        success: function(b) {
            if (b.retStatus == 200) {
                window.location.href = b.resultObject
            } else {
                alert(b.resultObject)
            }
        }
    })
};
function calculateSmsNumber(c) {
    var a = c.length + 6;
    var b = 1;
    if (a > 70) {
        b = parseInt(a / 67) + 1
    }
    return b
}
function closed_layer(a) {
    if (emptyStr(a)) {
        $(".common_right_layer").addClass("closed")
    } else {
        $("." + a).addClass("closed")
    }
}
function open_layer(a) {
    if (emptyStr(a)) {
        $(".common_right_layer").removeClass("closed")
    } else {
        $("." + a).removeClass("closed")
    }
}
function generateLoadingHtml(b) {
    var a = "<div class='loading'> <svg viewBox='0 0 120 120' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>";
    a += "<g id='circle' class='g_circles'>";
    a += " <circle id='12' transform='translate(35, 16.698730) rotate(-30) translate(-35, -16.698730) ' cx='35' cy='16.6987298' r='10'></circle>";
    a += "<circle id='11' transform='translate(16.698730, 35) rotate(-60) translate(-16.698730, -35) ' cx='16.6987298' cy='35' r='10'></circle>";
    a += "<circle id='10' transform='translate(10, 60) rotate(-90) translate(-10, -60) ' cx='10' cy='60' r='10'></circle>";
    a += "<circle id='9' transform='translate(16.698730, 85) rotate(-120) translate(-16.698730, -85) ' cx='16.6987298' cy='85' r='10'></circle>";
    a += "<circle id='8' transform='translate(35, 103.301270) rotate(-150) translate(-35, -103.301270) ' cx='35' cy='103.30127' r='10'></circle>";
    a += "<circle id='7' cx='60' cy='110' r='10'></circle>";
    a += "<circle id='6' transform='translate(85, 103.301270) rotate(-30) translate(-85, -103.301270) ' cx='85' cy='103.30127' r='10'></circle>";
    a += "<circle id='5' transform='translate(103.301270, 85) rotate(-60) translate(-103.301270, -85) ' cx='103.30127' cy='85' r='10'></circle>";
    a += "<circle id='4' transform='translate(110, 60) rotate(-90) translate(-110, -60) ' cx='110' cy='60' r='10'></circle>";
    a += "<circle id='3' transform='translate(103.301270, 35) rotate(-120) translate(-103.301270, -35) ' cx='103.30127' cy='35' r='10'></circle>";
    a += "<circle id='2' transform='translate(85, 16.698730) rotate(-150) translate(-85, -16.698730) ' cx='85' cy='16.6987298' r='10'></circle>";
    a += "<circle id='1' cx='60' cy='10' r='10'></circle></g></svg> </div>";
    if (emptyStr(b)) {
        $(".common_header").html(a)
    } else {
        $("." + b).html(a)
    }
}
function resetLoadingHtml(a) {
    if (emptyStr(a)) {
        $(".common_header").html("")
    } else {
        $("." + a).html("")
    }
}
function accAdd(g, d) {
    var c, b, a;
    try {
        c = g.toString().split(".")[1].length
    } catch(f) {
        c = 0
    }
    try {
        b = d.toString().split(".")[1].length
    } catch(f) {
        b = 0
    }
    a = Math.pow(10, Math.max(c, b));
    return Math.round(g * a + d * a) / a
}
function accSub(g, d) {
    var c, b, a;
    try {
        c = g.toString().split(".")[1].length
    } catch(f) {
        c = 0
    }
    try {
        b = d.toString().split(".")[1].length
    } catch(f) {
        b = 0
    }
    a = Math.pow(10, Math.max(c, b));
    n = (c >= b) ? c: b;
    return (Math.round(g * a - d * a) / a).toFixed(n)
}
function accDiv(h, f) {
    var d, c, b, a;
    try {
        d = h.toString().split(".")[1].length
    } catch(g) {
        d = 0
    }
    try {
        c = f.toString().split(".")[1].length
    } catch(g) {
        c = 0
    }
    b = Number(h.toString().replace(".", ""));
    a = Number(f.toString().replace(".", ""));
    return (b / a) * Math.pow(10, c - d)
}
function accMul(g, d) {
    var a = 0,
    c = g.toString(),
    b = d.toString();
    try {
        a += c.split(".")[1].length
    } catch(f) {}
    try {
        a += b.split(".")[1].length
    } catch(f) {}
    return Number(c.replace(".", "")) * Number(b.replace(".", "")) / Math.pow(10, a)
}
function renderIconColor(e) {
    var d = ["#7362b2", "#24acd8", "#46b9cb", "#66b969", "#f381c5", "#ff2d4a", "#ffa300", "#61d032"];
    var c = [["A", "B", "C"], ["D", "E", "F"], ["G", "H", "I"], ["J", "K", "L"], ["M", "N", "O"], ["P", "Q", "R"], ["S", "T", "U"], ["V", "W", "X", "Y", "Z"]];
    var f = 0;
    for (var b = 0; b < c.length; b++) {
        var a = c[b].toString();
        if (a.indexOf(e) > -1) {
            f = b;
            break
        }
    }
    return d[f]
}
function validUrl(a) {
    if (a.indexOf("http:") != -1 || a.indexOf("https:") != -1) {
        return a
    }
    return "http://" + a
}
function commonPagination(l, m, g, o, d) {
    if (emptyStr(l)) {
        l = 10
    }
    if (emptyStr(m)) {
        m = 0
    }
    if (emptyStr(g)) {
        g = 1
    }
    l = parseInt(l);
    m = parseInt(m);
    g = parseInt(g);
    var j = parseInt(m / l);
    if (m % l != 0) {
        j++
    }
    if (j > 1) {
        var c = g;
        var k = 5;
        if (c > j) {
            c = j
        } else {
            if (c < 1) {
                c = 1
            }
        }
        var b = "";
        b += "<li><a aria-label='Previous' href='" + d + "'><span aria-hidden='true'>首页</span></a></li>";
        var h = d.indexOf("?") == -1 ? "?": "&";
        if (c > 1) {
            b += "<li><a aria-label='Previous' href='" + d + "" + h + "page=" + (c - 1) + "'><span aria-hidden='true'>上一页</span></a></li>"
        } else {
            b += "<li><a aria-label='Previous' href='" + d + "'><span aria-hidden='true'>上一页</span></a></li>"
        }
        var a = c - parseInt(k / 2);
        if (a > j - k + 1) {
            a = j - k + 1
        }
        if (a <= 0) {
            a = 1
        }
        var p = a + k;
        if (p - 1 > j) {
            p = j + 1
        }
        for (var e = a; e < p; e++) {
            var f = "";
            if (e == c) {
                f = "active"
            }
            if (e == 1) {
                b += "<li class='" + f + "'><a href='" + d + "'>" + e + "</a></li>"
            } else {
                b += "<li class='" + f + "'><a href='" + d + "" + h + "page=" + e + "'>" + e + "</a></li>"
            }
        }
        b += "";
        if (c < j) {
            b += "<li><a aria-label='Next' href='" + d + "" + h + "page=" + (c + 1) + "'><span aria-hidden='true'>下一页</span></a></li>"
        } else {
            b += "<li><a aria-label='Next' href='" + d + "" + h + "page=" + j + "'><span aria-hidden='true'>下一页</span></a></li>"
        }
        b += "<li><a href='" + d + "" + h + "page=" + j + "'><span aria-hidden='true'>末页</span></a></li>";
        $("." + o).html(b)
    }
}
function showUpgradeMask(b, a) {
    if (window.ServicePay) {
        if (!emptyStr(b)) {
            window.ServicePay.loadServiceFunctions(b)
        }
        if (a) {
            window.ServicePay.init({
                eventId: a
            })
        }
    }
    $("#showUpgradeMaskId").show()
}
function windowOpen(b) {
    var a = window.open();
    setTimeout(function() {
        a.location = b
    },
    100);
    return false
}
function getDateDiff(a, e) {
    var c = new Date(Date.parse(a.replace(/-/g, "/"))).getTime();
    var b = new Date(Date.parse(e.replace(/-/g, "/"))).getTime();
    var d = Math.abs((c - b)) / (1000 * 60 * 60 * 24);
    return d
}
function intervalTime(a, b) {
    if (b == 0) {
        a.removeAttr("disabled");
        a.html("免费获取");
        a.removeAttr("style");
        a.attr("onclick", "getSmsRandomCode()");
        b = 60
    } else {
        a.attr("style", "cursor:no-drop; color:#9ea3a5; background:#f3f4f8;");
        a.attr("disabled", "disabled");
        a.attr("onclick", "");
        a.html(b + "秒");
        b--;
        setTimeout(function() {
            intervalTime(a, b)
        },
        1000)
    }
}
var unparseRequestHtml = function(a) {
    if (a && typeof a == "string") {
        a = a.replace(/&lt;/g, "<").replace(/&gt;/g, ">");
        a = a.replace(/&#40;/g, "(").replace(/&#41;/g, ")");
        a = a.replace(/&#39;/g, "'");
        a = a.replace(/&quot;/g, '"');
        a = a.replace(/&amp;/g, "&")
    }
    return a
};
function setOptionValue(b, e, c) {
    if (b == "radio") {
        $('input[name="' + e + '"][value="' + c + '"]').prop("checked", true)
    } else {
        if (b == "checkbox") {
            if (c == "") {
                return
            }
            var d = c.split(",");
            for (var a = 0; a < d.length; a++) {
                $('input[name="' + e + '"][value="' + d[a] + '"]').prop("checked", true)
            }
        } else {
            if (b == "select") {
                $("[name=" + e + "]").val(c)
            }
        }
    }
}
function reCycleColor(c) {
    var b = ["#EC5335", "#F7BC32", "#8AC14C", "#32AEDC", "#8B9AD3", "#D38BD1"];
    var a = b.length;
    if (c >= a) {
        c = c % a
    }
    return b[c]
}
function renderTicketColor(b, c) {
    var a = reCycleColor(b);
    $("#" + c + b).css("background", a)
}
function changeLanguage(a) {
    $.post("/language/changeShowLanguage.do", {
        index: a
    },
    function(b) {
        if (b.retStatus == 200) {
            window.location.href = window.location.href
        } else {
            alertWarningTips(b.resultObject)
        }
    },
    "json")
}
$(function() {
    if ($.support.msie) {
        $.ajaxSetup({
            cache: false
        })
    }
});
Date.prototype.formatDate = function(a) {
    var c = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        S: this.getMilliseconds()
    };
    if (/(y+)/.test(a)) {
        a = a.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length))
    }
    for (var b in c) {
        if (new RegExp("(" + b + ")").test(a)) {
            a = a.replace(RegExp.$1, (RegExp.$1.length == 1) ? (c[b]) : (("00" + c[b]).substr(("" + c[b]).length)))
        }
    }
    return a
};
function showMore(b) {
    $(b).attr("href", "javascript:void(0)");
    var a = $(b).parent().parent().find(".more");
    if (a.is(":hidden")) {
        a.show();
        $(b).text("收起>>")
    } else {
        a.hide();
        $(b).text("更多>>")
    }
}

$(function() {
    $("label").click(function() {
        $(".event_year>li").removeClass("current");
        $(this).parent("li").addClass("current");
        var a = $(this).attr("for");
        $("#" + a).parent().prevAll("div").slideUp(800);
        $("#" + a).parent().slideDown(800).nextAll("div").slideDown(800)
    })
});


$(function() {
	
	$(".Search input").focus(function(){
		this.defaultTxt = '找大会、找大咖、找视频';

		if($(this).val()==this.defaultTxt){
			$(this).val('');
			$(this).css({'color':'#333'});
		}
	}).blur(function(){
		if($(this).val()==''){
			$(this).css({'color':'#999'});
		}
	});
	// //搜索框
	// $(".Search").find('input[name="q"]').focus(function(){//
		// this.defaultTxt = '找大会、找大咖、找视频';
		// if($(this).val()==this.defaultTxt){
			// $(this).val('');
			// $(this).css({'color':'#333'});
		// };
		// $("#SearchTypeChange").show();
	// }).blur(function(){//
		// if($(this).val()==''||$(this).val()==this.defaultTxt){
			// $(this).css({'color':'#999'});
			// //$(this).val(this.defaultTxt)
		// }
	// }).bind('keyup',function(e){

		// var me = this;
		// var sTypeIn = SplitString(this.value,20);
		
		// var searchList = $('<ul id="SearchTypeChange"></ul>');
			// sListItem1 = $('<li class="SearchTypeChangeCur" m="meeting">搜 "<span class="blue">'+sTypeIn+'</span>" 相关会议</li>');
			// sListItem2 = $('<li m="people">搜 "<span class="blue">'+sTypeIn+'</span>" 相关大咖</li>');
			// sListItem3 = $('<li m="course">搜 "<span class="blue">'+sTypeIn+'</span>" 相关视频</li>');
			// searchList.append(sListItem1).append(sListItem2).append(sListItem3);
			// searchList.css({
				// 'position':'absolute',
				// 'left':0,
				// 'border':'1px solid rgba(0,0,0,0.12)',
				// 'border-top':'none',
				// 'background':'#FFF',
				// 'padding-top':'2px',
				// 'text-align':'left',
				// 'z-index':'99999',
				// 'top':'30px',
				// 'padding-left':'0px',
				// 'width':$(this).width()+52
			// });
		// if(e.keyCode == 38 ){
			// var I = $(".SearchTypeChangeCur").index();
			// if(I>0){
				// $(".SearchTypeChangeCur").removeClass('SearchTypeChangeCur').prev().addClass('SearchTypeChangeCur');
				// $('#_SearchType').val($(".SearchTypeChangeCur").attr('m'));
			// }
		// }else if(e.keyCode == 40){
			// var I = $(".SearchTypeChangeCur").index()
			// if(I<3){
				// $(".SearchTypeChangeCur").removeClass('SearchTypeChangeCur').next().addClass('SearchTypeChangeCur');
				// $('#_SearchType').val($(".SearchTypeChangeCur").attr('m'));
			// }
		// }else{
			// if($("#SearchTypeChange").length > 0){
				// $("#SearchTypeChange li span").text(sTypeIn);
			// }else{
				// $('.Search').append(searchList);
				// searchList.find('li').bind('mouseover',function(){
					// $(this).addClass('SearchTypeChangeCur').siblings().removeClass('SearchTypeChangeCur');
					// $('#_SearchType').val($(this).attr('m'));
				// }).bind('click',function(){

					// $('#q-form').submit();
				// });
				// searchList.bind('mouseleave',function(){
					// $(this).hide();
				// });

			// }
		// };

		// if(this.value.length == 0){
			// $("#SearchTypeChange").remove();
			// searchList.find('li').unbind()
		// }
	// }).bind('mouseenter',function(){
		// $("#SearchTypeChange").show();
	// });
})


function SplitString(value,len){
	var _tmp = '';
	var _length = 0;

	for (var i = 0; i < value.length; i++) {
		if (value.charCodeAt(i) > 255) {
			_length++;
			_length++;
		}else{
			_length++;
		}
		if(_length<len){
			_tmp+=value.charAt(i);
		}
	}
	if(_length>=len){
		_tmp+='..';
	}
	return _tmp;
}
function  search(){
        if(window.event.keyCode  ==13 ){
            if($('#q').val()==''){
                return false;
            }
        }

}
function seacher_submit () {
    if($('#q').val()==''){
        return false;
    }else {
        $('#q-form').submit();
    }

}

//获取当前时间
function getNowFormatDate() {
	var date = new Date();
	var seperator1 = "-";
	var seperator2 = ":";
	var month = date.getMonth() + 1;
	var strDate = date.getDate();
	var house = date.getHours();
	var Minutes = date.getMinutes();
	var Seconds = date.getSeconds();
	if (month >= 1 && month <= 9) {
		month = "0" + month;
	}
	if (strDate >= 0 && strDate <= 9) {
		strDate = "0" + strDate;
	}
	if (house >= 0 && house <= 9) {
		house = "0" + house;
	}
	if (Minutes >= 0 && Minutes <= 9) {
		Minutes = "0" + Minutes;
	}
	if (Seconds >= 0 && Seconds <= 9) {
		Seconds = "0" + Seconds;
	}
	var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
			+ " " + house + seperator2 + Minutes
			+ seperator2 + Seconds;
	return currentdate;
}