<?php
namespace Home\Controller;
use Think\Exception;
/**
 * 我反馈的
 * @author lishuaiqiu(asuma)
 */
class BugMysendController extends EntranceController{
    public function index(){
        $model = D('BugMysend');
        $prjects = $model -> getAllProject('2,3,-2,-3');
    
        $this->assign('pjt', $prjects);
        $this->display();
    }
    
    /**
     * BUG列表（我反馈的--页面）
     */
    public function mysendList(){
        $_POST['uid'] = $this->uid;
    
        $logic = LG('BugMysend');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
    
        $model = D('BugMysend');
        $count = $model -> getMySendBugCount($logic);
    
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getMySendBugList($logic);
        }
        
        $this->assign('list', $list);
        $this->assign('hashpage', $_POST['currpage']);
        $this->assign('pagecount', $logic->pageCount);
        $this->display();
    }
    
    /**
     * 反馈bug（页面）
     */
    public function feedback(){
        $model = D('BugMysend');
        $prjects = $model -> getAllProject();
    
        $this->assign('pjt', $prjects);
        $this->assign('timestamp', time());
        $this->assign('uid', $this->uid);
        $this->display();
    }
    
    /**
     * 模块选项列表（反馈bug--页面）
     */
    public function moduleList(){
        $project_id = $_POST['id'];
        $model = D('BugMysend');
        $modules = $model -> getAllModule($project_id);
    
        $html = '<select name="module" lay-search lay-filter="moduleChange" lay-verify="required">';
    
        if(empty($modules)){
            $html .= '<option value="">无</option>';
        }else{
            $html .= '<option value="">可输入搜索</option>';
            foreach ($modules as $v){
                $html .= '<option value="'.$v['id'].'" data-mid="'.$v['director_id'].'">'.$v['name'].'</option>';
            }
        }
    
        $html .= '</select>';
    
        exit($html);
    }
    
    /**
     * 成员选项列表（反馈bug--页面）
     */
    public function memberList(){
        $project_id = $_POST['id'];
        $model = D('BugMysend');
    
        $project = $model -> getProjectInfo($project_id);
        $members = $model -> getAllMember($project['department_id']);
    
        $html = '<select name="director" lay-search lay-verify="required">';
    
        if(empty($members)){
            $html .= '<option value="">无</option>';
        }else{
            $html .= '<option value="">可输入搜索</option>';
            foreach ($members as $v){
                $html .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
            }
        }
    
        $html .= '</select>';
    
        exit($html);
    }
    
    /**
     * 提交反馈操作（反馈bug--页面）
     */
    public function saveAdd(){
        try {
            if(autoCheckToken($_POST)){
                $logic = LG('BugMysend');
                $model = D('BugMysend');
    
                $_POST['uid'] = $this->uid;
                $post = $logic -> dealAddData($_POST);
    
                $result = $model -> insertBugInfo($post);
                !$result && exit('-1');
    
                tokenRepeatSubmit($_POST);
                exit('1');
            }
            exit('0');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 查看BUG（页面）
     */
    public function viewbug(){
        $id = $_GET['id'];
        
        $model = D('BugMysend');
        $id && $buginfo = $model -> getBugInfoById($id, $this->uid);
        empty($buginfo) && $this->jump('你好像在看不该看的', 'error_b');
        
        if($buginfo['status'] == 0){
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
                $logic = LG('BugMysend');
                $model = D('BugMysend');
        
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
    
    /**
     * 移交负责人 弹窗
     */
    public function changeToer(){
        $model = D('BugMysend');
        
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
        
            $model = D('BugMysend');
            $id && $buginfo = $model -> getBugInfoLess($id, $this->uid);
            empty($buginfo) && exit('0');
        
            $result = $model -> setBugToer($id, $toerid);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 删除Bug
     */
    public function deleteMysend(){
        try {
            $id = $_POST['id'];
        
            $model = D('BugMysend');
            $id && $buginfo = $model -> getBugInfoLite($id, $this->uid);
            empty($buginfo) && exit('0');
        
            $result = $model -> deleteMysendBug($id);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
}