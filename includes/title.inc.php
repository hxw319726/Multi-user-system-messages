<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月7日
*/
//判断是否是非法调用
if(!defined('IN_TG')){
    exit('非法调用');
}
//防止非html调用
if (!defined('SCRIPT')) {
	exit('script error');
}
global $_system;
?>
<title><?php echo $_system['webname']?></title>
<link rel="shortcut icon" href="favicon.ico"/>
<link rel="stylesheet"  href="./styles/1/basic.css"/>
<link rel="stylesheet"  href="./styles/1/<?php echo SCRIPT ?>.css"/>
