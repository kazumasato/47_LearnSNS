<?php
session_start();
//1.セッションを空にする
//セッション変数の破棄
$_SESSION=[];
//サーバ内のセッション変数のクリア
session_destroy();

//2.サインイン画面に遷移

header("Location: signin.php");
exit();
