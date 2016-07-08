<?php
$session_dir = C("SESSION_OPTIONS");
if (!is_dir($session_dir["path"]) && $session_dir["path"] != "") {
    createFolder($session_dir["path"]);
}

function checkSafeIP() {
    $ip_safe = array(
        "127.0.0.1", "39.65.190.*", "113.124.8.*"
    );
    $safe = FALSE;
    $come_ip = get_client_ip();
    foreach ($ip_safe as $ip_code) {
        $ip_code = str_replace("*", "", $ip_code);
        $res_code = strripos($come_ip, $ip_code);
        if ($res_code == 0) {
            $safe = TRUE;
        };
    }
    return $safe;
}

function findSafeIP() {
    $safe = FALSE;
    $come_ip = get_client_ip();
    if ($come_ip == "127.0.0.1") {
        $safe = TRUE;
    } else {
        $str = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $come_ip;
        $json = json_decode(file_get_contents($str), TRUE);
        if (is_array($json)) {
            if ($json["data"]["city_id"] == "370200" || $json["data"]["city_id"] == "370100") {
                $safe = TRUE;
            }
        } else {
            $safe = FALSE;
        }
    }
    return $safe;
}

function SafeIP() {
    $come_ip = get_client_ip();
    $str = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $come_ip;
    $json = json_decode(file_get_contents($str), TRUE);
    return $json["data"]["city_id"];
}

//解决不同服务器HTML转义的BUG
if (get_magic_quotes_gpc()) {

    function stripslashes_deep($value) {
        $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

function CC($word) {
    $readfile = APP_ROOT . "/App/Common/setting.php";

    if (is_file($readfile)) {
        require ($readfile);
        return $sysrow[$word];
    } else {
        freshsetting();
        require ($readfile);
        return $sysrow[$word];
    }
}

function freshsetting() {
    $readfile = APP_ROOT . "/App/Common/setting.php";
    $sysconfig = M("sysconfig")->select();
    $sysrow = array();
    foreach ($sysconfig as $sys) {
        $sysrow[$sys['varname']] = $sys['value'];
    }
    $text = "";
    $text .= '<?php' . "\r\n";
    $text .= '$sysrow = array();' . "\r\n";
    foreach ($sysrow as $sk => $sv) {
        $text .= '$sysrow["' . $sk . '"] = "' . $sv . '";' . "\r\n";
    }
    $text .= '?>' . "\r\n";
    writeinFile($readfile, $text);
}

function createFolder($path) {
    if (!file_exists($path)) {
        createFolder(dirname($path));
        mkdir($path, 0777);
    }
}

/**
 * 复制文件夹
 * @param type $src
 * @param type $dst
 */
function copyFolder($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if (is_dir($src . '/' . $file)) {
                copyFolder($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    } closedir($dir);
}

/**
 * 删除目录
 *
 * @param unknown_type $indir
 */
function RemoveDirFiles($indir) {
    if (!is_dir($indir)) {
        return;
    }
    $dh = dir($indir);
    while ($filename = $dh->read()) {
        if ($filename == "." || $filename == "..") {
            continue;
        } else if (is_file("$indir/$filename")) {
            @unlink("$indir/$filename");
        } else {
            RemoveDirFiles("$indir/$filename");
        }
    }
    $dh->close();
    @rmdir($indir);
}

//select函数
function CteatSelect($n, $arr) {
    $r = "";
    foreach ($arr as $h => $v) {
        $k = explode("|", $v);
        if (($n != "") && $n == $k[0]) {
            $r .= "<option value=\"{$k[0]}\" selected>{$k[1]}</option>";
        } else if ($n == "" && $h == 0) {
            $r .= "<option value=\"{$k[0]}\" selected>{$k[1]}</option>";
        } else {
            $r .= "<option value=\"{$k[0]}\">{$k[1]}</option>";
        }
    }
    return $r;
}

function Checked($t1, $t2) {
    $r = "";
    if ($t1 == $t2) {
        $r = ' checked="true"';
    }
    return $r;
}

function download($file_dir, $file_name) {
//参数说明：
//file_dir:文件所在目录
//file_name:文件名
    $file_dir = chop($file_dir); //去掉路径中多余的空格
    //得出要下载的文件的路径
    if ($file_dir != '') {
        $file_path = $file_dir;
        if (substr($file_dir, strlen($file_dir) - 1, strlen($file_dir)) != '/')
            $file_path .= '/';
        $file_path .= $file_name;
    }
    else
        $file_path = $file_name;

    //判断要下载的文件是否存在
    if (!file_exists($file_path)) {
        echo '对不起,你要下载的文件不存在。';
        return false;
    }
    $file_size = filesize($file_path);

    header("Content-type: application/octet-stream");
    header("Accept-Ranges: bytes");
    header("Accept-Length: $file_size");
    header("Content-Disposition: attachment; filename=" . $file_name);

    $fp = fopen($file_path, "r");
    $buffer_size = 1024;
    $cur_pos = 0;

    while (!feof($fp) && $file_size - $cur_pos > $buffer_size) {
        $buffer = fread($fp, $buffer_size);
        echo $buffer;
        $cur_pos += $buffer_size;
    }

    $buffer = fread($fp, $file_size - $cur_pos);
    echo $buffer;
    fclose($fp);
    return true;
}

/**
 * 写入文件
 * @param type $file 文件名称
 * @param type $text 写入内容
 */
function writeinFile($file, $text) {
    $fp_conf = fopen($file, "w");
    fwrite($fp_conf, $text);
    fclose($fp_conf);
}

/**
 * 处理表单提交数据，转化为数组
 * @param type $postarr
 * @param type $delkey
 * @return type
 */
function readPost($postarr, $delkey = array("dopost")) {
    $newarr = array();
    foreach ($postarr as $key => $value) {
        if (!in_array($key, $delkey)) {
            if(!is_array($value)){
                $value = urldecode($value);
            }
            $newarr[$key] = $value;
        }
    }
    return $newarr;
}

/**
 *  判断是否为手机浏览器
 * @return type
 */
function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

function http_post_data($url, $data_string) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($data_string))
    );
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();

    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $return_content;
}

