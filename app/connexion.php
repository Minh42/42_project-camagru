<?php
// obtain connnection to the database
require_once "../config/database.php";

if($user->is_logged_in() == FALSE)
{
    $user->redirect('index.php');
    exit;
}

$user_id = $_SESSION['user_session'];
$statement = $conn->prepare('SELECT firstname, lastname, username, profile_pic_url FROM users WHERE user_id=:user_id');
$statement->execute(array(':user_id' => $user_id));
while($row = $statement->fetch(PDO::FETCH_ASSOC)){
  $firstname = $row['firstname'];
  $lastname = $row['lastname'];
  $username = $row['username'];
  $profile_pic_url = $row['profile_pic_url'];
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Camagru</title>
    <link rel="stylesheet" href="../style/gallery.css" />
    <link rel="stylesheet" href="../style/caroussel.css" />
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
                <a href='../app/gallery.php' class="navbar-item">
                    Gallery
                </a>
                <a href='../app/account.php' class="navbar-item">
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
    
      <div class="hero-body">        
        <div class="container has-text-centered">
            <div class="tile is-ancestor">
                <div class="tile is-parent">
                    <article class="tile is-child box" id="container">
                        <p class="title" style="color:red;">Smile 😁 </i></p>
                        <figure class="image is-4">
                          <div class="outer-container">
                            <div class="inner-container" id="image_container">
                              <figure class="image is-4by3" id="video-overlay">
                                <img crossOrigin="Anonymous" id="preview"/>
                              </figure>
                              <figure class="image is-4by3" id="video-overlay2">
                                <img ondragstart="return false" class="preview2" id="preview2"/>
                              </figure>
                              
                              <div><video id="video">Video stream not available.</video></div>
                            </div>
                          </div>
                          </br>
                          <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                              <a class="button is-warning" onclick="toggle()">Select Camera</a>
                            </p>
                            <p class="control" id="show">
                            <a  class="button is-primary is-normal">
                              <span class="icon">
                                <i class="fas fa-camera"></i>
                              </span>
                              <button id="startbutton">Snapshot</button>
                            </a>
                            </p>
                          </div>
                          
                          <div class="modal" id="modal">
                            <div class="modal-background"></div>
                            <div class="modal-content">
                              <div class="card">
                              <header class="card-header">
                                <p class="card-header-title">
                                  Would you like to save this picture?
                                </p>
                              </header>
                                <div class="card-content">
                                  <div class="content">
                              
                                      <img id="final"/>
                                      <canvas id="canvas"></canvas>
                                 
                                  </div>

                                  <div class="card-content">
                                    <div class="media">
                                    <div class="media-left">
                                      <figure class="image is-48x48">
                                        <?php 
                                        if (isset($profile_pic_url)) {
                                          echo "<img style='border-radius:50%' src='$profile_pic_url'>";
                                        }
                                        else
                                          echo "<img style='border-radius:50%' src='https://bulma.io/images/placeholders/96x96.png'>";
                                        ?>
                                      </figure>
                                    </div>
                                    <div class="media-content">                                   
                                      <p class="title is-size-6-desktop" style="color:black"><?php echo $username ?></p>                             
                                    </div>
                                  </div>
                                  <div class="content">
                                    <textarea class="textarea" id="caption" placeholder="Add a caption..." maxlength="300"></textarea>
                                  </div>
                                  <div class="field is-grouped is-grouped-left">
                                  <p class="control">
                                    <a class="button is-primary" onclick="saveFile()">
                                      Submit
                                    </a>
                                  </p>
                                  <p class="control">
                                    <button class="button is-light" id="delete">Cancel</button>
                                  </p>
                                </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </figure>
                    </article>
                    <article class="tile is-child notification" style="background-color: rgba(255, 255, 255, 0.1);">
                        <p class="title">How does it work?</p>
                        <div class="content">
                            <p>⚡ Turn on your webcam or upload your own picture</p>
                            <p class="control">
                              <form method="post" enctype="multipart/form-data" id="form">
                                <input class="button is-light" type="file" name="fileToUpload" id="fileToUpload">
                              </form>
                            </p>
                        </div>
                        <div class="content">
                            <p>😍 Select an image and adjust it so it fits perfectly</p>
                        </div>

                        <div class="field is-grouped is-grouped-centered">
                          <p class="control">
                            <input type="button" id="zoomout" class="button is-small" value="Zoom out">
                          </p>
                          <p class="control">
                            <input type="button" id="zoomin" class="button is-small" value="Zoom in">
                          </p>
                        </div>

   
                         <div class="content">
                            <p>😎 Add filter effects to make it even more awesome</p>
                        </div>

                        <div class="field is-grouped">
                          <p class="control">
                            <div class="buttons has-addons ">
                              <span class="button is-small" id="grayscale">Grayscale</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('grayscale', this.valueAsNumber);" value="0" step="0.1" min="0" max="1"></span>
                            </div>
                          </p>
                          <p class="control">
                            <div class="buttons has-addons">
                              <span class="button is-small" id="sepia">Sepia</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('sepia', this.valueAsNumber);" value="0" step="0.1" min="0" max="1"></span>
                            </div>
                          </p>
                          <p class="control">
                            <div class="buttons has-addons">
                              <span class="button is-small" id="saturation">Saturation</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('saturate', this.valueAsNumber);" value="0" step="0.1" min="0" max="10"></span>
                            </div>
                          </p>
                        </div>

                        <div class="field is-grouped">
                          <p class="control">
                            <div class="buttons has-addons">
                              <span class="button is-small" id="hue">Hue-rotate</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('hue-rotate', this.value + 'deg');" value="0" step="30" min="0" max="360"></span>
                            </div>
                          </p>
                          <p class="control">
                            <div class="buttons has-addons" id="invert">
                              <span class="button is-small" id="invert">Invert</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('invert', this.valueAsNumber);" value="0" step="0.1" min="0" max="1"></span>
                            </div>
                          </p>
                          <p class="control">
                            <div class="buttons has-addons" id="opacity">
                              <span class="button is-small" id="opacity">Opacity</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('opacity', this.valueAsNumber);" value="0" step="0.1" min="0" max="1"></span>
                            </div>
                          </p>
                        </div>

                        <div class="field is-grouped">
                          <p class="control">
                            <div class="buttons has-addons">
                              <span class="button is-small" id="brightness">Brightness</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('brightness', this.valueAsNumber);" value="0" step="0.1" min="0" max="10"></span>
                            </div>
                          </p>
                          <p class="control">
                            <div class="buttons has-addons">
                              <span class="button is-small" id="contrast">Contrast</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('contrast', this.valueAsNumber);" value="0" step="0.1" min="0" max="10"></span>
                            </div>
                          </p>
                          <p class="control">
                            <div class="buttons has-addons">
                              <span class="button is-small" id="blur">Blur</span>
                              <span class="button is-small"><input class="range" type="range" oninput="set('blur', this.value + 'px');" value="0" step="1" min="0" max="10"></span>
                            </div>
                          </p>
                        </div>

                        <div class="content">
                            <p> 📸 Take a snapshot and save your picture</p>
                        </div>    

                    </article>
                </div>
                <div class="tile is-parent is-2">
                    
                <article class="tile is-child notification is-danger">
                  <div class="box has-text-centered">
       
                            <div class="gallery-container">
                            
                                <a href="#">
                                  <img class="thumbnail" id="thumbnail_1" src="../filtres/chat.png" data-image-id="8200864135" alt="chat">
                                </a>
                          
                                <a href="#">
                                  <img class="thumbnail" id="thumbnail_2" src="../filtres/weed.png" data-image-id="8200864135" alt="weed">
                                </a>
                            
                                <a href="#">
                                  <img class="thumbnail" id="thumbnail_3" src="../filtres/thug.png" data-image-id="8200864135" alt="thug">
                                </a>
                          
                                <a href="#" >
                                  <img class="thumbnail" id="thumbnail_4" src="../filtres/pineapple.png" data-image-id="8200864135" alt="pineapple">
                                </a>
                          
                                <a href="#">
                                  <img class="thumbnail" id="thumbnail_5" src="../filtres/sangoku.png" data-image-id="8200864135" alt="sangoku">
                                </a>
                          
                                <a href="#">
                                  <img class="thumbnail" id="thumbnail_6" src="../filtres/doge.png" data-image-id="8200864327" alt="doge">
                                </a>

                            </div>
      
                      </div>
                    </article>

                </div>
            </div>
        </div>
      </div>

      <!-- Hero content: will be in the middle -->
      <div class="hero-body">        
          <div class="container has-text-centered">
      <?php
      $statement = $conn->prepare('SELECT photo_id, image_path FROM photos WHERE user_id=:user_id ORDER BY date_created DESC LIMIT 8');
      $statement->bindParam(':user_id', $user_id);
      $statement->execute(array(':user_id'=>$user_id));
  
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
                  <button class="button is-danger" onclick="deletePicture()">Delete picture</button>
                </p>
              </div>
              <button class="modal-close is-large" onclick="closeModal()" aria-label="close"></button>
            </div>
          </article>

        </article>
      </div>
          
        </div>

    </div>


      <!-- Footer -->   
        
      <footer class="footer">
      </footer>
      
      <script src="../js/caroussel.js"></script>
      <script src="../js/camera.js"></script>
      <script src="../js/upload.js"></script>
      <script src="../js/filters.js"></script>
      <script src="../js/save_pictures.js"></script>
      <script src="../js/overlay.js"></script>
      <script src="../js/drag.js"></script>
      <script src="../js/comments.js"></script> 
  </body>
</html>