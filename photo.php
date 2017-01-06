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
define('SCRIPT', 'photo');

//引用公共信息
require dirname(__FILE__).'/includes/common.inc.php';
//删除目录
if ($_GET['action']=='delete'&&isset($_GET['id'])){
    if(!!$_rows=_fetch_array("SELECT
                                    tg_uniqid
                                FROM
                                    tg_user
                                WHERE
                                    tg_username='{$_COOKIE['username']}'
                                LIMIT
                                    1"))
    {
        _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
        //取得这个目录的物理地址
        if(!!$_rows=_fetch_array("SELECT
                                        tg_dir
                                    FROM
                                        tg_dir
                                    WHERE
                                        tg_id='{$_GET['id']}'
                                    LIMIT
                                        1")){
            $_html=array();
            $_html['url']=$_rows['tg_dir'];
            $_html=_html($_html);


            //3.删除磁盘的目录
            if (file_exists($_html['url'])){
              if (d_rmdir($_html['url'])){
                  //1.删除目录里的数据库图片
                  _query("DELETE FROM tg_photo WHERE tg_sid='{$_GET['id']}'");
                  //2.删除目录里的数据库
                  _query("DELETE FROM tg_dir WHERE tg_id='{$_GET['id']}'");
                  mysql_close();
                  _location('目录删除成功！','photo.php');
              }else {
                mysql_close();
                _alert_back('目录删除失败！');
              }
            }         
        }else {
            _alert_back('不存在此目录！');
        }


    }else {
        _alert_back('非法登录！');
    }
}

//读取数据
//分页模板
global $_pagenum,$_pagesize,$_system;
_page("SELECT tg_id FROM tg_dir", $_system['photo']);
//从数据库提取数据，获取结果集
$_result=_query("SELECT
                        tg_id,
                        tg_name,
                        tg_type,
                        tg_face
                    FROM
                        tg_dir
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
</head>
<body>
<?php 
//     include 'includes/header.inc.php';
    require ROOT_PATH.'includes/header.inc.php';

?>

<div id="photo">
    <h2>相册目录</h2>
    <?php 
     $_html=array();
    while (!!$_rows=mysql_fetch_array($_result,MYSQL_ASSOC)){
        $_html['id']=$_rows['tg_id'];
        $_html['name']=$_rows['tg_name'];
        $_html['type']=$_rows['tg_type'];
        $_html['face']=$_rows['tg_face'];
        $_html=_html($_html); 
        if (empty($_html['type'])){
            $_html['type_html']='(公开)';
        }else{
            $_html['type_html']='(私密)';
        }
        if(empty($_html['face'])){
            $_html['face_html']='';
        }else {
            $_html['face_html']='<img src="'.$_html['face'].'" alt="'.$_html['name'].'"/>';
        }
        //统计相册里的照片数量
        $_html['photo']=_fetch_array("SELECT COUNT(*) AS count FROM tg_photo WHERE tg_sid='{$_html['id']}'");
      
    ?>
    <dl>
        <dt><?php echo $_html['face_html']?></dt>
        <dd><a href="photo_show.php?id=<?php echo $_html['id']?>"><?php echo $_html['name'];?><?php echo $_html['type_html'];?>(<?php echo $_html['photo']['count']?>)</a></dd>
        <?php if (isset($_SESSION['admin']) && isset($_COOKIE['username'])) {?>
        <dd>[<a href="photo_dir_modify.php?id=<?php echo $_html['id']?>">修改</a>] [<a href="photo.php?action=delete&id=<?php echo $_html['id']?>">删除</a>]</dd>
        <?php }?>
    </dl>
    <?php }
    _free_result($_result);
    //_pageing函数调用分页，传入1|2，1表示数字分页，2表示文本分页。
    _pageing(1);
    ?>
    <?php if (isset($_SESSION['admin']) && isset($_COOKIE['username'])) {?>
        <p><a href="photo_add_dir.php">添加目录</a></p>
   <?php }?>
</div>










<?php 
    require ROOT_PATH.'includes/footer.inc.php';
?>


</body>
</html>