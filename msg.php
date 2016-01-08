<?php
$dsn = 'mysql:dbname=sample; host=localhost';
$user ='sample';
$pass='akasan123';    
try{
$db=new PDO($dsn,$user,$pass);
$stmt=$db->query('SELECT id,time,name,text,url FROM msgbord ORDER BY time DESC');//SQLの中に動的に値を仕込まない場合はqueryでSQLを発行したほうがシンプルですね
$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
$db=null;

}catch(PDOException $e){
echo $e->getMessage();
exit;
}
if(isset($results)):
    foreach($results as $result ):?>
    <div class='msg'>
        <h2 class="msgtitle">投稿者&nbsp;&nbsp;<?php echo $result['name'];?></h2>
        <p class="postdate"><?php echo $result['time'];?></p>
            <div class="picbox clear clearfix">
                <p class="text">
                <a href="<?php echo $result['url'];?>" target="blank"><img class="image" src="<?php echo $result['url'];?>" width="50%" height="auto"></a>
                <?php echo $result['text']; ?>
                </p>

            </div>
    </div>

        <?php endforeach;
        endif;
        ?>