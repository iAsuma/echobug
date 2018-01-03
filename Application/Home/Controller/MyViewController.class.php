<?php
namespace Home\Controller;
use Think\Exception;
class MyViewController extends EntranceController{
    public function index(){
        $model = D('MyView');
        if($this->utype == 2 || $this->utype == 3){
            $upper = $model -> getUndoneBugList($this->uid);
        }else{
            $upper = $model -> getMySendBugList($this->uid);
        }
        
        $this->assign('upper', $upper);
        $this->display();
    }
    
    public function contentList(){
        if($this->utype > 0){
            $_POST['department'] = $this->department;
        }
        
        $logic = LG('MyView');
        $logic->loadTable($_POST, $_POST['currpage'], 20);
        
        $model = D('MyView');
        $count = $model -> getAllBugCount($logic);
        
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getAllBugList($logic);
        }
        
        $this->assign('allbug', $list);
        
        $ret_arr = array(
            'content' => $this->fetch(),
            'pages' => $logic->pageCount
        );
        
        exit(json_encode($ret_arr));
    }
    
    public function content(){
        $model = D('MyView');
        $mysend_count = $model -> getMySendBugCount($this->uid);
        $mysend = $model -> getMySendBugList($this->uid);
        //pr($mysend);die;
        if($this->utype == 2 || $this->utype == 3){
            $mark = 1;
        }else{
            $mark = 2;
        }
        $this->assign('mysend', $mysend);
        $this->assign('mark', $mark);
        $this->display();
    }
    
    private function undoneList(){
        
        $this->display();
    }
    
    /**
     * 反馈bug--页面
     */
    public function feedback(){
        $model = D('MyView');
        $projects = $model -> getAllProject();
        
        $this->assign('pjt', $projects);
        $this->assign('timestamp', time());
        $this->assign('uid', $this->uid);
        $this->display();
    }
    
    /**
     * 模块选项列表（反馈bug--页面）
     */
    public function moduleList(){
        $project_id = $_POST['id'];
        $model = D('MyView');
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
        $model = D('MyView');
    
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
                $logic = LG('MyView');
                $model = D('MyView');
    
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
    
    public function viewbug(){
        $id = $_GET['id'];
        
        $model = D('MyView');
        $id && $buginfo = $model -> getBugInfoById($id, $this->uid, $this->department);
        
        empty($buginfo) && $this->jump('你好像在看不该看的');
        
        $this->assign('bug', $buginfo);
        $this->assign('userid', $this->uid);
        $this->display();
    }
}