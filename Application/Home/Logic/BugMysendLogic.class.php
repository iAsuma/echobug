<?php
namespace Home\Logic;
class BugMysendLogic extends ConditionLogic{
    public $conditionRules = array(
        'uid' => array (
            'field' => 'bg.fromer_id',
            'operator' => '=',
            'param' => 'uid',
            'htmlFiled' => 'uid'
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
            'field' => 'mi.name',
            'operator' => 'LIKE',
            'param' => 'director',
            'htmlFiled' => 'director'
        ),
        'weight' => array (
            'field' => 'bg.weight',
            'operator' => '=',
            'param' => 'weight',
            'htmlFiled' => 'weight'
        ),
        'status' => array (
            'field' => 'bg.status',
            'operator' => '=',
            'param' => 'status',
            'htmlFiled' => 'status'
        ),
        's_time' => array (
            'field' => 'bg.create_time',
            'operator' => '>=',
            'param' => 's_time',
            'htmlFiled' => 's_time'
        ),
        'e_time' => array (
            'field' => 'bg.create_time',
            'operator' => '<=',
            'param' => 'e_time',
            'htmlFiled' => 'e_time'
        )
    );
    
    public function loadTable($post, $page, $perpage){
        if($post['create_time']){
            $post['s_time'] = strtotime(date('Ymd0000' , strtotime($post['create_time'])));
            $post['e_time'] = strtotime(date('Ymd2359' , strtotime($post['create_time'])));
        }
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