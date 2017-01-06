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
error_reporting(E_ALL ^ E_NOTICE);
session_start();
/*判断是否是非法调用*/
define('IN_TG', true);
/*定义常量证明这是注册*/
define('SCRIPT', 'active');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';



// //测试数据库是否正常
// mysql_query("INSERT INTO tg_user(tg_username) VALUES('黄小伟')") or die('sql执行失败！');
//直接进入active.php页面是不行的
if(!isset($_GET['active'])){
    _alert_back('非法操作！');
}
//开始激活处理
if(isset($_GET['action']) && isset($_GET['active']) && $_GET['action']=='ok'){
    $_active=_mysql_string($_GET['active']);
    if (_fetch_array("SELECT tg_active FROM tg_user WHERE tg_active='$_active'LIMIT 1")) {
        //将tg_active设置为空、
        _query("UPDATE tg_user SET tg_active=NULL WHERE tg_active='$_active'LIMIT 1");
        if(_affected_rows()==1){
            mysql_close();
            _location('账户激活成功！', 'login.php');
        }else{
            mysql_close();
            _location('账户激活失败！', 'reg.php');
        }
    }else {
        _alert_back('非法操作！');
    }
}



//开始时间磋
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
<script type="text/javascript" src="js/reg.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>


<div id="active">
    <h2>激活账户</h2>
    <p>本页面用来模仿邮箱激活账户用的！</p>
    <p><a href="active.php?action=ok&amp;active=<?php echo $_GET['active']?>"><?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]?>active.php?action=ok&amp;active=<?php echo $_GET['active']?></a></p>
    
</div>







<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>