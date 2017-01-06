
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
define('SCRIPT', 'article');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
global $_system;
//读取回帖数据
if($_GET['action']=='rearticle'){
       //为防止恶意注册，跨站攻击
    _check_yzm($_POST['yzm'], $_SESSION['code']);
    //判断唯一标识符是否正确
    if(!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        //判断回复间隔
        _timed(time(), $_COOKIE['article_time'], $_system['re']);
    
        //引入验证文件
        include ROOT_PATH.'includes/check.func.php';
        //创建一个数组，用来存放提交的合法数据
        $_clean=array();
        $_clean['reid']=$_POST['reid'];
        $_clean['type']=$_POST['type'];
        $_clean['title']=$_POST['title'];
        $_clean["content"]= $_POST["content"];
        $_clean["username"]= $_COOKIE['username'];
        $_clean=_mysql_string($_clean);
        _query("INSERT INTO tg_article(
                                        tg_reid,
                                        tg_username,
                                        tg_title,
                                        tg_type,
                                        tg_content,
                                        tg_date
                                        )
                                 VALUES(
                                        '{$_clean['reid']}',
                                        '{$_clean['username']}',
                                        '{$_clean['title']}',
                                        '{$_clean['type']}',
                                        '{$_clean['content']}',
                                        NOW()
                                        )
                                
          ");
        if (_affected_rows()==1){
            setcookie('article_time',time());
            //累计评论量
            _query("UPDATE tg_article SET
                                            tg_commendcount=tg_commendcount+1
                                     WHERE
                                            tg_reid=0
                                        AND
                                            tg_id='{$_clean['reid']}'
                                                                    ");
            //关闭数据库清空session
            mysql_close();
            //session_destroy();
            //弹出提示后跳转到首页
            _location("回帖成功！", 'article.php?id='.$_clean['reid'].'');
    
        }else {
            //关闭数据库
            mysql_close();
            //session_destroy();
            //弹出提示后跳转到首页
            _alert_back("回帖失败！");
        }
    }else {
        _alert_back('唯一标识符异常！');
    }
    
    
    
    
}



//读出数据
if(isset($_GET['id'])){
    if(!!$_rows=_fetch_array("SELECT 
                                     tg_id,
                                     tg_username,
                                     tg_title,
                                     tg_type,
                                     tg_content,
                                     tg_readcount,
                                     tg_commendcount,
                                     tg_date,
                                     tg_last_modify_date
                                FROM 
                                     tg_article 
                                WHERE 
                                     tg_reid=0
                                 AND
                                      tg_id='{$_GET['id']}'")){
       //累计阅读量
        _query("UPDATE tg_article SET
                                    tg_readcount=tg_readcount+1 
                                    WHERE
                                    tg_id='{$_GET['id']}'
       ");
                                                              
                                      
                                      
      //存在  
      $_html=array();
      $_html['reid']=$_rows['tg_id'];
      $_html['username_subject']=$_rows['tg_username'];
      $_html['articleid']=$_rows['tg_id'];
      $_html['title']=$_rows['tg_title'];
      $_html['type']=$_rows['tg_type'];
      $_html['content']=$_rows['tg_content'];
      $_html['readcount']=$_rows['tg_readcount'];
      $_html['commendcount']=$_rows['tg_commendcount'];
      $_html['date']=$_rows['tg_date'];
      $_html['last_modify_date']=$_rows['tg_last_modify_date'];
      
      //拿出用户名去查找用户信息
      if(!!$_rows=_fetch_array("SELECT 
                                     tg_id,
                                     tg_face,
                                     tg_sex,
                                     tg_email,
                                     tg_url,
                                     tg_switch,
                                     tg_autograph
                                FROM 
                                     tg_user 
                                WHERE 
                                      tg_username='{$_html['username_subject']}'")){
        //提取用户信息
        $_html['userid']=$_rows['tg_id'];
        $_html['face']=$_rows['tg_face'];
        $_html['sex']=$_rows['tg_sex'];
        $_html['email']=$_rows['tg_email'];
        $_html['url']=$_rows['tg_url'];
        $_html['switch']=$_rows['tg_switch'];
        $_html['autograph']=$_rows['tg_autograph'];
        $_html=_html($_html);
        
        
        
        //创建全局变量，做带参的分页
        global $_id;
        $_id='id='.$_html['reid'].'&';
        //主题帖修改
        if($_html['username_subject']==$_COOKIE['username']){
            $_html['subject_modify']='[<a href="article_modify.php?id='.$_html['reid'].'">修改</a>]';
        }
        if ($_html['last_modify_date']!='0000-00-00 00:00:00'){
            $_html['last_modify_date_string']='本帖已由['.$_html['username_subject'].']于'.$_html['last_modify_date'].'修改！';
        }
        //给楼主回复
        if ($_COOKIE['username']){
            $_html['re']='<span>[<a href="#re" title="回复1楼的'. $_html['username_subject'].'" name="re">回复</a>]</span>';
        }
        //个性签名
        if($_html['switch']==1){
            $_html['autograph_html']= '<p class="autograph">'. $_html['autograph'].'</p>';
        }
        //读取回帖
        //分页模板
        global $_pagenum,$_pagesize,$_page;
        _page("SELECT tg_id FROM tg_article WHERE tg_reid='{$_html['reid']}'", 5);
        //从数据库提取数据，获取结果集
        $_result=_query("SELECT
                                tg_username,tg_type,tg_title,tg_content,tg_date
                           FROM
                                tg_article
                          WHERE
                                tg_reid='{$_html['reid']}'
                       ORDER BY
                                tg_date ASC
                          LIMIT
                                $_pagenum,$_pagesize");
        
        
      }else{
          //这个用户已经被删除了
          
      }
        
    }else{
        _alert_back('不存在这个帖子！');
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
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/article.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="article">
    <h2>文章列表</h2>
    <?php if ($_page==1){;?>
    <div id="subject">
        <dl>
            <dd class="user"><?php echo $_html['username_subject']?>(<?php echo $_html['sex']?>)[楼主]</dd>
            <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['face']?>" title="<?php echo $_html['username_subject']?>"/></dt>
            <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
            <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']?>">加为好友</a></dd>
            <dd class="guest">写留言</dd>
            <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']?>">给他送花</a></dd>
            <dd class="email">邮件：<a href="mailto:<?php echo $_html['email']?>"><?php echo $_html['email']?></a></dd>
            <dd class="url">网址：<a href="<?php echo $_html['url']?>" target="_blank"><?php echo $_html['url']?></a></dd>     
        </dl>
        <div class="content">
            <div class="user">
                <span><?php echo $_html['subject_modify']?>1#</span><?php echo $_html['username_subject']?>|发表于：<?php echo $_html['date']?>
            </div>
            <h3>主题：<?php echo $_html['title']?><img src="images/icon<?php echo $_html['type']?>.gif" alt="" /><?php echo $_html['re']?></h3>
            <div class="detail">
              <?php echo _ubb($_html['content']);
                    echo $_html['autograph_html'];
              ?>  
                              
            </div>
            <div class="read">
            <p><?php echo $_html['last_modify_date_string']?></p>
                                             阅读量:(<?php echo $_html['readcount']?>)评论量:(<?php echo $_html['commendcount']?>)
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <p class="line"></p> 
    <?php }?>
    <?php 
    $_i=2;
    while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){
                $_html['username']=$_rows['tg_username'];
                $_html['type']=$_rows['tg_type'];
                $_html['retitle']=$_rows['tg_title'];
                $_html['content']=$_rows['tg_content'];
                $_html['date']=$_rows['tg_date'];
                $_html=_html($_html);
                //拿出用户名去查找用户信息
                if(!!$_rows=_fetch_array("SELECT
                                                tg_id,
                                                tg_face,
                                                tg_sex,
                                                tg_email,
                                                tg_url,
                                                tg_switch,
                                                tg_autograph
                                            FROM
                                                tg_user
                                           WHERE
                                                tg_username='{$_html['username']}'")){
                    //提取用户信息
                $_html['userid']=$_rows['tg_id'];
                $_html['face']=$_rows['tg_face'];
                $_html['sex']=$_rows['tg_sex'];
                $_html['email']=$_rows['tg_email'];
                $_html['url']=$_rows['tg_url'];
                $_html['switch']=$_rows['tg_switch'];
                $_html['autograph']=$_rows['tg_autograph'];
                $_html=_html($_html);
                //沙发判断
                if ($_i==2 && $_page==1){
                    if($_html['username']==$_html['username_subject']){
                        $_html['username_html']=$_html['username'].'[楼主]';
                    }else {
                        $_html['username_html']=$_html['username'].'[沙发]';
                    }
                    
                }else {
                    $_html['username_html']=$_html['username'];
                }
                

                
                
                }else{
                    //这个用户已经被删除了
                
                }
                
                
                if($_COOKIE['username']){
                    $_html['re']='<span>[<a href="#re" title="回复'. ($_i+($_page-1)*$_pagesize).'楼的'. $_html['username'].'" name="re">回复</a>]</span>';
                }
        
        
    ?>
    <div class="re">
        <dl>
            <dd class="user"><?php echo $_html['username_html']?>(<?php echo $_html['sex']?>)</dd>
            <dt><img src="<?php echo $_html['face']?>" alt="<?php echo $_html['face']?>" title="<?php echo $_html['username']?>"/></dt>
            <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']?>">发消息</a></dd>
            <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']?>">加为好友</a></dd>
            <dd class="guest">写留言</dd>
            <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']?>">给他送花</a></dd>
            <dd class="email">邮件：<a href="mailto:<?php echo $_html['email']?>"><?php echo $_html['email']?></a></dd>
            <dd class="url">网址：<a href="<?php echo $_html['url']?>" target="_blank"><?php echo $_html['url']?></a></dd>     
        </dl>
        <div class="content">
            <div class="user">
                <span><?php echo ($_i+($_page-1)*$_pagesize)?>#</span><?php echo $_html['username']?>|发表于：<?php echo $_html['date']?>
            </div>
            <h3>主题：<?php echo $_html['retitle']?><img src="images/icon<?php echo $_html['type']?>.gif" alt="" /><?php echo $_html['re']?></h3>
            <div class="detail">
              <?php echo _ubb($_html['content'])?> 
              <?php         
                //个性签名
                if($_html['switch']==1){
                    echo  '<p class="autograph">'. _ubb($_html['autograph']).'</p>';
                }
        ?>                
            </div>
        </div> 
        <div style="clear: both;"></div>
    </div>
    <p class="line"></p>
    <?php
    $_i++; }
    
    _free_result($_result);
    //_pageing函数调用分页，传入1|2，1表示数字分页，2表示文本分页。
    _pageing(1);
    ?> 
    <?php if(isset($_COOKIE['username'])){?>
    <form method="post" action="?action=rearticle">
        <a id="re"></a>
        <input type="hidden" name="reid" value="<?php echo $_html['reid']?>"/>
        <input type="hidden" name="type" value="<?php echo $_html['type']?>"/>
        <dl>
            <dd>标&nbsp;题：<input type="text" name="title" class="text" value="RE:<?php echo $_html['title']?>"/>（*必填2-40位）</dd>
            <dd id="q">贴&nbsp;图：<a href="javascript:;">Q图系列[1]</a><a href="javascript:;">Q图系列[2]</a><a href="javascript:;">Q图系列[3]</a></dd>        
            <dd>
                <?php include ROOT_PATH.'includes/ubb.inc.php';?>
                <textarea name="content" rows="12"></textarea>
            </dd>
            <dd>验证码：<input type="text" name="yzm" class="text yzm"/><img src='
            code.php'id="code"/><input type="submit" name="button" class="submit" value="发表帖子"/></dd>      
        </dl>
    </form>
    <?php }

    ?>
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>