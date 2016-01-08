<?php
if($_SERVER['REQUEST_METHOD']==='POST'){//→自分にPOSTするので、ポストがあればという判定　
// ファイルアップロード処理はじまりはじまり
$uploaded_file='';//ファイルのパスを入れとく変数を初期化
$dir_name="./uploadfile";//画像の保存場所
$max_size=1024*1024*5;//ファイルの上限5M
            if(isset($_FILES["imgup"]["tmp_name"])){
                if($_FILES["imgup"]["size"]>$max_size){
                    $error_msg= "ファイルの上限は5Mです";
                    echo $error_msg;
                    exit;
                }
                $tmpfile=pathinfo($_FILES["imgup"]["name"]);
                if($tmpfile['extension']== "jpg" || $tmpfile['extension']== "jpeg" || $tmpfile['extension']== "JPG" || $tmpfile['extension']== "png" || $tmpfile['extension']== "gif"){
                    $file_name="upload_".time().".".$tmpfile['extension'];//ファイル名を作りなおします
                    $uploaded_file="$dir_name/$file_name";
                    move_uploaded_file($_FILES["imgup"]["tmp_name"],$uploaded_file);//fileがアップロードされている仮ディレクトリ(temp)から移動

                }else{
                    $error_msg="対応していないファイル形式です";
                    echo $error_msg;
                    exit;
                }
            }
//DB接続
$dsn = 'mysql:dbname=sample; host=localhost';
$user ='sample';
$pass='akasan123';
try{
$db=new PDO($dsn,$user,$pass);
$stmt=$db->prepare('INSERT INTO msgbord(name,text,url) VALUES (:name,:text,:url);');
$stmt->execute(array(
            ':name'=>h($_POST['name']),
            ':text'=>strip_tags($_POST['text'],'<br>'),//<br>タグだけサニタイジングしない
            ':url'=>$uploaded_file
            ));
$stmt=$db->prepare('SELECT id,time,name,text,url FROM msgbord');
$stmt->execute();
$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
$db=null;

}catch(PDOException $e){
echo $e->getMessage();
exit;
}

// ブラウザの更新ボタンを押した時の二重送信を防ぐ対策　POSTで送られたデータを処理した後リダイレクト
header('Location: http://weblabo.work/sample/imageup/',true);

}//if($_SERVER['REQUEST_METHOD']を閉じる括弧
function h($val){
    $s=htmlspecialchars($val,ENT_QUOTES);
    return $s;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link type="text/css" rel="stylesheet" href="style.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="js/script.js"></script>
<title>画像掲示板サンプル</title>
</head>
<body>
<div class="container">
    <div class="containts">
        <div id="upload" class="upload">
            <h1 class="title">簡易画像掲示板</h1>
            <h2 class="title">こちらから投稿してください</h2>
            <form method="post" action="" enctype="multipart/form-data">
            <p>
                <label for="name">名前</label>
                <input type="text" name="name" id="name" placeholder="ニックネーム">
            </p>
            <p>
                <label for="text">メッセージを入力</label>
            </p>
            <p>
                <textarea name="text" id="text" placeholder="何かメッセージを入力してください"></textarea></br>
                改行は反映されます。
            </p>
                <p>
                <label for="imgup">アップロード画像を選択</label>
                <?php
                if(isset($error_msg)){
                    echo "<p>$error_msg</p>";
                }
                ?>
                <input type="file" name="imgup" id="imgup">
            </p>
            <p class="align-right">
                <input type="submit" id="submit" value="投稿する" disabled>
            </p>

            </form>
        </div>
    </div>
    <!--ここから表示用です--DBにアクセスしてSELECTで取得するロジックは別ファイルで行って読み込みます--> 
    <?php include('msg.php');?>


</div>
</body>
</html>