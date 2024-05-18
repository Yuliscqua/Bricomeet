<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['abonnement'])) {
        $abonnement = $_POST['abonnement'];
        $pseudo = $_SESSION['pseudo'];

        $chemin_fichier = __DIR__ . '/bdd_users.txt';
        $utilisateurs = file($chemin_fichier, FILE_IGNORE_NEW_LINES);
        $nouveaux_utilisateurs = [];

        foreach ($utilisateurs as $utilisateur) {
            $donnees = explode(',', $utilisateur);
            if ($donnees[0] === $pseudo) {
                $donnees[count($donnees) - 1] = $abonnement;
            }
            $nouveaux_utilisateurs[] = implode(',', $donnees);
        }

        file_put_contents($chemin_fichier, implode(PHP_EOL, $nouveaux_utilisateurs) . PHP_EOL);

        header("Location: Accueil.html");
        exit;
    } else {
        echo "Méthode de requête non autorisée.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de l'abonnement - BricoMeet</title>
    <style type="text/css">
    * {
        margin:0px;
        padding:0px;
        box-sizing:border-box;
    }
    body {
        font-family:"Tw Cen MT",sans-serif;
    }
    .abonnements {
        transform: scale(1.5); 
        transform-origin: top;
        background-color: #eee; 
        height: 100%;
    }
    .abonnements .formules {
        width: 90%;
        max-width: 500px;
        text-align: center;
        margin:0 auto;
        padding:40px 20px
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
        position: fixed;
        bottom: -60px; 
        left: 50%;
        transform: translateX(-50%);
        background-color: #ffffff;
        padding: 10px 20px;
        border: 1px solid #cccccc; 
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        z-index: 9999; 
        font-size: 14px; 
    }

  </style>
  </head>
  <body>
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
            <form method="post" action=".">
              <input type="hidden" name="abonnement" value="subscriber_god">
              <button type="submit">Je souhaite devenir un dieu du bricolage</button>
            </form>
          </div>
        </div>
      </div>
      <div class=quit>
      <a href="Accueil.html">Je ne suis pas intéréssé pour l'instant</a>
      </div>
    </div>  
  </body>
</html>