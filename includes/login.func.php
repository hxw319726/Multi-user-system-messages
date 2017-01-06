<?php
/**
 * TestGuest Version1.0
 * ================================================
 * Copy 2016-2016
 * Web: http://www..com
 * ================================================
 * Author: word
 * Date: 2016年9月17日
 */
//防止恶意调用
if(!defined('IN_TG')){
    exit('非法调用');
}
//判断函数是否存在
if (!function_exists('_alert_back')){
    exit('_alert_back()函数不存在');
}
if (!function_exists('_mysql_string')){
    exit('_mysql_string()函数不存在');
} 
/**
 * @_setcookies生成cookie
 * @access public
 * @param  $_username cookie用户名
 * @param  $_uniqid cookie标识符
 * 
 * 
 *
 *
 */
function _setcookies($_username,$_uniqid,$_time){
    switch ($_time){
        case'0'://浏览器进程
            setcookie('username',$_username);
            setcookie('uniqid',$_uniqid);
            break;
        case'1'://一天
            setcookie('username',$_username,time()+86400);
            setcookie('uniqid',$_uniqid,time()+86400);
            break;
        case'2'://一周
            setcookie('username',$_username,time()+604800);
            setcookie('uniqid',$_uniqid,time()+604800);
            break;
        case'3'://一月
            setcookie('username',$_username,time()+2592000);
            setcookie('uniqid',$_uniqid,time()+2592000);
            break;
    }
}


/**
 * @_check_username表示检测并过滤用户名
 * @access public
 * @param string $_string受污染的用户名
 * @param int $_max_num 最大位数
 * @param int $_min_num最小位数
 * @return string 过滤后的用户名
 *
 *
 */
function _check_username($_string,$_min_num,$_max_num){
    //去两边空格
    $_string=trim($_string);
    //长度小于2为或大于20位都不行
    if(mb_strlen($_string,'utf-8')<$_min_num||mb_strlen($_string,'utf-8')>$_max_num){
        _alert_back('用户名长度小于'.$_min_num.'为或大于'.$_max_num.'位');
    }
    //限制敏感字符
    $_char_pattern='/[<>\'\"\ ]/';
    if(preg_match($_char_pattern, $_string)){
        _alert_back('用户名不能包含敏感字符');
    }
//     //限制敏感用户名
//     $_mg[0]='李炎恢';
//     $_mg[1]='黄小为';
//     $_mg[2]='哈U给你';
//     //告诉用户有哪些用户名不能注册
//     foreach($_mg as $value){
//         $_mg_string.='['.$value.']'.'\n';
//     }
//     //绝对匹配
//     if(in_array($_string, $_mg)){
//         _alert_back($_mg_string.'以上敏感用户名不得注册');
//     }
    //将用户名转义后返回
    return _mysql_string($_string);
}

/**
 * @_check_password表示检测密码和密码确认，加密密码
 * @access public
 * @param string $_first_pass密码
 * @param string $_end_pass 密码确认
 * @param int $_min_num最小几位密码
 * @return $_first_pass返回加密密码
 *
 *
 */
function  _check_password($_string,$_min_num){
    //判断密码
    if(strlen($_string)<$_min_num){
        _alert_back('密码不得小于'.$_min_num.'位!');
    }
//     //判断密码确认
//     if(strlen($_end_pass)<$_min_num){
//         _alert_back('密码确认不得小于'.$_min_num.'位!');
//     }
//     //判断密码和确定密码
//     if (!($_first_pass==$_end_pass)) {
//         _alert_back('密码和密码确认不能不同！');
//     }
    //将密码返回
    return _mysql_string(sha1($_string));

}

/**
 * @_check_time表示保留登录方式
 * @access public
 * @param string $_string传入方式
 * 
 *
 * @return $_string处理返回
 *
 *
 */
function _check_time($_string){
    $_time=array('0','1','2','3');
    if (!in_array($_string,$_time)){
        _alert_back('保留方式出错!');
    }
    return _mysql_string($_string);
    
}




















?>