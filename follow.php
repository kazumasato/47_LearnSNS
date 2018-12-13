<?php
require('dbconnect.php');
session_start();

$user_id=$_GET['following_id'];
$follower_id=$_SESSION['47_LearnSNS']['id'];

if (isset($_GET['unfollow'])) {
//フォロー解除の時
  $sql='DELETE FROM `followers` WHERE `user_id`=? AND `follower_id`=?';
}else{
//フォローする時
  $sql='INSERT INTO`followers`(`user_id`, `follower_id`)VALUES(?,?)';
}

  $data=[$user_id,$follower_id];
  $stmt=$dbh->prepare($sql);
  $stmt->execute($data);


header("Location: profile.php?user_id=" . $user_id);
exit();