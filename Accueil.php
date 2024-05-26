<?php
session_start();

$filename = 'bdd_users.txt';

// Récupérer toutes les lignes du fichier
$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($lines === false) {
    echo "Erreur lors de l'ouverture du fichier.";
    exit;
}

// Récupérer les 5 derniers inscrits
$last_five_users = array_slice($lines, -5);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Accueil.css">
    <title>BricoMeet</title>
  </head>
  <body>
    <nav class="nav">
      <div class="nav-left">
        <a href="Accueil.php" class="nav-brand">
          <img src="./assets/logo-1.png">
        </a>

        <ul class="nav-menu">
          <li>
            <div class="dropdown-container">
              <a href="Accueil.php" class="nav-link">
                Accueil
                <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <path fill="none" d="M0 0h24v24H0z"></path>
              <path fill="#F2F2F2" d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"></path>
            </svg>
              </a>

              <div class="dropdown-menu grid">
                <a href="Inscription.php"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 1024 1024" >
                  <path d="M589.3 260.9v30H371.4v-30H268.9v513h117.2v-304l109.7-99.1h202.1V260.9z" fill="#D1D5DB"/>
                  <path d="M516.1 371.1l-122.9 99.8v346.8h370.4V371.1z" fill="#D1D5DB"/>
                  <path d="M752.7 370.8h21.8v435.8h-21.8z" fill="#7c7c7c"/>
                  <path d="M495.8 370.8h277.3v21.8H495.8z" fill="#7c7c7c"/>
                  <path d="M495.8 370.8h21.8v124.3h-21.8z" fill="#7c7c7c"/>
                  <path d="M397.7 488.7l-15.4-15.4 113.5-102.5 15.4 15.4z" fill="#7c7c7c"/>
                  <path d="M382.3 473.3h135.3v21.8H382.3z" fill="#7c7c7c"/>
                  <path d="M382.3 479.7h21.8v348.6h-21.8zM404.1 806.6h370.4v21.8H404.1z" fill="#7c7c7c"/>
                  <path d="M447.7 545.1h261.5v21.8H447.7zM447.7 610.5h261.5v21.8H447.7zM447.7 675.8h261.5v21.8H447.7z" fill="#7c7c7c"/>
                  <path d="M251.6 763h130.7v21.8H251.6z" fill="#7c7c7c"/>
                  <path d="M251.6 240.1h21.8v544.7h-21.8zM687.3 240.1h21.8v130.7h-21.8zM273.4 240.1h108.9v21.8H273.4z" fill="#7c7c7c"/>
                  <path d="M578.4 240.1h130.7v21.8H578.4zM360.5 196.5h21.8v108.9h-21.8zM382.3 283.7h196.1v21.8H382.3zM534.8 196.5h65.4v21.8h-65.4z" fill="#7c7c7c"/>
                  <path d="M360.5 196.5h65.4v21.8h-65.4zM404.1 174.7h152.5v21.8H404.1zM578.4 196.5h21.8v108.9h-21.8z" fill="#7c7c7c"/></svg>
              
              <span>Inscription</span></a>
              </div>
            </div>

          </li>
        </ul>
      </div>
      <div class="nav-right">
        <div class="dropdown-container">
          <a class="nav-link btn-profile">
            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path fill="none" d="M0 0h24v24H0z"></path>
              <path fill="#F2F2F2" d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"></path>
            </svg>
            <span href="Inscription.php">Compte</span>
          </a>

          <div class="dropdown-menu profile-dropdown">
            <a href="Inscription.php">
              <span>Créer un compte</span>
            </a>
            <a href="Connexion.php">
              <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="20" height="20">
                <path d="M18.189 9a15 15 0 0 1 2.654 2.556c.105.13.157.287.157.444m-2.811 3a14.998 14.998 0 0 0 2.654-2.556A.704.704 0 0 0 21 12m0 0H8m5-7.472A6 6 0 0 0 3 9v6a6 6 0 0 0 10 4.472" stroke="#D1D5DB" fill="none" stroke-width="2px"></path>
              </svg>
              <span>Se connecter</span>
            </a>
          </div>
        </div>
      </div>
    </nav>
    <h2 class="titre"><a id="wrapper" href="#wrapper">Derniers Inscrits</a></h2>
    <div class="wrapper">
      <?php
        foreach ($last_five_users as $user_line) :
          $user_data = explode(",", $user_line);
          $photo='photos_profil/pdp_' . $user_data[0] . '.jpg';
          if (!file_exists($photo)) {
            $photo = 'photos_profil/default.jpg';
          } 
      ?>
          <div class="card">
            <img src="<?php echo htmlspecialchars($photo); ?>"/>
            <div class="info">
                <h1><?php echo htmlspecialchars($user_data[0]); ?></h1>
                <p>- Date d'inscription: <?php echo htmlspecialchars($user_data[16]); ?></br>- De: <?php echo htmlspecialchars($user_data[7]); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
    <div class="slider">
      <div class="slide-track">
        <div class="slide">
          <img src="./assets/logo-3.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-2.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-4.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-2.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-3.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-2.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-4.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-2.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-3.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-2.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-4.png" height="100" width="250" alt="" />
        </div>
        <div class="slide">
          <img src="./assets/logo-2.png" height="100" width="250" alt="" />
        </div>
      </div>
    </div>
    <h2 class="titre"><a id="presentation" href="#presentation">Bienvenue !</a></h2>
    <div class="presentation">
      <div class="image-box">
        <img src="./assets/accueil_photo.jpg" class="image">
      </div>
      <div class="contenu">
        <h1>BricoMeet</h1>
        <p>Bienvenue sur BricoMeet ! Le rendez-vous en ligne pour les bricoleurs du dimanche qui cherchent l'amour entre deux coups de marteau. 
        <br>Que vous soyez un expert en menuiserie ou simplement un amateur passionné par le bricolage, notre plateforme vous met en relation avec des âmes qui partagent votre amour pour le bricolage. 
        <br>Trouvez votre partenaire de projet, votre âme sœur de rénovation ou votre coéquipier pour des escapades créatives. 
        <br>Rejoignez-nous dès aujourd'hui et laissez les étincelles créatives s'allumer dans votre vie amoureuse !</p>
      </div>
    </div>
    <footer class="footer">
      <div class="waves">
        <div class="wave" id="wave1"></div>
        <div class="wave" id="wave2"></div>
        <div class="wave" id="wave3"></div>
        <div class="wave" id="wave4"></div>
      </div>
      <ul class="social-icon">
        <li class="social-icon__item"><a class="social-icon__link" href="https://github.com/Yuliscqua/Bricomeet">
            <ion-icon name="logo-facebook"></ion-icon>
          </a></li>
        <li class="social-icon__item"><a class="social-icon__link" href="https://github.com/Yuliscqua/Bricomeet">
            <ion-icon name="logo-twitter"></ion-icon>
          </a></li>
        <li class="social-icon__item">
          <a class="social-icon__link" href="https://github.com/Yuliscqua/Bricomeet">
            <ion-icon name="logo-linkedin"></ion-icon>
         </a>
        </li>
        <li class="social-icon__item">
          <a class="social-icon__link" href="https://github.com/Yuliscqua/Bricomeet">
            <ion-icon name="logo-instagram"></ion-icon>
          </a>
        </li>
      </ul>
      <ul class="menu">
        <li class="menu__item"><a class="menu__link" href="#">Accueil</a></li>
        <li class="menu__item"><a class="menu__link" href="#presentation">A propos</a></li>
      </ul>
      <p>&copy;2024 BricoMeet | Tous droits réservés</p>
    </footer>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  </body>
</html>