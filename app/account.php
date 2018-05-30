<?php
// obtain connnection to the database
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

?>

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
                <a href='../app/connexion.php' class="navbar-item is-active">
                  Home
                </a>
                <a href='../app/gallery.php' class="navbar-item">
                    Gallery
                </a>
                <a href='../app/account.php' class="navbar-item">
                    My account
                </a>
                <span class="navbar-item">
                  <a href='logout.php' class="button is-primary is-inverted">
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
            <div class="container has-text-centered">
                <div class="container">
                    <div class="columns">
                        <div class="column is-8 is-offset-2">
                        <article class="tile is-child box">

                        <h1 class="title has-text-left" style="color:black;">My details</h1>
                        <h2 class="subtitle has-text-left" style="color:black;">Feel free to edit any of your details below so your Camagru account is totally up to date.</h2>
                        
                        <figure class="image is-128x128">
                        <?php 

                        try {
                          $user_id = $_SESSION['user_session'];
                          $statement = $conn->prepare("SELECT * FROM users WHERE user_id=:user_id");
                          $statement->execute(array(':user_id'=>$user_id));
                          $row = $statement->fetch(PDO::FETCH_ASSOC);
                        }
                        catch(PDOException $e)
                        {
                          echo $e->getMessage();
                        }

                        if(!isset($row['profile_pic_url'])) {
                          echo "<img src='https://bulma.io/images/placeholders/128x128.png'>";
                        }
                        else
                          echo '<img src="'. $row['profile_pic_url'] . '"alt="avatar" width="100%">';
                        ?>

                        </figure>
                        <br />
                        <div class="container has-text-left">

                        <form action="upload_profile_pic.php" method="post" enctype="multipart/form-data">
                            <input class="button is-light" type="file" name="fileToUpload" id="fileToUpload">
                            <input class="button is-primary" type="submit" value="Upload" name="submit">
                        </form>
                        </div>
                        </br>
                        <form method="post" action="edit_info.php">
                            <div class="field">
                              <div class="control">
                                <h2 class="subtitle has-text-left" style="color:black;">First name:</h2>
                                <input class="input" type="text" value="<?php echo $row['firstname'];?>" name="firstname" id="firstname" required>
                              </div>
                            </div>
                            <div class="field">
                              <div class="control">
                                <h2 class="subtitle has-text-left" style="color:black;">Last name:</h2>
                                <input class="input" type="text" value="<?php echo $row['lastname'];?>" name="lastname" id="lastname" required>
                              </div>
                            </div>
                            <div class="field">
                            <h2 class="subtitle has-text-left" style="color:black;">Username:</h2>
                              <div class="control has-icons-left has-icons-right">
                                <input class="input" type="text" value="<?php echo $row['username'];?>" name="username" id="username" required>
                                <span class="icon is-small is-left">
                                  <i class="fas fa-user"></i>
                                </span>
                              </div>
                            </div>
                            <div class="field">
                            <h2 class="subtitle has-text-left" style="color:black;">Email:</h2>
                                <div class="control has-icons-left has-icons-right">
                                    <input class="input" type="email" value="<?php echo $row['email'];?>" name="email" id="email" required>
                                    <span class="icon is-small is-left">
                                    <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                              <h2 class="subtitle has-text-left" style="color:black;">Message notification:</h2>
                                <div class="field is-grouped is-grouped-left">
                                  <label class="checkbox is-grouped-left">
                                    <input type="checkbox" name="alert">
                                      Disable message notification
                                  </label>
                                </div>
                            </div>
                            </br>
                            <div class="field is-grouped is-grouped-left">
                            <p class="control">
                                <input class="button is-success" type="submit" name="edit_info" value="Save changes">
                            </p>
                            <a href='connexion.php' class="button is-danger">Cancel</a>
                            </div>
                          </form>

                        </br>
                        <h1 class="title has-text-left" style="color:black;">Change password</h1>
                        <h2 class="subtitle has-text-left" style="color:black;">Use the form below to change the password for your Camagru account.</h2>
                          <form method="post" action="change_password.php">
                            <div class="field">
                            <h2 class="subtitle has-text-left" style="color:black;">Current password:</h2>
                              <p class="control has-icons-left">
                                <input class="input" type="password" name="old_password" id="old_password">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-lock"></i>
                                </span>
                              </p>
                            </div>                           
                            <div class="field">
                            <h2 class="subtitle has-text-left" style="color:black;">New password:</h2>
                              <p class="control has-icons-left">
                                <input class="input" type="password" name="new_password" id="new_password">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-lock"></i>
                                </span>
                              </p>
                            </div>
                            <div class="field">
                            <h2 class="subtitle has-text-left" style="color:black;">Re-enter new password:</h2>
                              <p class="control has-icons-left">
                                <input class="input" type="password" name="confirmed_password" id="confirmed_password">
                                <span class="icon is-small is-left">
                                  <i class="fas fa-lock"></i>
                                </span>
                              </p>
                            </div>
                            </br>
                            <div class="field is-grouped is-grouped-left">
                            <p class="control">
                                <input class="button is-success" type="submit" name="change_password" value="Save changes">
                            </p>
                            <a href='connexion.php' class="button is-danger">Cancel</a>
                            </div>
                          </form>

                          </br>
                          <h1 class="title has-text-left" style="color:black;">Delete account</h1>
                          <h2 class="subtitle has-text-left" style="color:black;">Once your account is closed, it's no longer accessible by you or anyone else.</h2>
                            <div class="field is-grouped">
                              <p class="control">
                                <input class="button is-danger" type="submit" id="Delete" value="Delete">
                              </p>
                              <a href='connexion.php' class="button is-light">Cancel</a>
                            </div>
                    


                          </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal" id="modal">
          <div class="modal-background"></div>
          <div class="modal-card">
            <header class="modal-card-head">
              <p class="modal-card-title">Are you sure you want to delete your account?</p>
            </header>
            <footer class="modal-card-foot">
              <form method="post" action="delete_account.php">
                <div class="field is-grouped">
                  <p class="control">
                    <input class="button is-danger" type="submit" name="delete_account" value="Yes">
                  </p>
                  <a class="button is-light" id="no">No</a>
                </div>
              </form>
            </footer>
          </div>
        </div>


      <!-- Footer -->   
        
      <footer class="footer">
      </footer>
      
      <script>
          document.getElementById('Delete').addEventListener('click', function () {
          document.getElementById('modal').classList.add("is-active");

          document.getElementById('no').addEventListener('click', function () {
          document.getElementById('modal').classList.remove("is-active");
          });

        });
      </script>



  </body>
</html>