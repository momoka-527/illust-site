
<header>
  <div class="header">
    <h1>
      <a href="index.php">イラストサイト</a>
    </h1>
    <nav class="top-nav">
      <ul>
      <?php
      if(empty($_SESSION['user_id'])){
      ?>
              <li><a href="login.php" class="nav-item">ログイン</a></li>
              <li><a href="signup.php" class="sign-up">ユーザー登録</a></li>
      <?php
    }else{
      ?>
          <li><a href="logout.php" class="nav-item">ログアウト</a></li>
          <li><a href="mypage.php" class="nav-item">マイページ</a></li>
          <li><a href="irustManage.php" class="nav-item">作品管理</a></li>
          <li><a href="irustPost.php" class="nav-item nav-irustpost">作品投稿</a></li>
      <?php
      }
      ?>
      </ul>
    </nav>
    <!-- 検索バー -->
    <div class="search-form">
      <form>
          <input type="text" name="search" placeholder="SEARCH">
          <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
      </form>
    </div>
  </div>
</header>
