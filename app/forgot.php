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

                  
                        <h1 class="title">Find your account</h1>
                        <p class="subtitle">Enter the email address associated with your account, and we’ll email you a link to reset your password.</p>

                        <form method="post" action="../app/reset.php" id="form">
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control is-expended">
                                <input class="input" type="email" name="email" placeholder="Enter your email" required>
                            </p>
                            <p class="control">
                                <button class="button is-info" type="submit" name="reset">Search</button>
                            </p>
                            </form>
                            <p class="control">
                                <button class="button is-success" type="submit" name="cancel">Cancel</button>
                            </p>
                        </div>
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