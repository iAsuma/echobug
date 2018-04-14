<?php
namespace Home\Controller;
use Think\Exception;
/**
 * 未处理的
 * @author lishuaiqiu(asuma)
 */
class BugUndoneController extends EntranceController{
    public function index(){
        $model = D('BugUndone');
        $prjects = $model -> getAllProject('2,3,-2,-3');
        
        $this->assign('pjt', $prjects);
        $this->display();
    }
    
    /**
     * 列表
     */
    public function undoneList(){
        $_POST['uid'] = $this->uid;
    
        $logic = LG('BugUndone');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
        
        $model = D('BugUndone');
        $count = $model -> getUndoneBugCount($logic);
    
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getUndoneBugList($logic);
        }
        
        $this->assign('list', $list);
        $this->assign('hashpage', $_POST['currpage']);
        $this->assign('pagecount', $logic->pageCount);
        $this->display();
    }
    
    /**
     * 查看BUG（页面）
     */
    public function viewbug(){
        $id = $_GET['id'];
    
        $model = D('BugUndone');
        $id && $buginfo = $model -> getBugInfoById($id, $this->uid);
        empty($buginfo) && $this->jump('你好像在看不该看的', 'error_b');
        
        if($_GET['hashpage']){
            $this->assign('ihash', $_GET['hashpage']);
        }
    
        $this->assign('bug', $buginfo);
        $this->display();
    }
    
    /**
     * 修改Bug状态
     */
    public function changeStatus(){
        try {
            $id = $_POST['id'];
            $status = $_POST['status'];
        
            $model = D('BugUndone');
            $id && $buginfo = $model -> getBugInfoLite($id, $this->uid);
            empty($buginfo) && exit('0');
        
            $result = $model -> updateBugStatus($id, $status);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 关闭
     */
    public function closeBug(){
        try {
            $id = $_POST['id'];
            $notes = $_POST['notes'];
        
            $model = D('BugUndone');
            $id && $buginfo = $model -> getBugInfoLite($id, $this->uid);
            empty($buginfo) && exit('0');
        
            $result = $model -> updateBugStatusInClose($id, $notes);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 移交负责人 弹窗
     */
    public function changeToer(){
        $model = D('BugUndone');
    
        $id = $_POST['id'];
        $id && $buginfo = $model -> getBugInfoLess($id, $this->uid);
    
        $members = $model -> getAllMemberById($buginfo['department_id']);
    
        $this->assign('bug', $buginfo);
        $this->assign('members', $members);
        $this->display();
    }
    
    /**
     * 移交负责人 操作
     */
    public function setToer(){
        try {
            $id = $_POST['bug_id'];
            $toerid = $_POST['toerid'];
    
            $model = D('BugUndone');
            $id && $buginfo = $model -> getBugInfoLess($id, $this->uid);
            empty($buginfo) && exit('0');
    
            $result = $model -> setBugToer($id, $toerid);
            !$result && exit('-1');
    
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
}