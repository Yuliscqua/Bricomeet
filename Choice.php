<?php
session_start();

if (!isset($_SESSION['pseudo'])) {
    echo "Vous devez être connecté pour voir cette page.";
    exit;
}

$pseudo_connecte = $_SESSION['pseudo'];
$pp_pseudo_connecte = 'photos_profil/pdp_' . $pseudo_connecte . '.jpg';
$filename = 'bdd_users.txt';

$is_admin = false;
$profile = null;

$utilisateurs = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($utilisateurs as $utilisateur) {
    $data = explode(",", trim($utilisateur));
    if ($data[0] === $pseudo_connecte) {
        $is_admin = ($data[17] === 'admin');
        $profile = [
            'Pseudo' => $data[0],
            'Nom' => $data[13],
            'Prénom' => $data[14],
        ];
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['pseudo'];
    $user_found = false;
    
    foreach ($utilisateurs as $utilisateur) {
        $donnees = explode(',', $utilisateur);
        if ($donnees[0] === $pseudo && $pseudo != $pseudo_connecte) {
            $user_found = true;
            $_SESSION['pseudo2'] = $donnees[0];
            break;
        }
    }
    
    if ($user_found) {
        header("Location: Chat.php");
        exit;
    } else {
      $error_message = "Cet utilisateur n'existe pas ! Ou alors c'est vous-même !";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messagerie de <?php echo htmlspecialchars($profile['Prénom'] . ' ' . $profile['Nom']); ?></title>
  <link rel="stylesheet" type="text/css" href="Accueil.css">
  <style>
        body, html {
          height: 100%;
          margin: 0;
        }
        
        .content {
          position: fixed;
          bottom: 50%;
          width: 70%;
          display: flex;
          justify-content: center;
          align-items: center;
        }

        .input_fields4 {
          display: flex;
          justify-content: center;
          align-items: center;
          margin: 50px 100px 50px 100px;
        }

        .search {
          display: flex;
          width: 350%;
          max-width: 500px;
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

        fieldset {
          width: 250%;
          background: #171717;
          border-radius: 10px;
        }

        .error-message {
            color: red;
            text-align: left;
            margin-top: 10px;
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
            <div class="dropdown-container">
              <a href="Accueil_Utilisateur.php" class="nav-link">Accueil</a>
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
          <a class="nav-link btn-profile">
            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path fill="none" d="M0 0h24v24H0z"></path>
              <path fill="#F2F2F2" d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"></path>
            </svg>
            <span href="Inscription.php"><?php echo htmlspecialchars($profile['Pseudo']); ?></span>
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
            <a href="#">
              <span>Paramètres</span>
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
    </nav></br>
    <div class="content">
      <form action="" method="post">
      <fieldset>
        <div class="input_fields4">
          <input class="search" type="text" id="pseudo" name="pseudo" maxlength="15" placeholder="Entre le pseudo de la personne à qui tu veux envoyer un message !" required>
          <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
          <?php endif; ?>
        </div>
      </fieldset>
      </form>
    </div>
