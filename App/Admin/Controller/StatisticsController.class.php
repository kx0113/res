<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class StatisticsController extends BaseController {
    public $statistics;
    public function _initialize(){
        parent::_initialize();
        //模型初始化
        $this->statistics = D('Statistics');
    }
    //首页
    public function index(){
        $this->display();
    }
    //存入数字
    // 1 每日邀约数量
    // 2 面试数量
    // 3 面试通过数量
    public function addNum(){
        if(empty($_REQUEST['statistics_num'])){
            $this->resMsg('非法操作！','/index',1);
            exit;
        }
        $data['statistics_num'] = intval($_REQUEST['statistics_num']);
        $data['statistics_time'] = date('Y-m-d',time());
        $data['statistics_add_time'] = time();
        $data['statistics_user'] = $this->cid;
        $data['statistics_class'] = $_REQUEST['statistics_class'];
        $res = $this->statistics->AddData($data);
        if($res){
            switch ($_REQUEST['statistics_class']){
                case 1:
                    $this->VisitOperation('录入每日邀约数量['.$res.','.$_REQUEST['statistics_num'].']',1);
                    break;
                case 2:
                    $this->VisitOperation('录入面试数量['.$res.','.$_REQUEST['statistics_num'].']',1);
                    break;
                case 3:
                    $this->VisitOperation('录入面试通过数量['.$res.','.$_REQUEST['statistics_num'].']',1);
                    break;
                default:
                    $this->VisitOperation('非法操作');
            }
            $json['result'] = 'success';
        } else{
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }
    //修改状态
    public function show_change()
    {
        $where['statistics_id'] = $_REQUEST['statistics_id'];
        $data['statistics_status'] = $_REQUEST['statistics_status'];
        $res = M('Statistics')->where($where)->data($data)->save();
        if($res){
            $json['res'] = 'suc';
        }else{
            $json['res'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    public function statcontent(){
        $statistics_time = $_REQUEST['statistics_time'];
        $statistics_class = $_REQUEST['statistics_class'];
        $where = array('statistics_time'=>$statistics_time,'statistics_class'=>$statistics_class);
        $res = M('Statistics')->where($where)->order('statistics_id ASC')->select();
        foreach($res as $k=>$v){
            $admin_id[]=$v['statistics_user'];
        }
        $admin = M('admin')->field('id,account')->where(array('id'=>array('in',$admin_id)))->select();
        foreach($res as $k1=>$v1){
            foreach($admin as $k12=>$v12){
                if($v12['id']==$v1['statistics_user']){
                    $res[$k1]['admin_user'] = $v12['account'];
                }
            }
        }
        $this->assign('list',$res);
        $this->assign('statistics_class',$statistics_class);
        $this->assign('statistics_time',$statistics_time);
        $this->display();
    }
    //查看统计数量
    public function quantity(){
        $statistics_class = $_REQUEST['statistics_class'];
        switch ($statistics_class){
            case 1:
                $this->VisitOperation('每日邀约数量');
                break;
            case 2:
                $this->VisitOperation('面试数量');
                break;
            case 3:
                $this->VisitOperation('面试通过数量');
                break;
            default:
                $this->VisitOperation('招聘统计');
        }
        $sql = "SELECT COUNT(*) countr,statistics_time,SUM(statistics_num) statistics_num
FROM `tp_statistics`
WHERE statistics_class = {$statistics_class}
AND statistics_status = 1
GROUP BY statistics_time
ORDER BY statistics_time ASC  ";
        //---------------------------------
        $sq = "SELECT
  month_num,
  IFNULL(num, 0) AS sume,
 IFNULL(snum, 0) AS  snumr
FROM
  tp_month_num m
  LEFT JOIN
    (SELECT
      DAY(statistics_time) AS d,
      COUNT(*) AS num
    FROM
      tp_statistics
    WHERE YEAR(statistics_time) = '2016'
      AND MONTH(statistics_time) = '06'
       AND statistics_class =1
    GROUP BY DAY(statistics_time)) s
    ON m.month_num = s.d
  LEFT JOIN
    (SELECT
      DAY(statistics_time) AS d,
      SUM(statistics_num) AS snum
    FROM
      tp_statistics
    WHERE YEAR(statistics_time) = '2016'
      AND MONTH(statistics_time) = '06'
      AND statistics_class =1
    GROUP BY DAY(statistics_time)) t
    ON m.month_num = t.d
WHERE m.month_num <= DAY(
    LAST_DAY(CONCAT('2016', '-', '06', '-', '1'))
  )
ORDER BY m.month_num ASC ";
        $res = M()->query($sql);
      //  dump($res);
        $this->assign('list',$res);
        $this->assign('statistics_class',$statistics_class);
        $this->display();
    }
    public function index1(){
        $where['statistics_status'] = 1;
        $name = I('name');
        //搜索
        if($name){
            $where['statistics_name'] = array("like","%".$name."%");
        }
        $count = $this->statistics->where($where)->count();
        if($name){
            if($count == 0){
                $this->assign('not_found',2);
            }
        }else{
            if($count == 0){
                $this->assign('not_found',1);
            }
        }

        $page = new Page($count,10);
        $res = $this->statistics->where($where)
            ->order('statistics_id DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        //视图展示数据集
        $this->assign('page', $page->show());
        $this->assign('name',$name);
        $this->assign('list',$res);
        $this->display();
    }

    //修改
    public function EditData() {
        if(empty($_REQUEST['statistics_id'])){ exit; }
      if($_POST){
          $where['statistics_id'] = I('statistics_id');
          $data['statistics_name'] = $_REQUEST['statistics_name'];
          $data['statistics_sort'] = $_REQUEST['statistics_sort'];
          $data['statistics_time'] = time();
          if($this->statistics->create()){
              if($this->statistics->statisticsSaveData($where,$data)){
                  $this->resMsg(C("operation_succ_status"),'/index',1);
                  exit;
              }else{
                  $this->resMsg(C("operation_error_status"),'/index',1);
                  exit;
              }
          }
          else{
              $error = $this->statistics->getError();
              $this->resMsg($error,'',2);
              exit;
          }
      }
        $where['statistics_id'] = $_REQUEST['statistics_id'];
        $res = $this->statistics->where($where)->find();
        $this->assign('list',$res);
        $this->display('add');
    }

    //添加
    public function add(){
        if($_POST){
            $data['statistics_name'] = trim($_REQUEST['statistics_name']);
            $data['statistics_sort'] = $_REQUEST['statistics_sort'];
            $data['statistics_time'] = time();
            if($this->statistics->create($data)){
                if($this->statistics->statisticsAddData($data)){
                    $this->resMsg(C("operation_succ_status"),'/index',1);
                    exit;
                }else{
                    $this->resMsg(C("operation_error_status"),'/index',1);
                }
            }
            else{
                 $error = $this->statistics->getError();
                $this->resMsg($error,'',2);
                exit;
            }
        }
        $this->display();
    }
}