<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月19日
*/
session_start();
error_reporting(E_ALL ^ E_NOTICE);
/*判断是否是非法调用*/
define('IN_TG', true);
/*定义常量证明这是注册*/
define('SCRIPT', 'manage');
//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//管理员才可以登录
_manage_login();

//开始时间
define('START_TIME', _runtime());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
    //公共头文件
    require ROOT_PATH.'includes/title.inc.php';
?>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="member">
<?php 
    require ROOT_PATH.'includes/manage.inc.php';
?>
    <div id="member_main">
        <h2>后台管理中心</h2>
        <dl>
            <dd>服务器主机名称：<?php echo $_SERVER['SERVER_NAME']?></dd>
            <dd>服务器版本：<?php echo $_SERVER['HTTP_USER_AGENT']?></dd>
            <dd>通信协议名称/版本：<?php echo $_SERVER['SERVER_PROTOCOL']?></dd>
            <dd>服务器ip：<?php echo $_SERVER['SERVER_ADDR']?></dd>
            <dd>客户端ip：<?php echo $_SERVER['REMOTE_ADDR']?></dd>
            <dd>服务器端口：<?php echo $_SERVER['SERVER_PORT']?></dd>
            <dd>客户端端口：<?php echo $_SERVER['REMOTE_PORT']?></dd>
            <dd>管理员邮箱：<?php echo $_SERVER['SERVER_ADMIN']?></dd>
            <dd>host头部内容：<?php echo $_SERVER['HTTP_HOST']?></dd>
            <dd>服务器主目录：<?php echo $_SERVER['DOCUMENT_ROOT']?></dd>
            <dd>服务器系统盘：<?php echo $_SERVER['SystemRoot']?></dd>
            <dd>脚本执行的绝对路径：<?php echo $_SERVER['SCRIPT_FILENAME']?></dd>
            <dd>Apache及php版本：<?php echo $_SERVER['SERVER_SOFTWARE']?></dd>
            
        </dl>
    </div>
</div>


<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>