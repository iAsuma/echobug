<?php
/**
 * 效率阅读打印输出
 */
function pr($echo){
    echo "<pre>";
    print_r($echo);
    echo "</pre>";
}

/**
 * 实例化Logic模型
 */
function LG($model){
    empty($model) && throw_exception('实例化Logic模型错误');
    $logic = D($model, 'Logic');
    return $logic;
}

/**
 * 格式化时间
 */
function format_date($date)
{
    $yy = substr ( $date, 0, 4 );
    $yy_str = $yy;

    $mm = substr ( $date, 4, 2 );
    $mm && $mm_str = "-".$mm;

    $dd = substr ( $date, 6, 2 );
    $dd && $dd_str = "-".$dd;

    $hh = substr ( $date, 8, 2 ) ? substr ( $date, 8, 2 ) . "点" : "";
    $hh && $hh_str = " ".$hh;

    return $yy_str.$mm_str.$dd_str.$hh_str;
}

/**
 * 自动表单令牌验证
 */
function autoCheckToken($data) {
    if(C('TOKEN_ON')){
        $name   = C('TOKEN_NAME');

        if(!isset($data[$name]) || !isset($_SESSION[$name])) { // 令牌数据无效
            return false;
        }

        // 令牌验证
        list($key,$value)  =  explode('_',$data[$name]);
        if($_SESSION[$name][$key] == $value) {
            return true;
        }
        // 开启TOKEN重置
        if(C('TOKEN_RESET')) {
            unset($_SESSION[$name][$key]);
        }

        return false;
    }

    return true;
}

/**
 * 表单提交成功后销毁session， 防止重复提交
 */
function tokenRepeatSubmit($data) {
    $name   = C('TOKEN_NAME');

    // 令牌验证
    list($key,$value)  =  explode('_',$data[$name]);
    if($_SESSION[$name][$key] == $value) { // 防止重复提交
        unset($_SESSION[$name][$key]); // 验证完成销毁session
    }
}

/**
 * 获取客户端IP，优化的 get_client_ip()
 */
function get_user_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 新建一个间隔固定大小的数组
 * $param $statr int 数组开始值
 * $param $distance int 数组相邻的值间隔大小
 * $param $end int 数组最大值
 *
 * return 新建的数组
 */
function createNewArr($start, $distance, $end) {
    $arr = array ();

    for($k = ( int ) $start; $k <= ( int ) $end;) {
        $arr [] = $k;
        $k += $distance;
    }

    return $arr;
}

/**
 * 防止XSS攻击
 */
function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return htmlspecialchars($val);
}