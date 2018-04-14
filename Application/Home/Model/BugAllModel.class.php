<?php
namespace Home\Model;
class BugAllModel extends PublicModel{
    /**
     * 所有的bug 总数
     */
    public function getAllBugCount($obj){
        $sql = "SELECT COUNT(*) AS num FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mb ON bg.fromer_id=mb.id 
            LEFT JOIN member_info mi ON bg.toer_id=mi.id 
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 ".$obj->wheresql;
        
        $result = $this->native_select_one($sql, $obj->paramData);
        return $result['num'];
    }
    
    /**
     * 所有的bug 列表
     */
    public function getAllBugList($obj){
        $sql = "SELECT bg.id,bg.summary,bg.status,bg.toer_id,bg.weight,bg.create_time,bg.finall_time,bg.note,
            pl.name pname,pl.codename,pm.name modname,pm.director_id,mb.name fromname,mi.name toname,mis.name mname,
            CASE bg.status WHEN 2 THEN 4 WHEN 0 THEN 3 WHEN 1 THEN 2 WHEN -2 THEN 1 END mark
            FROM buglist bg 
            LEFT JOIN project_list pl ON bg.project_id=pl.id 
            LEFT JOIN project_module pm ON bg.modular_id=pm.id 
            LEFT JOIN member_info mb ON bg.fromer_id=mb.id 
            LEFT JOIN member_info mi ON bg.toer_id=mi.id 
            LEFT JOIN member_info mis ON pm.director_id=mis.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 ".$obj->wheresql." ORDER BY mark DESC,bg.id DESC ".$obj->limitData;
        
        return $this->native_select_all($sql, $obj->paramData);
    }
    
    /**
     * 根据ID查询bug信息
     * @param $id bug id
     * @param $department 部门id
     */
    public function getBugInfoById($id, $department){
        $sql = "SELECT bg.id,bg.status,bg.summary,bg.content,bg.weight,bg.project_id,bg.fromer_id,bg.toer_id,bg.finall_time,bg.test_environment,bg.note,bg.create_time,
            pl.name pname,pl.department_id,pl.codename,pm.director_id,pm.name modname,mb.name fromname,mi.name toname,mis.name mname
            FROM buglist bg
            LEFT JOIN project_list pl ON bg.project_id=pl.id
            LEFT JOIN project_module pm ON bg.modular_id=pm.id
            LEFT JOIN member_info mb ON bg.fromer_id=mb.id
            LEFT JOIN member_info mi ON bg.toer_id=mi.id
            LEFT JOIN member_info mis ON pm.director_id=mis.id
            WHERE bg.status != -1 AND pl.status != -1 AND pm.status != -1 AND bg.id = :id";
        
        $data = array('id' => $id);
        
        if($department){
            $sql .= ' AND pl.department_id = :department';
            $data['department'] = $department;
        }
        
        return $this->native_select_one($sql, $data);
    }
    
    /**
     * 所有成员
     */
    public function getAllMember($department_id=0){
        $sql = "SELECT id,name FROM member_info WHERE status = 1 AND department_id = :department_id";
        return $this->native_select_all($sql, array('department_id' => $department_id));
    }
    
    /**
     * 获取bug状态
     */
    public function getBugStatus($id){
        return $this->select_one('buglist', array('id' => $id),'id,status');
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
}