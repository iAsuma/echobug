<?php
namespace Home\Controller;
use Think\Exception;
/**
 * 成员管理
 * @author lishuaiqiu(asuma)
 */
class StaffController extends EntranceController{
    public function index(){
        $model = D('Staff');
        $department = $model -> getAllDepartment();
        
        $this->assign('dpat', $department);
        $this->display();
    }
    
    /**
     * 成员列表
     */
    public function staffList(){
        $logic = LG('Staff');
        $logic->loadTable($_POST, $_POST['currpage'], 15);
        
        $model = D('Staff');
        $count = $model -> getStaffCount($logic);
        
        if($count){
            $logic->setPageCount($count);
            $list = $model -> getStaffList($logic);
        }
        
        $this->assign('list', $list);
        $this->assign('hashpage', $_POST['currpage']);
        $this->assign('pagecount', $logic->pageCount);
        $this->display();
    }
    
    /**
     * 添加成员
     */
    public function add(){
        $model = D('Staff');
        $department = $model -> getAllDepartment();
        $levelArr = $model -> getlevel();
        
        $ulevel = $this->level;
        
        $this->assign('emailpos', C('DEFAULT_ACC_POS'));
        $this->assign('defaultpwd', C('DEFAULT_ACC_PWD'));
        $this->assign('dpat', $department);
        $this->assign('lvl', $levelArr);
        $this->assign('ulevel', $ulevel);
        $this->display();
    }
    
    /**
     * 保存添加
     */
    public function saveAdd(){
        try {
            if(autoCheckToken($_POST)){
                $logic = LG('Staff');
                $model = D('Staff');
                
                $post = $logic -> dealAddData($_POST, $model);
                
                $result = $model -> insertStaffInfo($post);
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
        $model = D('Staff');
        
        $id = $_GET['id'];
        $id && $member_info = $model -> getMemberInfo($id);
        empty($member_info) && $this->jump('你好像在看不该看的', 'error_b');
        
        $department = $model -> getAllDepartment();
        $levelArr = $model -> getlevel();
        
        if($_GET['hashpage']){
            $this->assign('ihash', $_GET['hashpage']);
        }
        
        
        $this->assign('member', $member_info);
        $this->assign('repwd', C('RESET_ACC_PWD'));
        $this->assign('dpat', $department);
        $this->assign('lvl', $levelArr);
        $this->assign('ulevel', $this->level);
        $this->display();
    }
    
    /**
     * 修改成员信息
     */
    public function modifyMember(){
        try {
            if(autoCheckToken($_POST)){
                $logic = LG('Staff');
                $model = D('Staff');
                
                $post = $logic -> dealUpdateData($_POST, $model);
        
                $result = $model -> updateStaffInfo($post);
                !$result && exit('-1');
        
                tokenRepeatSubmit($_POST);
                exit('1');
            }
            exit('0');
        } catch (Exception $e) {
            exit('-2');
        }
    }
    
    public function setStaffLevel(){
        
    }
    
    /**
     * 删除成员
     */
    public function deleteStaff(){
        try {
            $id = $_POST['id'];
        
            $model = D('Staff');
            $id && $staff = $model -> getMemberInfo($id);
            empty($staff) && exit('0');
        
            $result = $model -> deleteMember($id);
            !$result && exit('-1');
        
            exit('1');
        } catch (Exception $e) {
            exit('-2');
        }
    }
}