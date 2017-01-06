
<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016.9.17
*/
session_start();
/*判断是否是非法调用*/
define('IN_TG', true);
/*定义常量证明这是注册*/
define('SCRIPT', 'login');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php'; 
//登录状态判断
_login_state();
global $_system;

//开始处理登陆状态
if($_GET['action']=='login'){
    if ($_system['code']==1){
        //为防止恶意注册，跨站攻击
        _check_yzm($_POST['yzm'], $_SESSION['code']);  
    }
    //引入验证文件
    include ROOT_PATH.'includes/login.func.php';
    //创建一个数组，用来存放提交的合法数据
    $_clean=array();
    $_clean["username"]= _check_username($_POST["username"],2,20);
    $_clean['password']=_check_password($_POST['password'], 6);
    $_clean['time']=_check_time($_POST['time']);
    //到数据库去验证
    if(!!$_rows=_fetch_array("SELECT tg_username,tg_uniqid,tg_level FROM tg_user WHERE tg_username='{$_clean['username']}'AND tg_password='{$_clean['password']}'AND tg_active=''LIMIT 1")){
       //登录成功后记录登录次数，更新登录时间，ip
        _query("UPDATE tg_user SET 
                                    tg_last_time=NOW(),
                                    tg_last_ip='{$_SERVER['REMOTE_ADDR']}',
                                    tg_login_count=tg_login_count+1
                               WHERE
                                    tg_username='{$_rows['tg_username']}'         
                                   ");
       
//         session_destroy();
        _setcookies($_rows['tg_username'], $_rows['tg_uniqid'],$_clean['time']);
        if ($_rows['tg_level']==1){
            $_SESSION['admin']=$_rows['tg_username'];
        }
        mysql_close();
        _location(null, 'index.php');
    }else{
       
//         session_destroy();
        mysql_close();
       _location('用户名密码错误或者账户未被激活！', 'login.php');
    }
}
//开始时间
define('START_TIME', _runtime());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
    //title
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/code.js"></script>
<?php if ($_system['code']==1){?>
<script type="text/javascript" src="js/login.js"></script>
<?php }?>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>
<div id="login">
    <h2>登陆</h2>
    <form method="post" action="login.php?action=login" name="login">
        <dl>
            <dd><span>用户名:</span><input type="text" name="username" class="text"/></dd>
            <dd><span>密码:</span><input type="password" name="password" class="text"/></dd>
            <dd><span>保留:</span><input type="radio" name="time" value="0" checked="checked" />不保留&nbsp;<input type="radio" name="time" value="1" />一天&nbsp;<input type="radio" name="time" value="2" />一周&nbsp;<input type="radio" name="time" value="3" />一月</dd>
            <?php if($_system['code']==1){?>
            <dd><span>验证码</span><input type="text" name="yzm" class="text yzm"/><img src='code.php'id="code"/></dd>
            <?php }?>
            <dd><input type="submit" name="button" class="submit" value="登陆"/><input type="submit" name="button" class="submit qingchu" value="注册"/></dd>
        </dl>
     </form>
</div>

<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>