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
define('SCRIPT', 'member_modify');


//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//修改资料
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
        $_clean['password']=_check_modify_password($_POST['password'], 6);
        $_clean['sex']=_check_sex($_POST['sex']);
        $_clean['face']=_check_face($_POST['face']);
        $_clean["email"]= _check_email($_POST["email"],6,40);
        $_clean["qq"]= _check_qq($_POST["qq"]);
        $_clean["url"]= _check_http($_POST["url"],40);
        $_clean['switch']=$_POST['switch'];
        $_clean['autograph']=_check_autograph($_POST['autograph'], 200);
        if(empty($_clean['password'])){
            _query("UPDATE tg_user SET
                                        tg_sex='{$_clean['sex']}',
                                        tg_face='{$_clean['face']}',
                                        tg_email='{$_clean['email']}',
                                        tg_qq='{$_clean['qq']}',
                                        tg_url='{$_clean['url']}',
                                        tg_switch='{$_clean['switch']}',
                                        tg_autograph='{$_clean['autograph']}'
                                WHERE
                                        tg_username='{$_COOKIE['username']}'
                                        ");
        }else{
            _query("UPDATE tg_user SET
                                        tg_password='{$_clean['password']}',
                                        tg_sex='{$_clean['sex']}',
                                        tg_face='{$_clean['face']}',
                                        tg_email='{$_clean['email']}',
                                        tg_qq='{$_clean['qq']}',
                                        tg_url='{$_clean['url']}',
                                        tg_switch='{$_clean['switch']}',
                                        tg_autograph='{$_clean['autograph']}'
                                WHERE
                                        tg_username='{$_COOKIE['username']}'
                                        ");
        }
        if (_affected_rows()==1){
            //关闭数据库清空session
            mysql_close();
            //session_destroy();
            //弹出提示后跳转到首页
            _location("恭喜您，修改成功！", "member.php");
    
        }else {
            //关闭数据库
            mysql_close();
            //session_destroy();
            //弹出提示后跳转到首页
            _location("很遗憾，没有任何被修改！", "member_modify.php");
        }
    }
}
//判断是否正常登入
if(isset($_COOKIE['username'])){
    //获取数据
    $_rows=_fetch_array("SELECT
                                tg_username,tg_sex,tg_face,tg_email,tg_url,tg_qq,tg_switch,tg_autograph 
                            FROM 
                                tg_user 
                            WHERE 
                                tg_username='{$_COOKIE['username']}'");
    //判断这个数据是不是有值
    if($_rows){
        $_html=array();
        $_html['username']=$_rows['tg_username'];
        $_html['sex']=$_rows['tg_sex'];
        $_html['face']=$_rows['tg_face'];
        $_html['email']=$_rows['tg_email'];
        $_html['url']=$_rows['tg_url'];
        $_html['qq']=$_rows['tg_qq'];
        $_html['reg_time']=$_rows['tg_reg_time'];
        $_html['switch']=$_rows['tg_switch'];
        $_html['autograph']=$_rows['tg_autograph'];
        $_html=_html($_html);
        
        //修改性别
       if ($_html['sex']=='女'){
           $_html['sex_html']='<input type="radio" name="sex" value="女" checked="checked"/>女<input type="radio" name="sex" value="男"/>男';
       }else if($_html['sex']=='男'){
           $_html['sex_html']='<input type="radio" name="sex" value="男" checked="checked"/>男<input type="radio" name="sex" value="女"/>女';
       }
        
       //修改头像
       $_html['face_html']='<select name="face">';
       foreach (range(1, 9) as $_num){
           if ($_html['face']=='face/m0'.$_num.'.gif'){
                $_html['face_html'].='<option value="face/m0'.$_num.'.gif" selected="selected">face/m0'.$_num.'.gif</option>';
           }else {
               $_html['face_html'].='<option value="face/m0'.$_num.'.gif">face/m0'.$_num.'.gif</option>';
           }
       }
       foreach (range(10, 64) as $_num){
           if ($_html['face']=='face/m'.$_num.'.gif'){
               $_html['face_html'].='<option value="face/m'.$_num.'.gif" selected="selected">face/m'.$_num.'.gif</option>';
           }else {
               $_html['face_html'].='<option value="face/m'.$_num.'.gif">face/m'.$_num.'.gif</option>';
           }
       }
       $_html['face_html'].='</select>';
       
       //修改个性签名
       if ($_html['switch']==0){
           $_html['switch_html']='<input type="radio" name="switch" value="1" />启用<input type="radio" name="switch" value="0" checked="checked"/>禁用';
       }else if($_html['switch']==1){
           $_html['switch_html']='<input type="radio" name="switch" value="1" checked="checked"/>启用<input type="radio" name="switch" value="0"/>禁用';
       } 
       

    }else{
        _alert_back('此用户不存在！');
    }

}else{
    _alert_back('非法登录！');
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
<script type="text/javascript" src="js/member_modify.js"></script>
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="member">
<?php 
    require ROOT_PATH.'includes/member.inc.php';
?>
    <div id="member_main">
        <h2>会员管理中心</h2>
        <form method="post" action="member_modify.php?action=modify">
            <dl>
                <dd>用 户 名：<?php echo $_html['username'];?></dd>
                <dd>密 &nbsp; 码：<input type="password" name="password" class="text"/>（不填则不修改）</dd>  
                <dd>性 &nbsp; 别：<?php echo $_html['sex_html'];?></dd>
                <dd>头 &nbsp; 像：<?php echo $_html['face_html'];?></dd>
                <dd>电子邮件：<input type="text" class="text" name="email" value="<?php echo $_html['email'];?>"/></dd>
                <dd>主 &nbsp; 页：<input type="text" class="text" name="url" value="<?php echo $_html['url'];?>"/></dd>
                <dd>Q &nbsp;&nbsp; Q：<input type="text" class="text" name="qq" value="<?php echo $_html['qq'];?>"/></dd>
                <dd>个性签名：<?php echo $_html['switch_html']?>&nbsp;&nbsp;(可以使用ubb)
                    <p><textarea  name="autograph" ><?php echo $_html['autograph']?></textarea></p>
                </dd>
                <dd>验 证 码：<input type="text" name="yzm" class="text yzm"/><img src="code.php" id="code"/><input type="submit" name="button" class="submit" value="修改"/></dd>
            </dl>
        </form>
    </div>
</div>


<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>