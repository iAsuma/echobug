<?php
namespace Home\Controller;
use Think\Exception;
/**
 * 所有的BUG
 * @author lishuaiqiu(asuma)
 */
class BugAllController extends EntranceController{
    public function index(){
        $this->display();
    }
    
    public function allList(){
        if($this->utype > 0){
            $_POST['department'] = $this->department;
        }
        
        $logic = LG('BugAll');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
        
        $model = D('BugAll');
        $count = $model -> getAllBugCount($logic);
        
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getAllBugList($logic);
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
    
        $model = D('BugAll');
        if($this->utype > 0){
            $department = $this->department;
        }
        $id && $buginfo = $model -> getBugInfoById($id, $department);
        empty($buginfo) && $this->jump('你好像在看不该看的', 'error_b');
        
        if($buginfo['status'] == 0 && $buginfo['fromer_id'] == $this->uid){
            $members = $model -> getAllMember($buginfo['department_id']);
            $this->assign('member', $members);
            $this->assign('timestamp', time());
            $this->assign('uid', $this->uid);
        }
    
        if($_GET['hashpage']){
            $this->assign('ihash', $_GET['hashpage']);
        }
    
        $this->assign('bug', $buginfo);
        $this->display();
    }
    
    /**
     * 修改bug 操作
     */
    public function saveModify(){
        try {
            if(autoCheckToken($_POST)){
                $logic = LG('BugAll');
                $model = D('BugAll');
    
                $_POST['uid'] = $this->uid;
                $bug = $model -> getBugStatus($_POST['bugid']);
                $bug['status'] != 0 && exit('-3');
    
                $post = $logic -> dealModifyData($_POST);
    
                $result = $model -> updateBugInfo($post);
                !$result && exit('-1');
    
                tokenRepeatSubmit($_POST);
                exit('1');
            }
            exit('0');
        } catch (Exception $e) {
            exit('-2');
        }
    }
}