/**
 * 
 */
window.onload=function(){
	var message=document.getElementsByName('message');
	var friend=document.getElementsByName('friend');
	var flower=document.getElementsByName('flower');
	for(i=0;i<message.length;i++){
		message[i].onclick=function(){
			centerWindow('message.php?id='+this.title,'message',250,400);
		};
	}
	for(i=0;i<friend.length;i++){
		friend[i].onclick=function(){
			centerWindow('friend.php?id='+this.title,'friend',250,400);
		};
	}
	for(i=0;i<flower.length;i++){
		flower[i].onclick=function(){
			centerWindow('flower.php?id='+this.title,'flower',250,400);
		};
	}
};


function centerWindow(url,name,height,width){
	var top=(screen.height-height)/2;
	var left=(screen.width-width)/2;
	window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
}