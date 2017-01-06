<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016年9月6日
*/
session_start();
//判断是否是非法调用
if(!defined('IN_TG')){
    exit('非法调用');
}
?>
<div class="header">
    <h1>php多用户留言系统</h1>
    <ul>        
        
        <li><a href="index.php">首页</a></li>
        <?php 
            if(isset($_COOKIE['username'])){
                echo '<li><a href="member.php">'.$_COOKIE['username'].'•个人中心</a>'.$GLOBALS['message'].'</li>';
                echo "\n";
            }else{
                echo '<li><a href="reg.php">注册</a></li>';
                echo "\n";
                echo "\t";
                echo '<li><a href="login.php">登录</a></li>';
                echo "\n";
            }                  
        ?>
        <li><a href="blog.php">博友</a></li>
        <li><a href="photo.php">相册</a></li>
        <li><a href="#">风格</a></li>
        
        <?php 
            if (isset($_COOKIE['username']) && isset($_SESSION['admin'])){
                echo '<li><a href="manage.php">管理</a></li>';
            }
        
        
            if(isset($_COOKIE['username'])){
                echo '<li><a href="logout.php">退出</a></li>';
            }
        ?>        
    </ul>
</div>