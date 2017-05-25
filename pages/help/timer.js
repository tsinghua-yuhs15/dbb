var timerID = null;
var timerRunning = false;

function stopclock() {
    if (timerRunning)
        clearTimeout(timerID);
    timerRunning = false;
}

function startclock() {
    stopclock();
    showtime();
}

function showtime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds()
    var timeValue = "" + hours;
    //定时初始化数据库的代码：   
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds
    document.Calc.time.value = timeValue;
    // you could replace the above with this
    // and have a clock on the status bar:
    // window.status = timeValue;
    timerID = setTimeout("showtime()", 1000);
    timerRunning = true;
    return '';
}