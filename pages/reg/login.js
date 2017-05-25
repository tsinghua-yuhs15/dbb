function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function deleteCookie() {
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        //成功识别cookies
    } else {
        //未成功识别cookies则重新进入登陆界面
        alert("自动登录失败，请重新输入账号密码");
        if (user != "" && user != null) {
            setCookie("username", user, 30);
        }
    }
}