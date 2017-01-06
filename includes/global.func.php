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
//核心函数库
/**
 * d_rmdir()//删除非空目录
 * @param $dirname 目录地址
 * @access public
 */
function d_rmdir($dirname) {  
    if(!is_dir($dirname)) {
        return false;
    }
    $handle = @opendir($dirname);
    while(($file = @readdir($handle)) !== false){
        if($file != '.' && $file != '..'){
            $dir = $dirname . '/' . $file;
            is_dir($dir) ? d_rmdir($dir) : unlink($dir);
        }
    }
    closedir($handle);
    return rmdir($dirname);
}




/**
 * _timed()判断发帖回复间隔
 * @param $_now_time当前时间
 * @param $_pre_time过去时间
 * @param $_second 间隔多少秒
 * @access public
 * @return float
 */
function _timed($_now_time,$_pre_time,$_second){
    if(($_now_time-$_pre_time)<$_second){
        _alert_back('请阁下休息一下在发帖！');
    }
}
/**
 * _runtime()获取执行耗时
 * @access public
 * @return float
 */
function _runtime(){
    $_mtime=explode(' ', microtime());
    return $_mtime[1]+$_mtime[0];
}

/**
 * 	_alert_back()弹出js小程序
 *  @param $_info弹出的信息
 * 	@access public
 * 	@return string
 */
function _alert_back($_info){
	echo "<script type='text/javascript'>alert('".$_info."');history.back();</script>";
	exit();
}
/**
 * 	_alert_close()弹出js小程序自动关闭window窗口
 *  @param $_info弹出的信息
 * 	@access public
 * 	@return string
 */
function _alert_close($_info){
    echo "<script type='text/javascript'>alert('".$_info."');window.close();</script>";
    exit();
}
/**
 * 	_alert_back()弹出js小程序
 *  @param $_info弹出的信息
 * 	@access public
 * 	@return string
 */
function _location($_info,$_url){
    if(!empty($_info)){
    	echo "<script type='text/javascript'>alert('$_info');location.href='$_url';</script>";
    	exit();
    }else{
        header('Location:'.$_url);
    }
}
/**
 * 	_unsetcookies()删除cookie
 * 	@access public
 * 
 */
function _unsetcookies(){
    setcookie('username','',time()-1);
    setcookie('uniqid','',time()-1);
    session_destroy();
    _location(null, 'index.php');
}
/**
 * 	_session_destroy()删除session
 * 	@access public
 *
 */
function _session_destroy(){
	if(session_start()){
		session_destroy();
	}
}

function _login_state(){
    if(isset($_COOKIE['username'])){
        _alert_back('登录状态无法进行本操作！');
    }
}

/**
 * 	_mysql_string()判断是否自带转义功能
 *  @param $_string转义字符串或数组
 * 	@access public
 * 	@return string
 */
function _mysql_string($_string){
   if(!GPC){
       if(is_array($_string)){
           foreach ($_string as $_key=>$_value){
               //$_string[$_key]=htmlspecialchars($_value);
               $_string[$_key]=_mysql_string($_value);//用递归
           }
       }else{
           $_string=mysql_real_escape_string($_string);
       }
   }
   return $_string;    
}
/**
 * 	_sha1_uniqid()生成唯一标识符
 * 	@access public
 * 	@return string返回唯一标识符
 */
function _sha1_uniqid(){
    return _mysql_string(sha1(uniqid(rand(),true)));
}
/**
 * 	_check_yzm()验证码验证函数
 *  @param $_first_code
 *  @param $_end_code
 * 	@access public
 * 
 */
function _check_yzm($_first_code,$_end_code){
	if(!($_first_code==$_end_code)){
		_alert_back('验证码不正确');
	}
}
/**
 * 	_code()生成验证码函数
 *	验证码宽度$_width=75;
 *	验证码高度$_height=25;
 * 	随机数个数$_rnd_code
 * 	是否要边框$flag=false/true
 * 	@access public
 * 	@return void
 */
