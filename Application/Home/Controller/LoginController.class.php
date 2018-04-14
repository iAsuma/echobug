<?php
namespace Home\Controller;
use Think\Controller;
use Think\Exception;
class LoginController extends Controller{
    public function index(){
        $login = D('Login');
        if($login->isLogged()){
            redirect(U('Panel/index'));
        }

        $this->display();
    }
    
    public function checkUserLogin(){
        $login = D('Login');
        try {
            $loginName = I('post.username');
            $userPwd = I('post.userpassword');
            $isRemember = I('post.is_remember_user');
            $verifyCode = I('post.verification_code');

            empty($loginName) && exit('-100');
            empty($userPwd) && exit('-200');

            !$this->check_verify($verifyCode) && exit('-300');
             
            $loginUser = $login->loginUser($loginName, $userPwd, $isRemember);
            !$loginUser && exit('-101'); // 帐号不存在
            
            exit('1');
        } catch (Exception $e) {
            exit('-1');
        }
    }
    
    //退出登录
    public function logout(){
        unset($_SESSION[C('USER_AUTH_KEY')]); //注销session
        
        cookie(C('USER_COOKIE_KEY'), NULL); //注销cookie
        
        redirect(U('Login/index')); // 跳转回登录首页
    }
    
    //生成验证码
    public function verifyImg()
    {
        $Verify = new \Think\Verify();
        $Verify->seKey = "GOTOECHO.COM";
        $Verify->fontSize = 12;
        $Verify->imageW = 100;
        $Verify->imageH = 36;
        echo $Verify->entry();
    }

    //检测验证码
    public function check_verify($code)
    {
        $Verify = new \Think\Verify();
        $Verify->seKey = "GOTOECHO.COM";
        return $Verify->check($code);
    }
}