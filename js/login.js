window.onload=function(){
	code();
	//表单验证
	var fm=document.getElementsByTagName('form')[0];
	fm.onsubmit=function(){
		//能使用客户端验证，尽量使用客户端
		//用户名验证
		if(fm.username.value.length<2||fm.username.value.length>20){
			alert("用户名不得小于两位和大于20位！");
			fm.username.value='';//清空
			fm.username.focus();//将焦点移到表单字段
			return false;
		}
		if((/[<>\'\"\ ]/.test(fm.username.value))){
			alert("用户名不得包含非法字符！");
			fm.username.value='';//清空
			fm.username.focus();//将焦点移到表单字段
			return false;
		}
		//密码验证
		if(fm.password.value.length<6){
			alert("密码不得小于6位！");
			fm.password.value='';//清空
			fm.password.focus();//将焦点移到表单字段
			return false;		
		}
		//yzm
		if(fm.yzm.value.length<4){
			alert("验证码不得小于4位！");
			fm.yzm.value='';//清空
			fm.yzm.focus();//将焦点移到表单字段
			return false;			
		}
		
	}
}