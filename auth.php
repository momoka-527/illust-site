<?php

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  ログイン認証
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//ログインしている場合
if(!empty($_SESSION['login_date'])){
  debug('ログイン済みユーザーです。');

  //現在日時が最終ログイン日時＋ログイン有効期限を超えていた場合
  if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){ //time関数...現在のUNIXタイムスタンプを返す
    debug('ログイン有効期限切れユーザーです。');

    //ログアウト処理(セッション削除)
    session_destroy();
    //ログアウトさせた後、ログインページへ遷移
    header("Location:login.php");

  //現在日時が最終ログイン日時＋ログイン有効期限を超えていなかった場合
  }else{
    debug('ログイン有効期限内ユーザーです。');

    //最終ログイン日時を現在日時に更新
    $_SESSION['login_date'] = time(); //UNIXタイムスタンプ

    //$_SERVERはあらかじめ用意されている関数
    //['PHP_SELF']は現在実行中のスクリプトファイル名、ドメインからのパスを返す(/homepage/login.php)
    //basename関数を使うことで、ファイル名だけを取り出せる
    if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      debug('マイページへ遷移します。');
      header("Location:mypage.php");
    }

  }
//ログインしていない場合
}else{
  debug('ログインしていないユーザーです。');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
    header("Location:login.php"); //ログインページへ
  }
}

?>
