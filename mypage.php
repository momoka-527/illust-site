<?php
require('function.php');

debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debug('■   マイページ');
debug('■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-■-');
debugLogStart();

//ログイン認証読み込み
require('auth.php');
?>
<?php
$siteTitle = 'マイページ';
//head部分読み込み
require('head.php');
?>
<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>

  <p id="js-show-msg" class="msg-slide" style="display:none;">
    <?php echo getSessionFlash('msg_success'); ?>
  </p>
  <!-- メインコンテンツ -->
  <div id="content">
    <section id="main">
      <div class="mypage-prof">
        <div class="prof-img">
          <img src="images/2018032904-00.png" alt="プロフィール画像">
        </div>
        <div class="prof-body">
          <div class="prof-item">
            <h2>ニックネーム</h2>
            <div>
              <span>フォロー準備中</span>
            </div>
          </div>
          <div class="prof-item">
            <div>
              <a href="profEdit.php" class="prof-btn">プロフィール編集</a>
            </div>
            <div>
              <a href="passEdit.php" class="prof-btn">パスワード変更</a>
            </div>
            <div>
              <a href="withdraw.php" class="prof-btn">退会する</a>
            </div>
          </div>
          <div class="prof-comment">
            <p>ここに自己紹介文ここに自己紹介文ここに自己紹介文ここに自己紹介文ここに自己紹介文ここに自己紹介文
            ここに自己紹介文ここに自己紹介文ここに自己紹介文ここに自己紹介文</p>
          </div>
        </div>
      </div>
      <div class="list">
        <h3>投稿作品</h3>
        <ul>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <a href="irustDetails.php">タイトル</a>
              </div>
            </div>
          </li>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <a href="irustDetails.php">タイトル</a>
              </div>
            </div>
          </li>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <a href="irustDetails.php">タイトル</a>
              </div>
            </div>
          </li>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <a href="irustDetails.php">タイトル</a>
              </div>
            </div>
          </li>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <a href="irustDetails.php">タイトル</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="list">
        <h3>ブックマーク</h3>
        <ul>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <div>
                  <a href="irustDetails.php">タイトル</a>
                </div>
                <div class="author">
                  <a href="mypage.php">作者</a>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <div>
                  <a href="irustDetails.php">タイトル</a>
                </div>
                <div class="author">
                  <a href="mypage.php">作者</a>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <div>
                  <a href="irustDetails.php">タイトル</a>
                </div>
                <div class="author">
                  <a href="mypage.php">作者</a>
                </div>
              </div>
            </div>
          </li>
          <li>
            <div class="panel">
              <div class="panel-head">
                <a href="irustDetails.php"><img src="images/2018032904-00.png" alt=""></a>
              </div>
              <div class="panel-body">
                <div>
                  <a href="irustDetails.php">タイトル</a>
                </div>
                <div class="author">
                  <a href="mypage.php">作者</a>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
