<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

/**
 * 地理数据，Ajax方式的省市县乡四级联动
 * 
 * 
 * @package ORG
 */
class Geo {
  
  /**
   * 外链快捷菜单
   *
   * @param array $target
   * - 目标对象，例如用户，公众号等。对象中包含省市区县信息。
   * @param url $url
   * 
   * @access public
   * @return void
   */
  public function renderGeo($target = array(), $url = '') {
    $province         = $this->getProvinceList();
    $provinceOptions  = $this->renderRegionOptions($province);
    
    // 省份列表选项
    $provinceOptions = $this->renderRegionOptions($province, $target['province']);
    
    // 城市列表选项
    if (!empty($target['province']) && !empty($target['city'])) {
      $city = $this->getRegionName($target['province'],2);
      $cityOptions = $this->renderRegionOptions($city, $target['city']);
    }
    else {
      $cityOptions = '';
    }
    
    // 区县列表选项
    if (!empty($target['city']) && !empty($target['district'])) {
      $district = $this->getRegionName($target['city'],3);
      $districtOptions = $this->renderRegionOptions($district, $target['district']);
    }
    else {
      $districtOptions = '';
    }
    
    // 乡镇列表选项
    if (!empty($target['district']) && !empty($target['town'])) {
      $town = $this->getRegionName($target['district'],4);
      $townOptions = $this->renderRegionOptions($town, $target['town']);
    }
    else {
      $townOptions = '';
    }
    
    
    $ajax_url = U("Home/Geo/getRegionOptions");
    $html = '';
    
    $html .= <<<EOT
      <select class="region" id="province" name="province" required ><option value="0" >省份</option>{$provinceOptions}</select>
      <select class="region" id="city"     name="city"     required ><option value="0" >城市</option>{$cityOptions}</select>
      <select class="region" id="district" name="district" required ><option value="0" >区县</option>{$districtOptions}</select>
      <select class="region" id="town"     name="town"     required ><option value="0" >乡镇</option>{$townOptions}</select>
    
    <script type="text/javascript">
      $(document).ready(function(){
        <!-- 点选省份获取城市列表 -->
        $("#province").change(function() {
          var myurl = '{$ajax_url}';
          $.ajax({url:myurl,async:false});
          $.ajax({
             type: "POST",
             url: myurl,
             data: "parentid=" + $(this).val() + "&geo_type=2&r=" + Math.random(),
             dataType:'html',
             success: function(data,status){
               $("#city").html(data);
               $("#district").html("<option value='0' >区县</option>");
               $("#town").html("<option value='0' >乡镇</option>");
             }
          });
        });
        
        <!-- 点选城市获取区县列表 -->
        $("#city").change(function() {
          var myurl = '{$ajax_url}'; 
          $.ajax({url:myurl,async:false});
          $.ajax({
             type: "POST",
             url: myurl,
             data: "parentid=" + $(this).val() + "&geo_type=3&r=" + Math.random(),
             dataType:'html',
             success: function(data,status){
               $("#district").html(data);
               $("#town").html("<option value='0' >乡镇</option>");
             }
          });
        });
        
        <!-- 点选区县获取乡镇列表 -->
        $("#district").change(function() {
          var myurl = '{$ajax_url}';
          $.ajax({url:myurl,async:false});
          $.ajax({
             type: "POST",
             url: myurl,
             data: "parentid=" + $(this).val() + "&geo_type=4&r=" + Math.random(),
             dataType:'html',
             success: function(data,status){
               $("#town").html(data);
             }
          });
        });
        
      });
    </script>
EOT;
    return $html;
  }
  
  /**
   * 外链快捷菜单
   * 
   * @access public
   * @return void
   *
   * 
   */
  public function getProvinceList() {
    $province = M('Geo')
        ->where(array('geo_type' => 1))
        ->order('geo_firstletter ASC')
        ->select();
        
    return $province;
  }
  
  /**
   * 发布行政区划下拉框选项
   *
   * @param array $region
   *   下拉框数组
   * @param int $selectedid 
   *   已选中的行政区划ID
   * 
   * 
   */
  public function renderRegionOptions($region, $selectedid = 0) {
    $str = '';
    if ($region){
      foreach ($region as $c){
        $selected = '';
        if ($c['geo_id'] == $selectedid){
          $selected = 'selected';
        }
        $str .= '<option value="' . $c['geo_id'] . '" '.$selected.'>' . 
                $c['geo_firstletter'] . ' - ' . $c['geo_name'] . 
                '</option>';
      }
    }
    return $str;
  }
  
  /**
   * Ajax回调，获取下拉框的内容
   */
  public function getRegionOptions() {
    $currentU = $this->currentUser;
    if(!empty($_POST['parentid']) && !empty($_POST['geo_type'])){
      $region = M('Geo')
          ->where(array('geo_type' => $_POST['geo_type'], 'geo_parentid' => $_POST['parentid']))
          ->order('geo_firstletter ASC')
          ->select();
      
      if ($_POST['geo_type'] == 2) {
        $targetOptions = '<option value="0">城市</option>';
        $selectedid = $currentU['city'];
      }
      elseif ($_POST['geo_type'] == 3) {
        $targetOptions = '<option value="0">区县</option>';
        $selectedid = $currentU['district'];
      }
      elseif ($_POST['geo_type'] == 4) {
        $targetOptions = '<option value="0">乡镇</option>';
        $selectedid = $currentU['town'];
      }
      
      $targetOptions .= $this->renderRegionOptions($region, $selectedid);
    }
    echo $targetOptions;
  }
  
  /**
   * 根据地区类型，获取地区数组。
   */
  function getGeoArray($geo_type = 1) {
    $geo = D('geo')->where('geo_type=' . $geo_type)->order('geo_firstletter ASC')->select();
    $target = array();
    foreach ($geo as $region) {
      $target[$region['geo_id']] = $region['geo_name'];
    }
    
    return $target;
  }
  
  /**
   * 根据父类ID和类型获取行政区划结果
   */
  public function getRegionName($geo_parentid,$geo_type) {
    $region = M('Geo')
          ->where(array('geo_parentid' => $geo_parentid, 'geo_type' => $geo_type))
          ->select();
    return $region;
  }
}