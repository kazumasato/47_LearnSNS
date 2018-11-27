<?php
session_start();
require('dbconnect.php');

$sql='SELECT * FROM `users` WHERE `id`=?';
$data=[$_SESSION['47_LearnSNS']['id']];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);
$signin_user=$stmt->fetch(PDO::FETCH_ASSOC);

$sql='SELECT *FROM`users`';
$stmt=$dbh->prepare($sql);
$stmt->execute();
$users=[];
while (true) {
    $record=$stmt->fetch(PDO::FETCH_ASSOC);
    if ($record==false) {
        break;
    }
  $users[]=$record;
}
// echo "<pre>";
// var_dump($users);
// echo "</pre>";
?>
<?php include('layouts/header.php'); ?>
<body style="margin-top: 60px; background: #E4E6EB;">
    <?php include('navbar.php'); ?>
    <?php foreach ($users as $user):?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="thumbnail">
                    <div class="row">
                        
                        <div class="col-xs-2">
                            <img src="user_profile_img/<?php echo $user['img_name']; ?>" width="80px">
                        </div>
                        <div class="col-xs-1">
                            名前 <a href="profile.php?user_id=<?php echo $user['id'];?>" style="color: #7f7f7f;"><?php echo $user['name']; ?></a>
                            <br>
                        </div>
                    <?php echo $user['created']; ?>
                    </div>
                    <div class="row feed_sub">
                        <div class="col-xs-12">
                            <span class="comment_count">つぶやき数：10</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</body>
<?php include('layouts/footer.php'); ?>
</html>
