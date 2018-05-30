<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Camagru</title>
    <link rel="stylesheet" href="../style/style.css" />
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
            <form method="post" action="login.php">

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
                  <input class="input" type="email" placeholder="Your Email address" name="email" id="email" required>
                </a>
                <a class="navbar-item">
                  <input class="input" type="password" placeholder="Your Password" name="password" id="password" required>
                </a>
                <span class="navbar-item">
                  <div class="control">
                    <input class="button is-primary is-inverted" type="submit" name="login" value="Log in">
                  </div>
                </span>
              </div>
              </form>
            </div>
          </div>
        </header>
      </div>
    
      <div class="hero-body">
        <div class="container has-text-centered">
            <div class="container">
                <div class="columns">
                    <div class="column is-8 is-offset-2">

                    <article class="tile is-child box">
                    <h1 class="title has-text-left" style="color:black;">Reset password</h1>
                    <h2 class="subtitle has-text-left" style="color:black;">We'll ask for this password whenever you sign in.</h2>
                      <form method="post" action="reset_password2.php">
                        <input type="hidden" name="email" value="<?php echo $_GET['email'] ?>"></input>
                        <input type="hidden" name="token" value="<?php echo $_GET['hash'] ?>"></input>                       
                        <div class="field">
                        <h2 class="subtitle has-text-left" style="color:black;">New password:</h2>
                          <p class="control has-icons-left">
                            <input class="input" type="password" name="new_password" id="new_password" required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-lock"></i>
                            </span>
                          </p>
                        </div>
                        <div class="field">
                        <h2 class="subtitle has-text-left" style="color:black;">Re-enter new password:</h2>
                          <p class="control has-icons-left">
                            <input class="input" type="password" name="confirmed_password" id="confirmed_password" required>
                            <span class="icon is-small is-left">
                              <i class="fas fa-lock"></i>
                            </span>
                          </p>
                        </div>
                        <div class="field is-grouped is-grouped-left">
                        <p class="control">
                            <input class="button is-success" type="submit" name="reset_password" value="Save changes">
                        </p>
                        </div>
                      </form>
                      </article>
                    </div>
                </div>
            </div>
        </div>
      </div>




  

      <!-- Footer -->   
        
      <footer class="footer">
        
      </footer>

  </body>
</html>