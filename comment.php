<?php
session_start();
require('dbconnect.php');
//1.必要な値を取得
$user_id=$_SESSION['47_LearnSNS']['id'];
$feed_id=$_POST['feed_id'];
$comment=$_POST['write_comment'];

//2.DBへの保存
//宿題
$sql='INSERT INTO `comments`(`comment`,`user_id`,`feed_id`,`created`) VALUES (?,?,?,NOW());';
$data=[$comment,$user_id,$feed_id];
$stmt=$dbh->prepare($sql);
$stmt->execute($data);
//3.タイムライン画面に再遷移
header('Location: timeline.php');
exit();