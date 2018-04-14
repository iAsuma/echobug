<?php
namespace Home\Model;
class BugUndoneModel extends PublicModel{
    /**
     * 所有项目
     */
    public function getAllProject($status = '2,3'){
        $sql = "SELECT id,name,codename FROM project_list WHERE status IN($status)";
        return $this->native_select_all($sql);
    }
    
    /**
     * 未处理的 总数
     */
    public function getUndoneBugCount($obj){
        $sql = "SELECT COUNT(*) AS num FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mb ON bg.fromer_id=mb.id  
            WHERE bg.status IN(0,2) AND pl.status != -1 AND pm.status != -1 ".$obj->wheresql;
        $result = $this->native_select_one($sql,  $obj->paramData);
        
        return $result['num'];
    }
    
    /**
     * 未处理的  列表
     */
    public function getUndoneBugList($obj){
        $sql = "SELECT bg.id,bg.summary,bg.status,bg.toer_id,bg.weight,bg.create_time,bg.finall_time,bg.note,
            pl.name pname,pl.codename,pm.name modname,mb.name fromname FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mb ON bg.fromer_id=mb.id 
            LEFT JOIN member_info mis ON pm.director_id=mis.id 
            WHERE bg.status IN(0,2) AND pl.status != -1 AND pm.status != -1 ".$obj->wheresql." ORDER BY bg.weight ASC,bg.id DESC ".$obj->limitData;
        
        return $this->native_select_all($sql, $obj->paramData);
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
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 AND bg.id = :id AND bg.toer_id = :toer_id";
        return $this->native_select_one($sql, array('id' => $id, 'toer_id' => $uid));
    }
    
    /**
     * 获取bug信息
     */
    public function getBugInfoLite($id, $uid){
        $sql = "SELECT id,summary FROM buglist WHERE id = :id AND toer_id = :toer_id AND status != -1";
        return $this->native_select_one($sql,  array('id' => $id, 'toer_id' => $uid));
    }
    
    /**
     * 修改bug状态
     */
    public function updateBugStatus($id, $status){
        return $this->think_update('buglist', array('status' => $status, 'finall_time' => time()), array('id' => $id));
    }
    
    /**
     * 关闭Bug
     */
    public function updateBugStatusInClose($id, $notes){
        return $this->think_update('buglist', array('status' => -2, 'finall_time' => time(), 'note' => $notes), array('id' => $id));
    }
    
    /**
     * 获取bug信息
     */
    public function getBugInfoLess($id, $uid){
        $sql = "SELECT bg.id,bg.summary,bg.toer_id,pl.department_id FROM buglist bg LEFT JOIN project_list pl ON bg.project_id=pl.id
            WHERE bg.id = :id AND bg.toer_id = :toer_id AND bg.status != -1";
        return $this->native_select_one($sql,  array('id' => $id, 'toer_id' => $uid));
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
}