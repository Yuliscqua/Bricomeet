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
              <a href="#" class="nav-link">Accueil</a>
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
    <h2 class="titre"><a id="wrapper" href="#wrapper">Derniers Inscrits</a></h2>
    <div class="wrapper">
      <div class="card">
          <img src="./assets/stock-photo-single-young-woman-assembling-pieces-of-new-furniture-231316309.jpg"/>
          <div class="info">
              <h1>Marie-Madeleine</h1>
              <p>- Âge: 70 ans</br>- De: Montcul</p>
          </div>
      </div>

      <div class="card">
          <img src="./assets/stock-photo-bearded-builder-isolated-on-white-background-professional-builder-with-tools-1488541394.jpg"/>
          <div class="info">
              <h1>Roberto Carlos</h1>
              <p>- Âge: 22 ans</br>- De: Argenteuil</p>
          </div>
      </div>

      <div class="card">
        <img src="./assets/stock-photo-young-woman-renovating-her-bedroom-and-has-a-painter-carpet-in-her-hand-2161426729.jpg"/>
        <div class="info">
            <h1>x_RousseDu69_x</h1>
            <p>- Âge: 44 ans</br>- De: Lyon</p>
        </div>
    </div>
    <div class="card">
      <img src="./assets/stock-photo-young-man-bricolage-working-at-home-165773783.jpg"/>
      <div class="info">
          <h1>Manu Macron</h1>
          <p>- Âge: 18 ans</br>- De: Paris</p>
      </div>
    </div>

      <div class="card">
          <img src="./assets/stock-photo-pretty-young-woman-doing-diy-work-at-home-788769397.jpg"/>
          <div class="info">
              <h1>Shakira</h1>
              <p>- Âge: 99 ans</br>- De: Marseille</p>
          </div>
      </div>
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
