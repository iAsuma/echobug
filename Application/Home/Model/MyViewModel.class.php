<?php
namespace Home\Model;
class MyViewModel extends PublicModel{
    /**
     * 上部分的 五条数据 --未处理的
     */
    public function getUndoneBugList($uid){
        $sql = "SELECT bg.id,bg.summary,bg.status,bg.weight,bg.create_time,bg.finall_time,bg.toer_id,bg.fromer_id,
            pl.name pname,pl.codename,pm.name modname,mi.name fromname FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            LEFT JOIN member_info mi ON bg.fromer_id=mi.id
            WHERE bg.status IN(0,2) AND pl.status != -1 AND pm.status != -1 AND bg.toer_id = :toer_id ORDER BY bg.weight ASC,bg.id DESC LIMIT 0,5";
        
        return $this->native_select_all($sql, array('toer_id' => $uid));
    }
    
    /**
     * 上部分的 五条数据 --我反馈的
     */
    public function getMySendBugList($uid){
        $sql = "SELECT bg.id,bg.summary,bg.status,bg.weight,bg.create_time,bg.finall_time,bg.toer_id,bg.fromer_id,
            pl.name pname,pl.codename,pm.name modname,
            CASE bg.status WHEN 0 THEN 4 WHEN 2 THEN 3 WHEN 1 THEN 2 WHEN -1 THEN 1 END mark
            FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            LEFT JOIN member_info mi ON bg.fromer_id=mi.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 AND bg.fromer_id = :fromer_id ORDER BY mark DESC,bg.id DESC LIMIT 0,5";
    
        return $this->native_select_all($sql, array('fromer_id' => $uid));
    }
    
    /**
     * BUG总数
     */
    public function getAllBugCount($obj){
        $sql = "SELECT COUNT(*) AS num FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 ".$obj->wheresql;
        
        $result = $this->native_select_one($sql, $obj->paramData);
        return $result['num'];
    }
    
    /**
     * 下面部分的所有BUG
     */
    public function getAllBugList($obj){
        $sql = "SELECT bg.id,bg.summary,bg.status,bg.weight,bg.create_time,bg.finall_time,
            pl.name pname,pl.codename,pm.name modname,
            CASE bg.status WHEN 2 THEN 4 WHEN 0 THEN 3 WHEN 1 THEN 2 WHEN -2 THEN 1 END mark
            FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            LEFT JOIN member_info mi ON bg.fromer_id=mi.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 ".$obj->wheresql." ORDER BY mark DESC,bg.id DESC ".$obj->limitData;
        
        return $this->native_select_all($sql, $obj->paramData);
    }
    
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
     * 获取bug信息
     */
    public function getBugInfoById($id, $uid, $department_id){
        if($department_id){
            $or_con = " AND (bg.fromer_id = :userid OR bg.toer_id = :userid OR mi.department_id = :department_id)";
        }
        
        $sql = "SELECT bg.id,bg.status,bg.summary,bg.content,bg.weight,bg.project_id,bg.fromer_id,bg.toer_id,bg.finall_time,bg.test_environment,bg.note,bg.create_time,
            pl.name pname,pl.department_id,pl.codename,pm.director_id,pm.name modname,mb.name fromname,mi.name toname,mis.name mname 
            FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mb ON bg.fromer_id=mb.id 
            LEFT JOIN member_info mi ON bg.toer_id=mi.id 
            LEFT JOIN member_info mis ON pm.director_id=mis.id 
            WHERE bg.id = :id AND bg.status != -1 AND pl.status != -1 AND pm.status != -1".$or_con;
        
        return $this->native_select_one($sql, array('id' => $id, 'userid' => $uid, 'department_id' => $department_id));
    }
}