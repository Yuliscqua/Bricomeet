<?php
session_start();

if (!isset($_SESSION['pseudo'])) {
    echo "Vous devez être connecté pour voir cette page.";
    exit;
}

$pseudo_connecte = $_SESSION['pseudo'];

// Lire le fichier texte
$filename = 'bdd_users.txt';
$file = fopen($filename, "r");
$profile = null;

if ($file) {
    while (($line = fgets($file)) !== false) {
        $data = explode(",", trim($line));
        if ($data[0] === $pseudo_connecte) {
            $photo_path = __DIR__ . '/photos_profil/pdp_' . $data[0] . '.jpg';
            $photo_url = 'photos_profil/pdp_' . $data[0] . '.jpg';
            // Vérifiez si la photo de profil existe
            if (!file_exists($photo_path)) {
                $photo_url = 'photos_profil/default.jpg'; // Image par défaut
            }
            $profile = [
                'Pseudo' => $data[0],
                'Sexe' => $data[1],
                'ID' => $data[2],
                'Date de naissance' => $data[3],
                'Profession' => $data[4],
                'Pays' => $data[5],
                'Code postal' => $data[6],
                'Ville' => $data[7],
                'Statut marital' => $data[8],
                'Enfants' => $data[9],
                'Taille (cm)' => $data[10],
                'Poids (kg)' => $data[11],
                'Description' => $data[12],
                'Nom' => $data[13],
                'Prénom' => $data[14],
                'Adresse' => $data[15],
                'Date d\'inscription' => $data[16],
                'Rôle' => $data[17],
                'Photo' => $photo_url // Chemin de la photo de profil ou image par défaut
            ];
            break;
        }
    }
    fclose($file);
} else {
    echo "Erreur lors de l'ouverture du fichier.";
    exit;
}

if (!$profile) {
    echo "Profil non trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil de <?php echo htmlspecialchars($profile['Prénom'] . ' ' . $profile['Nom']); ?></title>
  <style>
@import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap");
@import "https://unpkg.com/open-props" layer(design.system);

*,
*::before,
*::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
}

body {
  margin: 0;
  padding: 0;
  background-color: #353535;
  color: #f2f2f2;
  font-family: "Montserrat", sans-serif;
}

a,
button {
  all: unset;
  cursor: pointer;
}

ul {
  list-style: none;
}

.nav {
  height: 56px;
  padding: 0.8rem 1.5rem;
  background: #171717;
  display: flex;
  justify-content: space-between;
  position: relative;
}

.nav::after{
  position: absolute;
  content: "";
  top: 15px;
  left: -3%;
  right: 0;
  z-index: -1;
  height: 100%;
  width: 105%;
  transform: scale(0.9) translateZ(0);
  filter: blur(15px);
  background: linear-gradient(
    to left,
    #57d8ff,
    #4283e4,
    #2d50c4,
    #3616c3,
    #3b0083,
    #3616c3,
    #2d50c4,
    #4283e4,
    #57d8ff
  );
  background-size: 200% 200%;
  -webkit-animation: animateGlow 4s linear infinite;
  animation: animateGlow 4s linear infinite;
}

@keyframes animateGlow {
  0% {
    background-position: 0% 50%;
  }
  100% {
    background-position: 200% 50%;
  }
}

.nav-left,
.nav-right {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.8rem;
}
.nav-brand {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-right: 0.5rem;
  translate: 0 -0.08rem;
  transition: opacity 0.3s ease-in-out;
}
.nav-brand > img {
  width : 170px;
  height : 46px;
}

.nav-brand:hover {
  opacity: 0.5;
  cursor: pointer;
}

.nav-menu {
  display: flex;
  gap: 0.75rem;
}

.nav-link {
  font-size: 16px;
  font-weight: 600;
  padding: 0.6rem 0.625rem;
  border-radius: 0.5rem;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.15s;
}
.nav-link > svg {
  font-weight: 600;
  width: 14px;
  height: 14px;
  margin-left: 10px;
}
.nav-link:hover {
  background-color: #212121;
}

.btn-profile {
  font-size: 16px;
  padding: 0.5rem 5.2rem 0.5rem 0.4rem;
}
.btn-profile > svg {
  width: 24px;
  height: 24px;
  margin-right: 10px;
}

/* DROPDOWN MENU */
.dropdown-container {
  position: relative;
  overflow: visible;
}

.dropdown-menu {
  position: absolute;
  left: 0;
  display: block;
  visibility: hidden;
  opacity: 0;
  top: calc(100% + 8px);
  background-color: #212121;
  padding: 1rem;
  gap: 0.5rem;
  border-radius: 0.75rem;
  z-index: 9999;
  transition: 0.3s;
}
.dropdown-container:hover > .dropdown-menu {
  opacity: 1;
  visibility: visible;
}
.dropdown-menu.grid {
  display: flex;
  grid-template-columns: repeat(2, 240px);
}
.dropdown-menu > a {
  font-weight: 600;
  padding: 1rem;
  background-color: #292929;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.dropdown-menu > a:hover {
  background-color: #353535;
}
.dropdown-menu > a > span:nth-of-type(2) {
  color: #9ca3af;
}

/* Profile Dropdown */
.profile-dropdown {
  width: 100%;
  padding: 5px;
  translate: 0 -20px;
  opacity: 0;
  transition: 0.3s ease;
}
.dropdown-container:hover:has(.profile-dropdown) > .profile-dropdown {
  translate: 0 0;
  opacity: 1;
}
.profile-dropdown > a {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 8px;
  background-color: transparent;
  color: #d1d5db;
  transition: 0.15s ease;
}
.profile-dropdown > a:hover {
  background-color: #171717;
  color: #f0f3f5;
}

