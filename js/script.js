$(function(){
$('#upload,.msg').draggable();
$('.msg').resizable();
//textarea内の改行を<br>タグに置き換える
$('#text').on('change',function(){
	var text=$(this).val();
	text=text.replace(/\r\n/g, "<br>");
	text = text.replace(/(\n|\r)/g, "<br>");
	$(this).val(text);
	
	
});

//名前と本文の空ならば投稿できない(submitボタン押せない)ように
$('#submit').prop('disabled',true).val('名前と本文を入力してください');//送信ボタン初期値
//テキストエリア、テキストボックスの入力にリアルタイムに反応するようにする
$('#name,#text').on('click blur keydown keyup keypress change',function(){
var nameval=$('#name').val();
var textval=$('#text').val();

	if(nameval!=''&& textval!=''){
	 $('#submit').prop('disabled',false).val('投稿する');
	 
	}else{
	$('#submit').prop('disabled',true).val('名前と本文を入力してください');
	}

});



});