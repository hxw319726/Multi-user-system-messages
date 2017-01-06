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
define('SCRIPT', 'reg');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//登录状态判断
_login_state();


// //测试数据库是否正常
// mysql_query("INSERT INTO tg_user(tg_username) VALUES('黄小伟')") or die('sql执行失败！');





global $_system;
//判断是否提交
if($_GET['action']=='reg'){
    if (empty($_system['register'])){
        exit('禁止非法注册！');
    }
	//为防止恶意注册，跨站攻击
    _check_yzm($_POST['yzm'], $_SESSION['code']);
	//接收信息$_POST['username']污染数据
	//引入验证文件
	include ROOT_PATH.'includes/check.func.php';
	//创建一个数组，用来存放提交的合法数据
	$_clean=array();
	//可以通过唯一标识符来防止恶意注册，跨站攻击
	$_clean['uniqid']=_check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
	//active也是一个唯一标识符，用来刚注册的用户进行激活处理，方可登陆
	$_clean['active']=_sha1_uniqid();
	//头尾空格要去掉
	$_clean["username"]= _check_username($_POST["username"],2,20);
	$_clean['password']=_check_password($_POST['password'], $_POST["notpassword"], 6);
	$_clean['question']=_check_question($_POST['question'],2,20);
	$_clean['answer']=_check_answer($_POST['question'],$_POST['answer'], 2, 20);
	$_clean['sex']=_check_sex($_POST['sex']);
	$_clean['face']=_check_face($_POST['face']);
	$_clean["email"]= _check_email($_POST["email"],6,40);
	$_clean["qq"]= _check_qq($_POST["qq"]);
	$_clean["url"]= _check_http($_POST["url"],40);
	//注册用户之前要判断用户名是否重复
	
	_is_repeat(
				"SELECT tg_username FROM tg_user WHERE tg_username='{$_clean["username"]}'", 
				"对不起，此用户名已被注册！"
	);
	
	//注册用户到数据库
	_query("INSERT INTO tg_user(
	    tg_uniqid,
	    tg_active,
	    tg_username,
	    tg_password,
	    tg_question,
	    tg_answer,
	    tg_sex,
	    tg_face,
	    tg_email,
	    tg_qq,
	    tg_url,
	    tg_reg_time,
	    tg_last_time,
	    tg_last_ip
	                                   ) VALUES(
	                                               '{$_clean['uniqid']}',
	                                               '{$_clean['active']}',
	                                               '{$_clean['username']}',
	                                               '{$_clean['password']}',
	                                               '{$_clean['question']}',
	                                               '{$_clean['answer']}',
	                                               '{$_clean['sex']}',
	                                               '{$_clean['face']}',
	                                               '{$_clean['email']}',
	                                               '{$_clean['qq']}',
	                                               '{$_clean['http']}',
	                                                NOW(),
	                                                NOW(),
	                                               '{$_SERVER["REMOTE_ADDR"]}'
	                                                )"
	);
	if (_affected_rows()==1){
	    $_clean['id']=_insert_id();
	    //关闭数据库清空session
	    mysql_close();
	    //session_destroy();
	    //注册成功后生成xml
	    _set_xml('new.xml', $_clean);
	    //弹出提示后跳转到首页
	    _location("恭喜您，注册成功！", "active.php?active=".$_clean['active']);
	    
	}else {
	    //关闭数据库
	    mysql_close();
	    //session_destroy();
	    //弹出提示后跳转到首页
	    _location("很遗憾，注册失败！", "reg.php"); 
	}

}else{
    $_SESSION['uniqid']=$_uniqid=_sha1_uniqid();
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
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/reg.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>
<div id="reg">
    <h2>会员注册</h2>
    <?php if (!empty($_system['register'])){?>
    <form method="post" action="reg.php?action=reg" name="reg">
        <input type="hidden" name="uniqid" value="<?php echo $_uniqid;?>"/>
        <dl>
            <dt>请认真填写以下内容</dt>
            <dd><span>用户名:</span><input type="text" name="username" class="text"/>（*必填至少两位）</dd>
            <dd><span>密码:</span><input type="password" name="password" class="text"/>（*必填至少两位）</dd>
            <dd><span>确认密码:</span><input type="password" name="notpassword" class="text" />（*同上）</dd>
            <dd><span>密码提示:</span><input type="text" name="question" class="text"/>（*必填至少两位）</dd>
            <dd><span>密码回答:</span><input type="text" name="answer" class="text"/>（*必填至少两位）</dd>
            <dd><span>性别:</span><input type="radio" name="sex" value="男" checked="checked" />男&nbsp;&nbsp;<input type="radio" name="sex" value="女" />女</dd>
            <dd><input type="hidden" name="face" value="face/m01.gif"/><img src="face/m01.gif" id="faceimg" alt="头像选择" title="头像选择" /></dd>
            <dd><span>电子邮件:</span><input type="text" name="email" class="text"/>（*必填用于激活）</dd>
            <dd><span>QQ:</span><input type="text" name="qq" class="text"/></dd>
            <dd><span>主页地址:</span><input type="text" name="url" class="text" value="http://"/></dd>
            <dd><span>验证码</span><input type="text" name="yzm" class="text yzm"/><img src='
            code.php'id="code"/></dd>
            <dd><input type="submit" name="button" class="submit" value="注册"/></dd>
        </dl>
    </form>
    <?php }else {
        echo '<h4 style="text-align:center;">本网站已经禁止注册！</h4>';
    }?>
</div>



<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>