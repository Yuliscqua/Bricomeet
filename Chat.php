<?php
session_start();

$user = $_SESSION['pseudo'];
$chatter = $_SESSION['pseudo2'];
$file = fopen('messages.txt', "r");

function ajouterMessage($donnees_message, $chemin_fichier) {
  $messages = file($chemin_fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $nouvel_utilisateur = implode(',', $donnees_message);
  file_put_contents($chemin_fichier, $nouvel_utilisateur . PHP_EOL, FILE_APPEND);
  return true; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $donnees_message = [];
  $donnees_message[0] = $user;
  $donnees_message[1] = $chatter;
  $donnees_message[2] = $_POST['chat'];
  $donnees_message[3] = date('H-i-s');

  $chemin_fichier = __DIR__ . '/messages.txt';

  ajouterMessage($donnees_message, $chemin_fichier);
      
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BricoMeet</title>
    <link rel="stylesheet" href="Accueil.css">
  </head>
  <body>
    <nav class="nav">
      <div class="nav-left">
        <a href="#" class="nav-brand">
          <img src="./assets/logo-1.png">
        </a>

        <ul class="nav-menu">
          <li>
            <div class="dropdown-container">
              <a href="Accueil_Abonne.php" class="nav-link">Accueil</a>
            </div>
          </li>
        </ul>
        <ul>
          <li>
            <a href="Choice.php" class="nav-link">Messages</a>
          </li>
        </ul>
        <ul>
          <li>
            <a href="Recherche.php" class="nav-link">Rechercher</a>
          </li>
        </ul>
      </div>
      <div class="nav-right">
        <div class="dropdown-container">
          <a href="#" class="nav-link btn-profile">
            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path fill="none" d="M0 0h24v24H0z"></path>
              <path fill="#F2F2F2" d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"></path>
            </svg>
            <span href="Inscription.html">Compte</span>
          </a>

          <div class="dropdown-menu profile-dropdown">
            <a href="#">
              <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                <path fill="none" d="M0 0h24v24H0z"></path>
                <path fill="#D1D5DB" d="M10 3h4a8 8 0 1 1 0 16v3.5c-5-2-12-5-12-11.5a8 8 0 0 1 8-8zm2 14h2a6 6 0 1 0 0-12h-4a6 6 0 0 0-6 6c0 3.61 2.462 5.966 8 8.48V17z"></path>
              </svg>
              <span>Envoyer un commentaire</span>
            </a>
            <a href="Profil.php">
              <svg id="Layer_1" version="1.1" viewBox="0 0 150 150" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"width="20" height="20">
                <g>
                  <path fill="#D1D5DB" d="M30,49c0,18.7,15.3,34,34,34s34-15.3,34-34S82.7,15,64,15S30,30.3,30,49z M90,49c0,14.3-11.7,26-26,26S38,63.3,38,49   s11.7-26,26-26S90,34.7,90,49z"/>
                  <path fill="#D1D5DB" d="M24.4,119.4C35,108.8,49,103,64,103s29,5.8,39.6,16.4l5.7-5.7C97.2,101.7,81.1,95,64,95s-33.2,6.7-45.3,18.7L24.4,119.4z"/>
                </g>
              </svg>
              <span>Profil</span>
            </a>
            <a href="Accueil.html">
              <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="20" height="20">
                <path d="M18.189 9a15 15 0 0 1 2.654 2.556c.105.13.157.287.157.444m-2.811 3a14.998 14.998 0 0 0 2.654-2.556A.704.704 0 0 0 21 12m0 0H8m5-7.472A6 6 0 0 0 3 9v6a6 6 0 0 0 10 4.472" stroke="#D1D5DB" fill="none" stroke-width="2px"></path>
              </svg>
              <span>Se déconnecter</span>
            </a>
          </div>
        </div>
      </div>
    </nav>
    </div></br>
    <h1 class="name_chatted"><?php echo htmlspecialchars($chatter);?></h1></br>
    <div class='framechat'>
      <?php
      if ($file){
        while (($line = fgets($file)) != false){
          $data = explode(",",trim($line));
          if ($data[0] === $user && $data[1] === $chatter){
            ?> <div class="first"><?php echo htmlspecialchars($data[2]);?></br>
            <?php echo htmlspecialchars($data[3]);?></div></br><?php
          }
          else if($data[1] === $user && $data[0] === $chatter){
            ?> <div class="second"><?php echo htmlspecialchars($data[2]);?></br>
          <?php echo htmlspecialchars($data[3]);?></div></br><?php
          }
        }
      }
      ?>
    </div>
    <?php if(isset($_SESSION['pseudo'])) { ?>
    <div id='result'></div>
    <div class='chatbody'>
      <form method="post" >
      <input type='text' name='chat' id='msgbox' placeholder="Tapez votre message..." required/>
      <input type='submit' name='send' id='send' class='btn btn-send' value='Envoyer' />
    </form>
    </div>
    <?php } ?>
  </body>
</html>
