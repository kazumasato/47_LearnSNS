<?php
require('dbconnect.php');

//likesテーブルへの登録方法
$feed_id = $_POST['feed_id'];
$user_id = $_POST['user_id'];
$sql = 'INSERT INTO `likes` (`feed_id`, `user_id`) VALUES (?, ?)';
$data = [$feed_id, $user_id];
$stmt = $dbh->prepare($sql);
$res = $stmt->execute($data);
echo json_encode($res);