/**
 * Created by tinywan on 2017/11/16.
 */

var Cookie = {
    set: function (key, val, expiresDays) {
        // 判断是否设置expiresDays
        if (expiresDays) {
            var date = new Date();
            date.setTime(date.getTime() + expiresDays * 24 * 3600 * 1000); // 格式化时间
            var expiresStr = 'expires=' + date.toGMTString() + ';';
        } else {
            var expiresStr = "";
        }
        //escape() 函数可对字符串进行编码，这样就可以在所有的计算机上读取该字符串。注释：ECMAScript v3 反对使用该方法，应用使用 decodeURI() 和 decodeURIComponent() 替代它。
        document.cookie = key + '=' + encodeURI(val) + ';' + expiresStr;
    },
    get: function (key) {
        // "Apple888=4278; autoLogin=1; auth=1; auth-root=1; age=26; set-age=26; set-age-expire=26; set-age-expire=26; set-name-https=Tinywan; UserTable[Name]=Tinywan; UserTable[Age]=24;"
        // decodeURI() 函数可对 encodeURI() 函数编码过的 URI 进行解码。
        var cookieStr = document.cookie.replace(/[]/g, ''); //空格全局替换为空
        // 把字符串拆分为一个数组
        var cookieArr = cookieStr.split(';');
        var res;
        for (i = 0, len = cookieArr.length; i < len; i++) {
            console.log(cookieArr[i]); // age=26
            var arr = cookieArr[i].split('=');
            if (arr[0] == key) {
                res = arr[1];
                break;
            }
        }
        return decodeURI(res);
    }
};