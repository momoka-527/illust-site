<?php
//ログ
//エラーログを保存する
ini_set('log_errors','On');
//エラーログの保存先
ini_set('error_log','php.log');

//デバッグフラグ
$debug_flg = true;

function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  セッション準備、セッションの有効期限を伸ばす処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//セッションファイルの置き場を変更する。(/var/tmp/以下に置くと30日は削除されない)
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定
//(30日以上経っているものに対してだけ100分の１の確率で削除)
ini_set('session.gc_maxlifetime',60*60*24*30);
//ブラウザを閉じても削除されないようにcookie自体の有効期限を伸ばす
ini_set('session.cookie_lifetime',60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える(なりすましのセキュリティ対策)
session_regenerate_id();


//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面表示処理開始ログ吐き出し関数
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID:'.session_id());
  debug('セッション変数の中身:'.print_r($_SESSION,true));
  debug('現在日時タイムスタンプ:'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug('ログイン期限日時タイムスタンプ:'.($_SESSION['login_date'] + $_SESSION['login_limit']));
  }
}

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  メッセージを定数に設定
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
define('MSG01','入力必須です。');
define('MSG02','Emailの型式で入力してください。');
define('MSG03','パスワードが一致していません。');
define('MSG04','6文字以上で入力してください。');
define('MSG05','入力文字が多すぎます。');
define('MSG06','半角英数字で入力してください。');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08','このメールアドレスは既に登録されています。');
define('MSG09','メールアドレスまたはパスワードが違います。');
define('MSG10','古いパスワードが違います。');
define('MSG11','古いパスワードと同じパスワードです。');
define('MSG12','文字で入力してください。');
define('MSG13','正しくありません。');
define('MSG14','有効期限が切れています。');
define('MSG15','半角数字のみご利用いただけます。');
define('SUC00','パスワードを再発行しました。ログイン後、任意のパスワードに設定し直すことをおすすめします。');
define('SUC01','パスワードを変更しました。');
define('SUC02','プロフィールを変更しました。');
define('SUC03','メールを送信しました。');
define('SUC04','登録しました。');
define('SUC05','コメントを投稿しました。');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  バリデーション関数
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//エラーメッセージ格納用の配列
$err_msg = array();

