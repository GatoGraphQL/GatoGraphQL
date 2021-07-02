<?php
/* *
 * 支付宝接口公用函数
 * 详细：该类是请求、通知返回两个文件所调用的公用函数核心处理文件
 * 版本：3.2
 * 日期：2011-03-25
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

/**
 * 生成签名结果
 *
 * @param $sort_para 要签名的数组
 * @param $key 支付宝交易安全校验码
 * @param $sign_type 签名类型 默认值：MD5
 * return 签名结果字符串
 */
function buildMysign($sort_para, $key, $sign_type = "MD5")
{
    //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    $prestr = createLinkstring($sort_para);
    //把拼接后的字符串再与安全校验码直接连接起来
    $prestr = $prestr.$key;
    //把最终的字符串签名，获得签名结果
    $mysgin = sign($prestr, $sign_type);
    return $mysgin;
}
/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 *
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 */
function createLinkstring($para)
{
    $arg  = "";
    while (list($key, $val) = each($para)) {
        $arg.=$key."=".$val."&";
    }
    //去掉最后一个&字符
    $arg = substr($arg, 0, count($arg)-2);
    
    //如果存在转义字符，那么去掉转义
    if (get_magic_quotes_gpc()) {
        $arg = stripslashes($arg);
    }
    
    return $arg;
}

/**
 * 除去数组中的空值和签名参数
 *
 * @param $para 签名参数组
 * return 去掉空值与签名参数后的新签名参数组
 */
function paraFilter($para)
{
    $para_filter = array();
    while (list($key, $val) = each($para)) {
        if ($key == "sign" || $key == "sign_type" || $val == "") {
            continue;
        } else {
            $para_filter[$key] = $para[$key];
        }
    }
    return $para_filter;
}
/**
 * 对数组排序
 *
 * @param $para 排序前的数组
 * return 排序后的数组
 */
function argSort($para)
{
    ksort($para);
    reset($para);
    return $para;
}
/**
 * 签名字符串
 *
 * @param $prestr 需要签名的字符串
 * @param $sign_type 签名类型 默认值：MD5
 * return 签名结果
 */
function sign($prestr, $sign_type = 'MD5')
{
    $sign='';
    if ($sign_type == 'MD5') {
        $sign = md5($prestr);
    } elseif ($sign_type =='DSA') {
        //DSA 签名方法待后续开发
        die("DSA 签名方法待后续开发，请先使用MD5签名方式");
    } else {
        die("支付宝暂不支持".$sign_type."类型的签名方式");
    }
    return $sign;
}


/**
 * 远程获取数据
 * 注意：该函数的功能可以用curl来实现和代替。curl需自行编写。
 * $url 指定URL完整路径地址
 *
 * @param $input_charset 编码格式。默认值：空值
 * @param $time_out 超时时间。默认值：60
 * return 远程输出的数据
 */
function getHttpResponse($url, $input_charset = '', $time_out = "60")
{
    $urlarr     = parse_url($url);
    $errno      = "";
    $errstr     = "";
    $transports = "";
    $responseText = "";
    if ($urlarr["scheme"] == "https") {
        $transports = "ssl://";
        $urlarr["port"] = "443";
    } else {
        $transports = "tcp://";
        $urlarr["port"] = "80";
    }
    $fp=@fsockopen($transports . $urlarr['host'], $urlarr['port'], $errno, $errstr, $time_out);
    if (!$fp) {
        die("ERROR: $errno - $errstr<br />\n");
    } else {
        if (trim($input_charset) == '') {
            fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
        } else {
            fputs($fp, "POST ".$urlarr["path"].'?_input_charset='.$input_charset." HTTP/1.1\r\n");
        }
        fputs($fp, "Host: ".$urlarr["host"]."\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $urlarr["query"] . "\r\n\r\n");
        while (!feof($fp)) {
            $responseText .= @fgets($fp, 1024);
        }
        fclose($fp);
        $responseText = trim(stristr($responseText, "\r\n\r\n"), "\r\n");
        
        return $responseText;
    }
}
/**
 * 实现多种字符编码方式
 *
 * @param $input 需要编码的字符串
 * @param $_output_charset 输出的编码格式
 * @param $_input_charset 输入的编码格式
 * return 编码后的字符串
 */
function charsetEncode($input, $_output_charset, $_input_charset)
{
    $output = "";
    if (!isset($_output_charset)) {
        $_output_charset  = $_input_charset;
    }
    if ($_input_charset == $_output_charset || $input ==null) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
    } elseif (function_exists("iconv")) {
        $output = iconv($_input_charset, $_output_charset, $input);
    } else {
        die("sorry, you have no libs support for charset change.");
    }
    return $output;
}
/**
 * 实现多种字符解码方式
 *
 * @param $input 需要解码的字符串
 * @param $_output_charset 输出的解码格式
 * @param $_input_charset 输入的解码格式
 * return 解码后的字符串
 */
function charsetDecode($input, $_input_charset, $_output_charset)
{
    $output = "";
    if (!isset($_input_charset)) {
        $_input_charset  = $_input_charset ;
    }
    if ($_input_charset == $_output_charset || $input ==null) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input, $_output_charset, $_input_charset);
    } elseif (function_exists("iconv")) {
        $output = iconv($_input_charset, $_output_charset, $input);
    } else {
        die("sorry, you have no libs support for charset changes.");
    }
    return $output;
}
