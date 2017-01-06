<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月29日
*/
error_reporting(E_ALL ^ E_NOTICE);
/*判断是否是非法调用*/
define('IN_TG', true);
/*定义常量证明这是注册*/
define('SCRIPT', 'thumb');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';

//缩略图
if (isset($_GET['filename'])&&isset($_GET['percent'])){
    _thumb($_GET['filename'], $_GET['percent']);
}else {
    //出错！
}







?>