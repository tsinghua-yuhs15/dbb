var exist_err = true;

function check_field(kind, value) {
    if (kind == 'name') {
        if (value.length == 0) {
            document.getElementById("err_name").innerHTML = "昵称不可为空";
            exist_err = true;
        }
        else if (value.length > 10) {
            document.getElementById("err_name").innerHTML = "昵称应为1~10位";
            exist_err = true;
        }
        else {
            live_check_OK('err_name', kind, value, '昵称');
        }
    }
    if (kind == 'pwd') {
        if (value.length < 6 || value.length > 20) {
            document.getElementById("err_password").innerHTML = "密码长度应为6~20位<br/>支持英文、数字和特殊符号";
            exist_err = true;
        }
        else {
            document.getElementById("err_password").innerHTML = "";
            exist_err = false;
        }
    }
    if (kind == 'repass' || kind == 'pwd') {
        if (value != document.getElementById("password").value) {
            document.getElementById("err_repass").innerHTML = "两次输入的密码不一致";
            exist_err = true;
        }
        else {
            document.getElementById("err_repass").innerHTML = "";
            exist_err = false;
        }
    }
    if (kind == 'tel') {
        if (!(/^0?\d{11,11}$/.test(value))) {
            document.getElementById("err_tel").innerHTML = "手机号格式输入错误";
            exist_err = true;
        }
        else live_check_OK('err_tel', kind, value, '手机号');
    }
    if (kind == 'email') {
        if (value.length > 0) {
            if (!(/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/.test(value))) {
                document.getElementById("err_email").innerHTML = "邮箱输入错误";
                exist_err = true;
            }
            else live_check_OK('err_email', kind, value, '电子邮箱');
        }
    }
}

function live_check_OK(element_id, kind, value, hint) {
    if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行
        xmlhttp = new XMLHttpRequest();
    }
    else { // IE6, IE5 浏览器执行
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == 'OK') {
                document.getElementById(element_id).innerHTML = '可以使用';
                exist_err = false;
            }
            else {
                document.getElementById(element_id).innerHTML = '该' + hint + '已存在';
                exist_err = true;
            }
        }
    }
    xmlhttp.open("GET", "reg.php?action=live_check&kind=" + kind + "&q=" + value, true);
    xmlhttp.send();
}

function InputCheck(RegForm) {
    if (exist_err) {
        alert("表单尚存在错误，请检查后提交!");
        return false;
    }
}