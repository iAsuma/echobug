<?php
namespace Home\Model;
class LoginModel extends PublicModel{
    /**
     * 判断是否登录
     */
    public function isLogged(){
    	// 验证是否登录平台
    	if((int)$_SESSION[C('USER_AUTH_KEY')]['uid'] > 0) {
    		return true;
    	} elseif($uid = $this -> getCookieUid()) {
    		return $this->loginUserByUid($uid);
    	} else {
    		return false;
    	}
    }
    
    /**
     * 获取cookie中记录的用户ID
     */
    public function getCookieUid() {
    	$cookie = cookie(C('USER_COOKIE_KEY'));
    	$cookie = explode('_', base64_decode($cookie));
    	if($cookie[0] == 'bugministore') {
    		$user = $this->select_one('member_info', array('id' => (int)$cookie[1], 'userpwd' => $cookie[2]), 'id,name');
    		if($user) {
    			return $user['id'];
    		}
    		return false;
    	}
    	
    	return false;
    }
    
    /**
     * 用uid登陆平台
     */
    private function loginUserByUid($uid) {
    	$user= $this->select_one('member_info', array('id' => $uid));
    	
    	return $user? $this->registerLogin($user) : false;
    }
    
    /**
     * 登录
     */
    public function loginUser($username, $password = null, $is_remember_user= false) {
    	$user = $this->getUserInfo($username, $password);
    	
    	return $user ? $this->registerLogin($user , $is_remember_user) : false;
    }
    
    /**
     * 获取用户状态信息
     */
    public function getUserInfo($username, $password) {
        if(empty($username) || empty($password)){
            return false;
        }
    	
    	$user = $this->select_one('member_info', array('login_name' => $username));
    	!$user && exit('-101');
    	
    	md5(substr(md5($password), 0, 12)) != $user['userpwd'] && exit('-201');
    	
    	$user['status'] == '-2' && exit('-102'); //账号冻结
    	$user['status'] == '-1' && exit('-103'); //账号删除
    	
    	return $user;
    }
    
    
    /**
     * 注册用户的登陆状态 (即: 注册cookie + 注册session + 记录登陆信息)
     * @param array   $user 用户信息
     * @param boolean $is_remember_user 是否记住用户登录状态
     */
    private function registerLogin(array $user, $is_remember_user = false) {
    	if(empty($user)) {
    		return false;
    	}
    	
    	$_SESSION[C('USER_AUTH_KEY')]['uid'] = $user['id'];
    	$_SESSION[C('USER_AUTH_KEY')]['login']	= $user['login_name'];
    	$_SESSION[C('USER_AUTH_KEY')]['membername']	= $user['name'];
    	$_SESSION[C('USER_AUTH_KEY')]['membertype']	= $user['type'];
    	$_SESSION[C('USER_AUTH_KEY')]['memberlevel']	= $user['level'];
    	$_SESSION[C('USER_AUTH_KEY')]['departmentid']	= $user['department_id'];
    	
    	if ($is_remember_user) {
    		$expire = 3600*24*30;
    		cookie(C('USER_COOKIE_KEY'), base64_encode("bugministore_".$user['id']."_".$user['userpwd']), array('expire' => $expire));
    	}
    	
    	//记录登录时间
    	$this->recordLoginTime($user);
    	return $user;
    }
    
    /**
     * 作用：记录用户登录时间
     * 参数：$user登录用户数据
     */
    public function recordLoginTime($user){
    	if(empty($user)) {
    		return false;
    	}
    	$thisTime = time();
    	
    	$lastTime = $user['this_login_time'] ? $user['this_login_time'] : $thisTime;
    	
    	return $this->think_update('member_info', array('update_time' => $thisTime, 'this_login_time' => $thisTime,'last_login_time' => $lastTime), array('id' => $user['id']));
    }
}