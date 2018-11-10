<?php
session_start();
//1.エラー内容を保持する配列変数を定義
$errors = [];
//確認画面から戻ってきた場合
if (isset($_GET['action'])&& $_GET['action']=='rewrite') {
  $_POST['input_name']=$_SESSION['47_LearnSNS']['name'];
  $_POST['input_email']=$_SESSION['47_LearnSNS']['email'];
  $_POST['input_password']=$_SESSION['47_LearnSNS']['password'];
  //check.phpへの遷移が行われないように
  $errors['rewrite']=true;
}

//空で変数定義
$name='';
$email='';

//2.送信されたデータと比較
if (!empty($_POST)) {
  $name = $_POST['input_name'];
  $email = $_POST['input_email'];
  $password = $_POST['input_password'];

  if ($name == '') {
    //3.入力項目に不備があった場合、配列変数に格納
    $errors['name'] = 'blank';
  }

  if ($email == '') {
    $errors['email'] = 'blank';
  }
  //strlen(文字列)
  //文字列の文字数を返す
  $count = strlen($password);

  if ($password == '') {
    $errors['password'] = 'blank';
  }elseif ($count < 4 || $count > 16) {
  //文字数チェック　4~16文字
  //4文字未満あるいは16文字より多い
    $errors['password'] = 'length';
  }

  //画像は$_POSTではなく、$_FILESで受け取る
  //$_FILESにはtype="file"で選択されたデータが入る
  //ただし、ルールが二つある
  //1.formタグにenctype="multipart/form-data"が指定されている
  //2.formタグにmethod="POST"が指定されている
  //$_FILES[キー]['name']画像名
  //$_FILES[キー]['tmp_name']画像データそのもの
  //画像名を取得
  $file_name='';
  if (!isset($_GET['action'])) {
    $file_name=$_FILES['input_img_name']['name'];
  }
  if (!empty($file_name)) {
      //画像が選択されている時の処理

      //拡張子チェック
      //1.画像ファイル名の拡張子を取得
      //substr(文字列、何文字目から)
      //指定されたレンジの文字を取得
      $file_type=substr($file_name, -3);//PNG

      //2.大文字は小文字化
      $file_type=strtolower($file_type);//png

      //3.jpg,png,gifと比較し、当てはまらない場合$errors['img_name']に格納
      if ($file_type!='png' && $file_type!='jpg' && $file_type!='gif') {
        $errors['img_name']='type';
      }
  }else{
    $errors['img_name'] = 'blank';
  }

//バリデーション成功時の処理=入力不備がなかった時
  if (empty($errors)) {
    //1.プロフィール画像のアップロード
    //一意のファイル名を設定
    $date_str=date('YmdHis');
    $submit_file_name=$date_str . $file_name;
    //アップロード
    //move_uploaded_file(画像ファイル、アップロード先)
    move_uploaded_file($_FILES['input_img_name']['tmp_name'],'../user_profile_img/' . $submit_file_name);

    //2.セッションへ送信データを保存する
    //サーバに用意された一時的にデータを保持できる機能
    //同じサーバ内であれば出し入れ自由
    //$_SESSION 連想配列形式で値を保持
    //使用するためにはsession_start();を
    $_SESSION['47_LearnSNS']['name']=$name;
    $_SESSION['47_LearnSNS']['email']=$email;
    $_SESSION['47_LearnSNS']['password']=$password;
    $_SESSION['47_LearnSNS']['img_name']=$submit_file_name;

    //3.次のページへ遷移する

    //header('Location:遷移先')
    header('Location: check.php');
    exit();
  }

}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Learn SNS</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">アカウント作成</h2>
                <form method="POST" action="signup.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">ユーザー名</label>
                        <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎"
                            value="<?php echo htmlspecialchars($name); ?>">
                            <!--
                                isset(変数)
                                変数が定義されていればtrue
                            -->
                            <?php if (isset($errors['name'])&& $errors['name'] == 'blank'):?>
                                <p class="text-danger">ユーザー名を入力してください</p>
                            <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com"
                            value="<?php echo htmlspecialchars($email); ?>">
                            <?php if (isset($errors['email'])&& $errors['email'] == 'blank'):?>
                                <P class="text-danger">メールアドレスを入力してください</P>
                            <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
                        <?php if (isset($errors['password'])&& $errors['password'] == 'blank'):?>
                                <P class="text-danger">パスワードを入力してください</P>
                            <?php endif; ?>
                            <?php if (isset($errors['password'])&& $errors['password'] == 'length'):?>
                                <p class="text-danger">パスワードは4~16文字で入力してください</p>
                            <?php endif; ?>
                            <?php if (!isset($errors['password'])&& 'blank'): ?>
                              <p class="text-danger">パスワードを再入力してください</p>
                              <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="img_name">プロフィール画像</label>
                        <input type="file" name="input_img_name" id="img_name" accept="image/*">
                        <?php if (isset($errors['img_name'])&& $errors['img_name']== 'blank'):?>
                            <P class="text-danger">画像を選択してください</P>
                        <?php endif; ?>
                            <?php if (isset($errors['img_name'])&& $errors['img_name'] == 'type'):?>
                                <p class="text-danger">拡張子がpng,jpg,gifの画像を選択してください</p>
                            <?php endif; ?>
                    </div>
                    <input type="submit" class="btn btn-default" value="確認">
                    <span style="float: right; padding-top: 6px;">ログインは
                        <a href="../signin.php">こちら</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../assets/js/jquery-3.1.1.js"></script>
<script src="../assets/js/jquery-migrate-1.4.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</html>