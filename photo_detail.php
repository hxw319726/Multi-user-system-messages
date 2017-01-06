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
define('SCRIPT', 'photo_detail');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
global $_system;
//评论
if ($_GET['action']=='rephoto'){
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
        $_clean['sid']=$_POST['sid'];
        $_clean['title']=$_POST['title'];
        $_clean["content"]= $_POST["content"];
        $_clean["username"]= $_COOKIE['username'];
        $_clean=_mysql_string($_clean);
        _query("INSERT INTO tg_photo_commend(
                                        tg_sid,
                                        tg_username,
                                        tg_title,
                                        tg_content,
                                        tg_date
                                    )
                                        VALUES(
                                        '{$_clean['sid']}',
                                        '{$_clean['username']}',
                                        '{$_clean['title']}',
                                        '{$_clean['content']}',
                                        NOW()
                                    )
                                
                                    ");
        if (_affected_rows()==1){
            //累计评论量
            _query("UPDATE tg_photo SET
                                        tg_commendcount=tg_commendcount+1
                                  WHERE
                                        tg_id='{$_clean['sid']}'
                                        ");
            //关闭数据库清空session
            mysql_close();
            //弹出提示后跳转到首页
            _location("评论成功！", 'photo_detail.php?id='.$_clean['sid'].'');
    
        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _alert_back("评论失败！");
        }
    }else {
        _alert_back('唯一标识符异常！');
    }
}


