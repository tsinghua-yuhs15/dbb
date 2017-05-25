var source = [];

function showRecord(time, voltage, capacity, event) {
    var a = document.createElement('span'); //1、创建元素
    var text = time + " " + voltage + "V" + " " + capacity + "%";
    a.innerHTML = "<div class='weui_cell_hd'><i class='weui_icon_success_no_circle'></i></div><div class='weui_cell_bd weui_cell_primary'><p>" + text + "</p></div>";
    a.setAttribute("class", "weui_cell");
    var container = document.getElementById('area'); //2、找到父级元素
    container.appendChild(a); //3、在末尾中添加元素
}

function getRecord() {
    if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {
        // IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == "forbidden") {
                window.location.href = "/reg/login.html";
                return;
            }
            else {
                source.length = 0;
                var jsonData = eval(xmlhttp.responseText);
                for (var i = 0; i < jsonData.length; i++) {
                    source[i] = [jsonData[i].i, jsonData[i].t
                                        , jsonData[i].v
                                        , jsonData[i].e
                                        , jsonData[i].c];
                }
                for (var i = 0; i < jsonData.length; i++) {
                    var time = source[i]['1'];
                    var voltage = source[i]['2'];
                    var event = source[i]['3'];
                    var capacity = source[i]['4'];
                    showRecord(time, voltage, capacity, event);
                }
            };
        }
    }
    xmlhttp.open("GET", "history_detail.php");
    xmlhttp.send();
}
getRecord();