function kill_data($str) {
    $str = trim($str);
    $str = str_replace("，", "", $str);
    $str = str_replace(",", "", $str);
    return $str;
}

function phone2mi($phone) {
    $key1 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
    $key2 = array("h", "c", "t", "d", "i", "p", "o", "x", "r", "l");
    $mi = $phone;
    foreach ($key1 as $k => $mmm) {
        $mi = str_replace($mmm, $key2[$k], $mi);
    }
    return $mi;
}

function mi2phone($mi) {
    $key1 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
    $key2 = array("h", "c", "t", "d", "i", "p", "o", "x", "r", "l");
    $phone = $mi;
    foreach ($key2 as $k => $mmm) {
        $phone = str_replace($mmm, $key1[$k], $phone);
    }
    return $phone;
}

function replceSQL($str) {
    $r = array(
        'select',
        'insert',
        'update',
        'delete',
        '\'',
        '/*',
        '*/',
        'union',
        'concat',
        'load_file',
        'outfile',
        'substr'
    );
    for ($i = 0; $i < 100; $i++) {
        $str = str_ireplace($r, '', $str, $count);
        if ($count == 0)
            break;
    }

    return $str;
}

function RemoveXSS($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are
    // allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they
    // *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're
    // normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8
        // chars
        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search [$i])) . ';?)/i', $search [$i], $val); // with
        // a
        // ;
        // @
        // @
        // 0{0,7}
        // matches
        // '0'
        // zero
        // to
        // seven
        // times
        $val = preg_replace('/(&#0{0,8}' . ord($search [$i]) . ';?)/', $search [$i], $val); // with
        // a
        // ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = Array(
        'javascript',
        'vbscript',
        'expression',
        'applet',
        'meta',
        'xml',
        'blink',
        'link',
        'style',
        'script',
        'embed',
        'object',
        'iframe',
        'frame',
        'frameset',
        'ilayer',
        'layer',
        'bgsound',
        'title',
        'base'
    );
    $ra2 = Array(
        'onabort',
        'onactivate',
        'onafterprint',
        'onafterupdate',
        'onbeforeactivate',
        'onbeforecopy',
        'onbeforecut',
        'onbeforedeactivate',
        'onbeforeeditfocus',
        'onbeforepaste',
        'onbeforeprint',
        'onbeforeunload',
        'onbeforeupdate',
        'onblur',
        'onbounce',
        'oncellchange',
        'onchange',
        'onclick',
        'oncontextmenu',
        'oncontrolselect',
        'oncopy',
        'oncut',
        'ondataavailable',
        'ondatasetchanged',
        'ondatasetcomplete',
        'ondblclick',
        'ondeactivate',
        'ondrag',
        'ondragend',
        'ondragenter',
        'ondragleave',
        'ondragover',
        'ondragstart',
        'ondrop',
        'onerror',
        'onerrorupdate',
        'onfilterchange',
        'onfinish',
        'onfocus',
        'onfocusin',
        'onfocusout',
        'onhelp',
        'onkeydown',
        'onkeypress',
        'onkeyup',
        'onlayoutcomplete',
        'onload',
        'onlosecapture',
        'onmousedown',
        'onmouseenter',
        'onmouseleave',
        'onmousemove',
        'onmouseout',
        'onmouseover',
        'onmouseup',
        'onmousewheel',
        'onmove',
        'onmoveend',
        'onmovestart',
        'onpaste',
        'onpropertychange',
        'onreadystatechange',
        'onreset',
        'onresize',
        'onresizeend',
        'onresizestart',
        'onrowenter',
        'onrowexit',
        'onrowsdelete',
        'onrowsinserted',
        'onscroll',
        'onselect',
        'onselectionchange',
        'onselectstart',
        'onstart',
        'onstop',
        'onsubmit',
        'onunload'
    );
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced
    // something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra [$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra [$i] [$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra [$i], 0, 2) . '<x>' . substr($ra [$i], 2); // add
            // in
            // <>
            // to
            // nerf
            // the
            // tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the
            // hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

function safeHTML($str) {
    $str = replceSQL($str);
    $str = RemoveXSS($str);
    return htmlspecialchars($str);
}

function sfdata($code) {
    $backdata = "";
    switch ($code) {
        case 50:
        case 51:
        case 46:
        case 41:
        case 43:
            $backdata = "快递已收件";
            break;
        case 80:
        case 8000:
            $backdata = "已签收";
            break;
        case 30:
        case 31:
        case 627:
        case 3036:
            $backdata = "快件转运中";
            break;
        case 33:
            $backdata = "快件派送不成功";
            break;
        case 70:
            $backdata = "客户要求自取";
            break;
        case 99:
        case 631:
            $backdata = "快件已退回";
            break;
        case 44:
        case 630:
        case 634:
            $backdata = "快件派送中";
            break;
        case "":
        case 0:
            $backdata = "无信息";
            break;
        default:
            $backdata = $code;
            break;
    }
    return $backdata;
}

function http_post($url, $param) {
    $oCurl = curl_init();
    if (stripos($url, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
    }

    $strPOST = http_build_query($param);

    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($oCurl, CURLOPT_POST, true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    } else {
        return false;
    }
}

function TF($num, $text1, $text2) {
    if ($num == 0) {
        return $text1;
    } else {
        return $text2;
    }
}

?>
