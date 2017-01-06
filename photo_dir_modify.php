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
define('SCRIPT', 'photo_dir_modify');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//管理员才能登陆
_manage_login();

//修改目录
if($_GET['action']=='modify'){
    //判断唯一标识符是否正确
    if(!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);

        //引入验证文件
        include ROOT_PATH.'includes/check.func.php';
        //创建一个数组，用来存放提交的合法数据
        $_clean=array();
        $_clean['id']=$_POST['id'];
        $_clean['name']=_check_dir_name($_POST['name'], 2, 20);
        $_clean['type']=$_POST['type'];
        $_clean['face']=$_POST['face'];
        if (!empty($_clean['type'])){
            $_clean['password']=_check_dir_password($_POST['password'], 6);
        }
        $_clean["content"]=$_POST["content"];
        $_clean=_mysql_string($_clean);
        if (empty($_clean['type'])){
            _query("UPDATE tg_dir SET
                                    tg_type='{$_clean['type']}',
                                    tg_name='{$_clean['name']}',
                                    tg_password=NULL,
                                    tg_content='{$_clean['content']}',
                                    tg_face='{$_clean['face']}'
                                    
                                WHERE
                                    tg_id='{$_clean['id']}'
                                LIMIT
                                    1");  
        }else {
            _query("UPDATE tg_dir SET
                                    tg_type='{$_clean['type']}',
                                    tg_name='{$_clean['name']}',
                                    tg_password='{$_clean['password']}',
                                    tg_content='{$_clean['content']}',
                                    tg_face='{$_clean['face']}'
                                
                                WHERE
                                    tg_id='{$_clean['id']}'
                                    LIMIT
                                    1");
        }
        if (_affected_rows()==1){
            //关闭数据库清空session
            mysql_close();
            //弹出提示后跳转到首页
            _location("相册目录修改成功！", "photo.php");
             
        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _alert_back("相册目录修改失败！");
        }
    }else {
        _alert_back('非法登录！');
    }
}



//读出数据
if(isset($_GET['id'])){
    if(!!$_rows=_fetch_array("SELECT
                                    tg_id,
                                    tg_name,
                                    tg_type,
                                    tg_content,
                                    tg_face,
                                    tg_date
                                FROM
                                    tg_dir
                               WHERE
                                    tg_id='{$_GET['id']}'")){
        //存在
        $_html=array();
        $_html['id']=$_rows['tg_id'];
        $_html['name']=$_rows['tg_name'];
        $_html['type']=$_rows['tg_type'];
        $_html['content']=$_rows['tg_content'];
        $_html['date']=$_rows['tg_date'];
        $_html['face']=$_rows['tg_face'];
        $_html=_html($_html);
        //相册类型
        if(empty($_html['type'])){
            $_html['type_html']='<input type="radio" name="type" value="0" checked="checked"/>公开<input type="radio" name="type" value="1" />私密';
        }else{
            $_html['type_html']='<input type="radio" name="type" value="0" />公开<input type="radio" name="type" value="1" checked="checked"/>私密';
        }


    }else{
        _alert_back('不存在这个相册！');
    }

}else{
    _alert_back('非法操作！');
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
    <h2>修改相册目录</h2>
    <form method="post" action="?action=modify">
        <dl>
            <dd>相册名称：<input type="text" name="name" class="text" value="<?php echo $_html['name']?>"/></dd>
            <dd>相册类型：<?php echo $_html['type_html']?></dd>
            <dd id="pass" <?php if($_html['type']==1){echo 'style="display:block;"';}?>>相册密码：<input type="password" name="password" class="text"/></dd>
            <dd>相册封面：<input type="text" name="face" class="text" value="<?php echo $_html['face']?>"/></dd>
            <dd>相册描述：<textarea name="content" ><?php echo $_html['content']?></textarea></dd>
            <dd><input type="submit"  class="submit" value="修改目录"/></dd>
        </dl>
        <input type="hidden" value="<?php echo $_html['id']?>" name="id"/>
    </form>
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>