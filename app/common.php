<?php
// 应用公共文件
/**
 * getCodeMsg 获取具体错误信息
 * @param $code
 * @return string
 */
function get_code_msg($code) {
    $msg = config('code.msg');
    return $msg[$code];
}

/**
 * generate_json 生成规范JSON
 * @param $code
 * @param null $msg null 自动获取code错误信息
 * @param array $data
 * @return \think\response\Json
 */
function generate_json($code,$msg = null,$data = []) {
    $json['code'] = $code;
    if (is_null($msg)) {
        $json['msg'] = get_code_msg($code);
    } else {
        $json['msg'] = $msg;
    }
    $json['data'] = $data;
    return json($json);
}