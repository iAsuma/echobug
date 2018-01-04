<?php
namespace Home\Model;
class StaffModel extends PublicModel{
    /**
     * 所有部门信息
     */
    public function getAllDepartment(){
        return $this->select_all('department');
    }
    
    /**
     * 成员总数
     */
    public function getStaffCount($obj){
        $sql = "SELECT COUNT(*) AS num FROM member_info mi WHERE mi.status != -1".$obj->wheresql;
        $result = $this->native_select_one($sql, $obj->paramData);
        return $result['num'];
    }
    
    /**
     * 成员列表
     */
    public function getStaffList($obj){
        $sql = "SELECT mi.*,dt.name dname FROM member_info mi LEFT JOIN department dt ON mi.department_id=dt.id WHERE mi.status != -1 ".$obj->wheresql." ORDER BY id DESC".$obj->limitData;
        
        return $this->native_select_all($sql, $obj->paramData);
    }
    
    /**
     * 根据账号查信息
     */
    public function getMemberInfoByAcc($loginName){
        return $this->select_one('member_info', array('login_name' => $loginName), 'id,name');
    }
    
    /**
     * 添加员工
     */
    public function insertStaffInfo($post){
        $data = array(
            'name' => $post['member_name'],
            'login_name' => $post['member_account'],
            'userpwd' => $post['member_pwd'],
            'department_id' => $post['department'],
            'job' => $post['member_job'],
            'type' => $post['member_type'],
            'level' => $post['level'],
            'status' => 1,
            'qq' => $post['qq'],
            'cellphone' => $post['cellphone'],
            'create_time' => time()
        );
        
        return $this->think_insert('member_info', $data);
    }
    
    /**
     * 获取等级信息
     */
    public function getlevel(){
        $levelArr = array(
            array(
                'id' => '4',
                'name' => '四级',
                'tips' => '四级账号无控制台所有权限，一般情况下普通成员，请设置为四级账号，也是推荐的级别'
            ),
            array(
                'id' => '3',
                'name' => '三级',
                'tips' => '无添加项目与模块权限（包括一二级限制的）'
            ),
            array(
                'id' => '2',
                'name' => '二级',
                'tips' => '无添加成员权限（包括一级限制的）'
            ),
            array(
                'id' => '1',
                'name' => '一级',
                'tips' => '一级无添加部门的权限'
            ),
            array(
                'id' => '0',
                'name' => '系统',
                'tips' => '系统拥有最高（即所有）权限'
            )
        );
        
        return $levelArr;
    }
    
    /**
     * 获取成员信息
     */
    public function getMemberInfo($id){
        $sql = "SELECT * FROM member_info WHERE id = :id AND status != -1";
        return $this->native_select_one($sql, array('id' => $id));
    }
    
    /**
     * 判断账号是否存在
     */
    public function getMemberInfoByAccS($loginName, $uid){
        $sql = "SELECT id,name FROM member_info WHERE login_name = :login_name AND id != :id";
        
        return $this->native_select_one($sql, array('login_name' => $loginName, 'id' => $uid));
    }
    
    /**
     * 修改员工
     */
    public function updateStaffInfo($post){
        $data = array(
            'name' => $post['member_name'],
            'login_name' => $post['member_account'],
            'department_id' => $post['department'],
            'job' => $post['member_job'],
            'type' => $post['member_type'],
            'level' => $post['level'],
            'qq' => $post['qq'],
            'cellphone' => $post['cellphone'],
            'update_time' => time()
        );
        
        if($post['pwd_reset']){
            $data['userpwd'] = $post['member_pwd'];
        }
        
        return $this->think_update('member_info', $data, array('id' => $post['member_id']));
    }
    
    /**
     * 删除员工
     */
    public function deleteMember($id){
        return $this->think_update('member_info', array('status' => '-1'), array('id' => $id));
    }
}