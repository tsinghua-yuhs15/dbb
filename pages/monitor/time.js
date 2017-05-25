//更新相关
var last_response_time = null
    , last_report_time = null
    , user_last_id = 0
    , interval = 5000
    , t = null
    , qu_t = null
    , exist_abnormal = false
    , response_obj = null
    , state = 1;
qu_t = setTimeout('refresh_time()', 1000);
//绘图相关
//四个临时数据
var V1, A1, W1, Wh1, sec = 0
    , i = 0;
var data = new Array();
data[0] = new Array();
data[1] = new Array();
data[2] = new Array();
var time = null;
var voltage = null;
var index = null;
var Time = new Array();
var Voltage = new Array();
Time.push(time);
Voltage.push(voltage);
var current_time = new Date();
var year = current_time.getFullYear();
var month = current_time.getMonth() + 1;
var source = []; //收到的图表数据-Array
function update_chart() {
    var line_title = ["Monitor"]; //曲线名称
    var title = sec + " 秒前充电参数展示"; //统计图标标题
    var x_label = "秒"; //X轴标题
    var y_label = "参\n数"; //Y轴标题
    var data_max = 60; //Y轴最大刻度
    //var data_max = Math.max.apply(null, data[0][1]) + 5; //Y轴最大刻度
    var data_min = 0;
    var obj = document.getElementById("during");
    var index = obj.selectedIndex;
    var x_during = parseInt(obj.options[index].value);
    //var data_min = Math.min.apply(null, data[0]) - 5;
    document.getElementById("chart1").innerHTML = "";
    //j.jqplot.diagram.base("chart1", data, line_title, title, x, x_label, y_label, data_max, data_min, 1);
    if (data[0].length >= x_during) {
        data[0].pop();
        data[1].pop();
        data[1].pop();
        data[2].pop();
        data[2].pop();
    }
    jQuery.jqplot('chart1', data, {
        title: '充电参数曲线'
        , axes: {
            xaxis: {
                min: sec - x_during < 0 ? -10 : sec - x_during - 10
                , max: sec < x_during ? x_during + 5 : sec + 5
                , tickInterval: x_during / 10
            }
            , yaxis: {
                min: data_min
                , max: data_max
                , tickInterval: 4.0
            }
        }
        , seriesDefaults: {
            showMarker: true
        }
        , grid: {
            borderWidth: 2.0, //设置图表的(最外侧)边框宽度
            shadow: true, //为整个图标（最外侧）边框设置阴影，以突出其立体效果
        }
        , markerOptions: {
            lineWidth: 100
            , size: 0
            , show: false
        }
    }); //target：要显示的位置。data：显示的数据。options：其它配置
}

function update_sucess() {
    last_response_time = new Date();
    document.getElementById("update-time").innerHTML = last_report_time.Format("yyyy.MM.dd hh:mm:ss");
    data[0].push([sec, V1]);
    data[1].push([sec, A1 * 15]);
    data[2].push([sec, W1 / 2.5]);
    i++;
    update_chart();
}

function refresh_time() {
    var current_time = new Date();
    document.getElementById("update-time-desc").innerHTML = getTimediff(last_report_time, current_time) + "ago";
    if (state == 2) {
        document.getElementById("time-diff").innerHTML = "刷新于 " + getTimediff(last_response_time, current_time) + "前";
    }
    setTimeout('refresh_time()', 1000);
    sec++;
}

function refresh_data() {
    if (window.XMLHttpRequest) { // IE7+, Firefox, Chrome, Opera, Safari 浏览器执行
        xmlhttp = new XMLHttpRequest();
    }
    else { // IE6, IE5 浏览器执行
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == "") {
                return;
            }
            state = 2; //Response received.
            last_response_time = new Date();
            if (xmlhttp.responseText == "nope") return;
            if (xmlhttp.responseText == "you have no charging battery") {
                document.getElementById("Power-info").innerHTML = "您目前没有正在充电的电池~";
                stop_update();
                return;
            }
            if (xmlhttp.responseText == "forbidden") {
                window.location.href = "/reg/login.html";
                return;
            }
            else {
                response_obj = JSON.parse(xmlhttp.responseText);
                user_last_id = response_obj.id;
                var last_report_time_str = response_obj.time;
                last_report_time = new Date(last_report_time_str.replace(/-/g, "/"));
                exist_abnormal = response_obj.abnormal;
                V1 = response_obj.V1;
                A1 = response_obj.A1;
                W1 = response_obj.W1;
                Wh1 = response_obj.Wh1;
                document.getElementById("Power-info").innerHTML = response_obj.table;
                if (exist_abnormal != "0") {
                    document.getElementById("abnormal_txt").style.visibility = "visible";
                    document.getElementById("abnormal_img").style.visibility = "visible";
                }
                else {
                    document.getElementById("abnormal_txt").style.visibility = "hidden";
                    document.getElementById("abnormal_img").style.visibility = "hidden";
                }
                update_sucess();
            }
        }
    }
    xmlhttp.open("GET", "live_refresh_all.php?id=" + user_last_id, true);
    state = 1; //Wait for response..
    document.getElementById("time-diff").innerHTML = "请求数据中...";
    xmlhttp.send();
}

function change_interval() {
    var obj = document.getElementById("interval");
    var index = obj.selectedIndex;
    interval = obj.options[index].value;
    query_update();
};

function query_update() {
    refresh_data();
    clearTimeout(t);
    t = setTimeout('query_update()', interval);
}

function stop_update() {
    clearTimeout(t);
};
/*
--author: meizz
 对Date的扩展，将 Date 转化为指定格式的String
 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
 例子： 
 (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
 (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
 */
Date.prototype.Format = function (fmt) {
        var o = {
            "M+": this.getMonth() + 1, //月份 
            "d+": this.getDate(), //日 
            "h+": this.getHours(), //小时 
            "m+": this.getMinutes(), //分 
            "s+": this.getSeconds(), //秒 
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
            "S": this.getMilliseconds() //毫秒 
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
            if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }
    /*
                调用：
                var time1 = new Date().Format("yyyy-MM-dd");
                var time2 = new Date().Format("yyyy-MM-dd hh:mm:ss");
    
                */
function getTimediff(date1, date2) {
    var date3 = date2.getTime() - date1.getTime(); //时间差的毫秒数  
    //计算出相差天数
    var days = Math.floor(date3 / (24 * 3600 * 1000));
    //计算出小时数  
    var leave1 = date3 % (24 * 3600 * 1000); //计算天数后剩余的毫秒数  
    var hours = Math.floor(leave1 / (3600 * 1000));
    //计算相差分钟数  
    var leave2 = leave1 % (3600 * 1000); //计算小时数后剩余的毫秒数  
    var minutes = Math.floor(leave2 / (60 * 1000));
    //计算相差秒数  
    var leave3 = leave2 % (60 * 1000); //计算分钟数后剩余的毫秒数
    var seconds = Math.round(leave3 / 1000);
    //alert((days > 0 ? days + "天" : "") + (hours > 0 ? hours + "小时" : "") + ((minutes > 0) ? (minutes + "分钟") : ("")) + seconds + " 秒");
    return (days > 0 ? days + "Day " : "") + (hours > 0 ? hours + "Hour " : "") + ((minutes > 0) ? (minutes + "Min ") : ("")) + (seconds + "s ");
}