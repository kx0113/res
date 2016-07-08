<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

/**
 * 微信URL处理类
 * @category    ORG
 * @package     ORG
 */
class Common {

    /**
     * 转换链接
     *
     * @param string $url      数据库中的外链地址
     * @param string $token    微信公众号令牌
     * @param string $wecha_id 访问用户的微信标识
     * @return string 可访问的URL链接
     */
    public function renderLink($url, $token, $wecha_id) {
        $link = '';
        $urlArr       = explode(' ', $url);
        $urlInfoCount = count($urlArr);
        if ($urlInfoCount > 1) {
            $item = $urlArr[0];
            $item_value = $urlArr[1];
            $itemid = intval($urlArr[1]);
        }

        // URL参数
        // $args = array('token'=>$token, 'wecha_id'=>$wecha_id);

        // 启用泛解析后的URL
        if ($wecha_id) {
            $args = array('token'=> $token, 'wecha_id'=>$wecha_id);
        }
        else {
            $args = array('token'=> $token);
        }

        $base_wap_url = rtrim(C('site_url'), '/') . '/Wap';

        if (self::strExists($url, '微网站')|| self::strExists($url, '微官网') || self::strExists($url, '首页') || self::strExists($url, '官网') || self::strExists($url, 'home')) {
            $link = $base_wap_url . '/Index/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '车主卡展示')) {
            $link = $base_wap_url . '/CarOwnerCardService/show/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '车主之家')) {
            $link = $base_wap_url . '/CarOwnerHome/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '联盟商户')) {
            $link = $base_wap_url . '/CarServiceProvider/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '汽车服务')) {
            $link = $base_wap_url . '/CarService/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '二手车主页')) {
            $link = $base_wap_url . '/Used/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '汽车资讯')) {
            $link = $base_wap_url . '/CarInformation/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '交警服务')) {
            $link = $base_wap_url . '/PoliceService/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '车主卡服务')) {
            $link = $base_wap_url . '/CarOwnerCardService/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '爱车活动')) {
            $link = $base_wap_url . '/CarOwnerActivity/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '最新政策')) {
            $link = $base_wap_url . '/Policy/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '战略合作')) {
            $link = $base_wap_url . '/StrategicCooperation/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '联盟公告')) {
            $link = $base_wap_url . '/Announcement/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '网友建议')) {
            $link = $base_wap_url . '/Suggestions/add/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '爱车专员')) {
            $link = $base_wap_url . '/Commissioner/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '学驾照')) {
            $link = $base_wap_url . '/DrivingLicense/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '二手车主页')) {
            $link = $base_wap_url . '/Car/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '推荐车型')) {
            $link = $base_wap_url . '/Car/lists/recommend/1/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '二手车服务')) {
            $link = $base_wap_url . '/UsedCarService/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '刮刮卡')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/Guajiang/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '微调研')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/Survey/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '大转盘')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/Lottery/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '优惠券')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/Coupon/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '砸金蛋')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/GoldenEgg/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '水果达人')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/LuckyFruit/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '微表单')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/Selfform/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, array('在线留言','留言模块'))) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/Selflam/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '在线预约')) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            $link = $base_wap_url . '/Selfrese/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '会员卡')) {
            $card = M('member_card_create')->where(array(
                'token'     => $token,
                'wecha_id'  => $wecha_id
            ))->find();
            if ($card == false) {
                $link = $base_wap_url . '/Card/get_card/' . $this->argsToUrl($args);
            }
            else {
                $link = $base_wap_url . '/Card/vip/' . $this->argsToUrl($args);
            }
        }
        elseif (self::strExists($url, '微商城')) {
            $shop_url = array('index'=>'门店列表','cart'=>'分类列表','my'=>'订单列表');
            $shop_url_array = explode('-',$url);
            if (count($shop_url_array) == 1){
                $link = $base_wap_url . '/Shop/index/' . $this->argsToUrl($args);
            }else{
                $link = $base_wap_url . '/Shop/'.array_search($shop_url_array[1],$shop_url).'/' . $this->argsToUrl($args);
            }
        }
        elseif (self::strExists($url, array('网点', '微网点'))) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
                $link = $base_wap_url . '/Company/lists/' . $this->argsToUrl($args);
            }
            else{
                $link = $base_wap_url . '/Company/search/' . $this->argsToUrl($args);
            }
        }
        elseif (self::strExists($url, array('产品中心'))) {
            $link = $base_wap_url . '/Vshop/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, array('服务热线'))) {
            $link = $base_wap_url . '/Structure/serviceHotline/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, '产品报修')) {
            if ($item_value) {
                if ($item_value == '报修记录'){
                    $link = $base_wap_url . '/Repair/index/' . $this->argsToUrl($args);
                }else if ($item_value == '提交报修单'){
                    $link = $base_wap_url . '/Repair/add/' . $this->argsToUrl($args);
                }
            }else{
                $link = $base_wap_url . '/Repair/index/' . $this->argsToUrl($args);
            }
        }
        elseif (self::strExists($url, '产品咨询')) {
            if ($item_value) {
                if ($item_value == '咨询记录'){
                    $link = $base_wap_url . '/Vshop/consultation/' . $this->argsToUrl($args);
                }else if ($item_value == '提交咨询单'){
                    $link = $base_wap_url . '/Vshop/consultationAdd/' . $this->argsToUrl($args);
                }
            }else{
                $link = $base_wap_url . '/Vshop/consultation/' . $this->argsToUrl($args);
            }
        }
        elseif (self::strExists($url, '我要推荐')) {
            if ($item_value) {
                if ($item_value == '推荐记录'){
                    $link = $base_wap_url . '/Recommendation/index/' . $this->argsToUrl($args);
                }else if ($item_value == '提交推荐'){
                    $link = $base_wap_url . '/Recommendation/add/' . $this->argsToUrl($args);
                }
            }else{
                $link = $base_wap_url . '/Recommendation/index/' . $this->argsToUrl($args);
            }
        }
        elseif (self::strExists($url, array('留言板'))) {
            $link = $base_wap_url . '/Reply/index/' . $this->argsToUrl($args);
        }
        elseif (self::strExists($url, array('相册', '微相册'))) {
            if ($itemid) {
                $args = array_merge($args, array('id'=>$itemid));
            }
            if ($urlInfoCount == 1) { // 一级Ajax，精确到相册列表
                $link = $base_wap_url . '/Photo/index/' . $this->argsToUrl($args);
            }
            else if ($urlInfoCount == 2) { // 两级Ajax，精确到某一相册
                $args = array_merge($args, array('id'=>$urlArr[1]));
                $link = $base_wap_url . '/Photo/plist/' . $this->argsToUrl($args);
            }
        }
        elseif (self::strExists($url, array('微投票', '投票'))) {
            if ($itemid) {
                $args = array_merge($args, array('voteid'=>$itemid));
            }
            $link = $base_wap_url . '/Vote/index/' . $this->argsToUrl($args);
        }
        else {
            if ((strpos($url,"http://") === false) and (strpos($url,"https://") === false) and (strpos($url,"tel:") === false)) {
                // 绝对路径
                $url = "http://".$url;
            }
            // 转译URL中特殊的html字符，必须与保存URL的动作对应。
            $link = htmlspecialchars_decode($url);
        }
        return $link;
    }

    /**
     * 判断带关键字的连接是否存在
     */
    public function strExists ($haystack, $needle) {
        if ( is_array($needle) ) {
            foreach ( $needle as $row ) {
                if (strpos($haystack, $row) !== FALSE) {
                    return true;
                }
            }
        }
        else {
            return !(strpos($haystack, $needle) === FALSE);
        }
    }

    /**
     * 将数组型的参数转换为可用的URL
     */
    public function argsToUrl($args = array()) {
        $output = '';
        if (is_array($args) && !empty($args)) {
            foreach ($args as $k => $v) {
                $output .= $k . '/' . $v . '/';
            }
        }

        return rtrim($output, '/') . '';
    }

}