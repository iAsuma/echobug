<?php
namespace Home\Model;
class ProjectModel extends PublicModel{
    protected $autoCheckFields = false;
    
    /**
     * 项目总数
     */
    public function getProjectCount($obj){
        $sql = "SELECT COUNT(*) AS num FROM project_list pl LEFT JOIN member_info mi ON pl.director_id=mi.id WHERE pl.status != -1 ".$obj->extwherestr.$obj->wheresql;
        $result = $this->native_select_one($sql, $obj->paramData);
        return $result['num'];
    }
    
    /**
     * 项目列表
     */
    public function getProjectList($obj){
        $sql = "SELECT pl.*,mi.name mname,dt.name dname FROM project_list pl 
            LEFT JOIN member_info mi ON pl.director_id=mi.id LEFT JOIN department dt ON pl.department_id=dt.id 
            WHERE pl.status != -1 ".$obj->extwherestr.$obj->wheresql." ORDER BY pl.id DESC ".$obj->limitData;
        
        return $this->native_select_all($sql, $obj->paramData);
    }
    
    /**
     * 所有部门
     */
    public function getAllDepartment(){
        return $this->select_all('department', '', 'id,name');
    }
    
    /**
     * 根据ID查询部门信息
     */
    public function getDepartmentInfo($department_id){
        $department = $this->select_one('department', array('id' => $department_id), 'id,name');
        if($department_id != $department['id']) return false;
        
        return $department;
    }
    
    /**
     * 除管理员外的所有成员
     */
    public function getAllMember(){
        $sql = "SELECT * FROM member_info WHERE type != 0 ";
        return $this->native_select_all($sql);
    }
    
    /**
     * 根据部门ID的查询所有成员
     */
    public function getAllMemberInDepart($department_id){
        $memberinfo = $this->select_all('member_info', array('department_id' => $department_id), 'id,name');
        return $memberinfo;
    }
    
    /**
     * 根据ID获取成员信息
     */
    public function getMemberInfo($member_id){
        $member = $this->select_one('member_info', array('id' => $member_id), 'id,name');
        return $member;
    }
    
    /**
     * 新增项目
     */
    public function insertProjectInfo($post){
        $data = array(
            'name' => $post['project_name'],
            'codename' => $post['code_name'],
            'director_id' => $post['director'],
            'create_time' => time(),
            'description' => $post['description'],
            'status' => $post['status'],
            'start_time' => $post['startdate'],
            'end_time' => $post['enddate'],
            'department_id' => $post['department'],
            'isview' => $post['isview']
        );
        
        return $this->think_insert('project_list', $data);
    }
    
    /**
     * 根据id查询项目信息
     */
    public function getProjectInfoById($id, $department_id){
        $sql = "SELECT * FROM project_list WHERE id = :id AND status != -1";
        
        if($department_id){
            $sql .= ' AND department_id = '.$department_id;
        }
        
        return $this->native_select_one($sql, array('id' => $id));
    }
    
    /**
     * 修改项目信息
     */
    public function updateProjectInfo($post){
        $data = array(
            'name' => $post['project_name'],
            'codename' => $post['code_name'],
            'director_id' => $post['director'],
            'description' => $post['description'],
            'start_time' => $post['startdate'],
            'end_time' => $post['enddate'],
            'department_id' => $post['department'],
            'isview' => $post['isview']
        );
        
        return $this->think_update('project_list', $data, array('id' => $post['project_id']));
    }
    
    public function setProjectStatus($id, $status, $project_status, $laststatus){
        if($status){
            $data = array(
                'status' => $status,
                'laststatus' => $project_status
            );
        }else{
            if($laststatus == -2 || $laststatus == -3 || $laststatus == ""){
                $data = array(
                    'status' => 0,
                    'laststatus' => $project_status
                );
            }else{
                $data = array(
                    'status' => $laststatus,
                    'laststatus' => $project_status
                );
            }
        }
        
        
        return $this->think_update('project_list', $data, array('id' => $id));
    }
    
    /**
     * 删除项目
     */
    public function deleteProject($id){
        return $this->think_update('project_list', array('status' => '-1'), array('id' => $id));
    }
}