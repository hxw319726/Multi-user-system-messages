<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月18日
*/
session_start();
/*判断是否是非法调用*/
define('IN_TG', true);
//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
_unsetcookies();
session_destroy();










?>