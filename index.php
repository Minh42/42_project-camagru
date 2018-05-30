<?php require_once __DIR__ . "/config/database.php"; ?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Camagru</title>
    <link rel="stylesheet" href="style/style.css" />
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
            <form method="post" action="app/login.php">

              <a class="navbar-item">
                <img src="https://fontmeme.com/permalink/180409/0fd220bde405d5a4a2b31c756f93c6e5.png" alt="Logo">
                <span class="icon">
                    <i class="fas fa-camera fa-1x";></i>
                  </span>
              </a>
              <span class="navbar-burger burger" data-target="navbarMenuHeroC">
                <span></span>
                <span></span>
                <span></span>
              </span>
            </div>
            <div id="navbarMenuHeroC" class="navbar-menu">
              <div class="navbar-end">
                <a class="navbar-item is-active">
                  Home
                </a>
                <a class="navbar-item">
                  <input class="input" type="email" placeholder="Your Email address" name="email" id="email2" required>
                </a>
                <a class="navbar-item">
                  <input class="input" type="password" placeholder="Your Password" name="password" id="password2" required>
                </a>
                <span class="navbar-item">
                  <div class="control">
                    <input class="button is-primary is-inverted" type="submit" name="login" value="Log in">
                  </div>
                </span>
                <a href='app/forgot.php' class="navbar-item">
                    Forgot password?
                </a>
              </div>
              </form>
            </div>
          </div>
        </header>
      </div>
    

      <?php
      $statement = $conn->prepare('SELECT photo_id AS new_table FROM likes GROUP BY photo_id ORDER BY new_table DESC LIMIT 1');
      $statement->execute();
      $most_upvoted = $statement->fetchColumn();

      $statement = $conn->prepare('SELECT image_path FROM photos WHERE photo_id=:photo_id');
      $statement->bindParam(':photo_id', $most_upvoted);
      $statement->execute();
      $url = $statement->fetchColumn();
      $url = substr($url, 3);
      ?>

      <div class="hero-body">        
        <div class="container has-text-centered">
            <div class="tile is-ancestor">
                <div class="tile is-vertical is-8">
                  <div class="tile">
                    <div class="tile is-parent is-vertical">
                      <article class="tile is-child notification is-warning">
                        <p class="title has-text-left">Camagru lets you easily capture moments with friends, discover all the creations from our community and share them on social networks.</p>
                      </article>
                    </div>
                    <div class="tile is-parent">
                        <article class="tile is-child box">
                            <p class="title" style="color:red;">Most upvoted photo <i class="fas fa-heart"></i></p>
                            <figure class="image is-4by3">
                              <?php
                                if (is_string($url)) {
                                  echo "<img src='$url'>";
                                }
                                else
                                  echo "<img src='filtres/doge_portrait.jpg'>";
                              ?>
                    
                            </figure>
                          </article>
                    </div>
                  </div>
                  <div class="tile is-parent">
                    <article class="tile is-child">
                      <div class="content">
                        <img src="filtres/apple.png">
                      </div>
                    </article>
                  </div>
                </div>
                <div class="tile is-parent">
                  <article class="tile is-child box">
                    <div class="content">
                        <h1 class="title" style="color:black;">Create an account</h1>
                        <h2 class="subtitle" style="color:black;">It's free.</h2>
                        <form enctype="multipart/form-data" id="form">                        
                            <div class="field">
                              <div class="control has-icons-left has-icons-right">
                                <input class="input" type="email" placeholder="Email address" value="<?php if(isset($error)){echo $email;}?>" name="email" id="email" required>
                                <span class="icon is-small is-left">
                                  <i class="fas fa-envelope"></i>
                                </span>
                                <p class="help is-danger has-text-left" id="error_email"></p>
                              </div>
                            </div>
                            <div class="field">
                              <div class="control">
                                <input class="input" type="text" placeholder="First name" value="<?php if(isset($error)){echo $firstname;}?>" name="firstname" id="firstname" required>
                              </div>
                              <p class="help is-danger has-text-left" id="error_firstname"></p>
                            </div>
                            <div class="field">
                              <div class="control">
                                <input class="input" type="text" placeholder="Last name" value="<?php if(isset($error)){echo $lastname;}?>" name="lastname" id="lastname" required>
                              </div>
                              <p class="help is-danger has-text-left" id="error_lastname"></p>
                            </div>
                            <div class="field">
                              <div class="control has-icons-left has-icons-right">
                                <input class="input" type="text" placeholder="Username" value="<?php if(isset($error)){echo $username;}?>" name="username" id="username" required>
                                <span class="icon is-small is-left">
                                  <i class="fas fa-user"></i>
                                </span>
                                <p class="help is-danger has-text-left" id="error_username1"></p>
                                <p class="help is-danger has-text-left" id="error_username2"></p>
                              </div>
                            </div>
                            <div class="field">
                              <p class="control has-icons-left">
                                <input class="input" type="password" placeholder="New password" name="password" id="password" required>
                                <span class="icon is-small is-left">
                                  <i class="fas fa-lock"></i>
                                </span>
                              </p>
                            </div>                           
                            <div class="field">
                              <p class="control has-icons-left">
                                <input class="input" type="password" placeholder="Confirm password" name="confirmed_password" id="confirmed_password" required>
                                <span class="icon is-small is-left">
                                  <i class="fas fa-lock"></i>
                                </span>
                                <p class="help is-danger has-text-left" id="error_password1"></p>
                                <p class="help is-danger has-text-left" id="error_password2"></p>
                                <p class="help is-danger has-text-left" id="error_password3"></p>
                              </p>
                            </div>

                            <div class="control">
                                <input class="button is-success" type="submit" name="signup" id="signup" value="Sign up">
                            </div>
                          </form>
                      </div>
                    </article>
                  </div>
            </div>
        </div>
      </div>


      <!-- Registration modal -->
      <div class="modal" id="modal">
        <div class="modal-background"></div>
        <div class="modal-content">
          <div class="notification is-light">
            <button class="delete" id="delete"></button>
              <div class="content">
                <h2>A verification link has been sent to your email account</h2>
                <p>Please click on the link that has just been sent to your email account to verify your email and continue the registration process.</p>
              </div>
              
          </div>
        </div>
      </div>

      <!-- Hero content: will be in the middle -->
      <div class="hero-body">        
        <div class="container has-text-centered" id="content">
           
          <?php
            $statement = $conn->prepare('SELECT photo_id, image_path FROM photos ORDER BY date_created DESC LIMIT 20');
            $statement->execute();
        
            while ($data = $statement->fetch(PDO::FETCH_ASSOC)) { ?>

              <div class="gallery">
                  <img class="picture" src="<?php echo substr($data['image_path'], 3) ?>" id="<?php echo $data['photo_id'] ?>" width="612" height="612">
              </div>
          <?php } ?>
          
        </div>
      </div>

      <div class="modal" id="modal2">
        <div class="modal-background"></div>
        <div class="modal-content">
          <div class="notification is-light">
            <button class="delete" id="delete2"></button>
              <div class="content">
                <h2>Interested in trying out our application?</h2>
                <p>Please login to your existing Camagru account to get started. To create a Camagru account, please register.</p>
              </div>
              
          </div>
        </div>
      </div>

      <!-- Footer -->   
        
      <footer class="footer">
      </footer>

      <script src="js/signup.js"></script>
      <script src="js/infinite_scroll2.js"></script> 

  </body>
</html>