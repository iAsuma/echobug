<?php
namespace Home\Logic;
class ModuleLogic extends ConditionLogic{
    public $conditionRules = array(
        'department' => array (
            'field' => 'mi.department_id',
            'operator' => '=',
            'param' => 'department',
            'htmlFiled' => 'department'
        ),
        'mod_name' => array (
            'field' => 'pm.name',
            'operator' => 'LIKE',
            'param' => 'mod_name',
            'htmlFiled' => 'mod_name'
        ),
        'project' => array (
            'field' => 'pm.project_id',
            'operator' => '=',
            'param' => 'project',
            'htmlFiled' => 'project'
        ),
        'director' => array (
            'field' => 'mi.name',
            'operator' => 'LIKE',
            'param' => 'director',
            'htmlFiled' => 'director'
        )
    );
    
    public function loadTable($post, $page, $perpage){
        $this->_construct($post, $page, $perpage);
    }
    
    public function dealAddData($post, $modelObj){
        $post['module_name'] = remove_xss(trim($post['module_name']));
        ($post['module_name'] == "" || $post['module_name'] == null) && exit('-100');
        (strlen($post['module_name']) < 3 || strlen($post['module_name']) > 30) && exit('-101');
        
        $post['project'] && $projectInfo = $modelObj -> getProjectInfo($post['project']);
        empty($projectInfo) && exit('-200');
        
        $post['director'] && $memberInfo = $modelObj -> getMemberInfo($post['director']);
        empty($memberInfo) && exit('-300');
        
        return $post;
    }
}