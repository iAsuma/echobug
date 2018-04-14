<?php
namespace Home\Controller;
use Think\Controller;
class EntranceController extends Controller{
    protected $uid = NULL;
    protected $loginName = NULL;
    protected $uname = NULL;
    protected $utype = NULL;
    protected $level = NULL;
    protected $department = NULL;
    
    public function _initialize(){
        //header('Content-type:text/html;charset=utf-8');
        $login = D('Login');
        if(!$login->isLogged()){
        	redirect(U('Login/index'));
        }
        
        $session = session(C('USER_AUTH_KEY'));
        $this->uid = $session['uid'];
        $this->loginName = $session['login'];
        $this->uname = $session['membername'];
        $this->utype = $session['membertype'];
        $this->level = $session['memberlevel'];
        $this->department = $session['departmentid'];
        
        $domain = C('DOMAIN_NAME');
        if($domain){
            if(C('IS_HTTPS')){
                $indexUrl = 'https://'.$domain;
            }else{
                $indexUrl = 'http://'.$domain;
            }
        }else{
            $indexUrl = U('Index/index');
        }
        
        $this->assign('indexurl', $indexUrl);
        $this->assign('uname', $this->uname);
    }
    
    protected function jump($message='', $type, $jumpUrl='', $ajax=false){
        if($type == 'error_b'){
            C('TMPL_ACTION_ERROR', 'Public:error_b');
        }
        
        $this->error($message, $jumpUrl, $ajax);
    }
}