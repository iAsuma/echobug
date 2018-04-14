<?php
namespace Home\Controller;
use Think\Exception;
/**
 * 项目管理
 * @author lishuaiqiu(asuma)
 */
class ProjectController extends EntranceController{
    public function index(){
        $this->display();
    }
    
    /**
     * 项目列表
     */
    public function projectList(){
        $logic = LG('Project');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
        
        $model = D('Project');
        $count = $model -> getProjectCount($logic);
        
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getProjectList($logic);
        }
        
        $this->assign('list', $list);
        $this->assign('hashpage', $_POST['currpage']);
        $this->assign('dpt', $this->department);
        $this->assign('pagecount', $logic->pageCount);
        $this->display();
    }
    
    /**
     * 新增项目页面
     */
    public function add(){
        $utype = $this->utype;
        $model = D('Project');
        
        if($utype == 0){
            $depart = $model -> getAllDepartment();
        }else{
            $depart = $model -> getDepartmentInfo($this->department);
        }
        
        $this->assign('utype', $utype);
        $this->assign('depart', $depart);
        $this->display();
    }
    
    /**
     * 新增项目页面 成员列表
     */
    public function memberlist(){
        $department_id = $_POST['id'];
        $director_id = $_POST['mid'];
        
        $model = D('Project');
        $members = $model -> getAllMemberInDepart($department_id);
        
        $html = '<select name="director" lay-search lay-verify="required">';
        
        if(empty($members)){
            $html .= '<option value="">无</option>';
        }else{
            $html .= '<option value="">可输入搜索</option>';
            foreach ($members as $v){
                if($v['id'] == $director_id){
                    $sel = ' selected ';
                }else{
                    $sel = '';
                }
                $html .= '<option value="'.$v['id'].'"'.$sel.'>'.$v['name'].'</option>';
            }
        }
        
        $html .= '</select>';
        
        exit($html);
    }
    
    /**
     * 新增项目 操作
     */
    public function saveAdd(){
        try {
            if(autoCheckToken($_POST)){
                $logic = LG('Project');
                $depart = D('Project');
                
                $post = $logic -> dealAddData($_POST, $depart);
                
                $result = $depart -> insertProjectInfo($post);
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
     * 成员信息详情
     */
    public function details(){
        $model = D('Project');
        
        $id = $_GET['id'];
        $id && $project_info = $model -> getProjectInfoById($id);
        empty($project_info) && $this->jump('你好像在看不该看的', 'error_b');
        
        $utype = $this->utype;
        $dpt_id = $this->department;
        
        if($utype == 0 || ($utype < 4 && $project_info['department_id'] == $dpt_id)){
           $depart = $model -> getAllDepartment();
        }else{
           $depart = $model -> getDepartmentInfo($project_info['department_id']);
        }
         
        if($_GET['hashpage']){
            $this->assign('ihash', $_GET['hashpage']);
        }
        
        $this->assign('utype', $utype);
        $this->assign('depart', $depart);
        $this->assign('dpt_id', $dpt_id);
        $this->assign('pjt', $project_info);
        $this->display();
    }
    
    /**
     * 修改项目
     */
    public function saveModify(){
        try {
            if(autoCheckToken($_POST)){
                $logic = LG('Project');
                $model = D('Project');
        
                $post = $logic -> dealAddData($_POST, $model);
        
                $result = $model -> updateProjectInfo($post);
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
     * 设置项目状态
     */
    public function setting(){
        $id = $_POST['id'];
        
        $model = D('Project');
        $id && $project_info = $model -> getProjectInfoById($id, $this->department);
        
        empty($project_info) && exit('你好像在看不该看的');
        
        $this->assign('pjt', $project_info);
        $this->display();
    }
    
    public function setStatus(){
        try {
            $id = $_POST['project_id'];
            $status = $_POST['status'];
        
            $model = D('Project');
            $id && $project_info = $model -> getProjectInfoById($id, $this->department);
            empty($project_info) && exit('-3');
            
            $result = $model -> setProjectStatus($id, $status, $project_info['status'], $project_info['laststatus']);
            !$result && exit('-1');
            
            exit('1');
        } catch (Exception $e) {
            throw_exception($e->getMessage());
            exit('-2');
        }
    }
    
    /**
     * 删除项目
     */
    public function deleteProject(){
        try {
            $id = $_POST['id'];
        
            $model = D('Project');
            $id && $project_info = $model -> getProjectInfoById($id);
            empty($project_info) && exit('0');
        
            $result = $model -> deleteProject($id);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
}