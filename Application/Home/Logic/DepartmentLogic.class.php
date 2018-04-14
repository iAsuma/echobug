<?php
namespace Home\Logic;
class DepartmentLogic extends ConditionLogic{
    public function loadTable($post, $page, $perpage){
        $this->_construct($post, $page, $perpage);
    }
    
    /**
     * 处理添加部门的表单数据
     */
    public function dealAddData($post){
        $post['dep_name'] = remove_xss(trim($post['dep_name']));
        ($post['dep_name'] == "" || $post['dep_name'] == null) && exit('-100');
        (strlen($post['dep_name']) < 3 || strlen($post['dep_name']) > 30) && exit('-101');
        
        return $post;
    }
}