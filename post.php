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
define('SCRIPT', 'post');
//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';

//发帖前要先登录
if(!isset($_COOKIE['username'])){
    _location('发帖前要先登录!', 'login.php');
}
//发帖
if($_GET['action']=='post'){
    //为防止恶意注册，跨站攻击
    _check_yzm($_POST['yzm'], $_SESSION['code']);
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
        global $_system;
        //验证是否在规定时间内发帖！
        _timed(time(), $_rows['tg_post_time'], $_system['post']);
        
        //引入验证文件
        include ROOT_PATH.'includes/check.func.php';
        //创建一个数组，用来存放提交的合法数据
        $_clean=array();
        $_clean['username']=$_COOKIE['username'];
        $_clean['type']=$_POST['type'];
        $_clean['title']=_check_post_title($_POST['title'],2,40);
        $_clean["content"]=_check_post_content($_POST["content"], 10);
        $_clean=_mysql_string($_clean);
        _query("INSERT INTO tg_article(
                                    tg_username,
                                    tg_type,
                                    tg_title,
                                    tg_content,
                                    tg_date
                                   
                                            ) VALUES(
                                                '{$_clean['username']}',
                                                '{$_clean['type']}',
                                                '{$_clean['title']}',
                                                '{$_clean['content']}',
                                                NOW()
                                                
                                                                            )"
        );
        if (_affected_rows()==1){
            $_clean['id']=_insert_id();
//             //用cookie验证发帖间隔
//             setcookie('post_time',time());
            //数据库验证发帖
            $_clean['time']=time();
            _query("UPDATE tg_user SET tg_post_time='{$_clean['time']}' WHERE tg_username='{$_COOKIE['username']}'");
            //关闭数据库清空session
            mysql_close();
            session_destroy();
            //弹出提示后跳转到首页
            _location("帖子发表成功！", "article.php?id=".$_clean['id']);
             
        }else {
            //关闭数据库
            mysql_close();
            session_destroy();
            //弹出提示后跳转到首页
            _alert_back("帖子发表失败！");
        }
        

    }
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
    <h2>发表帖子</h2>
    <form method="post" action="?action=post" name="post">
        <dl>
            <dt>请认真填写以下内容</dt>
            <dd class="type">类&nbsp;型：
                <?php 
                foreach (range(1, 16) as $_num){
                    if($_num==1){
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
            <dd>标&nbsp;题：<input type="text" name="title" class="text"/>（*必填2-40位）</dd>
            <dd id="q">贴&nbsp;图：<a href="javascript:;">Q图系列[1]</a><a href="javascript:;">Q图系列[2]</a><a href="javascript:;">Q图系列[3]</a></dd>        
            <dd>
                <?php include ROOT_PATH.'includes/ubb.inc.php';?>
                <textarea name="content" rows="12"></textarea>
            </dd>
            <dd>验证码：<input type="text" name="yzm" class="text yzm"/><img src='
            code.php'id="code"/><input type="submit" name="button" class="submit" value="发表帖子"/></dd>
        </dl>
    </form>
</div>



<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>