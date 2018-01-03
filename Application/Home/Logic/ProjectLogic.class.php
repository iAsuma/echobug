<?php
namespace Home\Logic;
class ProjectLogic extends ConditionLogic{
    public $extwherestr = "";
    public $conditionRules = array(
        'status' => array (
            'field' => 'pl.status',
            'operator' => '=',
            'param' => 'status',
            'htmlFiled' => 'status'
        ),
        'director' => array (
            'field' => 'mi.name',
            'operator' => 'LIKE',
            'param' => 'director',
            'htmlFiled' => 'director'
        )
    );
    
    public function loadTable($post, $page, $perpage){
        if($post['pname']){
            $this->extwherestr = " AND (Lower(pl.name) LIKE Lower('%".$post['pname']."%') OR Lower(pl.codename) LIKE Lower('%".$post['pname']."%')) ";
        }
        $this->_construct($post, $page, $perpage);
    }
    
    /**
     * 处理添加成员的表单数据
     */
    public function dealAddData($post, $model){
        $post['project_name'] = remove_xss(trim($post['project_name']));
        ($post['project_name'] == "" || $post['project_name'] == null) && exit('-100');
        (strlen($post['project_name']) < 3 || strlen($post['project_name']) > 30) && exit('-101');
        
        $post['code_name'] = remove_xss(trim($post['code_name']));
        if($post['code_name'] != ""){
            (strlen($post['code_name']) < 3 || strlen($post['code_name']) > 30) && exit('-201');
        }
        
        empty($post['isview']) && exit('-300');
        if($post['isview'] == 2){
            ($post['code_name'] == "" || $post['code_name'] == null) && exit('-301');
        }
        
        $post['department'] && $departInfo = $model -> getDepartmentInfo($post['department']);
        empty($departInfo) && exit('-400');
        
        $post['director'] && $memberInfo = $model -> getMemberInfo($post['director']);
        empty($memberInfo) && exit('-500');
        
        $post['startdate'] = trim($post['startdate']);
        empty($post['startdate']) && exit('-600');
        $post['startdate'] = date('Ymd', strtotime($post['startdate']));
        
        $post['enddate'] = trim($post['enddate']);
        empty($post['enddate']) && exit('-700');
        $post['enddate'] = date('Ymd', strtotime($post['enddate']));
        
        $post['status'] = 0;
//         if($post['startdate'] > date('Ymd')){
//             $post['status'] = 0;
//         }else{
//             $post['status'] = -2;
//         }
        
        if($post['description'] != ""){
            (strlen($post['description']) < 3 || strlen($post['description']) > 200) && exit('-801');
        }
        return $post;
    }
}