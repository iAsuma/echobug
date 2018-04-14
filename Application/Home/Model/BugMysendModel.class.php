<?php
namespace Home\Model;
class BugMysendModel extends PublicModel{
    /**
     * 所有项目
     */
    public function getAllProject($status = '2,3'){
        $sql = "SELECT id,name,codename FROM project_list WHERE status IN($status)";
        return $this->native_select_all($sql);
    }
    
    /**
     * 项目的所有模块
     */
    public function getAllModule($project_id){
        $sql = "SELECT id,name,director_id FROM project_module WHERE project_id = :project_id AND status =1";
        return $this->native_select_all($sql, array('project_id' => $project_id));
    }
    
    /**
     * 根据ID查询项目信息
     */
    public function getProjectInfo($project_id){
        $sql = "SELECT * FROM project_list WHERE status != -1 AND id = :project_id";
        return $this->native_select_one($sql, array('project_id' => $project_id));
    }
    
    /**
     * 所有成员
     */
    public function getAllMember($department_id=0){
        $sql = "SELECT id,name FROM member_info WHERE status = 1 AND department_id = :department_id";
        return $this->native_select_all($sql, array('department_id' => $department_id));
    }
    
    /**
     * 新增BUG
     */
    public function insertBugInfo($post){
        $data = array(
            'summary' => $post['summary'],
            'content' => $post['content'],
            'status' => 0,
            'project_id' => $post['project'],
            'modular_id' => $post['module'],
            'fromer_id' => $post['uid'],
            'toer_id' => $post['director'],
            'weight' => $post['weight'],
            'create_time' => time(),
            'test_environment' => $post['test_environment']
        );
        
        return $this->think_insert('buglist', $data);
    }
    
    /**
     * 修改BUG
     */
    public function updateBugInfo($post){
        $data = array(
            'summary' => $post['summary'],
            'content' => $post['content'],
            'status' => 0,
            'toer_id' => $post['director'],
            'weight' => $post['weight'],
            'note' => time(),
            'test_environment' => $post['test_environment']
        );
        
        return $this->think_update('buglist', $data, array('id' => $post['bugid']));
    }
    
    /**
     * 根据ID查询bug信息
     * @param $id bug id
     * @param $uid 用户id
     */
    public function getBugInfoById($id, $uid){
        $sql = "SELECT bg.id,bg.status,bg.summary,bg.content,bg.weight,bg.project_id,bg.toer_id,bg.finall_time,bg.test_environment,bg.note,bg.create_time,
            pl.name pname,pl.department_id,pl.codename,pm.director_id,pm.name modname,mb.name fromname,mi.name toname,mis.name mname 
            FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mb ON bg.fromer_id=mb.id 
            LEFT JOIN member_info mi ON bg.toer_id=mi.id 
            LEFT JOIN member_info mis ON pm.director_id=mis.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 AND bg.id = :id AND bg.fromer_id = :fromer_id";
        return $this->native_select_one($sql, array('id' => $id, 'fromer_id' => $uid));
    }
    
    /**
     * 我反馈的 总数
     */
    public function getMySendBugCount($obj){
        $sql = "SELECT COUNT(*) AS num FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mi ON bg.toer_id=mi.id 
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1".$obj->wheresql;
        
        $result = $this->native_select_one($sql, $obj->paramData);
        return $result['num'];
    }
    
    /**
     * 我反馈的  列表
     */
    public function getMySendBugList($obj){
        $sql = "SELECT bg.id,bg.summary,bg.status,bg.toer_id,bg.weight,bg.create_time,bg.finall_time,bg.note,
            pl.name pname,pl.codename,pm.name modname,pm.director_id,mi.name mname,mis.name mmname,
            CASE bg.status WHEN 2 THEN 4 WHEN 0 THEN 3 WHEN 1 THEN 2 WHEN -2 THEN 1 END mark
            FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mi ON bg.toer_id=mi.id 
            LEFT JOIN member_info mis ON pm.director_id=mis.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 ".$obj->wheresql." ORDER BY mark DESC,bg.id DESC ".$obj->limitData;
        
        return $this->native_select_all($sql, $obj->paramData);
    }
    
    /**
     * 获取bug状态
     */
    public function getBugStatus($id){
        return $this->select_one('buglist', array('id' => $id),'id,status');
    }
    
    /**
     * 获取bug信息
     */
    public function getBugInfoLess($id, $uid){
        $sql = "SELECT bg.id,bg.summary,bg.toer_id,pl.department_id FROM buglist bg LEFT JOIN project_list pl ON bg.project_id=pl.id 
            WHERE bg.id = :id AND bg.fromer_id = :fromer_id AND bg.status = 0";
        return $this->native_select_one($sql,  array('id' => $id, 'fromer_id' => $uid));
    }
    
    /**
     * 获取bug信息
     */
    public function getBugInfoLite($id, $uid){
        $sql = "SELECT id,summary FROM buglist WHERE id = :id AND fromer_id = :fromer_id AND status != -1";
        return $this->native_select_one($sql,  array('id' => $id, 'fromer_id' => $uid));
    }
    
    /**
     * 部门内所有成员
     */
    public function getAllMemberById($department_id){
        $result = $this->select_all('member_info', array('status' => 1, 'department_id' => $department_id), 'id,name');
        return $result;
    }
    
    /**
     * 修改bug负责人
     */
    public function setBugToer($id, $toerid){
        return $this->think_update('buglist', array('toer_id' => $toerid),array('id' => $id));
    }
    
    /**
     * 删除Bug
     */
    public function deleteMysendBug($id){
        return $this->think_update('buglist', array('status' => '-1'), array('id' => $id));
    }
}