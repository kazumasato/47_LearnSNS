<?php
session_start();
require('dbconnect.php');

$sql='SELECT * FROM `users` WHERE `id`=?';
$data=[$_SESSION['47_LearnSNS']['id']];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);

$signin_user=$stmt->fetch(PDO::FETCH_ASSOC);


$user_sql='SELECT * FROM `users` WHERE`id`=?';
$user_data=[$_GET['user_id']];
$user_stmt=$dbh->prepare($user_sql);
$user_stmt->execute($user_data);
$user=$user_stmt->fetch(PDO::FETCH_ASSOC);

$sql='SELECT * FROM `followers` WHERE `user_id`=? AND `follower_id`=?';
$data=[$user['id'],$signin_user['id']];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);
$is_followed = $stmt->fetch(PDO::FETCH_ASSOC);

// echo "<pre>";
// var_dump($is_followed);
// echo "</pre>";


$sql = 'SELECT `u`.* FROM `followers` AS `f` LEFT JOIN `users` AS `u` ON `u`.`id` = `f`.`follower_id` WHERE `f`.`user_id` = ?';
$data = [$user['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$followers = [];
while (true) {
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($record == false) {
    break;
  }
  $followers[] = $record;
}


$sql = 'SELECT `u`.* FROM `followers` AS `f` LEFT JOIN `users` AS `u` ON `u`.`id` = `f`.`user_id` WHERE `f`.`follower_id` = ?';
$data = [$user['id']];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$followings = [];
while (true) {
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($record == false) {
    break;
  }
  $followings[] = $record;
}


?>
<?php include('layouts/header.php'); ?>
<body style="margin-top: 60px; background: #E4E6EB;">
    <?php include("navbar.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-3 text-center">
                <img src="user_profile_img/<?php echo $user['img_name']; ?>" class="img-thumbnail" />
                <h2><?php echo $user['name']; ?></h2>
                <?php if ($signin_user['id'] != $user['id']): ?>

                    <?php if ($is_followed == false):?>
                        <!-- フォローしてない -->
                <a href="follow.php?following_id=<?php echo$user['id'];?>">
                    <button class="btn btn-default btn-block">フォローする</button>
                </a>
                    <?php else: ?>
                        <!-- フォローしてる -->
                <a href="follow.php?following_id=<?php echo$user['id'];?>&unfollow=true">
                    <button class="btn btn-danger btn-block">フォロー解除</button>
                </a>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
            
            <div class="col-xs-9">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">Followers</a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">Following</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab1" class="tab-pane fade in active">
                        <?php foreach ($followers as $follower): ?>
                        <div class="thumbnail">
                            <div class="row">
                                <div class="col-xs-2">
                                    <img src="user_profile_img/<?php echo$follower['img_name']; ?>" width="80px">
                                </div>
                                <div class="col-xs-10">
                                    名前 <a href="profile.php" style="color: #7F7F7F;">
                                        <?php echo $follower['name']; ?>
                                    </a>
                                    <br>
                                    <?php echo $follower['created']; ?>からメンバー
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="tab2" class="tab-pane fade">
                        <?php foreach ($followings as $following):?>
                        <div class="thumbnail">
                            <div class="row">
                                <div class="col-xs-2">
                                    <img src="user_profile_img/<?php echo$following['img_name'];?>" width="80px">
                                </div>
                                <div class="col-xs-10">
                                    名前 <a href="profile.php" style="color: #7F7F7F;">
                                        <?php echo $following['name']; ?>
                                    </a>
                                    <br>
                                    <?php echo $following['created']; ?>からメンバー
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</body>
<?php include('layouts/footer.php'); ?>
</html>