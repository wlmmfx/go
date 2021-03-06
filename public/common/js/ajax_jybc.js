/*
 * Ajax_JYBC   V1.0.01
 * Last Update for 任向阳  on 2017/11/23
 */


/**
 * 参数说明:
 * 1.url(必填)
 * 2.config(选填,各种参数的配置)
 *        2.1.type:默认post
 *        2.2.async:默认true
 *        2.3.cache:默认false
 *        2.4.dataType:默认json
 *        2.5.contentType:默认utf8
 *        2.6.data:默认无参数,此处一般需要使用者手动添加
 *        2.7.tipType:默认:'console',控制台提示;'layer',layer提示;其他方式看需求增加,仅在函数值无效时有效
 * 3.func_suc(选填):即ajax中的success
 * 4.func_error(选填):即ajax中的error
 * 5.func_comp(选填):即ajax中的complete
 * 其他说明
 * 1.防止重复提交(自动启用,但在同步加载模式下看不出效果);
 * 2.提示模式,参考tipType;
 */
function ajaxBC(url, config, func_suc, func_error, func_comp) {
    //1.request url repeat judge
    if (!window.lstAjaxUrl) {
        window.lstAjaxUrl = {};
    }
    if (window.lstAjaxUrl[url]) {
        console.log('ajax (url:' + url + ') submit repeat!');//重复提示在控制台
        return;
    } else {
        window.lstAjaxUrl[url] = true;
    }
    //2.init necessary param
    config = config || {};
    config.type = config.type || 'post';//默认为type=get
    config.async = config.async || true;//默认为异步加载
    config.cache = config.cache || false;//默认禁用缓存
    config.dataType = config.dataType || 'json';
    config.contentType = config.contentType || 'application/x-www-form-urlencoded; charset=utf-8';
    config.data = config.data || {};

    config.tipType = config.tipType || 'console';//默认:'console',控制台提示;'layer',layer提示;其他方式看需求增加,仅在函数值无效时有效

    var func_send;//before send,not in param

    if (config.tipType === 'console') {
        func_send = function () {
        };
        func_suc = func_suc || function (data) {
                console.log(data);
            };
        func_error = func_error || function () {
                console.log('error');
            };
        func_comp = func_comp || function () {
            };
    } else if (config.tipType === 'layer') {//需要layer支持
        if (!window.lstLayerLoad) {
            window.lstLayerLoad = {};
        }
        func_send = function () {
            window.lstLayerLoad[url] = layer.msg('加载中,请稍后...', {time: 0});//layer.load(0, {shade: false,time:0}); //0代表加载的风格，支持0-2
        };
        var func_suc_init = func_suc || function (data) {
                if (data.success == 1) {
                    layer.msg(data.message, {icon: 1});
                } else {
                    layer.msg(data.message, {icon: 2});
                }
            };
        func_suc = function (data) {
            layer.close(window.lstLayerLoad[url]);
            func_suc_init(data);
        };
        var func_error_init = func_error || function () {
                layer.msg('网络错误,请稍后重试!', {icon: 2});
            };
        func_error = function () {
            layer.close(window.lstLayerLoad[url]);
            func_error_init();
        };
        func_comp = func_comp || function () {
            };
    } else {
        console.log('nonsupport tipType.');
        return;
    }
    //3.get param
    $.ajax({
        url: url,
        type: config.type,
        async: config.async,
        cache: config.cache,
        dataType: config.dataType,
        contentType: config.contentType,
        data: config.data,
        beforeSend: function () {
            func_send();
        },
        success: function (data) {
            if (!window.lstAjaxResult) {
                window.lstAjaxResult = {};
            }
            window.lstAjaxResult[url] = data;//封装结果
            func_suc(data);
        },
        error: function () {
            func_error();
        },
        complete: function (data) {
            func_comp();
            window.lstAjaxUrl[url] = false;//释放url锁
        }
    });
}

/**
 * 常用的POST请求封装
 * create by 任向阳  on 2017/11/23
 * 参数说明:
 * 1.url(必填)
 * 2.data(选填,向后台传送的数据，是个数组，如：{id:1})
 * 3.func_suc(选填):即ajax中的success
 * 4.func_error(选填):即ajax中的error
 * 5.func_comp(选填):即ajax中的complete
 * 其他说明
 * 1.防止重复提交(自动启用,但在同步加载模式下看不出效果);
 * 2.提示模式,参考tipType;
 */
function ajaxBC_post(url, data, func_suc, func_error, func_comp) {
    //1.request url repeat judge
    if (!window.lstAjaxUrl) {
        window.lstAjaxUrl = {};
    }
    if (window.lstAjaxUrl[url]) {
        console.log('ajax (url:' + url + ') submit repeat!');//重复提示在控制台
        return;
    } else {
        window.lstAjaxUrl[url] = true;
    }
    //2.init necessary param
    var config = config || {};
    config.type = 'post';//默认为type=get
    config.async = true;//默认为异步加载
    config.cache = false;//默认禁用缓存
    config.dataType = 'json';
    config.contentType = 'application/x-www-form-urlencoded; charset=utf-8';
    config.data = data || {};

    config.tipType = config.tipType || 'console';//默认:'console',控制台提示;'layer',layer提示;其他方式看需求增加,仅在函数值无效时有效

    var func_send;//before send,not in param

    if (config.tipType === 'console') {
        func_send = function () {
        };
        func_suc = func_suc || function (data) {
                console.log(data);
            };
        func_error = func_error || function () {
                console.log('error');
            };
        func_comp = func_comp || function () {
            };
    } else if (config.tipType === 'layer') {//需要layer支持
        if (!window.lstLayerLoad) {
            window.lstLayerLoad = {};
        }
        func_send = function () {
            window.lstLayerLoad[url] = layer.msg('加载中,请稍后...', {time: 0});//layer.load(0, {shade: false,time:0}); //0代表加载的风格，支持0-2
        };
        var func_suc_init = func_suc || function (data) {
                if (data.success == 1) {
                    layer.msg(data.message, {icon: 1});
                } else {
                    layer.msg(data.message, {icon: 2});
                }
            };
        func_suc = function (data) {
            layer.close(window.lstLayerLoad[url]);
            func_suc_init(data);
        };
        var func_error_init = func_error || function () {
                layer.msg('请求失败,请稍后重试!', {icon: 2});
            };
        func_error = function () {
            layer.close(window.lstLayerLoad[url]);
            func_error_init();
        };
        func_comp = func_comp || function () {
            };
    } else {
        console.log('nonsupport tipType.');
        return;
    }
    //3.get param
    $.ajax({
        url: url,
        type: config.type,
        async: config.async,
        cache: config.cache,
        dataType: config.dataType,
        contentType: config.contentType,
        data: config.data,
        beforeSend: function () {
            func_send();
        },
        success: function (data) {
            func_suc(data);
        },
        error: function () {
            func_error();
        },
        complete: function () {
            func_comp();
            window.lstAjaxUrl[url] = false;//释放url锁
        }
    });
}