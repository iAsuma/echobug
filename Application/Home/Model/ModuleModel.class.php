<?php
namespace Home\Model;
class ModuleModel extends PublicModel{
    protected $autoCheckFields = false;
    
    /**
     * 模块总数
     */
    public function getModuleCount($obj){
        $sql = "SELECT COUNT(*) AS num FROM project_module pm LEFT JOIN project_list pl ON pm.project_id=pl.id LEFT JOIN member_info mi ON pm.director_id=mi.id 
            WHERE pm.status = 1 AND pl.status != -1 ".$obj->wheresql;
        $result = $this->native_select_one($sql, $obj->paramData);
        
        return $result['num'];
    }
    
    /**
     * 模块列表
     */
    public function getModuleList($obj){
        $sql = "SELECT pm.*,pl.name pname,mi.name mname FROM project_module pm 
            LEFT JOIN project_list pl ON pm.project_id=pl.id LEFT JOIN member_info mi ON pm.director_id=mi.id 
            WHERE pm.status = 1 AND pl.status != -1 ".$obj->wheresql." ORDER BY pm.id DESC ".$obj->limitData;
        
        return $this->native_select_all($sql, $obj->paramData);
    }
    
    /**
     * 所有项目
     */
    public function getAllProject($department_id=0){
        if($department_id > 0){
            $sql = "SELECT id,name FROM project_list WHERE status != -1 AND department_id = :department_id";
            $result = $this->native_select_all($sql, array('department_id' => $department_id));
        }else{
            $sql = "SELECT id,name FROM project_list WHERE status != -1";
            $result = $this->native_select_all($sql);
        }
        
        return $result;
    }
    
    /**
     * 所有成员
     */
    public function getAllMember($department_id=0){
        if($department_id > 0){
            $sql = "SELECT id,name FROM member_info WHERE status = 1 AND type > 0 AND department_id = :department_id";
            $result = $this->native_select_all($sql, array('department_id' => $department_id));
        }else{
            $sql = "SELECT id,name FROM member_info WHERE status = 1 AND type > 0";
            $result = $this->native_select_all($sql);
        }
        
        return $result;
    }
    
    /**
     * 根据ID查询项目信息
     */
    public function getProjectInfo($project_id){
        $sql = "SELECT * FROM project_list WHERE status != -1 AND id = :project_id";
        return $this->native_select_one($sql, array('project_id' => $project_id));
    }
    
    /**
     * 根据ID查询用户信息
     */
    public function getMemberInfo($director_id){
        $sql = "SELECT * FROM member_info WHERE status = 1 AND id = :director_id";
        
        return $this->native_select_one($sql, array('director_id' => $director_id));
    }
    
    /**
     * 获取最近添加的模块
     */
    public function getHistoryModule($idsArr){
        $idstr = implode(",", $idsArr);
        if($idstr){
            $sql = "SELECT pm.id,pm.name,pl.name pname,mi.name mname FROM project_module pm 
            LEFT JOIN project_list pl ON pm.project_id=pl.id LEFT JOIN member_info mi ON pm.director_id=mi.id 
            WHERE pm.status = 1 AND pl.status != -1 AND pm.id IN($idstr) ORDER BY pm.id DESC LIMIT 0, 20";
            
            $result = $this->native_select_all($sql);
        }
        
        return $result;
    }
    
    /**
     * 删除模块
     */
    public function deleteModById($id){
        return $this->think_update('project_module', array('status' => '-1'), array('id' => $id));
    }
    
    /**
     * 新增模块
     */
    public function insertModuleInfo($post){
        $data = array(
            'name' => $post['module_name'],
            'project_id' => $post['project'],
            'director_id' => $post['director'],
            'status' => 1
        );
        
        return $this->think_insert('project_module', $data, false, true);
    }
    
    /**
     * 模块信息
     */
    public function getModuleInfo($id){
        return $this->select_one('project_module', array('status' => 1, 'id' => $id));
    }
    
    /**
     * 模块信息
     */
    public function getModuleInfoS($id){
        $sql = "SELECT pm.*,pl.department_id FROM project_module pm LEFT JOIN project_list pl ON pm.project_id=pl.id WHERE pm.status = 1 AND pm.id = :id";
        return $this->native_select_one($sql, array('id' => $id));
    }
    
    /**
     * 部门内所有成员
     */
    public function getAllMemberById($department_id){
        $result = $this->select_all('member_info', array('status' => 1, 'department_id' => $department_id), 'id,name');
        return $result;
    }
    
    /**
     * 修改模块
     */
    public function updateModulet($id, $name){
        return $this->think_update('project_module', array('name' => $name), array('id' => $id));
    }
    
    /**
     * 设置模块负责人
     */
    public function setModuleDirector($id, $director_id){
        return $this->think_update('project_module', array('director_id' => $director_id), array('id' => $id));
    }
    
    /**
     * 删除模块
     */
    public function deleteModule($id){
        return $this->think_update('project_module', array('status' => '-1'), array('id' => $id));
    }
}