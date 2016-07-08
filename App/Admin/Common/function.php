<?php

//登陆成功日志记录
 function logAdd($msg,$id){
    $log['user_id'] = $id;//empty($id) ? $this->cid : $id;
    $log['time'] = time();
    $log['ip'] = get_client_ip();
    $log['operation'] =$msg;
    $log['status'] = '1';
    M('log')->add($log);

}
//登陆失败日志记录
 function loginErr($user_name,$password,$msg){
    $ler['username'] = $user_name;
    $ler['password'] = $password;
    $ler['info'] = $msg;
    $ler['time'] = time();
    $ler['ip'] = get_client_ip();
    M('login_err')->add($ler);
}
//-----------------------Index------------------------
/**
 * 判断权限
 * @param string $n
 * @return boolean
 */
function TestPower($n) {
    $res = FALSE;
    $myPower = session(C("SESSION_POWER"));
    if (preg_match('/All_power/i', $myPower)) {
        return TRUE;
    }
    if ($n == "") {
        return TRUE;
    }
    $Powers = explode(",", $myPower);
    $ns = explode(',', $n);
    foreach ($ns as $n) {
        if ($n == '') {
            continue;
        }
        if (in_array($n, $Powers)) {
            $res = TRUE;
            break;
        }
    }
    return $res;
}

function creatMenuList($menuData) {
    $menuArray = array();
    $result = "";
    $dex = 1;
    $icobase = "/Public/admin/images/menu/";
    import("@.Class.mytag");
    $dtp = new MyTagParse();
    $dtp->SetNameSpace('menu', '<', '>');
    $dtp->LoadSource($menuData);
    $dtp2 = new MyTagParse();
    $dtp2->SetNameSpace('menu', '<', '>');
    foreach ($dtp->CTags as $k => $val) {
        if ($val->GetName() == 'top' && ($val->GetAtt('rank') == '' || TestPower($val->GetAtt('rank')) )) {
            $menuListTittle = $val->GetAtt('name');
            $menuOpen = $val->GetAtt('open');
            $ico = $val->GetAtt('ico');
            if ($ico == "") {
                $icourl = $icobase . "auto.png";
            } else {
                $icourl = $icobase . $ico;
            }

            $menuArray[$dex] = "";
            $menuArray[$dex] .= "<div class=\"menu_list\">\r\n";

            $menuArray[$dex] .= "<div class=\"menu_father\">\r\n";
            $menuArray[$dex] .= "<div class=\"menu_ico\" style=\"background:url($icourl) center center no-repeat;\"></div>\r\n";
            $menuArray[$dex] .= "<div class=\"menu_text\">{$menuListTittle}</div>\r\n";
            $menuArray[$dex] .= "</div>\r\n";

            $menuArray[$dex] .= "<div class=\"menu_child\">\r\n";
            $menuArray[$dex] .= "<div class=\"menu_child_centent\">\r\n";
            $menuArray[$dex] .= "<div class=\"menu_child_list\">\r\n";
            $dtp2->LoadSource($val->InnerText);
            foreach ($dtp2->CTags as $k2 => $val2) {
                if ($val2->GetName() == 'item' && ($val2->GetAtt('rank') == '' || TestPower($val2->GetAtt('rank')) )) {
                    $linkto = U($val2->GetAtt('link'));
                    $menuArray[$dex] .= "<div data-url=\"{$linkto}\" class=\"menu_child_cube\"><div class=\"menu_child_cube_chico\"></div><div class=\"menu_child_cube_chtxt\">{$val2->GetAtt('name')}</div></div>\r\n";
                }
            }
            $menuArray[$dex] .= "</div>\r\n";
            $menuArray[$dex] .= "</div>\r\n";
            $menuArray[$dex] .= "</div>\r\n";

            $menuArray[$dex] .= "</div>\r\n";
        }else{
            $menuArray[$dex] = "";
        }
        $dex++;
    }

    $pushhtml = "";
    for ($u = 1; $u <= count($menuArray); $u++) {
        $pushhtml .= $menuArray[$u];
    }
    $outlink = U("login/logout");
    $result = "";
    $result .= "<div id=\"menumain\">";
    $result .= "<div id=\"menu_centent\">";
    $result .= "<div id=\"menu_box\">{$pushhtml}</div>";
//    $result .= "<a id=\"menu_exit\" href=\"{$outlink}\">";
//    $result .= "<div id=\"menu_exitbtn\"></div>";
//    $result .= "<div id=\"menu_exittext\">退出系统</div>";
//    $result .= "</a>";
    $result .= "</div>";
    $result .= "<div id=\"menu_line\"></div>";
    $result .= "</div>";
    $result .= "<div id=\"menublackbg\"></div>";
    $listener = U('timer/listen');
    $result .= '<script type="text/javascript">' . "var ListenUrl = '{$listener}';" . '</script>';
    $result .= '<div id="loadbox"><div id="loadboxer"><div id="load_ico"><div id="load_icoimg"><img src="__PUBLIC__/'.C("tmp_name").'/images/base/loading.gif" width="100%" height="100%" /></div></div><div id="load_content">正在加载中...</div></div></div>';
    return $result;
}


