<?php
/**
 * TestGuest Version1.0
 * ================================================
 * Copy 2016-2016
 * Web: http://www..com
 * ================================================
 * Author: word
 * Date: 2016/9/13
 */
//防止恶意调用
if(!defined('IN_TG')){
	exit('非法调用');
}
/**
 * @_connect()创建连接数据库
 * @access public
 * @return void
 */
function _connect(){
	//
	global $_conn;
	if(!$_conn=@mysql_connect(DB_HOST,DB_USER,DB_PWD)){
		exit("数据库连接失败！");
	}
}
/**
 * @_select_db()选择一张数据库
 * @access public
 * @return void
 */
function _select_db(){
	if(!mysql_select_db(DB_NAME)){
		exit("找不到指定数据库！");
	}
}
/**
 * @_set_names()选择字符集
 * @access public
 * @return void
 */
function _set_names(){
	if(!mysql_query('SET NAMES UTF8')){
		exit("字符集错误！");
	}
}

/**
 * @_num_rows()浮点数返回整数
 * @param string $result结果集
 * @access public
 *
 */
function _num_rows($result){
	return mysql_num_rows($result);
}
/**
 * @_query()数据库查询
 * @param string $_sql数据库查询内容
 * @access public
 * @return $_result资源句柄
 */
function _query($_sql){
    if(!$_result=mysql_query($_sql)){
        exit("SQL执行失败!".mysql_error());
    }
    return $_result;
}
/**
 * @_fetch_array判断是否存在重复
 * @param string $_sql数据库查询内容
 * @access public
 * @return bool 空false
 */
function _fetch_array($_sql){
	return mysql_fetch_array(_query($_sql),MYSQL_ASSOC);
}
/**
 * _affected_rows表示影响到的记录数
 */
function _affected_rows(){
    return mysql_affected_rows();
}

/**
 * _free_result()销毁结果集
 * @param $_result结果集；
 * @access public
 */
function _free_result($_result){
    return mysql_free_result($_result);
}

/**
 * 
 * @param unknown $_sql
 * @param unknown $_info
 */
function _is_repeat($_sql,$_info){
	if(_fetch_array($_sql)){
		_alert_back($_info);
	}
}

/**
 * _insert_id()取得上一步 INSERT 操作产生的 ID 
 * 
 */
function _insert_id(){
   return mysql_insert_id();
}
















 ?>