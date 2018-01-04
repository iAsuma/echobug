<?php
namespace Home\Model;
class DepartmentModel extends PublicModel{
    /**
     * 部门总数
     */
    public function getDepartmentCount(){
        $result = $this->think_count('department');
        return $result;
    }
    
    /**
     * 部门列表
     */
    public function getDepartmentList($obj){
        $sql = "SELECT dt.*,mi.name mname FROM department dt LEFT JOIN member_info mi ON dt.director_id=mi.id ORDER BY dt.id DESC".$obj->limitData;
        $result = $this->native_select_all($sql);
        return $result;
    }
    
    /**
     * 添加部门
     */
    public function insertDepartment($post){
        $data = array('name' => $post['dep_name'], 'director_id' => $post['director']);
        $result = $this->think_insert('department', $data, true);
        
        return $result;
    }
    
    /**
     * 所有成员
     */
    public function getAllMember(){
        $result = $this->select_all('member_info', array('status' => 1), 'id,name');
        return $result;
    }
    
    /**
     * 部门信息
     */
    public function getDepartmentInfo($id){
        $result = $this->select_one('department', array('id' => $id));
        return $result;
    }
    
    /**
     * 部门内所有成员
     */
    public function getAllMemberById($id){
        $result = $this->select_all('member_info', array('status' => 1, 'department_id' => $id), 'id,name');
        return $result;
    }
    
    /**
     * 设置部门负责人
     */
    public function setDepartmentDirector($id, $director_id){
        return $this->think_update('department', array('director_id' => $director_id), array('id' => $id));
    }
    
    /**
     * 修改部门信息
     */
    public function updateDepartment($id, $name){
        return $this->think_update('department', array('name' => $name), array('id' => $id));
    }
    
    /**
     * 删除部门
     */
    public function deleteDepartment($id){
        return $this->think_delete('department', array('id' => $id));
    }
}