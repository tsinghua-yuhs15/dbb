var source;

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
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

function cookies_stuff() {
    if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == "forbidden") {
                    window.location.href = "/reg/login.html";
                    return;
                } else {
                    source = null;
                    var jsonData = eval(xmlhttp.responseText);
                    source = jsonData[0].k;
                    setCookie(user_cookie, source, 3);
                };
            }
        }
        //alert("history.php?y=" + year + "m=" + month);
    xmlhttp.open("GET", "history.php?y=" + year + "&m=" + month);
    xmlhttp.send();
}