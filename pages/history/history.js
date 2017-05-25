 var data = new Array();
 data[0] = new Array();
 var time = null;
 var capacity = null;
 var index = null;
 var Time = new Array();
 var Capacity = new Array();
 var x = new Array();
 Time.push(time);
 Capacity.push(capacity);
 var current_time = new Date();
 var year = current_time.getFullYear();
 var month = current_time.getMonth() + 1;
 var source = []; //收到的图表数据-Array
 function update_chart() {
     var line_title = ["history"]; //曲线名称
     var title = year + "年" + month + "月充电记录"; //统计图标标题
     var x_label = "日\n期"; //X轴标题
     var y_label = "容\n量"; //Y轴标题
     var data_max = Math.max.apply(null, data[0]) + 5; //Y轴最大刻度
     var data_min = Math.min.apply(null, data[0]) - 5;
     document.getElementById("chart1").innerHTML = "";
     j.jqplot.diagram.base("chart1", data, line_title, title, x, x_label, y_label, data_max, data_min, 1);
 }

 function update_chart_data() {
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
                         source[i] = [jsonData[i].i, jsonData[i].d, jsonData[i].c];
                     }
                     data[0].length = 0;
                     x.length = 0;
                     for (var i = 0; i < jsonData.length; i++) {
                         data[0][i] = source[i]["2"];
                         x[i] = source[i]["1"];
                     }
                     if (data[0].length == 0) {
                         document.getElementById("chart1").innerHTML = "<p class='title-Secondary'>" + year + "年" + month + "月暂无数据</p>";
                     }
                     else {
                         update_chart()
                     }
                 };
             }
         }
         //alert("history.php?y=" + year + "m=" + month);
     xmlhttp.open("GET", "history.php?y=" + year + "&m=" + month);
     xmlhttp.send();
 }

 function next_month() {
     if (month == 12) {
         year++;
         month = 1;
     }
     else {
         month++;
     }
     update_chart_data();
 }

 function prev_month() {
     if (month == 1) {
         year--;
         month = 12;
     }
     else {
         month--;
     }
     update_chart_data();
 }

 function current_month() {
     var mydate = new Date();
     year = mydate.getFullYear();
     month = mydate.getMonth() + 1;
     update_chart_data();
 }