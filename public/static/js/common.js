/**
 * 格式化时间
 * @param fmt
 * @param date
 * @returns {*}
 */
function dateFormat(fmt, date) {
    var ret;
    const opt = {
        "Y+": date.getFullYear().toString(),        // 年
        "m+": (date.getMonth() + 1).toString(),     // 月
        "d+": date.getDate().toString(),            // 日
        "H+": date.getHours().toString(),           // 时
        "i+": date.getMinutes().toString(),         // 分
        "s+": date.getSeconds().toString()
        // 有其他格式化字符需求可以继续添加，必须转化成字符串
    };
    for (var k in opt) {
        ret = new RegExp("(" + k + ")").exec(fmt);
        if (ret) {
            fmt = fmt.replace(ret[1], (ret[1].length == 1) ? (opt[k]) : (opt[k].padStart(ret[1].length, "0")))
        }
    }

    return fmt;
}


/**
 * UpdateUrlParam 修改URL参数
 * @param thisURL
 * @param name
 * @param val
 * @returns {string}
 */
function updateUrlParam(thisURL, name, val) {
    // 如果 url中包含这个参数 则修改
    if (thisURL.indexOf(name + '=') > 0) {
        var v = getUrlParam(name);
        if (v != null) {
            // 是否包含参数
            thisURL = thisURL.replace(name + '=' + v, name + '=' + val);
        } else {
            thisURL = thisURL.replace(name + '=', name + '=' + val);
        }

    } // 不包含这个参数 则添加
    else {
        if (thisURL.indexOf("?") > 0) {
            thisURL = thisURL + "&" + name + "=" + val;
        } else {
            thisURL = thisURL + "?" + name + "=" + val;
        }
    }
    return thisURL;
}

/**
 * getUrlParam 获取URL参数
 * @param name
 * @returns {string|null}
 */
function getUrlParam(name) {//封装方法
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg); //匹配目标参数
    if (r != null) return unescape(r[2]);
    return null; //返回参数值
}


//获取QQ消息列表样式时间
const IN_DAY = 1000 * 60 * 60 * 24; //1天
var weeks = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];
function getDiffTimeStr(time,isCompleteTime) {
    var localTime = new Date(); //当前系统时间
    var createTime = new Date(time) //消息创建时间
    var diff = localTime.getTime() - createTime.getTime();
    var timeStr = '';
    if (diff <= IN_DAY) {
        timeStr = getTwoTime(createTime.getHours()) + ':' + getTwoTime(createTime.getMinutes());
    } else if (diff <= IN_DAY * 2) {
        timeStr = '昨天';
    } else if (diff <= IN_DAY * 7) {
        timeStr = weeks[createTime.getDay()];
    } else if(localTime.getFullYear() - createTime.getFullYear() < 1) {
        timeStr = getTwoTime(createTime.getMonth() + 1) + "-" + getTwoTime(createTime.getDate());
    } else {
        timeStr = createTime.getFullYear() + "-" + getTwoTime(createTime.getMonth() + 1) + "-" + getTwoTime(createTime.getDate());
    }
    if (diff > IN_DAY && isCompleteTime) {
        return timeStr + ' ' + getTwoTime(createTime.getHours()) + ':' + getTwoTime(createTime.getMinutes());
    } else {
        return timeStr;
    }
}
//把一位数的时间填充0
function getTwoTime(num) {
    if (num.toString().length < 2) {
        return '0' + num;
    }
    return num;
}


//消息大于99显示99+
function updateMsgCount(count) {
    if (count > 99) {
        return '99+'
    }
    return count;
}

//获取时间差 (分钟)
function timeDifference(time1,time2) {
    var time1Date = new Date(time1);
    var time2Date = new Date(time2);
    var diffTime = Math.abs(time1Date.getTime() - time2Date.getTime());
    return parseInt(diffTime / 1000 / 60);
}

//根据数组内ID返回数组
function getArrayValueById(arr,id) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i].id === id) {
            return arr[i];
        }
    }
}

//生成UUID
function createUuid() {
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4"; // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    return s.join("");
}