//-----------------------Index------------------------End
//-----------------------Filemaster------------------------
function cheakfiletype($string, $file) {
    $arr = explode(".", $file);
    $num = count($arr) - 1;
    if ($string == $arr[$num] && count($arr) > 1) {
        return TRUE;
    } else {
        return FALSE;
    }
}

//-----------------------Filemaster------------------------End
//-----------------------Database------------------------
function TjCount($tbname, &$mydb) {
    $row = $mydb->GetOne("SELECT COUNT(*) AS dd FROM $tbname");
    return $row['dd'];
}

function RpLine($str) {
    $str = str_replace("\r", "\\r", $str);
    $str = str_replace("\n", "\\n", $str);
    return $str;
}

//-----------------------Database------------------------End
//-----------------------Group------------------------
//验证权限（生成功能列表时候用到）
function TestGroupPower($n, $power) {
    $res = FALSE;
    $myPower = $power;

    if ($n == "") {
        return TRUE;
    }
    $Powers = explode(",", $myPower);
    $ns = explode(',', $n);
    foreach ($ns as $n) {
        if ($n == '') {
            continue;
        }
        if (in_array($n, $Powers)) {
            $res = TRUE;
            break;
        }
    }
    return $res;
}

//-----------------------Group------------------------End
//-----------------------Updata------------------------
function getUpdataLink() {
    $host = $_SERVER['HTTP_HOST'];
    $http_host = explode(".", $host);
    $http_host = array_reverse($http_host);
    if (count($http_host) > 2) {
        $domain_arr = array("com", "net", "org", "gov");
        if (in_array($http_host[1], $domain_arr)) {
            $host = $http_host[2] . "." . $http_host[1] . "." . $http_host[0];
        } else {
            $host = $http_host[1] . "." . $http_host[0];
        }
    }
    $ip = get_client_ip();

    $ban = getBan();
    $pro_code = C("UPDATA_CODE");
    $url = C("UPDATA_URL") . "/updata/json/index/pro_code/{$pro_code}/ban/{$ban}/host/$host/ip/$ip";
    return $url;
}

function getUpdataOkLink() {
    $host = $_SERVER['HTTP_HOST'];
    $ip = get_client_ip();

    $ban = getBan();
    $pro_code = C("UPDATA_CODE");
    $url = C("UPDATA_URL") . "/updata/json/ok/pro_code/{$pro_code}/ban/{$ban}/host/$host/ip/$ip";
    return $url;
}

function getBan() {
    $ban_file = APP_PATH . 'Lib/Inc/ban.txt';
    if (!is_file($ban_file)) {
        WriteFile($ban_file, "0");
    }
    $ban_text = file($ban_file);
    $ban = $ban_text[0];
    return $ban;
}

function saveBan($text) {
    $ban_file = APP_PATH . 'Lib/Inc/ban.txt';
    WriteFile($ban_file, $text);
}

//获取文件夹里的所有文件路径
function getfile($inpath) {
    $linkdata = "";
    $dh = dir($inpath);
    $files = $dirs = array();
    while (($file = $dh->read()) !== false) {
        if ($file != "." && $file != ".." && !is_dir("$inpath/$file")) {
            $linkdata .= $inpath . "/" . $file . "<br/>";
        } else if ($file != "." && $file != ".." && is_dir("$inpath/$file")) {
            $inpath2 = $inpath . "/" . $file;
            $linkdata .= getfile($inpath2);
        }
    }
    return $linkdata;
}

//获取文件夹里的所有文件夹路径
function getbag($inpath) {
    $linkdata = "";
    $dh = dir($inpath);
    $files = $dirs = array();
    while (($file = $dh->read()) !== false) {
        if ($file != "." && $file != ".." && is_dir("$inpath/$file")) {
            $linkdata .= $inpath . "/" . $file . "<br/>";
            $inpath2 = $inpath . "/" . $file;
            $linkdata .= getbag($inpath2);
        }
    }
    return $linkdata;
}

/**
 * 写入文件
 * @param type $file 文件名称
 * @param type $text 写入内容
 */
function WriteFile($file, $text) {
    $fp_conf = fopen($file, "w");
    fwrite($fp_conf, $text);
    fclose($fp_conf);
}

//-----------------------Updata------------------------End

