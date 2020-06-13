/**
 * UpdateUrlParam 修改URL参数
 * @param name
 * @param val
 * @constructor
 */
function updateUrlParam(name, val) {
    var thisURL = document.location.href;
    // 如果 url中包含这个参数 则修改
    if (thisURL.indexOf(name+'=') > 0) {
        var v = getUrlParam(name);
        if (v != null) {
            // 是否包含参数
            thisURL = thisURL.replace(name + '=' + v, name + '=' + val);
        }
        else {
            thisURL = thisURL.replace(name + '=', name + '=' + val);
        }

    } // 不包含这个参数 则添加
    else {
        if (thisURL.indexOf("?") > 0) {
            thisURL = thisURL + "&" + name + "=" + val;
        }
        else {
            thisURL = thisURL + "?" + name + "=" + val;
        }
    }
    location.href = thisURL;
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