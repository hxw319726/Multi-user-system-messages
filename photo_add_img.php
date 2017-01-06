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
define('SCRIPT', 'photo_add_img');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//会员才能登陆
if(!$_COOKIE['username']){
	_alert_back('必须登录才能上传图片！');
}
//图片信息写入数据库
if ($_GET['action']=='addimg'){
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
        $_clean=array();
        $_clean['name']=_check_photo_name($_POST['name'], 2, 20);
        $_clean['url']=_check_photo_url($_POST['url']);
        $_clean['content']=$_POST['content'];
        $_clean['sid']=$_POST['sid'];
        $_clean=_mysql_string($_clean);
        //写入数据库
        _query("INSERT INTO tg_photo(
                                    tg_name,
                                    tg_url,
                                    tg_content,
                                    tg_sid,
                                    tg_username,
                                    tg_date
                                    )
                              VALUE(
                                    '{$_clean['name']}',
                                    '{$_clean['url']}',
                                    '{$_clean['content']}',
                                    '{$_clean['sid']}',
                                    '{$_COOKIE['username']}',
                                    NOW()
                                    )");
        if (_affected_rows()==1){
            mysql_close();
            //弹出提示后跳转到首页
            _location("图片添加成功！", "photo_show.php?id=".$_clean['sid']);
             
        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _alert_back("图片添加失败！");
        }
        
        
        }else {
            _alert_back('非法登录!');
        }
}


//读取id
if (isset($_GET['id'])){
    if (!!$_rows=_fetch_array("SELECT tg_id,tg_dir FROM tg_dir WHERE tg_id='{$_GET['id']}'")){
        $_html=array();
        $_html['id']=$_rows['tg_id'];
        $_html['dir']=$_rows['tg_dir'];
        $_html=_html($_html);
    }else {
        _alert_back('不存在此相册！');
    }
}else {
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
<script type="text/javascript" src="js/photo_add_img.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="photo">
    <h2>上传图片</h2>
    <form method="post" action="?action=addimg">
    <input type="hidden" name="sid" value="<?php echo $_html['id'];?>"/>
        <dl>
            <dd>图片名称：<input type="text" name="name" class="text"/></dd>
            <dd>图片地址：<input type="text" name="url" id="url" readonly="readonly" class="text"/> <a href="javascript:;" title="<?php echo $_html['dir']?>" id="up">上传</a></dd>
            <dd>图片描述：<textarea name="content" rows="" cols=""></textarea></dd>
            <dd><input type="submit"  class="submit" value="添加图片"/></dd>
        </dl>
    </form>
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>