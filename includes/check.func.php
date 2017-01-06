<?php
/**
 * TestGuest Version1.0
 * ================================================
 * Copy 2016-2016
 * Web: http://www..com
 * ================================================
 * Author: word
 * Date: 2016年9月11日
 */
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
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
 * @_check_uniqid唯一标识符
 * @access public
 * @param string $_first_uniqid第一标识符
 * @param string $_end_uniqid 第二标识符
 * @return $_first_uniqid 返回该标识符
 */
function _check_uniqid($_first_uniqid,$_end_uniqid){
    if((strlen($_first_uniqid)!=40)||($_first_uniqid!=$_end_uniqid)){
        _alert_back('唯一标识符错误！');
    }
    //将用户名转义后返回
    return _mysql_string($_first_uniqid);
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
    global $_system;
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
	//限制敏感用户名
	$_mg=explode('|', $_system['string']);
	
	//告诉用户有哪些用户名不能注册
	foreach($_mg as $value){
		$_mg_string.='['.$value.']'.'\n';
	}
	//绝对匹配
	if(in_array($_string, $_mg)){
		_alert_back($_mg_string.'以上敏感用户名不得注册');
	}
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
function  _check_password($_first_pass,$_end_pass,$_min_num){
	//判断密码
	if(strlen($_first_pass)<$_min_num){
		_alert_back('密码不得小于'.$_min_num.'位!');
	}
	//判断密码确认
	if(strlen($_end_pass)<$_min_num){
		_alert_back('密码确认不得小于'.$_min_num.'位!');
	}
	//判断密码和确定密码
	if (!($_first_pass==$_end_pass)) {
		_alert_back('密码和密码确认不能不同！');
	}
	//将密码返回
	return _mysql_string(sha1($_first_pass));

}
/**
 * @_check_modify_password修改密码
 * @access public
 * @param string $_string密码
 * @param int $_min_num最小几位密码
 * @return $_string返回加密密码
 *
 *
 */
function  _check_modify_password($_string,$_min_num){
    if(!empty($_string)){
        //判断密码
        if(strlen($_string)<$_min_num){
            _alert_back('密码不得小于'.$_min_num.'位!');
        }  
    }else{
        return null;
    }
    //将密码返回
    return _mysql_string(sha1($_string));

}
/**
 * @_check_question密码提示
 * @access public
 * @param string $_string密码
 * @param int $_min_num 最小位
 * @param int $_max_num  最大位
 * @return $_string 返回密码提示
 *
 *
 */

function _check_question($_string,$_min_num,$_max_num){
    $_string=trim($_string);
	//长度小于4位或者大于20位都不可以
	if (mb_strlen($_string,'utf-8')<$_min_num||mb_strlen($_string,'utf-8')>$_max_num) {
		_alert_back('密码提示不得小于'.$_min_num.'位，不得大于'.$_max_num.'位');
	}
// 	返回密码提示
	return _mysql_string($_string);
}

/**
 * @_check_answer密码提示
 * @access public
 * @param string $_ques 密码提示
 * @param string $_answer 密码回答
 * @param int $_min_num 最小位
 * @param int $_max_num  最大位
 * @return $_answer 返回加密密码回答
 *
 *
 */

function _check_answer($_ques,$_answer,$_min_num,$_max_num){
    $_answer=trim($_answer);
	//长度小于4位或者大于20位都不可以
	if (mb_strlen($_answer,'utf-8')<$_min_num||mb_strlen($_answer,'utf-8')>$_max_num) {
		_alert_back('密码提示不得小于'.$_min_num.'位，不得大于'.$_max_num.'位');
	}
	//密码提示于密码回答不能一致
	if ($_ques==$_answer) {
		_alert_back('密码提示与回答不能一致');
	}
	
	// 	返回密码提示
	return _mysql_string(sha1($_answer));
}
/**
 * @_check_sex性别
 * @access public
 * @param string $_string 性别
 * @return $_string 
 *
 *
 */
function _check_sex($_string){
    return _mysql_string($_string);
}
/**
 * @_check_face头像
 * @access public
 * @param string $_string 头像地址
 * @return $_string
 *
 *
 */
function _check_face($_string){
    return _mysql_string($_string);
}
/**
 * @_check_email邮箱地址验证
 * @access public
 * @param string $_string 邮箱地址
 * @return $_string 邮箱地址
 *
 *
 */
function _check_email($_string,$_min_num,$_max_num){

// 		1140480831@qq.com
		if(!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $_string)){
			_alert_back('邮箱地址不正确！');
		}

		if(strlen($_string)<$_min_num||strlen($_string)>$_max_num){
		    _alert_back('您输入的邮箱地址不合法！');
		}
	

	return _mysql_string($_string);
}


/**
 * @_check_qq验证
 * @access public
 * @param string $_string qq号
 * @return $_string qq号
 *
 *
 */
