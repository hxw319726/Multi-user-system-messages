window.onload=function(){
	var faceimg=document.getElementById('faceimg');

	faceimg.onclick=function(){
		window.open('face.php','face','width=400,height=400,top=0,left=0;scrollbars=1')
	};
	code();
	//表单验证
	var fm=document.getElementsByTagName('form')[0];
	var fn=document.getElementsByTagName('form');
//	console.log(fn.length);//为1，js对象指向这个节点
//	console.log(fm.length);//为14，指向这个节点对象
//	console.log(fn.toString());
//	console.log(fm.toString());
//	console.log(Element);
//	console.log(document);
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
		if(fm.password.value!=fm.notpassword.value){
			alert("密码和密码确认不一致！");
			fm.notpassword.value='';//清空
			fm.notpassword.focus();//将焦点移到表单字段
			return false;		
		}
		//密码提示验证
		if(fm.question.value.length<2||fm.question.value.length>20){
			alert("密码提示不得小于2位或者大于20位！");
			fm.question.value='';//清空
			fm.question.focus();//将焦点移到表单字段
			return false;		
		}
		//密码确认验证
		if(fm.answer.value.length<2||fm.answer.value.length>20){
			alert("密码确认不得小于2位或者大于20位！");
			fm.answer.value='';//清空
			fm.answer.focus();//将焦点移到表单字段
			return false;		
		}
		if(fm.question.value==fm.answer.value){
			alert("密码提示和密码确认不得相等！");
			fm.answer.value='';//清空
			fm.answer.focus();//将焦点移到表单字段
			return false;		
		}
		
		//邮箱验证
		if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value)){
			alert("邮箱地址不正确！");
			fm.email.value='';//清空
			fm.email.focus();//将焦点移到表单字段
			return false;		
		}
		//qq
		if(fm.qq.value!=''){
			if(!/^[1-9]{1}[0-9]{4,9}$/.test(fm.qq.value)){
				alert("qq号码不正确！");
				fm.qq.value='';//清空
				fm.qq.focus();//将焦点移到表单字段
				return false;	
			}
		}
		//url
		if(fm.http.value!=''){
			if(!/^https?:\/\/(\w+\.)?[\w\.\-]+(\.\w+)+$/.test(fm.http.value)){
				alert("网址不正确！");
				fm.http.value='';//清空
				fm.http.focus();//将焦点移到表单字段
				return false;
			}
		}
		
		//yzm
		if(fm.yzm.value.length<4){
			alert("验证码不得小于4位！");
			fm.yzm.value='';//清空
			fm.yzm.focus();//将焦点移到表单字段
			return false;			
		}
		
		
		return true;
	}
	
	
	
	
};