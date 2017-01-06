<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016骞�9鏈�7鏃�
*/
session_start();
define('IN_TG', true);

define('SCRIPT', 'upimg');

require dirname(__FILE__).'/includes/common.inc.php';

//会员才能登陆
if(!$_COOKIE['username']){
	_alert_back('必须登录才能上传图片！');
}

if ($_GET['action']=='up') {
	    //判断唯一标识符是否正确
    if(!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        //开始上传
        //设置上传图片类型
        $_files = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif');
        //判断图片类型是否正确
        if (is_array($_files)) {
        	if (!in_array($_FILES['userfile']['type'], $_files)){
        		_alert_back('上传的图片必须是png，jpg，gif中的一种！');
        	}
        }
        //判断文件错误类型
        if($_FILES['userfile']['error'] > 0){
        	switch ($_FILES['userfile']['error']){
        		case 1:_alert_back('上传文件超过约定值1！');
        			break;
        		case 2:_alert_back('上传文件超过约定值2！');
        			break;
        		case 3:_alert_back('部分文件被上传！');
        			break;	
        		case 4:_alert_back('没有任何文件被上传！');
        			break;
        	}
        	exit;
        }
        //判断配置文件大小
        if($_FILES['userfile']['size'] > 1000000){
        	_alert_back('上传的文件不得超过1M！');
        }
        //获取文件的扩展名
        $_n=explode('.', $_FILES['userfile']['name']);
        $_name=$_POST['dir'].'/'.time().'.'.$_n[1];
        
        
        
        //移动文件
        if(is_uploaded_file($_FILES['userfile']['tmp_name'])){
        	if (!@move_uploaded_file($_FILES['userfile']['tmp_name'],$_name)){
        			_alert_back('移动失败！');
        	}else {
//         		_alert_close('上传成功！');
                echo "<script>alert('上传成功！');window.opener.document.getElementById('url').value='$_name';window.close();</script>";
        	   exit();
        	}
        }else {
        	_alert_back('上传的临时文件不存在！');
        }
        
        
        
        
        
    }else {
    	_alert_back('非法登录！');
    }
    
    
}

//接收dir
if (!isset($_GET['dir'])){
    _alert_back('非法操作！');
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 

    require ROOT_PATH.'includes/title.inc.php';
?>
</head>
<body>
    <div id="upimg" style="padding: 20px;">
        <form method="post" enctype="multipart/form-data" action="upimg.php?action=up">
        	<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
        	<input type="hidden" name="dir" value="<?php echo $_GET['dir'];?>" />
        	选择图片：<input type="file" name="userfile" style="border: 1px solid #333;"/>
        	<input type="submit" name="send" value="上传"/>       
        </form>
    </div>
</body>
</html>