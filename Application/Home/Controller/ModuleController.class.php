<?php
namespace Home\Controller;
use Think\Exception;
/**
 * 模块管理
 * @author lishuaiqiu(asuma)
 */
class ModuleController extends EntranceController{
    public function index(){
        $model = D('Module');
        $projects = $model -> getAllProject($this->department);
        
        $this->assign('pjt', $projects);
        $this->display();
    }
    
    /**
     * 模块列表
     */
    public function moduleList(){
        if($this->department != 0){
            $_POST['department'] = $this->department;
        }
        
        $logic = LG('Module');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
        
        $model = D('Module');
        $count = $model -> getModuleCount($logic);
        
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getModuleList($logic);
        }
        
        $this->assign('list', $list);
        $this->assign('pagecount', $logic->pageCount);
        $this->display();
    }
    
    /**
     * 添加模块页面
     */
    public function add(){
        $model = D('Module');
        $projects = $model -> getAllProject($this->department);
        
        if($this->utype != 0){
            $members = $model -> getAllMember($this->department);
            $this->assign('members', $members);
        }
        
        $cookie = cookie('his_mod');
        if($cookie){
            $cookieArr = explode("_", $cookie);
            $his_mod = $model -> getHistoryModule($cookieArr);
            
            $this->assign('hismod', $his_mod);
        }
        
        $this->assign('utype', $this->utype);
        $this->assign('pjt', $projects);
        $this->display();
    }
    
    /**
     * 添加模块页面 成员列表
     */
    public function memberlist(){
        $project_id = $_POST['pid'];
        $model = D('Module');
        
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
     * 添加模块页面 删除最近添加的模块 
     */
    public function delLatestMod(){
        try {
            $id = $_POST['id'];
            $model = D('Module');
            
            $result = $model -> deleteModById($id);
            !$result && exit('-1');
            
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 添加模块 操作
     */
    public function saveAdd(){
        try {
            $logic = LG('Module');
            $model = D('Module');
            
            $post = $logic -> dealAddData($_POST, $model);
            $result = $model -> insertModuleInfo($post);
            !$result && exit('-1');
            
            $cookie = cookie('his_mod');
            $new_cookie = empty($cookie) ? $result : $cookie.'_'.$result;
            cookie('his_mod', $new_cookie);
            
            exit($result);
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 修改模块
     */
    public function modifyModule(){
        try {
            $id = $_POST['id'];
            $name = $_POST['name'];
        
            $model = D('Module');
            $id && $module = $model -> getModuleInfo($id);
            empty($module) && exit('0');
        
            $result = $model -> updateModulet($id, $name);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 设置部门负责人  弹窗
     */
    public function setting(){
        $model = D('Module');
    
        $id = $_POST['id'];
        $id && $module = $model -> getModuleInfoS($id);
    
        $members = $model -> getAllMemberById($module['department_id']);
        
        $this->assign('module', $module);
        $this->assign('members', $members);
        $this->display();
    }
    
    /**
     * 保存设置的模块负责人
     */
    public function setDirector(){
        try {
            $id = $_POST['module_id'];
            $director_id = $_POST['director'];
    
            $model = D('Module');
            $id && $module = $model -> getModuleInfo($id);
            empty($module) && exit('0');
    
            $result = $model -> setModuleDirector($id, $director_id);
            !$result && exit('-1');
    
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 删除模块
     */
    public function deleteModule(){
        try {
            $id = $_POST['id'];
        
            $model = D('Module');
            $id && $module = $model -> getModuleInfo($id);
            empty($module) && exit('0');
        
            $result = $model -> deleteModule($id);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
}