//读取id
if (isset($_GET['id'])){
    if (!!$_rows=_fetch_array("SELECT 
                                    tg_id,
                                    tg_sid,
                                    tg_name,
                                    tg_url,
                                    tg_username,
                                    tg_readcount,
                                    tg_commendcount,
                                    tg_date,
                                    tg_content
                                FROM 
                                    tg_photo 
                               WHERE 
                                    tg_id='{$_GET['id']}'")){
        //防止加密相册图片穿插访问
        //可以先取得这个图片的sid，也就是他的目录
        //然后判断这个目录是否加密
        //如果是加密的 再判断是否有对应的cookie存在，并且等于相应的值
        //管理员不受这个限制
        if (!isset($_SESSION['admin'])){
            if (!!$_dirs=_fetch_array("SELECT tg_type,tg_id,tg_name FROM tg_dir WHERE tg_id='{$_rows['tg_sid']}'")){
                if (!empty($_dirs['tg_type'])&&$_COOKIE['photo'.$_dirs['tg_id']]!=$_dirs['tg_name']){
                    _alert_back('非法操作！');
                }
            }else {
                _alert_back('相册目录表出错了！');
            }
        }
                                    
        //累计阅读量
        _query("UPDATE tg_photo SET
                                    tg_readcount=tg_readcount+1
                               WHERE
                                    tg_id='{$_GET['id']}'
                                        ");                           
        $_html=array();
        $_html['id']=$_rows['tg_id'];
        $_html['sid']=$_rows['tg_sid'];
        $_html['name']=$_rows['tg_name'];
        $_html['url']=$_rows['tg_url'];
        $_html['username']=$_rows['tg_username'];
        $_html['readcount']=$_rows['tg_readcount'];
        $_html['commendcount']=$_rows['tg_commendcount'];
        $_html['date']=$_rows['tg_date'];
        $_html['content']=$_rows['tg_content'];
        $_html=_html($_html);
        
        
        //创建全局变量，做带参的分页
        global $_id;
        $_id='id='.$_html['id'].'&';
        //读取评论
        //分页模板
        global $_pagenum,$_pagesize,$_page;
        _page("SELECT tg_id FROM tg_photo_commend WHERE tg_sid='{$_html['id']}'", 5);
        //从数据库提取数据，获取结果集
        $_result=_query("SELECT
                                tg_username,tg_title,tg_content,tg_date
                            FROM
                                tg_photo_commend
                           WHERE
                                tg_sid='{$_html['id']}'
                        ORDER BY
                                tg_date ASC
                           LIMIT
                                $_pagenum,$_pagesize");
        
        //上一页，取得比自己大的id中，最小的那个即可
        $_html['preid']=_fetch_array("SELECT 
                                            min(tg_id) 
                                         AS
                                            id 
                                        FROM 
                                            tg_photo 
                                       WHERE 
                                            tg_sid='{$_html['sid']}' 
                                         AND  
                                            tg_id>'{$_html['id']}'
        ");
        if (!empty($_html['preid']['id'])){
            $_html['pre']='<a href="photo_detail.php?id='.$_html['preid']['id'].'" >上一页</a>';
        }else {
            $_html['pre']='<span>到头了</span>';
        }
       //下一页
        $_html['nextid']=_fetch_array("SELECT
                                            max(tg_id)
                                        AS
                                            id
                                        FROM
                                            tg_photo
                                        WHERE
                                            tg_sid='{$_html['sid']}'
                                        AND
                                            tg_id<'{$_html['id']}'
            ");
        if (!empty($_html['nextid']['id'])){
            $_html['next']='<a href="photo_detail.php?id='.$_html['nextid']['id'].'" >下一页</a>';
        }else {
            $_html['next']='<span>到头了</span>';
        }
        
    }else {
        _alert_back('不存在此图片！');
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
<script type="text/javascript" src="js/code.js"></script>
<script type="text/javascript" src="js/article.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="photo">
    <h2>图片详情</h2>
    
    <dl class="detail">
        <dd><?php echo $_html['name']?></dd>
        <dt><?php echo $_html['pre']?><img src="<?php echo $_html['url']?>" alt="" /><?php echo $_html['next']?></dt>
        <dd>[<a href="photo_show.php?id=<?php echo $_html['sid']?>">返回列表</a>]</dd>
        <dd>浏览量(<strong><?php echo $_html['readcount']?></strong>)&nbsp;评论量(<strong><?php echo $_html['commendcount']?></strong>)&nbsp;发表于：<?php echo $_html['date']?>&nbsp;上传者：<?php echo $_html['username']?></dd>
        <dd>简介：<?php echo $_html['content']?></dd>
    </dl>
    <?php 
    $_i=1;
    while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){
                $_html['username']=$_rows['tg_username'];
                $_html['title']=$_rows['tg_title'];
                $_html['content']=$_rows['tg_content'];
                $_html['redate']=$_rows['tg_date'];
                $_html=_html($_html);
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
                
                }else{
                    //这个用户已经被删除了
                
                }
     ?>
     <p class="line"></p> 
    <div class="re">
        <dl>
            <dd class="user"><?php echo $_html['username']?>(<?php echo $_html['sex']?>)</dd>
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
                <span><?php echo ($_i+($_page-1)*$_pagesize)?>#</span><?php echo $_html['username']?>|发表于：<?php echo $_html['redate']?>
            </div>
            <h3>主题：<?php echo $_html['title']?></h3>
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
    
    <?php 
    $_i++;

    }
    _free_result($_result);
    //_pageing函数调用分页，传入1|2，1表示数字分页，2表示文本分页。
    _pageing(1);
    ?>
    
    <?php if(isset($_COOKIE['username'])){?>
    <p class="line"></p> 
    <form method="post" action="?action=rephoto">
        <input type="hidden" name="sid" value="<?php echo $_html['id']?>"/>
        <dl class="rephoto">
            <dd>标&nbsp;题：<input type="text" name="title" class="text" value="RE:<?php echo $_html['name']?>"/>（*必填2-40位）</dd>
            <dd id="q">贴&nbsp;图：<a href="javascript:;">Q图系列[1]</a><a href="javascript:;">Q图系列[2]</a><a href="javascript:;">Q图系列[3]</a></dd>        
            <dd>
                <?php include ROOT_PATH.'includes/ubb.inc.php';?>
                <textarea name="content" rows="12"></textarea>
            </dd>
            <dd>验证码：<input type="text" name="yzm" class="text yzm"/><img src='
            code.php'id="code"/><input type="submit" name="button" class="submit" value="发表评论"/></dd>      
        </dl>
    </form>
    <?php } ?>   
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>