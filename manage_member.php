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
define('SCRIPT', 'manage_member');


//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if ((!isset($_COOKIE['username']))||(!isset($_SESSION['admin']))){
    _alert_back('非法登录！');
}
//分页模板
global $_pagenum,$_pagesize;
_page("SELECT tg_id,tg_username,tg_reg_time,tg_email FROM tg_user WHERE tg_level=0", 5);

//从数据库提取数据，获取结果集
$_result=_query("SELECT
                        tg_id,tg_username,tg_reg_time,tg_email
                   FROM 
                        tg_user 
                  WHERE
                        tg_level=0
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
        <form method="post" action="?action=delete">
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
                ?>
                <tr><td><?php echo $_html['id']?></td><td><?php echo $_html['username']?></td><td><?php echo $_html['email']?></td><td><?php echo $_html['reg_time']?></td><td><input name="ids[]" type="checkbox" value="<?php echo $_html['id'];?>"/></td></tr>
                <?php 
                }
                _free_result($_result);
                ?>
                <tr><td colspan="5"><label for="all">全选<input type="checkbox" name="chkall" id="all"/></label><input type="submit" value="批量删除"/></td></tr>
            </table>
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