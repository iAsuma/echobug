<?php
namespace Home\Logic;
class MyViewLogic extends ConditionLogic{
    public $conditionRules = array(
        'department' => array (
            'field' => 'pl.department_id',
            'operator' => '=',
            'param' => 'department',
            'htmlFiled' => 'department'
        )
    );
    
    public function loadTable($post, $page, $perpage){
        $this->_construct($post, $page, $perpage);
    }
    /**
     * 处理反馈bug的表单数据
     */
    public function dealAddData($post){
        empty($post['project']) && exit('-100');
        empty($post['module']) && exit('-200');
    
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