window.onload=function(){
	var img=document.getElementsByTagName('img');
	for(i=0;i<img.length;i++){
		img[i].onclick=function(){
			_opener(this.alt);
		};
	}
	
};
function _opener(alt){
	//opener表示父窗口，document为文档
	opener.document.getElementById('faceimg').src=alt;;
	opener.document.reg.face.value=alt;
}