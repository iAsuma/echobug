<?php
namespace Home\Controller;
use Think\Exception;
/**
 * 部门管理
 * @author lishuaiqiu(asuma)
 */
class DepartmentController extends EntranceController{
    public function index(){
        
        $this->display();
    }
    
    /**
     * 列表
     */
    public function departmentList(){
        $logic = LG('Department');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
        
        $model = D('Department');
        $count = $model -> getDepartmentCount();
        
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getDepartmentList($logic);
        }
        
        $this->assign('list', $list);
        $this->assign('pagecount', $logic->pageCount);
        $this->display();
    }
    
    /**
     * 添加部门页面
     */
    public function add(){
        $model = D('Department');
        $members = $model -> getAllMember();
        
        $this->assign('members', $members);
        $this->display();
    }
    
    /**
     * 添加部门操作
     */
    public function saveAdd(){
        try {
            if(autoCheckToken($_POST)){
                $logic = LG('Department');
                $post = $logic -> dealAddData($_POST);
                
                $model = D('Department');
                $result = $model -> insertDepartment($post);
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
     * 设置部门负责人  弹窗
     */
    public function setting(){
        $model = D('Department');
        
        $id = $_POST['id'];
        $id && $department = $model -> getDepartmentInfo($id);
        
        $members = $model -> getAllMemberById($id);
        
        $this->assign('dpt', $department);
        $this->assign('members', $members);
        $this->display();
    }
    
    /**
     * 保存设置的部门负责人  
     */
    public function setDirector(){
        try {
            $id = $_POST['department_id'];
            $director_id = $_POST['director'];
            
            $model = D('Department');
            $id && $department = $model -> getDepartmentInfo($id);
            empty($department) && exit('0');
            
            $result = $model -> setDepartmentDirector($id, $director_id);
            !$result && exit('-1');
            
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 修改部门
     */
    public function modifyDepartment(){
        try {
            $id = $_POST['id'];
            $name = $_POST['name'];
            
            $model = D('Department');
            $id && $department = $model -> getDepartmentInfo($id);
            empty($department) && exit('0');
            
            $result = $model -> updateDepartment($id, $name);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    /**
     * 删除部门
     */
    public function deleteDepartment(){
        try {
            $id = $_POST['id'];
            
            $model = D('Department');
            $id && $department = $model -> getDepartmentInfo($id);
            empty($department) && exit('0');
            
            $result = $model -> deleteDepartment($id);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
}