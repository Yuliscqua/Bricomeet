<?php
session_start();

$chemin_fichier = __DIR__ . '/bdd_users.txt';
$utilisateurs = file($chemin_fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$pseudo = $_SESSION['pseudo'];
$deja_abonne = false;

foreach ($utilisateurs as $utilisateur) {
    $donnees = explode(',', $utilisateur);
    if ($donnees[0] === $pseudo && ($donnees[count($donnees) - 2] == "subscriber_free" || $donnees[count($donnees) - 2] == "subscriber_classic" || $donnees[count($donnees) - 2] == "subscriber_god")) {
        $deja_abonne = true;
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['abonnement'])) {
        $abonnement = $_POST['abonnement'];

        $nouveaux_utilisateurs = [];

        foreach ($utilisateurs as $utilisateur) {
            $donnees = explode(',', $utilisateur);
            if ($donnees[0] === $pseudo) {
                $donnees[count($donnees) - 2] = $abonnement;
            }
            $nouveaux_utilisateurs[] = implode(',', $donnees);
        }

        file_put_contents($chemin_fichier, implode(PHP_EOL, $nouveaux_utilisateurs) . PHP_EOL);

        header("Location: Accueil_Abonne.php");
        exit;
    } elseif (isset($_POST['annuler_abonnement'])) {
        $nouveaux_utilisateurs = [];

        foreach ($utilisateurs as $utilisateur) {
            $donnees = explode(',', $utilisateur);
            if ($donnees[0] === $pseudo) {
                $donnees[count($donnees) - 2] = 'user';
            }
            $nouveaux_utilisateurs[] = implode(',', $donnees);
        }

        file_put_contents($chemin_fichier, implode(PHP_EOL, $nouveaux_utilisateurs) . PHP_EOL);

        header("Location: Accueil_Utilisateur.php");
        exit;
    } else {
        echo "Aucun abonnement fourni.";
        exit;
    }
}
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de l'abonnement - BricoMeet</title>
    <style type="text/css">
    @import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap");
    @import "https://unpkg.com/open-props" layer(design.system);
    * {
        margin:0px;
        padding:0px;
        box-sizing:border-box;
    }
    body {
      background-color: #353535;
      font-family: "Montserrat", sans-serif;
    }
    .abonnements {
        transform: scale(1.5); 
        transform-origin: top;
        background-color: #353535; 
        height: 100%;
    }
    .abonnements .formules {
        width: 90%;
        max-width: 500px;
        text-align: center;
        margin:10px auto;
        padding:20px 20px;
        color: #fff;
        background-color: #171717;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
    }

    .abonnements .formules h2{
        font-size: 30px;
        margin-bottom: 10px;
    }

    .abonnements .formules p{
        font-size: 15px;
    }


    .abonnements .grille {
        display:flex;
        flex-wrap: wrap;
        justify-content: center;
        gap:20px 0px;
        padding : 20px;
    }

    .abonnements .grille .partie{
        width: 250px;
        border: 1px solid #eee;
        text-align: center;
        padding:20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 2px 10px 5px rgba(0,0,0,0.05);
    }

    .abonnements .grille .partie .titre{
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #555;
    }

    .abonnements .grille .partie .prix{
        margin-bottom: 20px;
    }

    .abonnements .grille .partie .prix b{
        margin-bottom: -5px;
        display: block;
        font-size:40px;
    }

    .abonnements .grille .partie .details > *{
        color: #555;
        padding:8px 0px;
        border-bottom: 1px solid rgba(0,0,0,0.2);
    }

    .abonnements .grille .partie .button button{
        width: 100px;
        margin: 25px 0px 0px;
        padding: 10px;
        background: linear-gradient(to bottom, #3a7bd5, #3a6073);
        color:#fff;
        border-radius: 5px;
        outline: none;
        border:none;
        font-weight: 600;
        cursor:pointer;
    }

    .abonnements .grille .partie.chevronne {
        transform: scale(1.1);
    }

    .details > * {
        color: #fff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.5);
    }

    .quit {
        color: #fff;
        position: fixed;
        bottom: -60px; 
        left: 50%;
        transform: translateX(-50%);
        background-color: #171717;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        z-index: 9999; 
        font-size: 14px; 
    }

    .quit1:visited {
        color: #fff;
    }

    .deja-abo {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #353535;
        text-align: center;
    }

    .deja-abo h1 {
        color: #fff;
        font-size: 50px;
        text-align: center;
    }

    .annuler-abonnement-form {
            margin-top: 20px;
        }
    .annuler-abonnement-form button {
            padding: 20px 40px;
            background: linear-gradient(to bottom, #3a7bd5, #3a6073);
            color: #fff;
            border-radius: 10px;
            outline: none;
            border: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 20px;
    }
    </style>
  </style>
  </head>
  <body>
    <?php if ($deja_abonne): ?>
      <a href="Accueil_Abonne.php" class="nav-brand">
      <img src="./assets/logo-1.png">
      </a>  
            <div class="deja-abo">
                <h1>Tu as déjà un abonnement !</h1><br>
                <form method="post" action="" class="annuler-abonnement-form">
                  <input type="hidden" name="annuler_abonnement" value="user"><br>
                  <button type="submit">Souhaites-tu annuler ton abonnement ? Clique ici !</button>
                </form>
            </div>
    <?php else: ?>
      <a href="Accueil_Utilisateur.php" class="nav-brand">
      <img src="./assets/logo-1.png">
    </a>  
    <div class="abonnements">
      <div class="formules">
        <h2>Les formules proposées</h2>
        <p>3 formules te sont proposées pour profiter au mieux de l'expérience BricoMeet !</p>
      </div>
      <div class="grille">
        <div class="partie apprenti">
          <div class="titre">Apprenti bricoleur</div>
          <div class="prix">
            <b>0€</b>
            <span>pendant 24h</span>
          </div>
          <div class="details">
            <div>Tu souhaites découvrir le site sans trop te projeter ? Cette formule est faite pour toi !</div>
          </div>
          <div class="button">
            <form method="post" action="">
              <input type="hidden" name="abonnement" value="subscriber_free">
              <button type="submit">Je souhaite devenir un apprenti</button>
            </form>
          </div>
        </div>
        
        <div class="partie chevronne">
          <div class="titre">Bricoleur chevronné</div>
          <div class="prix">
            <b>9.99€</b>
            <span>par mois</span>
          </div>
          <div class="details">
            <div>Abonnement mensuel classique pour les passionnés de bricolage</div>
          </div>
          <div class="button">
            <form method="post" action="">
              <input type="hidden" name="abonnement" value="subscriber_classic">
              <button type="submit">Je souhaite devenir un bricoleur chevronné</button>
            </form>
          </div>
        </div>

        <div class="partie dieu">
          <div class="titre">Dieu du bricolage</div>
          <div class="prix">
            <b>99.99€</b>
            <span>par an</span>
          </div>
          <div class="details">
            <div>Abonnement annuel déstinés aux dieux du bricolage</div>
          </div>
          <div class="button">
            <form method="post" action="">
              <input type="hidden" name="abonnement" value="subscriber_god">
              <button type="submit">Je souhaite devenir un dieu du bricolage</button>
            </form>
          </div>
        </div>
      </div>
      <div class=quit>
      <a class=quit1 href="Accueil_Utilisateur.php">Je ne suis pas intéréssé pour l'instant</a>
      </div>
    </div>  
    <?php endif; ?>

  </body>
</html>
