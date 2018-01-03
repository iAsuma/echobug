<?php
namespace Home\Logic;
class StaffLogic extends ConditionLogic{
    public $conditionRules = array(
        'member_name' => array (
            'field' => 'mi.name',
            'operator' => 'LIKE',
            'param' => 'member_name',
            'htmlFiled' => 'member_name'
        ),
        'department' => array (
            'field' => 'mi.department_id',
            'operator' => '=',
            'param' => 'department',
            'htmlFiled' => 'department'
        ),
        'type' => array (
            'field' => 'mi.type',
            'operator' => '=',
            'param' => 'type',
            'htmlFiled' => 'type'
        ),
        'level' => array (
            'field' => 'mi.level',
            'operator' => '=',
            'param' => 'level',
            'htmlFiled' => 'level'
        )
    );
    
    public function loadTable($post, $page, $perpage){
        $this->_construct($post, $page, $perpage);
    }
    
    /**
     * 处理添加成员的表单数据
     */
    public function dealAddData($post, $model){
        $post['member_name'] = remove_xss(trim($post['member_name']));
        ($post['member_name'] == "" || $post['member_name'] == null) && exit('-100');
        (strlen($post['member_name']) < 3 || strlen($post['member_name']) > 18) && exit('-101');
        
        $email_pos = C('DEFAULT_ACC_POS') != false ? C('DEFAULT_ACC_POS') : '';
        $post['member_account'] = trim($post['member_account']).$email_pos;
        ($post['member_account'] == "" || $post['member_account'] == null) && exit('-200');
        !preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i', $post['member_account']) && exit('-201');
        $accountArr = explode("@", $post['member_account']);
        count($accountArr) != 2 && exit('-202');
        
        $memberInfo = $model -> getMemberInfoByAcc($post['member_account']);
        !empty($memberInfo) && exit('-203');
        
        $pwdstr = C('DEFAULT_ACC_PWD');
        if($pwdstr == 'pre'){
            $post['member_pwd'] = md5(substr(md5($accountArr[0]), 0, 12));
        }else{
            $post['member_pwd'] = md5(substr(md5($pwdstr), 0, 12));
        }
        
        ($post['department'] == "" || $post['department'] == null) && exit('-300');
        ($post['member_type'] == "" || $post['member_type'] == null) && exit('-400');
        
        $post['member_job'] = trim($post['member_job']);
        ($post['member_job'] == "" || $post['member_job'] == null) && exit('-500');
        (strlen($post['member_job']) < 3 || strlen($post['member_job']) > 24) && exit('-501');
        
        $post['cellphone'] = trim($post['cellphone']);
        ($post['cellphone'] == "" || $post['cellphone'] == null) && exit('-600');
        $mobileNum = preg_match('/^(\+)*(86)*0*1[3578]{1}[0-9]{1}[0-9]{8}$/', $post['cellphone']);
        !$mobileNum && exit('-601');
        
        if($post['qq'] != ""){
            $post['qq'] = trim($post['qq']);
            !preg_match('/^[1-9][0-9]{4,}$/', $post['qq']) && exit('-701');
        }
        
        ($post['level'] == "" || $post['level'] == null) && exit('-800');
        
        return $post;
    }
    
    public function dealUpdateData($post, $model){
        $post['member_name'] = remove_xss(trim($post['member_name']));
        ($post['member_name'] == "" || $post['member_name'] == null) && exit('-100');
        (strlen($post['member_name']) < 3 || strlen($post['member_name']) > 18) && exit('-101');
        
        $post['member_account'] = trim($post['member_account']);
        ($post['member_account'] == "" || $post['member_account'] == null) && exit('-200');
        !preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i', $post['member_account']) && exit('-201');
        $accountArr = explode("@", $post['member_account']);
        count($accountArr) != 2 && exit('-202');
        
        $memberInfo = $model -> getMemberInfoByAccS($post['member_account'], $post['member_id']);
        !empty($memberInfo) && exit('-203');
        
        if($post['pwd_reset']){
            $reset_pwd = C('RESET_ACC_PWD') ? C('RESET_ACC_PWD') : '123456';
            $post['member_pwd'] = md5(substr(md5($reset_pwd), 0, 12));
        }
        
        ($post['department'] == "" || $post['department'] == null) && exit('-300');
        ($post['member_type'] == "" || $post['member_type'] == null) && exit('-400');
        
        $post['member_job'] = trim($post['member_job']);
        ($post['member_job'] == "" || $post['member_job'] == null) && exit('-500');
        (strlen($post['member_job']) < 3 || strlen($post['member_job']) > 24) && exit('-501');
        
        $post['cellphone'] = trim($post['cellphone']);
        ($post['cellphone'] == "" || $post['cellphone'] == null) && exit('-600');
        $mobileNum = preg_match('/^(\+)*(86)*0*1[3578]{1}[0-9]{1}[0-9]{8}$/', $post['cellphone']);
        !$mobileNum && exit('-601');
        
        if($post['qq'] != ""){
            $post['qq'] = trim($post['qq']);
            !preg_match('/^[1-9][0-9]{4,}$/', $post['qq']) && exit('-701');
        }
        
        ($post['level'] == "" || $post['level'] == null) && exit('-800');
        
        return $post;
    }
}