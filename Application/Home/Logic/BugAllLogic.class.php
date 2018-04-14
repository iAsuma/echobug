<?php
namespace Home\Logic;
class BugAllLogic extends ConditionLogic{
    public $conditionRules = array(
        'department' => array (
            'field' => 'pl.department_id',
            'operator' => '=',
            'param' => 'department',
            'htmlFiled' => 'department'
        ),
        'bugid' => array (
            'field' => 'bg.id',
            'operator' => '=',
            'param' => 'bugid',
            'htmlFiled' => 'bugid'
        ),
        'project' => array (
            'field' => 'bg.project_id',
            'operator' => '=',
            'param' => 'project',
            'htmlFiled' => 'project'
        ),
        'director' => array (
            'field' => 'mb.name',
            'operator' => 'LIKE',
            'param' => 'director',
            'htmlFiled' => 'director'
        ),
        'weight' => array (
            'field' => 'bg.weight',
            'operator' => '=',
            'param' => 'weight',
            'htmlFiled' => 'weight'
        )
    );
    
    public function loadTable($post, $page, $perpage){
        $this->_construct($post, $page, $perpage);
    }
    
    /**
     * 处理修改反馈内容的表单数据
     */
    public function dealModifyData($post){
        ($post['summary'] == "" || $post['summary'] == null) && exit('-300');
        strlen($post['summary']) < 10 && exit('-301');
        strlen($post['summary']) > 200 && exit('-302');
    
        ($post['test_environment'] == "" || $post['test_environment'] == null) && exit('-400');
        strlen($post['test_environment']) < 4 && exit('-401');
        strlen($post['test_environment']) > 80 && exit('-402');
    
        empty($post['director']) && exit('-500');
        empty($post['weight']) && exit('-600');
    
        ($post['content'] == "" || $post['content'] == null) && exit('-700');
        strlen($post['content']) < 24 && exit('-701');
        strlen($post['content']) > 6000 && exit('-702');
    
        return $post;
    }
}