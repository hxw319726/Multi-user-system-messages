/**
 * 
 */
window.onload=function(){
	code();
	//表单验证
	var fm=document.getElementsByTagName('form')[0];
	fm.onsubmit=function(){		
		//短信内容
		if(fm.content.value.length<10||fm.content.value.length>200){
			alert("短信内容不得小于10位或大于200位！");
			fm.content.focus();//将焦点移到表单字段
			return false;			
		}
		//yzm
		if(fm.yzm.value.length<4){
			alert("验证码不得小于4位！");
			fm.yzm.focus();//将焦点移到表单字段
			return false;			
		}
		return true;
	};
};