<?php
// obtain connnection to the database
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

$user_id = $_SESSION['user_session'];
$statement = $conn->prepare('SELECT firstname, lastname, profile_pic_url FROM users WHERE user_id=:user_id');
$statement->execute(array(':user_id' => $user_id));
while($row = $statement->fetch(PDO::FETCH_ASSOC)){
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
  $profile_pic_url = $row['profile_pic_url'];
}
?>

<html>
  <head>
    <title>Camagru</title>
    <link rel="stylesheet" href="../style/gallery.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
  </head>

  <body>

    <section class="hero is-danger is-fullheight">
      <!-- Hero head: will stick at the top -->
      <div class="hero-head">
        <header class="navbar">
          <div class="container">
            <div class="navbar-brand">
              <a class="navbar-item">
                <img src="https://fontmeme.com/permalink/180409/0fd220bde405d5a4a2b31c756f93c6e5.png" alt="Logo">
                <span class="icon">
                    <i class="fas fa-camera fa-1x";></i>
                  </span>
              </a>
              <a class="navbar-item">
                      <?php
                        if (isset($profile_pic_url)) {
                          echo "<img style='border-radius:50%' src='$profile_pic_url'>";
                        }
                        else
                          echo "<img style='border-radius:50%' src='https://bulma.io/images/placeholders/128x128.png'>";
                      ?>
                        &nbsp;Hello, <?php echo $firstname."."; ?>
                </a>

              <span class="navbar-burger burger" data-target="navbarMenuHeroC">
                <span></span>
                <span></span>
                <span></span>
              </span>
            </div>
            <div id="navbarMenuHeroC" class="navbar-menu">
              <div class="navbar-end">
                <a href='connexion.php' class="navbar-item is-active">
                  Home
                </a>
                <a href='gallery.php' class="navbar-item">
                    Gallery
                </a>
                <a href='account.php' class="navbar-item">
                    My account
                </a>
                <span class="navbar-item">
                  <a href='../app/logout.php' class="button is-primary is-inverted">
                    <span class="icon">
                      <i class="fas fa-sign-in-alt"></i>
                    </span>
                    <span>Log out</span>
                  </a>
                </span>
              </div>
            </div>
          </div>
        </header>
      </div>
    
      <!-- Hero content: will be in the middle -->
      <div class="hero-body">        
          <div class="container has-text-centered" id="content">
               
              <?php
                $statement = $conn->prepare('SELECT photo_id, image_path FROM photos ORDER BY date_created DESC LIMIT 16');
                $statement->execute();
            
                while ($data = $statement->fetch(PDO::FETCH_ASSOC)) { ?>

                  <div class="gallery">
                      <img class="picture" src="<?php echo $data['image_path'] ?>" id="<?php echo $data['photo_id'] ?>" width="612" height="612">
                  </div>
              <?php } ?>
              
        </div>
      </div>

      <div class="modal" id="modal2">
        <div class="modal-background"></div>
          <div class="modal-content">

          <div class="tile is-parent">
            <article class="tile is-child box">
    

              <article class="media">
                <figure class="media-left">
                  <p class="image is-128x128">
                    <img id="this_picture">
                  </p>
                </figure>
              <div class="media-content">
                <div class="content">
                  <p>
                    <p id="username_modal" style='font-weight: bold'></p>
                    <p id="caption_modal"></p>
                    <br>
                    <a class="button is-info is-small" onclick="saveLikes()">
                      <span class="icon">
                      <i class="fas fa-thumbs-up"></i>
                      </span>
                      <span>Like</span>
                    </a>
                  </p>
				          <p id="likes"><small>0 😍</small></p> 
                </div>

                <div id="myHTMLWrapper"></div>

              </div>
            </article>
            <article class="media">
              <figure class="media-left">
                <p class="image is-64x64">
                  <?php
                    if (isset($profile_pic_url)) {
                      echo "<img style='border-radius:50%' src='$profile_pic_url'>";
                    }
                    else
                      echo "<img style='border-radius:50%' src='https://bulma.io/images/placeholders/128x128.png'>";
                  ?>
                </p>
              </figure>
              <div class="media-content">
                <div class="field">
                  <p class="control">
                    <textarea class="textarea" id="photo_comment" placeholder="Add a comment..."></textarea>
                  </p>
                </div>
                <div class="field is-grouped is-grouped-left">
                  <p class="control">
                    <button class="button" onclick="postComment()">Post comment</button>
                  </p>
                  <p class="control">
                    <button class="button is-danger" onclick="closeModal()">Cancel</button>
                  </p>
                </div>
              </div>
            </article>

          </article>
        </div>
            
          </div>

      </div>

      <!-- Footer -->   
        
      <footer class="footer">
      </footer>
      
      <script src="../js/infinite_scroll.js"></script> 
      <script src="../js/comments.js"></script> 
  </body>
</html>