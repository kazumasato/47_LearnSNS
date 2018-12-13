<?php
require('dbconnect.php');

//likesテーブルへの登録方法
$feed_id = $_POST['feed_id'];
$user_id = $_POST['user_id'];


if (isset($_POST['is_unlike'])) {
//削除処理
    $sql = 'DELETE FROM `likes` WHERE `feed_id` = ? AND `user_id` = ?';
}else{
//登録処理
    $sql = 'INSERT INTO `likes` (`feed_id`, `user_id`) VALUES (?, ?)';
}
$data = [$feed_id, $user_id];
$stmt = $dbh->prepare($sql);
$res = $stmt->execute($data);
echo json_encode($res);