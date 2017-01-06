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
define('SCRIPT', 'manage_set');


//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';

//管理员才可以登录
_manage_login();

//修改系统表
if($_GET['action']=='set'){
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
        //创建一个数组，用来存放提交的合法数据
        $_clean=array();
        $_clean['webname']=$_POST['webname'];
        $_clean['article']=$_POST['article'];
        $_clean['blog']=$_POST['blog'];
        $_clean["photo"]=$_POST["photo"];
        $_clean["skin"]= $_POST["skin"];
        $_clean["string"]= $_POST["string"];
        $_clean['post']=$_POST['post'];
        $_clean['re']=$_POST['re'];
        $_clean['register']=$_POST['register'];
        $_clean['code']=$_POST['code'];
        $_clean=_mysql_string($_clean);
        _query("UPDATE 
                        tg_system 
                    SET
                        tg_webname='{$_clean['webname']}',
                        tg_article='{$_clean['article']}',
                        tg_blog='{$_clean['blog']}',
                        tg_photo='{$_clean['photo']}',
                        tg_skin='{$_clean['skin']}',
                        tg_string='{$_clean['string']}',
                        tg_post='{$_clean['post']}',
                        tg_re='{$_clean['re']}',
                        tg_register='{$_clean['register']}',
                        tg_code='{$_clean['code']}'
                    WHERE
                        tg_id=1
                    LIMIT
                        1
            ");
        if (_affected_rows()==1){
            mysql_close();
            //弹出提示后跳转到首页
            _location("恭喜您，修改成功！", "manage_set.php");

        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _location("很遗憾，没有任何被修改！", "manage_set.php");
        }
    }else {
        _alert_back('异常！');
    }
}

