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
define('SCRIPT', 'photo_show');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//删除照片
if ($_GET['action']=='delete'&&isset($_GET['id'])){
    if(!!$_rows=_fetch_array("SELECT 
                                    tg_uniqid 
                                FROM 
                                    tg_user 
                               WHERE 
                                    tg_username='{$_COOKIE['username']}' 
                               LIMIT 
                                    1"))
    {
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        //取得这张图片的发布者
        if(!!$_rows=_fetch_array("SELECT
                                        tg_username,tg_id,tg_url,tg_sid
                                    FROM
                                        tg_photo
                                   WHERE
                                        tg_id='{$_GET['id']}'
                                   LIMIT
                                        1")){
          $_html=array();
          $_html['username']=$_rows['tg_username'];
          $_html['id']=$_rows['tg_id'];
          $_html['sid']=$_rows['tg_sid'];
          $_html['url']=$_rows['tg_url'];    
          $_html=_html($_html);
         //判断删除图片的身份是否合法
         if($_html['username']==$_COOKIE['username']||isset($_SESSION['admin'])){
             //删除图片
             _query("DELETE FROM tg_photo WHERE tg_id='{$_html['id']}'");
             if (_affected_rows()==1){
                 //删除图片的物理地址
                 if (file_exists($_html['url'])){
                     unlink($_html['url']);
                 }else {
                     _alert_back('磁盘里已不存在此图片！');
                 }
                 //关闭数据库清空session
                 mysql_close();
                 _location("图片删除成功！",'photo_show.php?id='.$_html['sid']);
             
             }else {
                 //关闭数据库
                 mysql_close();
                 //弹出提示后跳转到首页
                 _alert_back("图片删除失败！");
             }

           
         }else {
             _alert_back('非法操作！');
         }
        }else {
            _alert_back('不存在此图片！');
        }
        
        
    }else {
        _alert_back('非法登录！');
    }   
}


//读取id
if (isset($_GET['id'])){
    if (!!$_rows=_fetch_array("SELECT 
                                    tg_id,tg_name,tg_type 
                                FROM 
                                    tg_dir 
                                WHERE 
                                    tg_id='{$_GET['id']}'")
    ){
        $_dirhtml=array();
        $_dirhtml['id']=$_rows['tg_id'];
        $_dirhtml['name']=$_rows['tg_name'];
        $_dirhtml['type']=$_rows['tg_type'];
        $_dirhtml=_html($_dirhtml);
        //对比加密相册，验证信息
        if ($_POST['password']){
            if (!!$_rows=_fetch_array("SELECT
                                                tg_id
                                         FROM
                                                tg_dir
                                        WHERE
                                                tg_password='".sha1($_POST['password'])."'")
            ){
                //生成cookie
                setcookie('photo'.$_dirhtml['id'],$_dirhtml['name']);
                //重定向
                _location(null,'photo_show.php?id='.$_dirhtml['id']);
            }else {
                _alert_back('相册密码不正确！');
            }
        }
        
        
        
        
        
    }else {
        _alert_back('不存在此相册！');
    }
}else {
    _alert_back('非法操作！');
}
$_percent=0.3;
//读出缩略图
//分页模板
global $_pagenum,$_pagesize,$_system,$_id;
$_id='id='.$_dirhtml['id'].'&';
_page("SELECT tg_id FROM tg_photo WHERE tg_sid='{$_dirhtml['id']}'", $_system['photo']);
//从数据库提取数据，获取结果集
$_result=_query("SELECT
                        tg_id,tg_name,tg_username,tg_url,tg_readcount,tg_commendcount
                    FROM
                        tg_photo
                   WHERE
                        tg_sid='{$_dirhtml['id']}'
                ORDER BY
                        tg_date DESC
                   LIMIT
                        $_pagenum,$_pagesize");


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

<div id="photo">
    <h2><?php echo $_dirhtml['name']?></h2>
    <?php 
    if (empty($_dirhtml['type'])||$_COOKIE['photo'.$_dirhtml['id']]==$_dirhtml['name']||isset($_SESSION['admin'])){ 
        $_html=array();
        while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){
            $_html['id']=$_rows['tg_id'];
            $_html['username']=$_rows['tg_username'];
            $_html['name']=$_rows['tg_name'];
            $_html['url']=$_rows['tg_url'];
            $_html['readcount']=$_rows['tg_readcount'];
            $_html['commendcount']=$_rows['tg_commendcount'];
            $_html=_html($_html);         
        ?>
        <dl>
            <dt><a href="photo_detail.php?id=<?php echo $_html['id']?>"><img src="thumb.php?filename=<?php echo $_html['url']?>&percent=<?php echo $_percent?>" alt="" /></a></dt>
            <dd><a href="photo_detail.php?id=<?php echo $_html['id']?>"><?php echo $_html['name']?></a></dd>
            <dd>阅(<strong><?php echo $_html['readcount']?></strong>)评(<strong><?php echo $_html['commendcount']?></strong>)上传者：<?php echo $_html['username']?></dd>
            <?php if ($_html['username']==$_COOKIE['username']||isset($_SESSION['admin'])){?>
            <dd>[<a href="photo_show.php?action=delete&id=<?php echo $_html['id']?>">删除</a>]</dd>
            <?php }?>
        </dl>
        <?php }
        _free_result($_result);
        //_pageing函数调用分页，传入1|2，1表示数字分页，2表示文本分页。
            _pageing(1);
        ?>
        <?php if (isset($_COOKIE['username'])){?>
        <p><a href="photo_add_img.php?id=<?php echo $_dirhtml['id']?>">上传图片</a></p>
        <?php }
    }else {
        echo '<form method="post" action="photo_show.php?id='.$_dirhtml['id'].'">';
        echo '<p>请输入密码：<input type="password" name="password" /><input type="submit" value="提交"/></p>';
        echo '</form>';
    }
    
    
    
    ?>
    
    
    
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>