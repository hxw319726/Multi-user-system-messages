<?php
/**
* TestGuest Version1.0
* ================================================
* Copy 2016-2016 
* Web: http://www..com
* ================================================
* Author: word
* Date: 2016骞�9鏈�7鏃�
*/
session_start();
/*鍒ゆ柇鏄惁鏄潪娉曡皟鐢�*/
define('IN_TG', true);
/*瀹氫箟甯搁噺璇佹槑杩欐槸face*/
define('SCRIPT', 'face');
//寮曠敤鍏叡淇℃伅
require dirname(__FILE__).'/includes/common.inc.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
    //鍏叡澶存枃浠�
    require ROOT_PATH.'includes/title.inc.php';
?>
<script type="text/javascript" src="js/opener.js"></script>
</head>
<body>
    <div id="face">
        <h3>澶村儚閫夋嫨</h3>
        <dl>
            <?php foreach(range(1,9) as $num){?>
            <dd><img src="face/m0<?php echo $num?>.gif" alt="face/m0<?php echo $num?>.gif" title="头像<?php echo $num?>" /></dd>
            <?php }?>
            
        </dl>
        <dl>
            <?php foreach(range(10,64) as $num){?>
            <dd><img src="face/m<?php echo $num?>.gif" alt="face/m<?php echo $num?>.gif" title="头像<?php echo $num?>"/></dd>
            <?php }?>
            
        </dl>
    </div>
</body>
</html>