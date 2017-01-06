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
define('SCRIPT', 'blog');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//分页模板
global $_pagenum,$_pagesize,$_system;
_page("SELECT tg_id FROM tg_user", $_system['blog']);





//从数据库提取数据，获取结果集
$_result=_query("SELECT 
                        tg_id,
                        tg_username,    
                        tg_sex,
                        tg_face 
                  FROM 
                        tg_user 
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
<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="blog">
    <h2>博友列表</h2>
    <?php 
    $_html=array();
        while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){
            $_html['id']=$_rows['tg_id'];
            $_html['username']=$_rows['tg_username'];
            $_html['sex']=$_rows['tg_sex'];
            $_html['face']=$_rows['tg_face'];
            $_html=_html($_html);         
    ?>
    <dl>
        <dd class="user"><?php echo $_html['username']?>(<?php echo $_html['sex']?>)</dd>
        <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['username']?>" title="<?php echo $_html['username']?>"/></dt>
        <dd class="message"><a href="javascript:;" name="message"<?php echo 'title='.$_html[id]?>>发消息</a></dd>
        <dd class="friend"><a href="javascript:;" name="friend"<?php echo 'title='.$_html[id]?>>加为好友</a></dd>
        <dd class="guest">写留言</dd>
        <dd class="flower"><a href="javascript:;" name="flower"<?php echo 'title='.$_html[id]?>>给他送花</a></dd>     
    </dl>
    <?php }
    _free_result($_result);
    //_pageing函数调用分页，传入1|2，1表示数字分页，2表示文本分页。
        _pageing(2);
    ?>
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>