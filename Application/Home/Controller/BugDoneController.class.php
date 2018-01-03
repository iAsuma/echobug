<?php
namespace Home\Controller;
/**
 * 已处理的
 * @author lishuaiqiu(asuma)
 */
class BugDoneController extends EntranceController{
    public function index(){
        $model = D('BugDone');
        $prjects = $model -> getAllProject('2,3,-2,-3');
        
        $this->assign('pjt', $prjects);
        $this->display();
    }
    
    /**
     * 列表
     */
    public function doneList(){
        $_POST['uid'] = $this->uid;
        
        $logic = LG('BugDone');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
        
        $model = D('BugDone');
        $count = $model -> getDoneBugCount($logic);
        
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getDoneBugList($logic);
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
    
        $model = D('BugDone');
        $id && $buginfo = $model -> getBugInfoById($id, $this->uid);
        empty($buginfo) && $this->jump('你好像在看不该看的', 'error_b');
    
        if($_GET['hashpage']){
            $this->assign('ihash', $_GET['hashpage']);
        }
    
        $this->assign('bug', $buginfo);
        $this->display();
    }
}