//バリデーション関数(未入力チェック)
function validRequired($str, $key){
  if($str === ''){
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}

//バリデーション関数(最大文字数チェック)
function validMaxLen($str, $key, $max = 255){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
//バリデーション関数(最小文字数チェック)
function validMinLen($str, $key, $min = 6){
  if(mb_strlen($str) < $min){
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
//バリデーション関数(半角英数字チェック)
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
//半角数字チェック
function validNumber($str, $key){
  if(!preg_match("/^[0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG15;
  }
}
//バリデーション関数(Emailの型式チェック)
function validEmail($str, $key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$str)){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
//バリデーション関数(パスワードが一致しているか)
function validPassMatch($str1, $str2, $key){
  if($str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
//パスワードチェック関数
function validPass($str, $key){
  //半角英数字チェック
  validHalf($str, $key);
  //最大文字数チェック
  validMaxLen($str, $key);
  //最小文字数チェック
  validMinLen($str, $key);
}

//バリデーション関数(Email重複チェック)
function validEmailDup($email){
  global $err_msg;
  //例外処理
  try{
    //DB接続
    $dbh = dbConnect();
    //SQL文作成
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(':email' => $email);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    //クエリ結果の取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //クエリ結果は配列で帰ってくる
    //array_shiftで配列の一番最初だけ取り出す
  if(!empty($result['count(*)'])/*(array_shift($result))*/){
      $err_msg['email'] = MSG08;
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

//固定長チェック
function validLength($str, $key, $len = 8){
  if(mb_strlen($str) !== $len){
    global $err_msg;
    $err_msg[$key] = $len . MSG12;
  }
}

//フぉーム入力保持関数
function getFormData($str){
  global $dbFormData;

  //ユーザーデータがある場合
  if(!empty($dbFormData)){

    //フォームのエラーがある場合
    if(!empty($err_msg[$str])){

      //POSTにデータがある場合
      if(isset($_POST[$str])){ //issetは入っているかを判定する。 0でも入っていると判定する。空の配列でも入っていると判定する。
        return sanitize($_POST[$str]);
      }else{
        //データがない場合(フォームにエラーがある＝POSTされているはずなので、まずあり得ないが)はDBの情報を表示
        return sanitize($dbFormData[$str]);
      }

    }else{
      //POSTにデータがあり、DBの情報取りがう場合(このフォームも変更していてエラーはないが、他のフォームで引っかかっている状態)
      if(isset($_POST[$str]) && $_POST[$str] !== $dbFormData[$str]){
        return sanitize($_POST[$str]);
      }else{//そもそも変更していない
        return sanitize($dbFormData[$str]);
      }
    }
  }else{ //ユーザーデータがない
    if(isset($_POST[$str])){
      return sanitize($_POST[$str]);
    }
  }
}

//エラーメッセージ表示
function getErrMsg($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return $err_msg[$key];
  }
}

//タグ情報を取得してくる
function getTag(){
  debug('タグ情報を取得します');
  //例外処理
  try{
    //DB接続
    $dbh = dbConnect();
    //SQL文作成
    $sql = 'SELECT * FROM tag';
    $data = array();
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果の全情報を返却
      return $stmt->fetchAll();
      debug('$stmtの中身：'.print_r($stmt,true));
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
    $err_msg['common'] = MSG07;
  }
}

//画像アップロード
function uploadImg($file, $key){
  debug('画像アップロード処理開始');
  debug('FILE情報：'.print_r($file,true));

  if(isset($file['error']) && is_int($file['error'])){
    try{
      //バリデーション
      //$file['error']の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている
      //「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている
      switch($file['error']){
        case UPLOAD_ERR_OK: //OK
             break;
        case UPLOAD_ERR_NO_FILE: //ファイル未洗濯の場合
             throw new RuntimeException('ファイルが選択されていません。');
        case UPLOAD_ERR_INI_SIZE: //php.ini定義の最大サイズが超過した場合
        case UPLOAD_ERR_FORM_SIZE: //フォーム定義の最大サイズ超過した場合
             throw new RuntimeException('ファイルサイズが大きすぎます。');
        default: //その他の場合
             throw new RuntimeException('その他のエラーが発生しました。');
      }

      //$file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
      //exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
      $type = @exif_imagetype($file['tmp_name']);
      if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){//第３引数にはtrueを設定すると厳密にチェックしてくれるので、必ずつける
        throw new RuntimeException('画像形式が未対応です。');
      }

      //ファイルデータからSHA-1ハッシュをとってファイル名を決定し、ファイルを保存する
      //ハッシュ化しておかないと、アップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
      //DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
      //image_type_to_extension関数はファイルの拡張子を取得するもの
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);

      //move_upload_file = ファイルをアップロードする
      if(!move_uploaded_file($file['tmp_name'], $path)){ //ファイルを移動する
        //アップロードできなかった場合例外をはく
        throw new RuntimeException('ファイル保存時にエラーが発生しました。');
      }
      //保存したファイルパスのパーミッション(権限)を変更する
      chmod($path, 0644);

      debug('ファイルは正常にアップロードされました。');
      debug('ファイルパス：'.$path);
      return $path;

    }catch(RuntimeException $e){

      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();
    }
  }
}

function getProductOne($p_id){
  debug('イラスト情報とコメント一覧の情報を取得します。');
  debug('イラストID：'.$p_id);
  //例外処理
  try{
    //DB接続
    $dbh = dbConnect();
    //SQL作成
    $sql = 'SELECT i.id, i.title, i.tag, i.comment, i.pic1, i.pic2, i.pic3, i.user_id, i.create_date,
    u.nickname, u.picture,
    m.send_date, m.from_user, m.msg
    FROM illustration AS i LEFT JOIN users AS u ON i.user_id = u.id LEFT JOIN message AS m ON i.id = m.bord_id
    WHERE i.id = :p_id AND i.delete_flg = 0 AND u.delete_flg = 0';

    $data = array(':p_id' => $p_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果のデータを１レコード返却
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

//コメント欄の情報取得
function getMsg($p_id){
  //例外処理
  try{
    //DB接続
    $dbh = dbConnect();
    //SQL文作成
    $sql = 'SELECT m.msg, m.bord_id, m.send_date, m.from_user, u.id, u.nickname, u.picture FROM message AS m
    RIGHT JOIN users AS u ON u.id = m.from_user WHERE m.bord_id = :p_id AND u.delete_flg = 0';
    $data = array('p_id' => $p_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      $result = $stmt->fetchAll();
      return $result;
    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

//画面表示用関数(画像が空の時の表示用)
function showImg($path){
  if(empty($path)){
    return 'images/ELT140405465316_TP_V.jpg';
  }else{
    return $path;
  }
}

function getProduct($user_id, $p_id, $tag_id){
  debug('商品情報を取得します。');
  debug('ユーザーID：'.$user_id);
  debug('商品ID：'.$p_id);
  debug('タグ：'.$tag_id);

  //例外処理
  try{
    //DBへ接続
    $dbh = dbConnect();
    //SQL文作成
    $sql = 'SELECT * FROM illustration WHERE user_id = :user_id AND id = :p_id AND tag_id = :tag_id AND delete_flg = 0';
    $data = array(':user_id' => $user_id, ':p_id' => $p_id, ':tag_id' => $tag_id);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果のデータを１レコード返却
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}
function getProductList($currentMinNum = 1, $span = 20){
  debug('商品情報を取得します。');
  //例外処理
  try{
    //DBへ接続
    $dbh = dbConnect();
    //件数用のSQL文作成
    $sql = 'SELECT id FROM illustration';
    $data = array();
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount(); //総レコード数
    $rst['total_page'] = ceil($rst['total']/$span); //総ページ数 ceil = 切り上げ
    if(!$stmt){
      return false;
    }

    //ページング用のSQL文作成
    $sql = 'SELECT * FROM illustration ORDER BY create_date DESC';

    $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
    $data = array();
    debug('SQL:'.$sql);
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果のデータを全レコードを格納
      $rst['data'] = $stmt->fetchAll();
      return $rst;
    }else{
      return false;
    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}

//sessionを１回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}

//GETパラメータ付与
//$del_key : 付与から取り除きたいGETパラメーターのキー
function appendGetParam($arr_del_key){
  if(!empty($_GET)){
    $str = '?';
    foreach ($_GET as $key => $val) {
      if(!in_array($key,$arr_del_key,true)){ //取り除きたいパラメータじゃない場合に、URLにくっつけるパラメータ生成
        $str .= $key.'='.$val.'&';
      }
    }
    $str = mb_substr($str, 0, -1, "UTF-8");
    echo $str;
  }
}

//認証キー生成
function makeRandKey($length = 8){
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $str = '';
  for($i = 0; $i < $length; ++$i){
    $str .= $chars[mt_rand(0,61)];
  }
  return $str;
}

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  メール送信
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
function sendMail($from, $to, $subject, $comment){
  if(!empty($to) && !empty($subject) && !empty($comment)){
    //文字化けしないように設定(お決まりパターン)
    mb_language("Japanese"); //現在使っている言語を設定する
    mb_internal_encoding("UTF-8"); //内部の日本語を同園コーディング(機械がわかる言葉へ変換)するかを設定

    //メールを送信
    $result = mb_send_mail($to, $subject, $comment, "From: " .$from);
    //送信結果を判定
    if($result){
      debug('メールを送信しました。');
    }else{
      debug('エラー発生：メールの送信に失敗しました。');
    }
  }
}



//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  データベース関連
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//DB接続用関数
function dbConnect(){

  $dsn = 'mysql:dbname=illust;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options = array(
    //SQL失敗時、エラーコードのみ表示
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //デフォルトフェッチモードを連想配列型式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //バッファードクエリを使う
    //SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );

  //PDOオブジェクト生成
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}

//SQL実行関数
function queryPost($dbh, $sql, $data){
  //クエリー作成
  $stmt = $dbh->prepare($sql);
  //プレースホルダに値をセットし、SQL文を実行
  if(!$stmt->execute($data)){
    debug('クエリ失敗');
    debug('クエリ失敗のSQL：'.print_r($stmt,true));
    $err_msg['common'] = MSG07;
    return 0;
  }
  debug('クエリ成功');
  return $stmt;
}
//ユーザー情報を取得してくる関数
function getUser($user_id){
  debug('ユーザー情報を取得します。');
  //例外処理
  try{
    //DBへ接続
    $dbh = dbConnect();
    //sql文作成
    $sql = 'SELECT * FROM users WHERE id = :user_id AND delete_flg = 0';
    $data = array(':user_id' => $user_id);

    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
  //クエリ結果のデータを１レコード返却
    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  }catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
  //クエリ結果のデータを返却
//  return $stmt->fetch(PDO::FETCH_ASSOC);
}

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  その他
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//サニタイズ
function sanitize($str){
  return htmlspecialchars($str,ENT_QUOTES);
}


 ?>
