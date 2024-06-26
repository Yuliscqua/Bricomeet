<?php
session_start();


if (!isset($_SESSION['pseudo'])) {
  echo "Vous devez être connecté pour voir cette page.";
  exit;
}


$pseudo = isset($_GET['pseudo']) ? $_GET['pseudo'] : $_SESSION['pseudo'];
$pseudo_connecte= $_SESSION['pseudo'];
$pp_pseudo_connecte='photos_profil/pdp_' . $pseudo_connecte . '.jpg';
$is_editable = false;
if ($pseudo_connecte === $pseudo) {
  $is_editable = true;
}

$chemin_fichier = __DIR__ . '/bdd_users.txt';
$utilisateurs = file($chemin_fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);



$filename = 'bdd_users.txt';
$file = fopen($filename, "r");
$profile = null;


$is_admin = false;


if ($file) {
    while (($line = fgets($file)) !== false) {
        $data = explode(",", trim($line));
        if ($data[0] === $pseudo) {
            $photo_path = __DIR__ . '/photos_profil/pdp_' . $data[0] . '.jpg';
            $photo_url = 'photos_profil/pdp_' . $data[0] . '.jpg';
            if (!file_exists($photo_path)) {
                $photo_url = 'photos_profil/default.jpg';
            }
            $profile = [
                'Pseudo' => $data[0],
                'Sexe' => $data[1],
                'ID' => $data[2],
                'Date de naissance' => $data[3],
                'Profession' => $data[4],
                'Pays' => $data[5],
                'departement' => $data[6],
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
        }
        if ($data[0] === $pseudo_connecte && $data[17] === 'admin') {
          $is_admin = true;
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

$fichier_blocage = 'blocages.txt'; 
$estBloque = false;
if (file_exists($fichier_blocage)) {
  $blocages = file($fichier_blocage, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($blocages as $blocage) {
      list($bloqueur, $bloque) = explode(':', $blocage);
      if ($bloqueur === $pseudo_connecte && $bloque === $pseudo) {
          $estBloque = true;
          break;
      }
  }
}


if (isset($_POST['bloquer']) && $is_editable==false)  {
  file_put_contents($fichier_blocage, "$pseudo_connecte:$pseudo\n", FILE_APPEND);
  header("Location: Accueil_Utilisateur.php");
  exit;
}

if (isset($_POST['debloquer'])) {
  $utilisateur_a_debloquer = $_POST['pseudo'];
  $utilisateur_connecte = $_SESSION['pseudo']; 
  $fichier_blocage = 'blocages.txt'; 

  $blocages = file($fichier_blocage, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $nouveaux_blocages = array();
  foreach ($blocages as $blocage) {
      list($bloqueur, $bloque) = explode(':', $blocage);
      if (!($bloqueur === $utilisateur_connecte && $bloque === $utilisateur_a_debloquer)) {
          $nouveaux_blocages[] = $blocage;
      }
  }
  file_put_contents($fichier_blocage, implode("\n", $nouveaux_blocages));

  header("Location: profil.php?pseudo=$utilisateur_a_debloquer");
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

.profile-photo {
  margin-bottom: 55px;
  width: 200px;
  height: 200px;
  border: solid 3px #272727;
  border-radius: 19px;
  padding: 1.5rem;
  position: relative;
}

.profile-photo > img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
  border-radius: 19px;
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
            <a href="Accueil_Utilisateur.php" class="nav-link">Accueil</a>                        
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
          <a class="nav-link btn-profile">
            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path fill="none" d="M0 0h24v24H0z"></path>
              <path fill="#F2F2F2" d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"></path>
            </svg>
            <span><?php echo htmlspecialchars($_SESSION['pseudo']); ?></span>
            <div class="profile-pic">
              <?php $photo='photos_profil/pdp_' . $_SESSION['pseudo'] . '.jpg';
              if (!file_exists($photo)) {
                $photo = 'photos_profil/default.jpg';
              }
              ?>
              <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile Pic">
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
            <a href="Accueil.php">
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
          <form method="POST" action="modifier_profil.php" enctype="multipart/form-data" style="margin-left: 200px;">
              <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
              <input type="file" name="photo" accept="image/png, image/jpeg">
              <button type="submit">Modifier la photo de profil</button>
          </form>
      </div>
      <?php

      ?>
      <div class="profile-info">
          <div><strong>Sexe :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Sexe">
                      <select name="value">
                          <option value="Homme" <?php if ($profile['Sexe'] === 'Homme') echo 'selected'; ?>>Homme</option>
                          <option value="Femme" <?php if ($profile['Sexe'] === 'Femme') echo 'selected'; ?>>Femme</option>
                          <option value="Autre" <?php if ($profile['Sexe'] === 'Autre') echo 'selected'; ?>>Autre</option>
                      </select>
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Sexe']); ?>
              <?php endif; ?>
          </div>            
          <div><strong>Date de naissance :</strong> 
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Date de naissance">
                      <input type="date" name="value" value="<?php echo htmlspecialchars($profile['Date de naissance']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Date de naissance']); ?>
              <?php endif; ?>
          </div>
          <div><strong>Profession :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Profession">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['Profession']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Profession']); ?>
              <?php endif; ?>
          </div>
          <div><strong>Pays :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Pays">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['Pays']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Pays']); ?>
              <?php endif; ?>
          </div>            
          <div><strong>Département :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="departement">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['departement']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['departement']); ?>
              <?php endif; ?>
          </div>
          <div><strong>Ville :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Ville">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['Ville']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Ville']); ?>
              <?php endif; ?>
          </div>
          <div><strong>Statut marital :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Statut marital">
                      <select name="value">
                          <option value="Célibataire" <?php if ($profile['Statut marital'] === 'Célibataire') echo 'selected'; ?>>Célibataire</option>
                          <option value="En couple" <?php if ($profile['Statut marital'] === 'En couple') echo 'selected'; ?>>En couple</option>
                          <option value="Marié(e)" <?php if ($profile['Statut marital'] === 'Marié(e)') echo 'selected'; ?>>Marié(e)</option>
                          <option value="Divorcé(e)" <?php if ($profile['Statut marital'] === 'Divorcé(e)') echo 'selected'; ?>>Divorcé(e)</option>
                      </select>
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Statut marital']); ?>
              <?php endif; ?>
          </div> 
          <div><strong>Enfants :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Enfants">
                      <input placeholder="<?php echo htmlspecialchars($profile['Enfants']); ?>" type="number" id="Enfants" name="value" min="0" max="10" maxlength="2" step="1"/>
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Enfants']); ?>
              <?php endif; ?>
          </div>
          <div><strong>Taille (cm) :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Taille (cm)">
                      <input placeholder="<?php echo htmlspecialchars($profile['Taille (cm)']); ?>" type="number" id="taille" name="value" min="100" max="220" maxlength="2" step="1"/>
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Taille (cm)']); ?>
              <?php endif; ?>
          </div>
          <div><strong>Poids (kg) :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Poids (kg)">
                      <input placeholder="<?php echo htmlspecialchars($profile['Poids (kg)']); ?>" type="number" id="poids" name="value" min="30" max="200" maxlength="2" step="1"/>
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo htmlspecialchars($profile['Poids (kg)']); ?>
              <?php endif; ?>
          </div>
          <div><strong>Nom : </strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Nom">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['Nom']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  Info privée
              <?php endif; ?>
          </div>
          <div><strong>Prénom : </strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Prénom">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['Prénom']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  Info privée
              <?php endif; ?>
          </div>
          <div><strong>Adresse : </strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Adresse">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['Adresse']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  Info privée
              <?php endif; ?>
          </div>
          <div><strong>Mot de passe : </strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="ID">
                      <input type="text" name="value" value="<?php echo htmlspecialchars($profile['ID']); ?>">
                      <button type="submit">Modifier</button>
                  </form>
                <?php else : ?>
                  Info privée
              <?php endif; ?>
          </div>
          <div><strong>Date d'inscription :</strong>
          <?php echo htmlspecialchars($profile['Date d\'inscription']); ?>
          </div>
          <div><strong>Description :</strong>
              <?php if ($is_admin || $is_editable) : ?>
                  <form method="POST" action="modifier_profil.php">
                      <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($profile['Pseudo']); ?>">
                      <input type="hidden" name="field" value="Description">
                      <textarea name="value" rows="5"><?php echo htmlspecialchars($profile['Description']); ?></textarea>
                      <button type="submit">Modifier</button>
                  </form>
              <?php else : ?>
                  <?php echo nl2br(htmlspecialchars($profile['Description'])); ?>
              <?php endif; ?>
          </div>
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
