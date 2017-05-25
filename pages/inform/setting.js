$(function () {
    var mask = $('#mask');
    var weuiActionsheet = $('#weui_actionsheet');
    var flag;
    $('#monit_info').click(function () {
        var obj = document.getElementById("monit_info");

        if (obj.checked == true) {
            flag = 0;
            weuiActionsheet.addClass('weui_actionsheet_toggle');
            mask.show().addClass('weui_fade_toggle').click(function () {
                hideActionSheet(weuiActionsheet, mask);
            });
            $('#actionsheet_cancel').click(function () {
                hideActionSheet(weuiActionsheet, mask);
            });
            weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');

            function hideActionSheet(weuiActionsheet, mask) {
                if (flag == 0) {
                    obj.checked = false;
                }
                weuiActionsheet.removeClass('weui_actionsheet_toggle');
                mask.removeClass('weui_fade_toggle');
                weuiActionsheet.on('transitionend', function () {
                    mask.hide();
                }).on('webkitTransitionEnd',
                    function () {
                        mask.hide();
                    })
            }
        } else {
            document.getElementById("inter").innerHTML = "间隔时间";
        }
    });
    $('.weui_actionsheet_menu .weui_actionsheet_cell').click(function () {
        flag = 1;
        if ($(this).text() == "自定义") {
            var inter = prompt("请输入间隔时间（小时）:", "3h");
        } else {
            var inter = $(this).text();
        }
        $('#actionsheet_cancel').click();
        document.getElementById("inter").innerHTML += inter;
    })
});
var source = [];

function getSettingInfo() {

    var sys = document.getElementById("sys_info").checked;
    var monit = document.getElementById("monit_info").checked;
    var monit_time = document.getElementById("inter").innerHTML;

    var intr = document.getElementById("intr_info").checked;
    var inf_method;
    if (document.getElementById("x11").checked) {
        inf_method = 1;
    } else if (document.getElementById("x12").checked) {
        inf_method = 2;
    } else if (document.getElementById("x13").checked) {
        inf_method = 3;
    }
    var setting_submit_info = "" + sys + "." + monit + "." + monit_time + "." + intr + "." + inf_method;
    document.getElementById("inter1").value = setting_submit_info;
}

function update_setting_panel() {

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
                    var response_obj = JSON.parse(xmlhttp.responseText);
                    //alert(xmlhttp.responseText);
                    if (response_obj.s == "1") {
                        document.getElementById("sys_info").checked = true;
                    } else {
                        document.getElementById("sys_info").checked = false;
                    }
                    if (response_obj.mf == '1') {
                        document.getElementById("monit_info").checked = true;
                        document.getElementById("inter").innerHTML = "间隔时间" + response_obj.mt;
                    } else {
                        document.getElementById("monit_info").checked = false;
                        document.getElementById("inter").innerHTML = "间隔时间";
                    }
                    if (response_obj.ii == "1") {
                        document.getElementById("intr_info").checked = true;
                    } else {
                        document.getElementById("intr_info").checked = false;
                    }
                    if (response_obj.im == "1") {
                        document.getElementById("x11").checked = true;
                    } else if (response_obj.im == "2") {
                        document.getElementById("x12").checked = true;
                    } else if (response_obj.im == "3") {
                        document.getElementById("x13").checked = true;
                    }
                };
            }
        }
        //alert("history.php?y=" + year + "m=" + month);
    xmlhttp.open("GET", "loading.php");
    xmlhttp.send();
}