//读取系统表
if(!!$_rows=_fetch_array("SELECT
                                tg_webname,tg_article,tg_blog,tg_photo,tg_skin,tg_string,tg_post,tg_re,tg_code,tg_register
                            FROM
                                tg_system
                            WHERE
                                tg_id=1
                            LIMIT
                                 1")){
    //成功
    $_html=array();
    $_html['webname']=$_rows['tg_webname'];
    $_html['article']=$_rows['tg_article'];
    $_html['blog']=$_rows['tg_blog'];
    $_html['photo']=$_rows['tg_photo'];
    $_html['skin']=$_rows['tg_skin'];
    $_html['string']=$_rows['tg_string'];
    $_html['post']=$_rows['tg_post'];
    $_html['re']=$_rows['tg_re'];
    $_html['code']=$_rows['tg_code'];
    $_html['register']=$_rows['tg_register'];
    $_html=_html($_html);
    
    //文章
    if($_html['article']==10){
        $_html['article_html']='<select name="article"><option value="10" selected="selected">每页10条</option><option value="15" >每页15条</option></select>';
    }elseif ($_html['article']==15){
        $_html['article_html']='<select name="article"><option value="10" >每页10条</option><option value="15" selected="selected">每页15条</option></select>';
    }
    //博客
    if($_html['blog']==10){
        $_html['blog_html']='<select name="blog"><option value="10" selected="selected">每页10人</option><option value="15" >每页15人</option></select>';
    }elseif ($_html['blog']==15){
        $_html['blog_html']='<select name="blog"><option value="10" >每页10人</option><option value="15" selected="selected">每页15人</option></select>';
    }
    //相册
    if($_html['photo']==8){
        $_html['photo_html']='<select name="photo"><option value="8" selected="selected">每页8张</option><option value="12" >每页12张</option></select>';
    }elseif ($_html['photo']==12){
        $_html['photo_html']='<select name="photo"><option value="8" >每页8张</option><option value="12" selected="selected">每页12张</option></select>';
    }
    //皮肤
    if($_html['skin']==1){
        $_html['skin_html']='<select name="skin"><option value="1" selected="selected">皮肤1</option><option value="2" >皮肤2</option><option value="3" >皮肤3</option></select>';
    }elseif ($_html['skin']==2){
        $_html['skin_html']='<select name="skin"><option value="1" >皮肤1</option><option value="2" selected="selected">皮肤2</option><option value="3" >皮肤3</option></select>';
    }elseif ($_html['skin']==2){
        $_html['skin_html']='<select name="skin"><option value="1" >皮肤1</option><option value="2" >皮肤2</option><option value="3" selected="selected">皮肤3</option></select>';
    }
    //发帖
    if($_html['post']==30){
        $_html['post_html']='<input type="radio" name="post" value="30" checked="checked"/> 30秒 <input type="radio" name="post" value="60"/> 1分钟 <input type="radio" name="post" value="180"/> 3分钟';
    }elseif ($_html['post']==60){
        $_html['post_html']='<input type="radio" name="post" value="30" /> 30秒 <input type="radio" name="post" value="60" checked="checked"/> 1分钟 <input type="radio" name="post" value="180"/> 3分钟';
    }elseif ($_html['post']==180){
        $_html['post_html']='<input type="radio" name="post" value="30" /> 30秒 <input type="radio" name="post" value="60" /> 1分钟 <input type="radio" name="post" value="180" checked="checked"/> 3分钟';
    }
    //回帖帖
    if($_html['re']==15){
        $_html['re_html']='<input type="radio" name="re" value="15" checked="checked"/> 15秒 <input type="radio" name="re" value="30"/> 30秒&nbsp;<input type="radio" name="re" value="45"/> 45秒';
    }elseif ($_html['re']==30){
        $_html['re_html']='<input type="radio" name="re" value="15" /> 15秒 <input type="radio" name="re" value="30" checked="checked"/> 30秒&nbsp;<input type="radio" name="re" value="45"/> 45秒';
    }elseif ($_html['re']==45){
        $_html['re_html']='<input type="radio" name="re" value="15" /> 15秒 <input type="radio" name="re" value="30"/> 30秒&nbsp;<input type="radio" name="re" value="45" checked="checked" /> 45秒';
    }
    
    //是否启用验证码
    if($_html['code']==1){
        $_html['code_html']='<input type="radio" name="code" value="0" /> 不启用 <input type="radio" name="code" value="1" checked="checked"/> 启用';
    }else {
        $_html['code_html']='<input type="radio" name="code" value="0" checked="checked"/> 不启用 <input type="radio" name="code" value="1" /> 启用';
    }
    //是否开启注册
    if($_html['register']==1){
        $_html['register_html']='<input type="radio" name="register" value="0" /> 不启用 <input type="radio" name="register" value="1" checked="checked"/> 启用';
    }else {
        $_html['register_html']='<input type="radio" name="register" value="0" checked="checked"/> 不启用 <input type="radio" name="register" value="1" /> 启用';
    } 
    
    
    
}else {
    //失败
    _alert_back('系统表读取错误，请联系管理员检查！');
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
        <form method="post" action="?action=set">
            <dl>
                <dd>网&nbsp;站&nbsp;名&nbsp;称：<input type="text" name="webname" class="text" value="<?php echo $_html['webname'];?>"/></dd> 
                <dd>文章每页列表数：<?php echo $_html['article_html'];?></dd> 
                <dd>博客每页列表数：<?php echo $_html['blog_html'];?></dd>
                <dd>相册每页列表数：<?php echo $_html['photo_html'];?></dd>
                <dd>站点 默认 皮肤：<?php echo $_html['skin_html'];?></dd>
                <dd>非法 字符 过滤：<input type="text" name="string" class="text" value="<?php echo $_html['string'];?>"/>&nbsp;(*请用 | 线隔开)</dd>
                <dd>每次 发帖 限制：<?php echo $_html['post_html'];?></dd>
                <dd>每次 回帖 限制：<?php echo $_html['re_html'];?></dd>  
                <dd>是否启用验证码：<?php echo $_html['code_html'];?></dd> 
                <dd>是否 开启 注册：<?php echo $_html['register_html'];?></dd> 
                <dd><input type="submit"  class="submit" value="修改系统设置"/></dd> 
            </dl>
        </form>
    </div>
</div>


<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>