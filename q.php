<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016.9.24
*/
session_start();
/*鍒ゆ柇鏄惁鏄潪娉曡皟鐢�*/
define('IN_TG', true);
/*瀹氫箟甯搁噺璇佹槑杩欐槸face*/
define('SCRIPT', 'q');
//寮曠敤鍏叡淇℃伅
require dirname(__FILE__).'/includes/common.inc.php';

//贴图初始化
if (isset($_GET['num'])&&isset($_GET['path'])){
    if (!is_dir(ROOT_PATH.$_GET['path'])){
        _alert_back('非法操作！');
    }
    
}else {
    _alert_back('非法操作！');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
    //鍏叡澶存枃浠�
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/qpopener.js"></script>
</head>
<body>
    <div id="q">
        <h3>Q图</h3>
        <dl>
            <?php foreach(range(1,$_GET['num']) as $_num){?>
            <dd><img src="<?php echo $_GET['path'].$_num?>.gif" alt="<?php echo $_GET['path'].$_num?>.gif" title="头像<?php echo $_num?>" /></dd>
            <?php }?>
            
        </dl>
    </div>
</body>
</html>