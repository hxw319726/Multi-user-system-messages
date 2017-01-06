<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月21日
*/
session_start();
error_reporting(E_ALL ^ E_NOTICE);
/*判断是否是非法调用*/
define('IN_TG', true);
/*定义常量证明这是注册*/
define('SCRIPT', 'member_message_detail');


//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if (!isset($_COOKIE['username'])){
    _alert_back('请先登录！');
}
//删除短信模块
if($_GET['action']=='delete'&& isset($_GET['id'])){
	//验证短信是否合法
	if (!!$_rows=_fetch_array("SELECT tg_id FROM tg_message WHERE tg_id='{$_GET['id']}' LIMIT 1")){
		//进行危险操作要进行唯一标识符的验证
		if(!!$_rows2=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
			_uniqid($_rows2['tg_uniqid'], $_COOKIE['uniqid']);
			_query("DELETE FROM tg_message WHERE tg_id='{$_GET['id']}' LIMIT 1");
	        if (_affected_rows()==1){
	            //关闭数据库清空session
	            mysql_close();
	            //_session_destroy();
	            //弹出提示后跳转到首页
	            _location("短信删除成功！", "member_message.php");
	    
	        }else {
	            //关闭数据库
	            mysql_close();
	            //_session_destroy();
	            //弹出提示后跳转到首页
	            _alert_back("短信删除失败！");
	        }
		}else{
		    _alert_back('非法登录！');
		}
	}else {
		_alert_back('此短信不存在！');
	}	
	exit();
}


//判断是否正常登入
if(isset($_GET['id'])){
    //获取数据
    $_rows=_fetch_array("SELECT tg_id,tg_state,tg_fromuser,tg_content,tg_date FROM tg_message WHERE tg_id='{$_GET['id']}'");
    //判断这个数据是不是有值
    if($_rows){
        if(empty($_rows['tg_state'])){
            _query("UPDATE tg_message SET tg_state=1 WHERE tg_id='{$_GET['id']}' LIMIT 1");
        }
        if(!_affected_rows()){
            _alert_back('异常！');
        }
        $_html=array();
        $_html['id']=$_rows['tg_id'];
        $_html['fromuser']=$_rows['tg_fromuser'];
        $_html['content']=$_rows['tg_content'];
        $_html['date']=$_rows['tg_date'];
        $_html=_html($_html);

    }else{
        _alert_back('此短信不存在！');
    }

}else{
    _alert_back('非法登录！');
}









?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
    //公共头文件
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/member_message_detail.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="member">
<?php 
    require ROOT_PATH.'includes/member.inc.php';
?>
    <div id="member_main">
        <h2>短信详情</h2>
        <dl>
            <dd>发 信 人：<?php echo $_html['fromuser']?></dd>
            <dd>内 &nbsp; 容：<strong><?php echo $_html['content']?></strong></dd>
            <dd>发信时间：<?php echo $_html['date']?></dd>
            <dd><input type="button" id="return" value="返回" /><input type="button" id="delete" name="<?php echo $_html['id']?>" value="删除短信" /></dd>
        </dl>
    </div>

</div>



<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>