<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月6日
*/
//公共信息
error_reporting(E_ALL ^ E_NOTICE);
//防止恶意调用
if(!defined('IN_TG')){
    exit('非法调用');
}
//设置字符编码
header('Content-Type:text/html;charset=utf-8');

//转化硬路径常量
define('ROOT_PATH', substr(dirname(__FILE__),0,-8));

//拒接低版本
if(PHP_VERSION<'4.1.0'){
    exit('版本太低，请跟换版本。');
}
//引入函数库
require ROOT_PATH.'includes/global.func.php';
require ROOT_PATH.'includes/mysql.func.php';
//判断转义常量
define('GPC', get_magic_quotes_gpc());

//数据库连接
define('DB_HOST','localhost');
define('DB_USER', 'root');
define('DB_PWD', '');
define('DB_NAME', 'testguest');
//初始化数据库
_connect();
_select_db();
_set_names();

//短信提醒
$_message=_fetch_array("SELECT COUNT(tg_id) AS count FROM tg_message WHERE tg_state=0 AND tg_touser='{$_COOKIE['username']}'");
if(empty($_message['count'])){
    $GLOBALS['message']='<strong class="noread"><a href="member_message.php">(0)</a></strong>';
    
}else{
    $GLOBALS['message']='<strong class="read"><a href="member_message.php">('.$_message['count'].')</a></strong>';
}

//网站系统设置初始化
if(!!$_rows=_fetch_array("SELECT
                                *
                            FROM
                                tg_system
                            WHERE
                                tg_id=1
                            LIMIT
                                1"
)){
    $_system=array();
    $_system['webname']=$_rows['tg_webname'];
    $_system['article']=$_rows['tg_article'];
    $_system['blog']=$_rows['tg_blog'];
    $_system['photo']=$_rows['tg_photo'];
    $_system['skin']=$_rows['tg_skin'];
    $_system['string']=$_rows['tg_string'];
    $_system['post']=$_rows['tg_post'];
    $_system['re']=$_rows['tg_re'];
    $_system['code']=$_rows['tg_code'];
    $_system['register']=$_rows['tg_register'];
    $_system=_html($_system);
    
}else{
    exit('系统表读取错误，请联系管理员检查！');
}






?>