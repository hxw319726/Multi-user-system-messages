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

//判断是否是非法调用
if(!defined('IN_TG')){
    exit('非法调用');
}
?>
<div class="footer">
    <p>本程序执行耗时：<?php     echo round(_runtime()-START_TIME,4);?>秒</p>
    <p>版权所有翻版必究©</p>
    <p>本程序由word开发，源码可以任意修改和使用</p>
</div>