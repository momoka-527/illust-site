<?php
$siteTitle = 'イラスト編集画面';
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
      <div class="irustEdit-form-container">
        <h2>作品編集</h2>
        <div class="irustEdit-form">
          <form action="">
            <div class="list">
              <ul>
                <li>
                  <label class="panel">
                    画像1
                    <div class="panel-head">
                      <a href="#"><img src="images/2018032904-00.png" alt=""></a>
                    </div>
                  </label>
                </li>
                <li>
                  <label class="panel">
                    画像2
                    <div class="panel-head">
                      <a href="#"><img src="images/2018032904-00.png" alt=""></a>
                    </div>
                </li>
                <li>
                  <label class="panel">
                    画像3
                    <div class="panel-head">
                      <a href="#"><img src="images/2018032904-00.png" alt=""></a>
                    </div>
                  </label>
                </li>
              </ul>
            </div>
            <div>
              <div>
                <p>タイトル変更</p>
                <input type="text" name="title">
              </div>
              <div>
                <p>タグ編集</p>
                <input type="text" name="tag">
              </div>
              <div class="coment-form">
                <p>コメント編集</p>
                <textarea name="comment" rows="8" cols="80"></textarea>
                <span>0/250</span>
              </div>
            </div>

            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="更新する">
            </div>
          </form>
        </div>

      </div>
      <a href="mypage.php" class="return">&lt; マイページへ戻る</a>
    </section>
  </div>

  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
