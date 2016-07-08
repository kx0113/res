<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

/**
 * 部件
 *
 * @author sj
 * @package Widgets
 */
class Widgets {

    /**
     * 外链快捷菜单
     *
     * @access public
     * @return void
     */
    public function linkMenu($url = '',$r=0) {
        $html = '<div id="linktypepanel_s" ' . ($url == '' ? ' style="display:none"' : '') . '>';
        $html .= $url . '&nbsp;<a id="linktypeedit">修改</a></div>';
        $html .= '<div id="linktypepanel" ' .  ($url != '' ? ' style="display:none"' : '') . '>';
        $html .= '<input type="hidden" value="'.$url.'" name="url" id="linkkeyworld" />';
        $html .= '<select name="linktype" id="linktype">';
        $html .= '<option value="no">连接类型</option>';
        $html .= '<option value="nolink">不使用外链</option>';
        $html .= '<option value="网址">网址</option>';
        $html .= '<option value="微官网">微官网</option>';
        $html .= '<option value="图文信息">图文信息</option>';
        $html .= '<option value="电话">电话</option>';
        $html .= '<option value="车主之家">车主之家</option>';
        $html .= '<option value="车主卡展示">车主卡展示</option>';
        $html .= '<option value="车主卡服务">车主卡服务</option>';
        $html .= '<option value="爱车活动">爱车活动</option>';
        $html .= '<option value="最新政策">最新政策</option>';
        $html .= '<option value="战略合作">战略合作</option>';
        $html .= '<option value="联盟公告">联盟公告</option>';
        $html .= '<option value="网友建议">网友建议</option>';
        $html .= '<option value="爱车专员">爱车专员</option>';
        $html .= '<option value="联盟商户">联盟商户</option>';
        $html .= '<option value="二手车主页">二手车主页</option>';
        $html .= '<option value="推荐车型">推荐车型</option>';
        $html .= '<option value="二手车服务">二手车服务</option>';
        $html .= '<option value="交警服务">交警服务</option>';
//    $html .= '<option value="相册">相册</option>';
//     $html .= '<option value="会员卡">会员卡</option>';

//     $html .= '<option value="活动">活动</option>';
//     $html .= '<option value="微商城">微商城</option>';
//     $html .= '<option value="微网点">微网点</option>';
//     $html .= '<option value="微调研">微调研</option>';
//     $html .= '<option value="微表单">微表单</option>';
//     $html .= '<option value="产品中心">产品中心</option>';
//     $html .= '<option value="服务热线">服务热线</option>';
//     $html .= '<option value="产品报修">产品报修</option>';
//     $html .= '<option value="产品咨询">产品咨询</option>';
//     $html .= '<option value="我要推荐">我要推荐</option>';
//     $html .= '<option value="在线留言">在线留言</option>';
//     $html .= '<option value="在线预约">在线预约</option>';
//     $html .= '<option value="留言板">留言板</option>';
//     $html .= '<option value="地图">地图</option>';
//     $html .= '<option value="快捷工具">快捷工具</option>';


//    }

        $html .= <<<EOT
       </select>
      <input type="text" name="linktextbox" id="linktextbox" style="display: none;" />
      <select name="linksubtype" id="linksubtype" style="display: none"></select>
      <select name="linkthreetype" id="linkthreetype" style="display: none"></select>
      <div id="linklbs" style="display: none">
        <div id="linklbspage"></div>
        <label>地图显示标题：</label><input type="text" id="lbs_title" />
        <label>地图显示详情：</label><input type="text" id="lbs_info" />
      </div>
      <input type="hidden" name="longitude" id="longitude" />
      <input type="hidden" name="latitude" id="latitude" />
    </div>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#linklbs').mouseleave(function(){
          if($('#longitude').val() != '' && $('#latitude').val() != '') {
            $('#linkkeyworld').val("http://chabus.duapp.com/mapapi.php?lng=" + $('#longitude').val() + "&lat=" + $('#latitude').val() + "&title=" + $('#lbs_title').val() + "&info=" + $('#lbs_info').val());
          }
        });
        $('#linktype').change(function(){
          switch($('#linktype').val()) {
          case 'no':
            clear_linksubtype();
          break;
          case 'nolink':
            $('#linkkeyworld').val('');
          break;
          
          case '微官网':

          case '车主之家':
          case '车主卡展示':
          case '车主卡服务':
          case '爱车活动':
          case '最新政策':
          case '战略合作':
          case '联盟公告':
          case '网友建议':
          case '爱车专员':
          case '汽车服务':
          case '联盟商户':
          case '二手车市':
          case '交警服务':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
          break;
          
          case '网址':
            clear_linksubtype();
            $('#linktextbox').attr('placeholder', '请输入网址');
            $('#linktextbox').val('');
            $('#linktextbox').show();
            $('#linksubtype').hide();
            break;
            
          case '电话':
            clear_linksubtype();
            $('#linktextbox').attr('placeholder', '请输入电话号码');
            $('#linktextbox').val('');
            $('#linktextbox').show();
            $('#linksubtype').hide();
            break;
            
          case '相册':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $.get("/User/Ajax/linkMenu/fetch/photo", function(result){
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
            break;
            
          case '活动':
            clear_linksubtype();
            $.get("/User/Ajax/linkMenu/fetch/activitylist", function(result){
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;
          
          case '会员卡':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
          break;
          
          case '微表单':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $.get("/User/Ajax/linkMenu/fetch/selfform", function(result) {
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;
          
          case '微商城':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $.get("/User/Ajax/linkMenu/fetch/shop", function(result) {
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;

          case '微网点':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $.get("/User/Ajax/linkMenu/fetch/company", function(result) {
              //alert(result); return;
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;
          
          case '产品中心':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
          break;
          
          case '服务热线':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
          break;
          
          case '产品报修':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $("#linksubtype").html('<option value="报修记录">报修记录</option><option value="提交报修单">提交报修单</option>');
            $('#linksubtype').show();
          break;
          
          case '产品咨询':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $("#linksubtype").html('<option value="咨询记录">咨询记录</option><option value="提交咨询单">提交咨询单</option>');
            $('#linksubtype').show();
          break;
          
          case '我要推荐':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $("#linksubtype").html('<option value="推荐记录">推荐记录</option><option value="提交推荐">提交推荐</option>');
            $('#linksubtype').show();
          break;
          
          case '地图':
            clear_linksubtype();
            clear_linkthreetype();
            $.get('/User/Ajax/linkMenu/fetch/map', function(result){
              $('#linklbspage').html(result);
              $('#linklbs').show();
            });
          break;
          
          case '在线留言':
          case '留言模块':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $.get("/User/Ajax/linkMenu/fetch/Selflam", function(result) {
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;

          case '微调研':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $.get("/User/Ajax/linkMenu/fetch/Weidiaoyan", function(result) {
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;

          case '在线预约':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
            $.get("/User/Ajax/linkMenu/fetch/Selfrese", function(result) {
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;
          
          case '留言板':
            clear_linksubtype();
            $('#linkkeyworld').val( $('#linktype').val() );
          break;
          
          case '快捷工具':
            clear_linksubtype();
            $.get("/User/Ajax/linkMenu/fetch/Quicktools_type", function(result){
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
          break;
          case '图文信息':
            clear_linksubtype();
            $.get("/User/Ajax/linkMenu/fetch/Img", function(result){
              if (result.length > 0) {
                $("#linksubtype").html(result);
                $('#linksubtype').show();
              }
            });
            break;
          default:
            clear_linksubtype();
            $('#linkkeyworld').val($('#linktype').val());
            break;
          }
        });
        //二级菜单
        $('#linksubtype').change(function(){
          switch ( $('#linktype').val() ) {
            case '相册' :
              $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val() );
              //alert($('#linkkeyworld').val());
            break;
            case '活动' :
              switch ( $('#linksubtype').val() ) {
                case '大转盘' :
                  clear_linkthreetype();
                  $('#linkkeyworld').val( $('#linksubtype').val() );
                  $.get('/User/Ajax/linkMenu/fetch/lottery', function(result){
                    if (result.length > 0) {
                      $('#linkthreetype').html(result);
                      $('#linkthreetype').show();
                    }
                  });
                break;
                case '刮刮卡':
                  clear_linkthreetype();
                  $('#linkkeyworld').val( $('#linksubtype').val() );
                  $.get('/User/Ajax/linkMenu/fetch/guajiang', function(result){
                    if (result.length > 0) {
                      $('#linkthreetype').html(result);
                      $('#linkthreetype').show();
                    }
                  });
                break;
                case '优惠券':
                  clear_linkthreetype();
                  $('#linkkeyworld').val( $('#linksubtype').val() );
                  $.get('/User/Ajax/linkMenu/fetch/coupon', function(result){
                    if (result.length > 0) {
                      $('#linkthreetype').html(result);
                      $('#linkthreetype').show();
                    }
                  });
                break;
                case '微投票':
                  clear_linkthreetype();
                  $('#linkkeyworld').val( $('#linksubtype').val() );
                  $.get('/User/Ajax/linkMenu/fetch/vote', function(result){
                    if (result.length > 0) {
                      $('#linkthreetype').html(result);
                      $('#linkthreetype').show();
                    }
                  });
                break;
                case '砸金蛋' :
                  clear_linkthreetype();
                  $('#linkkeyworld').val( $('#linksubtype').val() );
                  $.get('/User/Ajax/linkMenu/fetch/goldenegg', function(result){
                    if (result.length > 0) {
                      $('#linkthreetype').html(result);
                      $('#linkthreetype').show();
                    }
                  });
                break;
                case '水果达人' :
                  clear_linkthreetype();
                  $('#linkkeyworld').val( $('#linksubtype').val() );
                  $.get('/User/Ajax/linkMenu/fetch/luckyfruit', function(result){
                    if (result.length > 0) {
                      $('#linkthreetype').html(result);
                      $('#linkthreetype').show();
                    }
                  });
                break;
              }
            break;
            case '微表单' :
              $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val() );
              //alert($('#linkkeyworld').val());
            break;

            case '在线留言' :
            case '留言模块':
              $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val() );
              //alert($('#linkkeyworld').val());
            break;
            case '微网点' :
              $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val() );
              //alert($('#linkkeyworld').val());
            break;
            case '微调研' :
              $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val() );
              //alert($('#linkkeyworld').val());
            break;
            case '在线预约' :
              $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val() );
              //alert($('#linkkeyworld').val());
            break;
            case '快捷工具' :
              clear_linkthreetype();
              $.get('/User/Ajax/linkMenu/fetch/Quicktools/type/' + $('#linksubtype').val() , function(result){
                if (result.length > 0) {
                  $('#linkthreetype').html(result);
                  $('#linkthreetype').show();
                }
              });
              // alert($('#linkkeyworld').val());
            break;
            case '微商城':
              clear_linkthreetype();
              if ($('#linksubtype').val() != ''){
                $('#linkkeyworld').val( $('#linktype').val() + '-' + $('#linksubtype').val());
              }
            break;
            case '产品报修':
              clear_linkthreetype();
              if ($('#linksubtype').val() != ''){
                $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val());
              }
            break;
            case '产品咨询':
              clear_linkthreetype();
              if ($('#linksubtype').val() != ''){
                $('#linkkeyworld').val( $('#linktype').val() + ' ' + $('#linksubtype').val());
              }
            break;
            case '图文信息' :
              clear_linkthreetype();
              $('#linkkeyworld').val($('#linksubtype').val());
              $.get('/User/Ajax/linkMenu/fetch/Imgs/classid/' + $('#linksubtype').val() , function(result){
                if (result.length > 0) {
                  $('#linkthreetype').html(result);
                  $('#linkthreetype').show();
                }
              });
            break;
          }
        });
        // 三级菜单,确定Ajax调用保存数据库数据的策略。
        $('#linkthreetype').change(function(){
          if ($('#linktype').val() == '快捷工具') {
            $('#linkkeyworld').val( $('#linkthreetype').val() );
          }
          else if ($('#linktype').val() == '图文信息') {
            $('#linkkeyworld').val( $('#linkthreetype').val() );
          }
          else {
            $('#linkkeyworld').val( $('#linksubtype').val() + ' ' + $('#linkthreetype').val() );
          }
        });
        //文本框
        $('#linktextbox').change(function(){
          if ($('#linktype').val() == '电话') {
            $('#linkkeyworld').val('tel:' + $('#linktextbox').val());
          }
          else {
            $('#linkkeyworld').val($('#linktextbox').val());
          }
        });
        //隐藏并清除linksubtype以下级信息
        function clear_linksubtype() {
          $('#linklbspage').html('');
          $('#linklbs').hide();
          $('#linktextbox').val('');
          $('#linktextbox').hide();
          $('#linksubtype').html('');
          $('#linksubtype').hide();
          $('#linkthreetype').html('');
          $('#linkthreetype').hide();
        }
        //隐藏并清除linkthreetype信息
        function clear_linkthreetype() {
          $('#linklbspage').html('');
          $('#linklbs').hide();
          $('#linkthreetype').html('');
          $('#linkthreetype').hide();
        }
        //修改
        $('#linktypeedit').click(function(){
          $('#linktypepanel_s').hide();
          $('#linktypepanel').show();
        });
      
      });
    </script>
EOT;
        return $html;
    }
}