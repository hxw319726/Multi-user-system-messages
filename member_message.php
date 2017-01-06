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
define('SCRIPT', 'member_message');


//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if (!isset($_COOKIE['username'])){
    _alert_back('请先登录！');
}
//批删除短信
if ($_GET['action']=='delete' && isset($_POST['ids'])){
    $_clean=array();
    $_clean['ids']=_mysql_string(implode(',', $_POST['ids']));
    //进行危险操作要进行唯一标识符的验证
    if(!!$_rows2=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")){
        _uniqid($_rows2['tg_uniqid'], $_COOKIE['uniqid']);
        _query("DELETE FROM tg_message WHERE tg_id IN({$_clean['ids']})");
        if (_affected_rows()){
            //关闭数据库清空session
            mysql_close();
            //弹出提示后跳转到首页
            _location("短信删除成功！", "member_message.php");
             
        }else {
            //关闭数据库
            mysql_close();
            //弹出提示后跳转到首页
            _alert_back("短信删除失败！");
        }
    }else{
        _alert_back('非法登录！');
    }
    

}

//分页模板
global $_pagenum,$_pagesize;
_page("SELECT tg_id FROM tg_message WHERE tg_touser='{$_COOKIE['username']}'", 5);

//从数据库提取数据，获取结果集
$_result=_query("SELECT
                        tg_id,tg_state,tg_fromuser,tg_content,tg_date 
                   FROM 
                        tg_message 
                  WHERE 
                        tg_touser='{$_COOKIE['username']}'
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
<script type="text/javascript" src="js/member_message.js"></script>
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
        <h2>短信管理中心</h2>
        <form method="post" action="?action=delete">
            <table>
                <tr><th>发信人</th><th>短信内容</th><th>时间</th><th>状态</th><th>操作</th></tr>
                <?php 
                $_html=array();
                while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){                                  
                    $_html['id']=$_rows['tg_id'];
                    $_html['fromuser']=$_rows['tg_fromuser'];
                    $_html['content']=$_rows['tg_content'];
                    $_html['date']=$_rows['tg_date'];
                    $_html=_html($_html);
                    if(empty($_rows['tg_state'])){
                        $_html['state']='<img src="images/read.gif" alt="未读"/>';
                    }else{
                        $_html['state']='<img src="images/noread.gif" alt="已读"/>';
                    }
                ?>
                <tr><td><?php echo $_html['fromuser']?></td><td><a href="member_message_detail.php?id=<?php echo $_html['id']?>" title="<?php echo $_html['content']?>"><?php echo _title($_html['content'])?></a></td><td><?php echo $_html['date']?></td><td><?php echo $_html['state']?></td><td><input name="ids[]" type="checkbox" value="<?php echo $_html['id'];?>"/></td></tr>
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