function background() {
    $name = C("SESSION_ADMINID");
    $admin = session($name);
    $where = array(
        "admin_id" => $admin
    );
    $row = M("neiqin_background")->where($where)->find();
    if (!is_array($row)) {
        $img = "01.jpg";
        $data = array(
            "admin_id" => $admin,
            "bgimg" => $img
        );
        M("neiqin_background")->add($data);
    } else {
        $img = $row['bgimg'];
    }

    $html = '
        <img id="bg" onload="win_bg();" src="__PUBLIC__/' . C("TMP_NAME") . '/images/background/' . $img . '" />
        <script type="text/javascript">
            $(function(){
                win_bg();
                $(window).resize(function(){
                    win_bg();
                })
            });
            function win_bg(){
                $("#bg").css("width","100%");
                $("#bg").css("height","auto");
                var win_w = $(window).width();
                var win_h = $(window).height();
                var pic_w = $("#bg").width();
                var pic_h = $("#bg").height();
                if(win_h<pic_h){
                    $("#bg").css("width","100%");
                    $("#bg").css("height","auto");
                }else{
                    $("#bg").css("height","100%");
                    $("#bg").css("width","auto");
                }
            }
        </script>';
    echo $html;
}
function readGroup($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("neiqin_group")->where($where)->find();
    return $row["groupname"];
}
function getGoodsEnterName($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("goods_enter")->where($where)->find();
    return $row["name"];
}
function getGoodsEnterUnit($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("goods_enter")->where($where)->find();
    return $row["unit"];
}
function getMobileEnterUnit($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("mobile_enter")->where($where)->find();
    return $row["unit"];
}
function getGoodsClassName($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("goods_class")->where($where)->find();
    return $row["name"];
}
function getGoodsEnterQuantity($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("goods_enter")->where($where)->find();
    return $row["quantity"];
}
function getMobileEnterQuantity($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("mobile_enter")->where($where)->find();
    return $row["quantity"];
}
//查询admin
function readUser($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("admin")->where($where)->find();
    return $row["nickname"];
}
//用户account
function readUserAccount($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("admin")->where($where)->find();
    return $row["account"];
}
function readRechargeMobile($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("recharge")->where($where)->find();
    return $row["mobile"];
}

function readUserHeadimg($ke_id) {
    $where = array(
        "user_id" => $ke_id
    );
    $row = M("user_head")->where($where)->find();
    return $row["head"];
}
function readMobileName($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("mobile_enter")->where($where)->find();
    return $row["name"];
}
function readUserName($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("user")->where($where)->find();
    return $row["name"];
}
function readKefu($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("admin")->where($where)->find();
    return $row["nickname"];
}
function readGoodsEnterName($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("goods_enter")->where($where)->find();
    return $row["name"];
}
//职位
function readStation($ke_id) {
    $where = array(
        "station_id" => $ke_id
    );
    $row = M("station")->where($where)->find();
    return $row["station_name"];
}
//部门
function readSection($ke_id) {
    $where = array(
        "id" => $ke_id
    );
    $row = M("user_grade")->where($where)->find();
    return $row["name"];
}


function getProductName($pro_name) {
    $row = M("product_things")->where(array("code" => $pro_name))->find();
    $grow = M("product_group")->where(array("id" => $row["gid"]))->find();
    if ($row) {
        return "【{$grow['name']}】" . $row["name"] . " " . $row["bag"];
    } else {
        return FALSE;
    }
}

function getProductNameByID($pro_id) {
    $row = M("product_things")->where(array("id" => $pro_id))->find();
    $grow = M("product_group")->where(array("id" => $row["gid"]))->find();
    if ($row) {
        return "【{$grow['name']}】" . $row["name"] . " " . $row["bag"];
    } else {
        return FALSE;
    }
}

function getProductID($pro_name) {
    $row = M("product_things")->where(array("code" => $pro_name))->find();
    if ($row) {
        return $row["id"];
    } else {
        return FALSE;
    }
}

function getGroupID($pro_name) {
    $row = M("product_things")->where(array("code" => $pro_name))->find();
    if ($row) {
        return $row["gid"];
    } else {
        return FALSE;
    }
}

function preday($num) {
    $now_Y = date("Y", time());
    $now_m = date("m", time());
    $now_d = date("d", time());
    $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
    $now_low = strtotime($now_time);

    $basetime = $now_low - $num * 86400;
    return date("Y-m-d", $basetime);
}

function aftday($num) {
    $now_Y = date("Y", time());
    $now_m = date("m", time());
    $now_d = date("d", time());
    $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
    $now_low = strtotime($now_time);

    $basetime = $now_low + $num * 86400;
    return date("Y-m-d", $basetime);
}

?>
