<?php
session_start();

function age_from_date_of_birth($dob) {
    if (empty($dob) || $dob === "?") {
      return 0;
    }
    $birthDate = new DateTime($dob);
    $today = new DateTime('today');
    $age = $birthDate->diff($today)->y;
    return $age;
}

// Initialisation des variables
$pseudo = $description = $situation = $interets = '';
$age_min = 0;
$age_max = 120;
$photo = false;
$resultats = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $age_min = isset($_POST['age_min']) ? (int)$_POST['age_min'] : 0;
    $age_max = isset($_POST['age_max']) ? (int)$_POST['age_max'] : 120;
    $situation = isset($_POST['situation']) ? trim($_POST['situation']) : '';
    $interets = isset($_POST['interets']) ? trim($_POST['interets']) : '';
    $photo = isset($_POST['photo']);

    // Lire le fichier texte
    $filename = 'bdd_users.txt';
    $file = fopen($filename, "r");
    $profiles = [];

    if ($file) {
        while (($line = fgets($file)) !== false) {
            $data = explode(",", trim($line));
            $photo_path = __DIR__ . '/photos_profil/pdp_' . $data[0] . '.jpg';
            $photo_url = 'photos_profil/pdp_' . $data[0] . '.jpg';
            if (!file_exists($photo_path)) {
                $photo_url = 'photos_profil/default.jpg';
            }
            $profiles[] = [
                'Pseudo' => $data[0],
                'Sexe' => $data[1],
                'Mot de passe' => $data[2],
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
                'Photo' => $photo_url
            ];

            if (count($data) > 1 && $data[0] === $_SESSION['pseudo']) {
              // La dernière donnée de la ligne est le rôle
              $_SESSION['role'] = $data[17];   
          }
        }
        fclose($file);
    } else {
        echo "Erreur lors de l'ouverture du fichier.";
        exit;
    }

    // Filtrer les profils
    foreach ($profiles as $profile) {
        $age = age_from_date_of_birth($profile['Date de naissance']);
        if ($pseudo && stripos($profile['Pseudo'], $pseudo) === false) continue;
        if ($description && stripos($profile['Description'], $description) === false) continue;
        if ($age < $age_min || $age > $age_max) continue;
        if ($situation && $profile['Statut marital'] !== $situation) continue;
        if ($photo && $profile['Photo'] === 'photos_profil/default.jpg') continue;
        $resultats[] = $profile;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de profils</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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

.profile-pic {
  width: 2.25rem;
  margin-left: 1rem;
  aspect-ratio: 1;
}
.profile-pic > img {
  width: 100%;
  height: 100%;
  display: block;
  border-radius: 0.375rem;
  object-fit: cover;
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

.titre{
  padding: 30px;
  padding-bottom: 5px;
  text-align: center;
}

.envoyer {
  margin: 13px 0px 13px 200px;
  position: relative;
  background-color: #202020;
  border-radius: 5px;
  box-shadow: #252525 0px 4px 0px 0px;
  padding: 15px;
  background-repeat: no-repeat;
  cursor: pointer;
  box-sizing: border-box;
  width: 145px;
  height: 49px;
  color: #fff;
  border: none;
  font-size: 18px;
  transition: all 0.3s ease-in-out;
  z-index: 1;
  overflow: hidden;
}

.envoyer::before {
  content: "";
  background-color: #353535;
  width: 0;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
  transition: width 700ms ease-in-out;
  display: inline-block;
}

.envoyer:hover::before {
  width: 100%;
}

.rechercher {
  display: flex;
  justify-content: center;
  align-items: center;
  background: #171717;
  margin: 50px 100px 20px 100px;
  border-radius: 10px;
}

.search {
  display: flex;
  width: 290%;
  max-width: 370px;
  height: 45px;
  padding: 12px;
  border-radius: 12px;
  border: 1.5px solid lightgrey;
  outline: none;
  transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
  box-shadow: 0px 0px 20px -18px;
}

.search:hover {
  border: 2px solid lightgrey;
  box-shadow: 0px 0px 20px -17px;
}

.search:active {
  transform: scale(0.95);
}

.search:focus {
  border: 2px solid grey;
}

.filtres {
  display: flex;
  justify-content: center;
  align-items: center;
}

.element {
  padding: 0px 10px 0px 10px;
}

.result {
  align-items: center;
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* 4 colonnes de largeur égale */
  gap: 20px; /* Espace entre les éléments, ajustez selon vos besoins */
  padding: 20px 0px 100px 80px;
}

.profile {
  margin-bottom: 55px;
  width: 350px;
  height: 700px;
  border: solid 3px #272727;
  border-radius: 19px;
  padding: 1.5rem;
  background: white;
  position: relative;
  display: flex;
  align-items: flex-end;
  transition: 0.4s ease-out;
  
}

.profile:hover {
transform: translateY(20px);
}

.profile:before {
content: "";
position: absolute;
top: 0;
left: 0;
display: block;
width: 100%;
height: 100%;
border-radius: 15px;
background: rgba(0, 0, 0, 0.6);
z-index: 2;
transition: 0.5s;
opacity: 0;
}

.profile:hover:before {
opacity: 1;
}

.profile img {
width: 100%;
height: 100%;
object-fit: cover;
position: absolute;
top: 0;
left: 0;
border-radius: 15px;
}

.profile .info {
position: relative;
z-index: 3;
color: white;
opacity: 0;
transform: translateY(30px);
transition: 0.5s;
}

.profile:hover .info {
opacity: 1;
transform: translateY(0px);
}

.profile .info h1 {
margin: 0px;
}

.profile .info p {
letter-spacing: 1px;
font-size: 15px;
margin-top: 8px;
}

.footer {
  position: fixed;
  bottom: 0;
  width: 100%;
  background: #171717;
  min-height: 100px;
  padding: 20px 50px;
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
          <?php if ($_SESSION['role'] == 'user'): ?>
            <li>
            <a href="Choix_abo.php" class="nav-link">Abonnements</a>
            </li>
          <?php else: ?>
            <li>
              <a href="Choice.php" class="nav-link">Messages</a>
            </li>
          <?php endif; ?>
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
            <span href="Inscription.php"><?php echo htmlspecialchars($_SESSION['pseudo']); ?></span>
            <div class="profile-pic">
              <img src="<?php echo 'photos_profil/pdp_' . $_SESSION['pseudo'] . '.jpg'; ?>" alt="Profile Pic">
            </div>
          </a>

          <div class="dropdown-menu profile-dropdown">
            <a>
              <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                <path fill="none" d="M0 0h24v24H0z"></path>
                <path fill="#D1D5DB" d="M10 3h4a8 8 0 1 1 0 16v3.5c-5-2-12-5-12-11.5a8 8 0 0 1 8-8zm2 14h2a6 6 0 1 0 0-12h-4a6 6 0 0 0-6 6c0 3.61 2.462 5.966 8 8.48V17z"></path>
              </svg>
              <span>Messages</span>
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
    <div class="container">
        <form class="formulaire" action="" method="post">
            <div class="rechercher">
              <div>
                <input class="search" type="text" id="pseudo" name="pseudo" maxlength="15" placeholder="Entre un pseudo" value="<?php echo htmlspecialchars($pseudo); ?>">
              </div>
              <div class="envoyer">
                <button type="submit">Rechercher</button>
              </div>
            </div>
            <div class="filtres">
              <div class="element">
                <label for="description">Mots clés :</label>
                <input type="text" id="description" name="description" maxlength="255" placeholder="Entre des mots clés" value="<?php echo htmlspecialchars($description); ?>">
              </div>
              <div class="element">
                <label for="age_min">Âge minimum :</label>
                <input type="number" id="age_min" name="age_min" min="0" max="120" value="<?php echo htmlspecialchars($age_min); ?>">
              </div>
              <div class="element">
                <label for="age_max">Âge maximum :</label>
                <input type="number" id="age_max" name="age_max" min="0" max="120" value="<?php echo htmlspecialchars($age_max); ?>">
              </div>
              <div class="element">
                <label for="situation">Situation amoureuse :</label>
                <select id="situation" name="situation">
                    <option value="">--Choisir une option--</option>
                    <option value="Célibataire" <?php if ($situation == 'Célibataire') echo 'selected'; ?>>Célibataire</option>
                    <option value="En couple" <?php if ($situation == 'En couple') echo 'selected'; ?>>En couple</option>
                    <option value="Marié(e)" <?php if ($situation == 'Marié(e)') echo 'selected'; ?>>Marié(e)</option>
                    <option value="Divorcé(e)" <?php if ($situation == 'Divorcé(e)') echo 'selected'; ?>>Divorcé(e)</option>
                </select>
              </div>
              <div class="element">
                <label>
                    <input type="checkbox" name="photo" <?php if ($photo) echo 'checked'; ?>> Avec photo de profil
                </label>
              </div>
            </div>  
        </form>  
    </div>
    <h2 class="titre"><a id="resultat" href="#resultat">Résultats</a></h2>
    <div class="result">
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php if (empty($resultats)): ?>
                <p>Aucun profil ne correspond à vos critères de recherche.</p>
            <?php else: ?>
                <?php foreach ($resultats as $profile): ?>                  
                    <div class="profile">
                        <img src="<?php echo htmlspecialchars($profile['Photo']); ?>" alt="Photo de profil">
                        <div class="info">
                            <h1><a href="Profil.php?pseudo=<?php echo urlencode($profile['Pseudo']); ?>"><?php echo htmlspecialchars($profile['Pseudo']); ?></a></h1>
                            <p><strong>- Sexe :</strong> <?php echo htmlspecialchars($profile['Sexe']); ?></p>
                            <p><strong>- Âge :</strong> <?php echo ($profile['Date de naissance'] === "?" ? "Âge non défini" : htmlspecialchars(age_from_date_of_birth($profile['Date de naissance']))); ?></p>
                            <p><strong>- Profession :</strong> <?php echo htmlspecialchars($profile['Profession']); ?></p>
                            <p><strong>- Pays :</strong> <?php echo htmlspecialchars($profile['Pays']); ?></p>
                            <p><strong>- Ville :</strong> <?php echo htmlspecialchars($profile['Ville']); ?></p>
                            <p><strong>- Statut marital :</strong> <?php echo htmlspecialchars($profile['Statut marital']); ?></p>
                            <p><strong>- Description :</strong> <?php echo htmlspecialchars($profile['Description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
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
        <li class="menu__item"><a class="menu__link" href="Accueil_Utilisateur.php">Accueil</a></li>
        <li class="menu__item"><a class="menu__link" href="Accueil_Utilisateur.php#presentation">A propos</a></li>
      </ul>
      <p>&copy;2024 BricoMeet | Tous droits réservés</p>
    </footer>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
