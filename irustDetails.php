<?php
//関数等読みこみ
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   イラスト詳細画面');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証ファイル読み込み
require('auth.php');

//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-
//  画面処理
//■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-

//画面表示用データ取得
//=================================
//ユーザーID取得
$u_id = $_SESSION['user_id'];
//GETパラメータを取得
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
debug('$p_id:'.print_r($p_id,true));

//DBから商品データを取得
$viewData = getProductOne($p_id);
$viewCommentData = getMsg($p_id);

debug('取得したDBデータ：$viewData:'.print_r($viewData,true));
debug('取得したDBデータ：$viewCommentData:'.print_r($viewCommentData,true));

//パラメータに不正な値が入っているかチェック
if(empty($viewData)){
  error_log('エラー発生：指定ページに不正な値が入りました。');
  header('Location:index.php'); //トップページへ
}

//post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');

  //バリデーションチェック
  $comment = (isset($_POST['comment'])) ? $_POST['comment'] : '';
  //最大文字数チェック
  validMaxLen($comment, 'comment');
  //未入力チェック
  validRequired($comment, 'comment');

  if(empty($err_msg)){
    debug('バリデーションチェッククリア');

    //例外処理
    try{
      //DB接続
      $dbh = dbConnect();
      //SQL文作成
      $sql = 'INSERT INTO message (send_date, from_user, msg, bord_id, create_date)
              VALUES (:send_date, :from_user, :msg, :bord_id, :create_date)';
      $data = array(':send_date' => date('Y-m-d H:i:s'), ':from_user' => $u_id, ':msg' => $comment,
                    ':bord_id' => $p_id, ':create_date' => date('Y-m-d H:i:s'));

      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      //クエリ成功の場合
      if($stmt){
        $_POST = array(); //postクリア
        debug('クエリ成功コメント成功');
        header("Location:".$_SERVER['PHP_SELF'].'?p_id='.$p_id); //自分自身に遷移する
      }

    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
  }
}
debug('デバッグ：画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
 ?>

<?php
$siteTitle = 'イラスト詳細画面';
//head部分読み込み
require('head.php');
?>
<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

  <!-- メインコンテンツ -->
  <div id="content">
    <section id="main">
      <div class="irust-details">
        <div class="irust-item">
            <div class="main-irust">
                <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="メイン画像：<?php sanitize($viewData['title']); ?>" id="js-switch-img-main">
            </div>
            <div class="sub-irust-list">
              <div class="sub-irust">
                  <img src="<?php echo showImg(sanitize($viewData['pic1'])); ?>" alt="サブ1画像：<?php sanitize($viewData['title']); ?>" class="js-switch-img-sub">
              </div>
              <div class="sub-irust">
                  <img src="<?php echo showImg(sanitize($viewData['pic2'])); ?>" alt="サブ2画像：<?php sanitize($viewData['title']); ?>" class="js-switch-img-sub">
              </div>
              <div class="sub-irust">
                  <img src="<?php echo showImg(sanitize($viewData['pic3'])); ?>" alt="サブ3画像：<?php sanitize($viewData['title']); ?>" class="js-switch-img-sub">
              </div>
            </div>
        </div>

        <!-- 投稿者情報 -->
        <div class="irust-user">
          <div class="irust-user-panel">
            <img src="<?php echo showImg(sanitize($viewData['picture'])); ?>" alt="<?php echo sanitize($viewData['nickname']); ?>">
          </div>
          <div>
            <a href="mypage.php"><?php echo sanitize($viewData['nickname']); ?></a>
          </div>

          <!--他人のみ-->
          <div>
            <input type="submit" name="follow" value="フォローする">
          </div>

        </div>
      </div>

        <div class="title-area">
          <h2><?php echo sanitize($viewData['title']); ?></h2>

            <a href="#" style="display:inline-block;">#<?php echo sanitize($viewData['tag']); ?></a>

          <div class="author-comment">
            <p><?php echo sanitize($viewData['comment']); ?></p>
          </div>
        </div>

      <!-- コメント欄 -->
        <div class="comment-area">
          <form class="comment-post" method="post" action ="">
            <h3>コメント</h3>
            <div class="comment-text">
              <div class="err_msg_area">
                <p>
                  <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                  <?php if(!empty($err_msg['comment'])) echo $err_msg['comment']; ?>
                </p>
              </div>
              <textarea name="comment" rows="1" style="height: 31px;" class="<?php if(empty($_POST['comment'])) echo 'err'; ?>"></textarea>
              <div>
                <input type="submit" class="" value="送信">
              </div>
            </div>
          </div>

          <?php foreach($viewCommentData as $key => $value): ?>

          <div class="comment-list">
            <div class="comment-img">
              <img src="<?php echo showImg(sanitize($value['picture'])); ?>" alt="">
            </div>
            <div class="comment-user">
              <div><?php echo sanitize($value['nickname']); ?></div>
              <div>
                <p><?php echo sanitize($value['msg']); ?></p>
                <span><?php echo sanitize($value['send_date']); ?></span>
              </div>
            </div>
          </div>

        <?php endforeach; ?>


    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
