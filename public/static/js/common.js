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
        "M+": date.getMinutes().toString(),         // 分
        "S+": date.getSeconds().toString()          // 秒
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



const JUST_NOW = 3000; //3s内
const IN_SECOND = 1000 * 60; //一分钟
const IN_MINUTE = 1000 * 60 * 60; //一小时
const IN_HOUR = 1000 * 60 * 60 * 12; //12小时
const IN_DAY = 1000 * 60 * 60 * 24 * 1; //1天
const IN_MONTH = 1000 * 60 * 60 * 24 * 30; //1个月

function updateMsgCount(count) {
    if (count > 99) {
        return '99+'
    }
    return count;
}
function updateTimeShow(time) {
    var localTime = new Date(); //当前系统时间
    var createTime = new Date(time) //消息创建时间
    var diff = localTime - createTime;
    if (diff <= JUST_NOW)
        return '刚刚';
    else if (diff <= IN_SECOND)
        return "1分钟内";
    else if (diff <= IN_MINUTE)
        return parseInt(diff / IN_SECOND) + '分钟前';
    else if (diff <= IN_MINUTE)
        return parseInt(diff / IN_MINUTE) + '小时前';
    else if (diff <= IN_HOUR * 2) {
        const list = createTime.toString().split(" ");
        const lastIndex = list[4].lastIndexOf(":")
        const realtime = list[4].toString().substring(0, lastIndex);
        return realtime;
    } else if (diff < IN_DAY * 7) {
        if (diff < IN_DAY) {
            return parseInt(diff / IN_HOUR) + '天前';
        }
        const t = createTime.toString().slice(0, 3);
        switch (t) {
            case "Mon":
                return '星期一';
            case "Tue":
                return '星期二';
            case "Wed":
                return '星期三';
            case "Thu":
                return '星期四';
            case "Fri":
                return '星期五';
            case "Sat":
                return '星期六';
            case "Sun":
                return '星期日';
        }
    } else {
        return createTime.getFullYear() + "-" + (createTime.getMonth() + 1) + "-" + createTime.getDate();
    }
}
