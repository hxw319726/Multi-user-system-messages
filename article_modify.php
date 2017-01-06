<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月7日
*/
session_start();
error_reporting(E_ALL ^ E_NOTICE);
/*判断是否是非法调用*/
define('IN_TG', true);
/*定义常量证明这是*/
define('SCRIPT', 'article_modify');
//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';

//发帖前要先登录
if(!isset($_COOKIE['username'])){
    _location('发帖前要先登录!', 'login.php');
}
//修改帖子
if($_GET['action']=='modify'){
    //为防止恶意注册，跨站攻击
    _check_yzm($_POST['yzm'], $_SESSION['code']);
    //接收信息$_POST['username']污染数据
    //判断唯一标识符是否正确
    if(!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);

        //引入验证文件
        include ROOT_PATH.'includes/check.func.php';
        //创建一个数组，用来存放提交的合法数据
        $_clean=array();
        $_clean['id']=$_POST['id'];
        $_clean['type']=$_POST['type'];
        $_clean['title']=_check_post_title($_POST['title'],2,40);
        $_clean["content"]=_check_post_content($_POST["content"], 10);
        $_clean=_mysql_string($_clean);
        _query("UPDATE tg_article SET
                                    tg_type='{$_clean['type']}',
                                    tg_title='{$_clean['title']}',
                                    tg_content='{$_clean['content']}',
                                    tg_last_modify_date=NOW()
                                WHERE
                                    tg_id='{$_clean['id']}'"
        );
        if (_affected_rows()==1){
            //关闭数据库清空session
            mysql_close();
           // session_destroy();
            //弹出提示后跳转到首页
            _location("帖子修改成功！", "article.php?id=".$_clean['id']);
             
        }else {
            //关闭数据库
            mysql_close();
           // session_destroy();
            //弹出提示后跳转到首页
            _alert_back("帖子修改失败！");
        }


    }else {
        _alert_back('非法登录！');
    }
}
//读取数据
if (isset($_GET['id'])){
    if(!!$_rows=_fetch_array("SELECT
                                    tg_username,
                                    tg_title,
                                    tg_type,
                                    tg_content
                              FROM
                                    tg_article
                              WHERE
                                    tg_reid=0
                                AND
                                     tg_id='{$_GET['id']}'")){
    //存在
    $_html=array();
    $_html['id']=$_GET['id'];
    $_html['username']=$_rows['tg_username'];
    $_html['title']=$_rows['tg_title'];
    $_html['type']=$_rows['tg_type'];
    $_html['content']=$_rows['tg_content'];
    $_html=_html($_html);
    //判断是否有权限修改
    if($_html['username']!=$_COOKIE['username']){
        _alert_back('你没有权限修改！');
    }
    
    }else{
        _alert_back('不存在此帖！');
    }
    
}else {
    _alert_back('非法操作！');
}



//开始时间磋
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
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/post.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>
<div id="post">
    <h2>修改帖子</h2>
    <form method="post" action="?action=modify" name="post">
    <input type="hidden" name="id" value="<?php echo $_html['id'];?>"/>
        <dl>
            <dt>请认真填写以下内容</dt>
            <dd class="type">类&nbsp;型：
                <?php 
                foreach (range(1, 16) as $_num){
                    if($_num==$_html['type']){
                        echo '<label for="type'.$_num.'"><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'" checked="checked"/>';
                    }else{
                        echo '<label for="type'.$_num.'"><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'"/>';
                    }
                    echo '<img src="images/icon'.$_num.'.gif" alt="类型"/></label>';
                    if($_num==8){
                        echo '<br/>&nbsp;&nbsp;&nbsp;&nbsp; ';
                    }
                }
                    
                ?>
            </dd>
            <dd>标&nbsp;题：<input type="text" name="title" value="<?php echo $_html['title']?>" class="text"/>（*必填2-40位）</dd>
            <dd id="q">贴&nbsp;图：<a href="javascript:;">Q图系列[1]</a><a href="javascript:;">Q图系列[2]</a><a href="javascript:;">Q图系列[3]</a></dd>        
            <dd>
                <?php include ROOT_PATH.'includes/ubb.inc.php';?>
                <textarea name="content" rows="12"><?php echo $_html['content']?></textarea>
            </dd>
            <dd>验证码：<input type="text" name="yzm" class="text yzm"/><img src='
            code.php'id="code"/><input type="submit" name="button" class="submit" value="修改帖子"/></dd>
        </dl>
    </form>
</div>



<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>