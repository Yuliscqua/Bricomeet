<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="projet.css">
    <title>Bienvenue sur BricoMeet !</title>
</head>
<body>
    <a href="Accueil.html" class="nav-brand">
        <img src="./assets/logo-1.png">
    </a>  
    <div class="content">
        <form action="" method="post">
            <h1><b>Connecte-toi !</b></h1>
            <fieldset>
                <div class="input_fields3">
                    <label for="pseudo">Pseudo :</label>
                    <input type="text" id="pseudo" name="pseudo" maxlength="50" placeholder="Entre ton pseudo" required>
                </div>
                <div class="input_fields3">
                    <label for="mdp">Mot de passe :</label>
                    <input type="password" id="mdp" name="mdp" placeholder="Entre ton mot de passe" required>
                    <?php
                session_start();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $pseudo = $_POST['pseudo'];
                    $mdp = $_POST['mdp'];
                    $chemin_fichier = __DIR__ . '/bdd_users.txt';
                    $utilisateurs = file($chemin_fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $connexion_reussie = false;

                    foreach ($utilisateurs as $utilisateur) {
                        $donnees = explode(',', $utilisateur);
                        if ($donnees[0] === $pseudo && $donnees[2] === $mdp) {
                            $connexion_reussie = true;
                            $_SESSION['pseudo'] = $pseudo;
                            $_SESSION['role'] = $donnees[count($donnees) - 1]; // Dernière valeur du tableau
                            break;
                        }
                    }

                    if ($connexion_reussie) {
                        header("Location: Accueil_Abonne.php");
                        exit;
                    } else {
                        echo "<p style='color:red; text-align:center;'>Pseudo ou mot de passe incorrect</p>";
                    }
                }
                ?>
                </div>
                <div class="remembermdp">
                    <label>
                        <input type="checkbox"> Se souvenir de moi ?
                    </label>
                </div>
                <h3><input id="submit" type="submit" value="Se connecter"></h3>
            </fieldset>
        </form>
        <a href="Inscription.html">Pas encore de compte ? Inscris-toi ici</a>
    </div>
</body>
</html>
