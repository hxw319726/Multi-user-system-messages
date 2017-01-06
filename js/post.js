/**
 * 
 */
window.onload=function(){
	code();
	var ubb=document.getElementById('ubb');
	var ubbing=ubb.getElementsByTagName('img');
	var fm=document.getElementsByTagName("form")[0];
	var font=document.getElementById('font');
	var color=document.getElementById('color');
	var html=document.getElementsByTagName("html")[0];

	fm.onsubmit=function(){
		//标题验证
		if(fm.title.value.length<2||fm.title.value.length>40){
			alert("标题不得小于2位和大于40位！");
			fm.title.value='';//清空
			fm.title.focus();//将焦点移到表单字段
			return false;
		}
		//内容验证
		if(fm.content.value.length<10){
			alert("内容不得小于10位！");
			fm.content.value='';//清空
			fm.content.focus();//将焦点移到表单字段
			return false;		
		}	
		//yzm
		if(fm.yzm.value.length<4){
			alert("验证码不得小于4位！");
			fm.yzm.value='';//清空
			fm.yzm.focus();//将焦点移到表单字段
			return false;			
		}		
		return true;	
	};
	
	
	
	
	
	var q=document.getElementById('q');
	var qa=q.getElementsByTagName("a");
	qa[0].onclick=function(){
		window.open('q.php?num=48&path=qpic/1/','q','width=400,height=400,scrollbars=1');
	}
	qa[1].onclick=function(){
		window.open('q.php?num=10&path=qpic/2/','q','width=400,height=400,scrollbars=1');
	}
	qa[2].onclick=function(){
		window.open('q.php?num=39&path=qpic/3/','q','width=400,height=400,scrollbars=1');
	}	
	
	html.onmouseup=function(){
		font.style.display='none';
		color.style.display='none';
	}
	ubbing[0].onclick=function(){
		font.style.display='block';
	};
	ubbing[2].onclick=function(){
		content("[b][/b]");
	};
	ubbing[3].onclick=function(){
		content("[i][/i]");
	};
	ubbing[4].onclick=function(){
		content("[u][/u]");
	};
	ubbing[5].onclick=function(){
		content("[s][/s]");
	};
	ubbing[7].onclick=function(){
		color.style.display='block';
		fm.t.focus();
	};
	ubbing[8].onclick=function(){
		var url=prompt("请输入网址","http://");
		if(url){
			if(/^https?:\/\/(\w+\.)?[\w\.\-]+(\.\w+)+$/.test(url)){
				content('[url]'+url+'[/url]');
			}else{
				alert('网址不合法！');
			}
		}	
	};
	ubbing[9].onclick=function(){
		var email=prompt("请输入电子邮件","");
		if(email){
			if(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(email)){
				content('[email]'+email+'[/email]');
			}else{
				alert('电子邮件不合法！');
			}
		}	
	};
	ubbing[10].onclick=function(){
		var image=prompt("请输入图片地址","");
		if(image){
			content('[image]'+image+'[/image]');
		}
			
	};
	ubbing[11].onclick=function(){
		var flash=prompt("请输入flash网址","http://");
		if(flash){
			if(/^https?:\/\/(\w+\.)?[\w\.\-]+(\.\w+)+/.test(flash)){
				content('[flash]'+flash+'[/flash]');
			}else{
				alert('flash不合法！');
			}
		}	
	};
	ubbing[12].onclick=function(){
		var url=prompt("请输入网址：","http://");
		if(url){
			if(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(url)){
				content('[url]'+url+'[/url]');
			}else{
				alert('网址不合法！');
			}
		}	
	};
	ubbing[18].onclick=function(){
		fm.content.rows+=2;
	};
	ubbing[19].onclick=function(){
		fm.content.rows-=2;
	};

	
	
	fm.t.onclick=function(){
		showcolor(this.value);
	}
	
	function content(string){
		fm.content.value+=string;
	}
};
function font(size){
	document.getElementsByTagName("form")[0].content.value+='[size='+size+'][/size]';
}
function showcolor(value){
	document.getElementsByTagName("form")[0].content.value+='[color='+value+'][/color]';
}