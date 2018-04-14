<?php
namespace Home\Logic;
class BugDoneLogic extends ConditionLogic{
     public $conditionRules = array(
        'toer_id' => array (
            'field' => 'bg.toer_id',
            'operator' => '=',
            'param' => 'toer_id',
            'htmlFiled' => 'toer_id'
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
        $post['toer_id'] = $post['uid'];
        $this->_construct($post, $page, $perpage);
    }
}