function _code($_width=75,$_height=25,$_rnd_code=4,$flag=false){
	$_nmsg='';
	//创建随机数
	for($i=0;$i<$_rnd_code;$i++){
	 	$_nmsg.=dechex(mt_rand(0, 15));
	}
	
	
	//保持在session
	$_SESSION['code']=$_nmsg;

	//创建一张图片
	$_img=imagecreatetruecolor($_width, $_height);
	//为一张图片分配颜色
	$white=imagecolorallocate($_img, 255, 255, 255);
	imagefill($_img, 0, 0, $white);
	if($flag){
		//绘制黑边框
		$_black=imagecolorallocate($_img, 0, 0, 0);
		imagerectangle($_img, 0, 0, $_width-1, $_height-1, $_black);	
	}
	//随机画线条
	for($i=0;$i<6;$i++){
		$_rnd_color=imagecolorallocate($_img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		imageline($_img,mt_rand(0,$_width), mt_rand(0,$_height), mt_rand(0,$_width), mt_rand(0,$_height), $_rnd_color);
	}
	//随机雪花
	for($i=0;$i<50;$i++){
		$_rnd_color=imagecolorallocate($_img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
		imagestring($_img, 5, mt_rand(1, $_width), mt_rand(1, $_height), '*', $_rnd_color);
	}
	//输出验证码
	for($i=0;$i<strlen($_SESSION['code']);$i++){
		$_rnd_color=imagecolorallocate($_img, mt_rand(0,100), mt_rand(0, 150), mt_rand(0, 200));
		imagestring($_img, 5, $i*$_width/$_rnd_code+mt_rand(1, 10), mt_rand(1, $_height/2), $_SESSION['code'][$i], $_rnd_color);
	}
	//输出图像
	header('Content-Type:image/png');
	imagepng($_img);
	//销毁
	imagedestroy($_img);
}
/**
 * 	_pageing()分页函数
 *  @param $_type分页类型，1表示数字分页，2表示文本分页
 * 	@access public
 * 	
 */
function _pageing($_type){
    global $_page,$_pageabsolute,$_num,$_id;
    if($_type==1){
        echo '<div id="page_num">';
           echo '<ul>';
                    for($i=0;$i<$_pageabsolute;$i++){
                        if(($i+1)==$_page){
                            echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'"class="selected">'.($i+1).'</a></li>';
                        }else{
                            echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a></li>';
                        }      
                    }
            echo '</ul>';
        echo '</div>';
        
    }else if($_type==2){
        echo '<div id="page_text">';
            echo '<ul>';
                echo '<li><strong> '.$_page.'/'.$_pageabsolute.'</strong>页|</li>';
                    echo '<li>共有<strong>'.$_num.'</strong>条数据|</li>';
                        if ($_page==1){
                            echo '<li>首页|</li>';
                            echo '<li>上一页|</li>';
                        }else{
                            echo '<li><a href="'.SCRIPT.'.php">首页</a>|</li>';
                            echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'">上一页</a>|</li>';
                        }
                        if ($_page==$_pageabsolute){
                            echo '<li>下一页|</li>';
                            echo '<li>尾页</li>';
                        }else {
                            echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'">下一页</a>|</li>';
                            echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.$_pageabsolute.'">尾页</a></li>';  
                        }                
                echo '</ul>';
           echo '</div>';
    }
}
/**
 * 	_page()分页函数
 *  @param $_sql sql语句
 *  @param $_size 一页要多少条数据
 * 	@access public
 *
 */
function _page($_sql,$_size){
   // 做成全局变量。让外面函数可以看见
    global $_num,$_page,$_pagenum,$_pageabsolute,$_pagesize;
    if(isset($_GET['page'])){
        //当前页码
        $_page=$_GET['page'];
        if(empty($_page)||$_page<=0||!is_numeric($_page)){
            $_page=1;
        }else{
            $_page=intval($_page);
        }
    }else{
        $_page=1;
    }
    //单页会员数
    $_pagesize=$_size;
    //总会员数
    //得到所有的数据的总和
    $_num= _num_rows(_query($_sql));
    if($_num==0){
        //总页码
        $_pageabsolute=1;
    }else{
        $_pageabsolute=ceil($_num/$_pagesize);
    }
    if($_page>$_pageabsolute){
        $_page=$_pageabsolute;
    }
    //第几条数据
    $_pagenum=($_page - 1)*$_pagesize;
}
/**
 * 	_title()对字符串截取
 *  @param $_string 
 *  @param $_star_num 从第几位开始
 *  @param $_num 几位
 * 	@access public
 *
 */
function _title($_string,$_star_num=0,$_num=14){
    if(mb_strlen($_string,'utf-8')>$_num){
        return mb_substr($_string,$_star_num,$_num,'utf-8');
    }
    return $_string;
}
/**
 * 	_html()对字符串html过滤显示函数
 *  @param $_string 传入数组按数组过滤，传入字符串按字符串过滤。
 * 	@access public
 *
 */
function _html($_string){
    if(is_array($_string)){
        foreach ($_string as $_key=>$_value){
//             $_string[$_key]=htmlspecialchars($_value);
                $_string[$_key]=_html($_value);//用递归
        }
    }else{
        $_string=htmlspecialchars($_string);
    }
    return $_string;
}
/**
 * 	_uniqid()进行数据的敏感操作验证cookie的唯一标识符
 *  @param $_mysql_uniqid 数据库的唯一标识符
 *  @param $_cookie_uniqid cookie的唯一标识符
 * 	@access public
 *
 */
function _uniqid($_mysql_uniqid,$_cookie_uniqid){   
    if($_mysql_uniqid!=$_cookie_uniqid){
        _alert_back('唯一标识符异常!');
    }
}
/**
 * 	_set_xml()创建xml文件
 *  @param $_mysql_uniqid 数据库的唯一标识符
 *  @param $_cookie_uniqid cookie的唯一标识符
 * 	@access public
 *
 */
function _set_xml($_newfile,$_clean){
   $_fp=@fopen($_newfile, 'w');
   if(!$_fp){
       exit('系统错误，文件不存在！');
   }
   flock($_fp, LOCK_EX);
   
   $_string="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="<vip>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="\t<id>{$_clean['id']}</id>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="\t<username>{$_clean['username']}</username>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="\t<sex>{$_clean['sex']}</sex>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="\t<face>{$_clean['face']}</face>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="\t<email>{$_clean['email']}</email>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="\t<url>{$_clean['url']}</url>\r\n";
   fwrite($_fp, $_string,strlen($_string));
   $_string="</vip>\r\n";
   fwrite($_fp, $_string,strlen($_string));
  
   flock($_fp, LOCK_UN);
   fclose($_fp);  
}
/**
 * 	_get_xml()获取xml
 *  @param $_xmlfile xml文件
 * 	@access public
 *
 */
function _get_xml($_xmlfile){
    $_html=array();
    if(file_exists($_xmlfile)){
        $_xml=file_get_contents($_xmlfile);
        preg_match_all('/<vip>(.*)<\/vip>/s', $_xml,$_dom);
        foreach ($_dom[1] as $_value){
            preg_match_all('/<id>(.*)<\/id>/s', $_value,$_id);
            preg_match_all('/<username>(.*)<\/username>/s', $_value,$_username);
            preg_match_all('/<sex>(.*)<\/sex>/s', $_value,$_sex);
            preg_match_all('/<face>(.*)<\/face>/s', $_value,$_face);
            preg_match_all('/<email>(.*)<\/email>/s', $_value,$_email);
            preg_match_all('/<url>(.*)<\/url>/s', $_value,$_url);
            $_html['id']=$_id[1][0];
            $_html['username']=$_username[1][0];
            $_html['sex']=$_sex[1][0];
            $_html['face']=$_face[1][0];
            $_html['email']=$_email[1][0];
            $_html['url']=$_url[1][0];
        }
    }else{
       echo '文件不存在！';
    }
    return $_html;
}
/**
 * 	_thumb()生成缩略图
 *  @param $_filename 图片地址
 *  @param $_percent 缩小比例
 * 	@access public
 *
 */
function _thumb($_filename,$_percent){
    //生成png标头文件
    header('Content-type:image/png');
    $_n=explode('.', $_filename);
    //获取文件信息，长和高
    list($_width,$_height)=getimagesize($_filename);
    //生成缩略图长和高
    $_new_width=$_width*$_percent;
    $_new_height=$_height*$_percent;
    //创建一个以0.3百分比的新长度画布
    $_new_image=imagecreatetruecolor($_new_width, $_new_height);
    switch ($_n[1]){
        case 'jpg':$_image=imagecreatefromjpeg($_filename);
            break;
        case 'png':$_image=imagecreatefrompng($_filename);
            break;
        case 'gif':$_image=imagecreatefromgif($_filename);
            break;
    }
    //按照已有的图片创建画布
    
    //将原来的图片采集后重新复制到新图上
    imagecopyresampled($_new_image, $_image, 0, 0, 0, 0, $_new_width, $_new_height, $_width, $_height);
    imagepng($_new_image);
    imagedestroy($_new_image);
    imagedestroy($_image);
}
/**
 * 	_get_xml()获取xml
 *  @param $_xmlfile xml文件
 * 	@access public
 *
 */
function _ubb($_string){
    $_string=nl2br($_string);
    $_string=preg_replace('/\[size=(.*)\](.*)\[\/size\]/U','<span style="font-size:\1px">\2</span>', $_string);
    $_string=preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>', $_string);
    $_string=preg_replace('/\[i\](.*)\[\/i\]/U','<em>\1</em>', $_string);
    $_string=preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline">\1</span>', $_string);
    $_string=preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through">\1</span>', $_string);
    $_string=preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color:\1">\2</span>', $_string);
    $_string=preg_replace('/\[url\](.*)\[\/url\]/U','<a href="\1" target="_blank">\1</a>', $_string);
    $_string=preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:\1">\1</a>', $_string);
    $_string=preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" />', $_string);
    $_string=preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed style="width:500px;height:500px;" src="\1"/>', $_string);
    return $_string;
}
/**
 * 	_manage_login()管理员登录判断
 *  
 * 	@access public
 *
 */
function _manage_login(){
    if((!isset($_COOKIE['username']))||(!isset($_SESSION['admin']))){
        _alert_back('非法登录！');
    }
}
?>