function _check_qq($_string){
	if(empty($_string)){
		return null;
	}else{
		// 		1140480831
		if(!preg_match('/^[1-9]{1}[0-9]{4,9}$/', $_string)){
			_alert_back('qq号码不正确！');
		}
	}
	return _mysql_string($_string);
}

/**
 * @_check_http网页地址验证
 * @access public
 * @param string $_string 网页地址
 * @return $_string 返回加密密码回答
 *
 *
 */
function _check_http($_string,$_max_num){
	if(empty($_string)||($_string=='http://')){
		return null;
	}else{
		// 		1140480831@qq.com
		if(!preg_match('/^https?:\/\/(\w+\.)?[\w\.\-]+(\.\w+)+$/', $_string)){
			_alert_back('网页地址不正确！');
		}
		if(strlen($_string)>$_max_num){
		    _alert_back('网址太长！');
		}
	}
	return _mysql_string($_string);
}
/**
 * @_check_content内容判断
 * @access public
 * @param string $_string 内容
 * @param int $_min_num 最小位默认10
 * @param int $_max_num  最大位默认200
 * @return $_answer 内容
 *
 *
 */

function _check_content($_string,$_min_num=10,$_max_num=200){
    $_string=trim($_string);
    //长度小于4位或者大于20位都不可以
    if (mb_strlen($_string,'utf-8')<$_min_num||mb_strlen($_string,'utf-8')>$_max_num) {
        _alert_back('内容不得小于'.$_min_num.'位，不得大于'.$_max_num.'位');
    }
    // 	返回内容
    return $_string;
}

/**
 * @_check_post_title帖子标题判断
 * @access public
 * @param string $_string 标题内容
 * @param int $_min_num 最小位默认
 * @param int $_max_num  最大位默认

 */

function _check_post_title($_string,$_min_num,$_max_num){
    $_string=trim($_string);
    //长度小于4位或者大于20位都不可以
    if (mb_strlen($_string,'utf-8')<$_min_num||mb_strlen($_string,'utf-8')>$_max_num) {
        _alert_back('帖子标题不得小于'.$_min_num.'位，不得大于'.$_max_num.'位');
    }
    // 	返回内容
    return $_string;
}
/**
 * @_check_post_content帖子内容判断
 * @access public
 * @param string $_string 内容
 * @param int $_num 最小位数

 */

function _check_post_content($_string,$_num){
    $_string=trim($_string);
    if (mb_strlen($_string,'utf-8')<$_num) {
        _alert_back('帖子内容不得小于'.$_num.'位!');
    }
    return $_string;
}
/**
 * @_check_autograph个性签名
 * @access public
 * @param string $_string 内容
 * @param int $_num 最小位数

 */

function _check_autograph($_string,$_num){
    $_string=trim($_string);
    if (mb_strlen($_string,'utf-8')>$_num) {
        _alert_back('个性签名不得大于'.$_num.'位!');
    }
    return $_string;
}
/**
 * @_check_dir_name相册名称判断
 * @access public
 * @param string $_string 标题内容
 * @param int $_min_num 最小位默认
 * @param int $_max_num  最大位默认

 */

function _check_dir_name($_string,$_min_num,$_max_num){
    $_string=trim($_string);
    //长度小于4位或者大于20位都不可以
    if (mb_strlen($_string,'utf-8')<$_min_num||mb_strlen($_string,'utf-8')>$_max_num) {
        _alert_back('相册名称不得小于'.$_min_num.'位，不得大于'.$_max_num.'位');
    }
    // 	返回内容
    return $_string;
}
/**
 * @_check_dir_password相册密码验证
 * @access public
 * @param string $_string密码
 * @param int $_min_num最小几位密码
 * @return $_string返回加密密码
 *
 *
 */
function  _check_dir_password($_string,$_min_num){
    //判断密码
    if(strlen($_string)<$_min_num){
        _alert_back('密码不得小于'.$_min_num.'位!');
    }
    //将密码返回
    return sha1($_string);
}
/**
 * @_check_photo_name图片名称判断
 * @access public
 * @param string $_string 标题内容
 * @param int $_min_num 最小位默认
 * @param int $_max_num  最大位默认

 */

function _check_photo_name($_string,$_min_num,$_max_num){
    $_string=trim($_string);
    //长度小于4位或者大于20位都不可以
    if (mb_strlen($_string,'utf-8')<$_min_num||mb_strlen($_string,'utf-8')>$_max_num) {
        _alert_back('相册名称不得小于'.$_min_num.'位，不得大于'.$_max_num.'位');
    }
    // 	返回内容
    return $_string;
}
/**
 * @_check_photo_url图片地址是否为空判断
 * @access public
 * @param string $_string 图片地址
 */

function _check_photo_url($_string){
    if (empty($_string)) {
        _alert_back('地址不能为空！');
    }
    return $_string;
}

?>