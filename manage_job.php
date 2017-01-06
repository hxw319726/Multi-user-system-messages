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
define('SCRIPT', 'manage_job');


//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if ((!isset($_COOKIE['username']))||(!isset($_SESSION['admin']))){
    _alert_back('非法登录！');
}

//添加管理员
if ($_GET['action']=='add'){
    //创建一个数组，用来存放提交的合法数据
    $_clean=array();
    $_clean['username']=$_POST['manage'];
    $_clean=_mysql_string($_clean);
    //判断唯一标识符是否正确
    if(!!$_rows=_fetch_array("SELECT
        tg_uniqid,
        tg_post_time
        FROM
        tg_user
        WHERE
        tg_username='{$_COOKIE['username']}'
        LIMIT
        1")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        _query("UPDATE 
                        tg_user
                   SET
                        tg_level=1
                 WHERE
                        tg_username='{$_clean['username']}'
                  
        ");
        if (_affected_rows()==1){
            mysql_close();
            //弹出提示后跳转到首页
            _location("修改成功！", "manage_job.php");
             
        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _alert_back("添加失败，不存在此用户！");
        }
    }else {
        _alert_back('非法登录！');
    }
}

//辞职
if ($_GET['action']=='job' && isset($_GET['id'])){
    //判断唯一标识符是否正确
    if(!!$_rows=_fetch_array("SELECT
                                    tg_uniqid
                                FROM
                                    tg_user
                                WHERE
                                    tg_uniqid='{$_COOKIE['uniqid']}'
                                LIMIT
                                    1"))
    {
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        _query("UPDATE
                        tg_user
                    SET
                        tg_level=0
                    WHERE
                        tg_username='{$_COOKIE['username']}'
                    AND 
                        tg_id='{$_GET['id']}'

            ");
        if (_affected_rows()==1){
            mysql_close();
            _session_destroy();
            //弹出提示后跳转到首页
            _location("辞职成功！", "index.php");
             
        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _alert_back("辞职失败！");
        }
    }else {
        _alert_back('非法登录！');
    }
}




//分页模板
global $_pagenum,$_pagesize;
_page("SELECT tg_id,tg_username,tg_reg_time,tg_email FROM tg_user WHERE tg_level=1", 5);

//从数据库提取数据，获取结果集
$_result=_query("SELECT
                        tg_id,tg_username,tg_reg_time,tg_email
                   FROM 
                        tg_user 
                  WHERE
                        tg_level=1
               ORDER BY 
                        tg_reg_time DESC 
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
<script type="text/javascript" src="js/member_message.js"></script>
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
        <h2>会员管理中心</h2>
            <table>
                <tr><th>ID</th><th>用户名</th><th>邮箱</th><th>注册时间</th><th>操作</th></tr>
                <?php 
                $_html=array();
                while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){                                  
                    $_html['id']=$_rows['tg_id'];
                    $_html['username']=$_rows['tg_username'];
                    $_html['reg_time']=$_rows['tg_reg_time'];
                    $_html['email']=$_rows['tg_email'];
                    $_html=_html($_html);
                    if ($_COOKIE['username']==$_html['username']){
                        $_html['job_html']='<a href="?action=job&id='.$_html['id'].'">辞职</a>';
                    }else {
                        $_html['job_html']='没有权限';
                    }
                ?>
                <tr><td><?php echo $_html['id']?></td><td><?php echo $_html['username']?></td><td><?php echo $_html['email']?></td><td><?php echo $_html['reg_time']?></td><td><?php echo $_html['job_html']?></td></tr>
                <?php 
                }
                _free_result($_result);
                ?>              
            </table>
            <form method="post" action="?action=add">
                <input type="text" name="manage" class="text" /><input type="submit"  class="submit" value="添加管理员"/>
            </form>
        <?php             
            //_pageing函数调用分页，传入1|2，1表示数字分页，2表示文本分页。
            _pageing(2);
        ?>
    </div>

</div>



<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>