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
error_reporting(E_ALL ^ E_NOTICE);
/*判断是否是非法调用*/
define('IN_TG', true);
/*定义常量证明这是注册*/
define('SCRIPT', 'photo_add_dir');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//管理员才能登陆
_manage_login();
//添加目录
if ($_GET['action']=='adddir'){
    //判断唯一标识符是否正确
    if(!!$_rows=_fetch_array("SELECT
                                    tg_uniqid
                                FROM
                                    tg_user
                                WHERE
                                    tg_username='{$_COOKIE['username']}'
                                LIMIT
                                    1")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        //引入验证文件
        include ROOT_PATH.'includes/check.func.php';
        //接收数据
        $_clean=array();
        $_clean['name']=_check_dir_name($_POST['name'], 2, 20);
        $_clean['type']=$_POST['type'];
        if(!empty($_clean['type'])){
            $_clean['password']=_check_dir_password($_POST['password'], 6);
        }       
        $_clean['content']=$_POST['content'];
        $_clean=_mysql_string($_clean);
        $_clean['dir']=time();
        //先检查一下主目录是否存在
        if(!is_dir('photo')){
            mkdir('photo',0777);
            
        }
        //在主目录里创建以当前时间磋为目录的相册目录
        if (!is_dir('photo/'.$_clean['dir'])){
            mkdir('photo/'.$_clean['dir']);
        }
        //判断是公开相册还是私密相册
        if(empty($_clean['type'])){
           _query("INSERT INTO tg_dir(
                                        tg_name,
                                        tg_type,
                                        tg_content,
                                        tg_dir,
                                        tg_date
                                        )
                                VALUE(
                                        '{$_clean['name']}',
                                        '{$_clean['type']}',
                                        '{$_clean['content']}',
                                        'photo/{$_clean['dir']}',
                                        NOW()
                                        )") ;
        }else {
            _query("INSERT INTO tg_dir(
                                            tg_name,
                                            tg_type,
                                            tg_content,
                                            tg_dir,
                                            tg_date,
                                            tg_password
                                        )
                                            VALUE(
                                            '{$_clean['name']}',
                                            '{$_clean['type']}',
                                            '{$_clean['content']}',
                                            'photo/{$_clean['dir']}',
                                            NOW(),
                                            '{$_clean['password']}'
                                        )") ;
        }
        if (_affected_rows()==1){
  
            mysql_close();
            //弹出提示后跳转到首页
            _location("相册添加成功！", "photo.php");
             
        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _alert_back("相册添加失败！");
        }
        
        
        
        
        
        
        
    
    }else {
        _alert_back('非法登录！');
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
    //公共头文件
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/photo_add_dir.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="photo">
    <h2>添加相册目录</h2>
    <form method="post" action="?action=adddir">
        <dl>
            <dd>相册名称：<input type="text" name="name" class="text"/></dd>
            <dd>相册类型：<input type="radio" name="type" value="0" checked="checked"/>公开<input type="radio" name="type" value="1" />私密</dd>
            <dd id="pass">相册密码：<input type="password" name="password" class="text"/></dd>
            <dd>相册描述：<textarea name="content" rows="" cols=""></textarea></dd>
            <dd><input type="submit"  class="submit" value="添加目录"/></dd>
        </dl>
    </form>
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>