.profile-dropdown > a:nth-child(3) {
  background-color: #5966f3;
  color: white;
}
.profile-dropdown > a:nth-child(3):hover {
  background-color: #4150f1;
}
.container {
  border-radius: 10px;
    width: 60%;
  margin: 50px auto;
  background: #171717;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
h1 {
  text-align: center;
}
.profile-info {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}
.profile-info div {
  width: 48%;
  margin-bottom: 20px;
}
.bio {
  margin-top: 20px;
}
.footer {
  margin: 200px auto auto auto;
  position: relative;
  width: 100%;
  background: #171717;
  min-height: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.social-icon,
.menu {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 10px 0;
  flex-wrap: wrap;
}

.social-icon__item,
.menu__item {
  list-style: none;
}

.social-icon__link {
  font-size: 2rem;
  color: #fff;
  margin: 0 10px;
  display: inline-block;
  transition: 0.5s;
}
.social-icon__link:hover {
  transform: translateY(-10px);
}

.menu__link {
  font-size: 1.2rem;
  color: #fff;
  margin: 0 10px;
  display: inline-block;
  transition: 0.5s;
  text-decoration: none;
  opacity: 0.75;
  font-weight: 300;
}

.menu__link:hover {
  opacity: 1;
}

        .footer p {
  color: #fff;
  margin: 15px 0 10px 0;
  font-size: 1rem;
  font-weight: 300;
}

.wave {
  position: absolute;
  top: -100px;
  left: 0;
  width: 100%;
  height: 100px;
  background: url("./assets/wave.png");
  background-size: 1000px 100px;
}

.wave#wave1 {
  z-index: 1000;
  opacity: 1;
  bottom: 0;
  animation: animateWaves 4s linear infinite;
}

.wave#wave2 {
  z-index: 999;
  opacity: 0.5;
  bottom: 10px;
  animation: animate 4s linear infinite !important;
}

.wave#wave3 {
  z-index: 1000;
  opacity: 0.2;
  bottom: 15px;
  animation: animateWaves 3s linear infinite;
}

.wave#wave4 {
  z-index: 999;
  opacity: 0.7;
  bottom: 20px;
  animation: animate 3s linear infinite;
}

@keyframes animateWaves {
  0% {
    background-position-x: 1000px;
  }
  100% {
    background-positon-x: 0px;
  }
}

@keyframes animate {
  0% {
    background-position-x: -1000px;
  }
  100% {
    background-positon-x: 0px;
  }
}
  </style>
</head>
<body>
<nav class="nav">
      <div class="nav-left">
        <a href="Accueil_Utilisateur.php" class="nav-brand">
          <img src="./assets/logo-1.png">
        </a>

        <ul class="nav-menu">
          <li>
              <a href="Accueil_Utilisateur.php" class="nav-link">
                Accueil
              </a>              
          </li>
        </ul>
        <ul>
          <li>
            <a href="#" class="nav-link">Rechercher</a>
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
            <a>
              <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                <path fill="none" d="M0 0h24v24H0z"></path>
                <path fill="#D1D5DB" d="M10 3h4a8 8 0 1 1 0 16v3.5c-5-2-12-5-12-11.5a8 8 0 0 1 8-8zm2 14h2a6 6 0 1 0 0-12h-4a6 6 0 0 0-6 6c0 3.61 2.462 5.966 8 8.48V17z"></path>
              </svg>
              <span>Messages</span>
            </a>
            <a href="#">
              <span>Paramètres</span>
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
    <div class="container">
        <h1>Profil de <?php echo htmlspecialchars($profile['Pseudo']); ?></h1>
        <div class="profile-photo">
          <img src="<?php echo htmlspecialchars($profile['Photo']); ?>" alt="Photo de profil">
        </div>
        <div class="profile-info">
            <div><strong>Sexe :</strong> <?php echo htmlspecialchars($profile['Sexe']); ?></div>
            <div><strong>Date de naissance :</strong> <?php echo htmlspecialchars($profile['Date de naissance']); ?></div>
            <div><strong>Profession :</strong> <?php echo htmlspecialchars($profile['Profession']); ?></div>
            <div><strong>Pays :</strong> <?php echo htmlspecialchars($profile['Pays']); ?></div>
            <div><strong>Code postal :</strong> <?php echo htmlspecialchars($profile['Code postal']); ?></div>
            <div><strong>Ville :</strong> <?php echo htmlspecialchars($profile['Ville']); ?></div>
            <div><strong>Statut marital :</strong> <?php echo htmlspecialchars($profile['Statut marital']); ?></div>
            <div><strong>Enfants :</strong> <?php echo htmlspecialchars($profile['Enfants']); ?></div>
            <div><strong>Taille (cm) :</strong> <?php echo htmlspecialchars($profile['Taille (cm)']); ?></div>
            <div><strong>Poids (kg) :</strong> <?php echo htmlspecialchars($profile['Poids (kg)']); ?></div>
            <div><strong>Adresse :</strong> <?php echo htmlspecialchars($profile['Adresse']); ?></div>
            <div><strong>Date d'inscription :</strong> <?php echo htmlspecialchars($profile['Date d\'inscription']); ?></div>
        </div>
        <div class="bio">
            <strong>Description :</strong>
            <p><?php echo htmlspecialchars($profile['Description']); ?></p>
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
        <li class="menu__item"><a class="menu__link" href="#">A propos</a></li>
      </ul>
      <p>&copy;2024 BricoMeet | Tous droits réservés</p>
    </footer>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
