function sorry() {
    alert("概念性功能，尚在建设中，我们会尽快补充.");
    return false;
}

function get_username() {
    if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行
        xmlhttp = new XMLHttpRequest();
    }
    else { // IE6, IE5 浏览器执行
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText != "guest") {
                document.getElementById("username").innerHTML = xmlhttp.responseText;
            }
        }
    }
    xmlhttp.open("GET", "/reg/login.php?action=getusername", true);
    xmlhttp.send();
}