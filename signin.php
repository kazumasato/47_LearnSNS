<?php
session_start();
require('dbconnect.php');


//エラーを格納する配列
$errors=[];
//POST送信時
if (!empty($_POST)) {

  $email = $_POST['input_email'];
  $password = $_POST['input_password'];
  if ($email=='') {
      $errors['email']='blank';
  }
  if ($password=='') {
      $errors['password']='blank';
  }
  if (empty($errors)) {
    //バリデーション通過時の処理
    //1.DBからレコードを取得
    //宿題
    //SELECT文を考える
    //ただし、パスワードは使わない
    $sql='SELECT * FROM `users` WHERE `email`=?';
    $data=[$email];
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);

    //DBから取得した値を$recordに入れる
    //$recordには連想配列が入ってくる
    //値がない場合はfalseが入る
    $record=$stmt->fetch(PDO::FETCH_ASSOC);

    //メールアドレスでの本人確認
    if ($record==false) {
      $errors['signin']='failed';
    }
    //2.パスワードが一致するか確認
    //password_verify(文字列、ハッシュ文字列)
    //文字列とハッシュ文字列を比較して、
    //合致した場合true
    if (password_verify($password, $record['password'])) {
      //認証成功
      //3.パスワードが一致した場合、サインイン処理
      //3-1.セッションにユーザのID追加
      $_SESSION['47_LearnSNS']['id']=$record['id'];
      //3-2.timeline.phpに遷移
      header('Location: timeline.php');
      exit();
    }else{
      //認証失敗
      $errors['signin']='failed';
    }
  }
}


?>
<?php include('layouts/header.php'); ?>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">サインイン</h2>
                <?php if (isset($errors['signin'])&& $errors['signin']=='failed'):?>
                  <p class="text-danger">サインインに失敗しました</p>
              <?php endif; ?>
                <form method="POST" action="signin.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
                            <?php if (isset($errors['email'])&& $errors['email'] == 'blank'):?>
                                <P class="text-danger">メールアドレスを入力してください</P>
                            <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
                            <?php if (isset($errors['password'])&& $errors['password'] == 'blank'):?>
                                <p class="text-danger">パスワードを入力してください</p>
                            <?php endif; ?>
                    </div>
                    <input type="submit" class="btn btn-info" value="サインイン">
                    <span style="float: right; padding-top: 6px;">
                        <a href="index.php">戻る</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include('layouts/header.php'); ?>
</html>
