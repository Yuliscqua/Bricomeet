<?php
session_start();

function ajouterUtilisateur($donnees_utilisateur, $chemin_fichier) {
    $utilisateurs = file($chemin_fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($utilisateurs as $utilisateur) {
        $donnees = explode(',', $utilisateur);
        if ($donnees[0] === $donnees_utilisateur['pseudo']) {
            return false;
        }
    }
    $nouvel_utilisateur = implode(',', $donnees_utilisateur);
    file_put_contents($chemin_fichier, $nouvel_utilisateur . PHP_EOL, FILE_APPEND);
    return true; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['pseudo'] = $_POST['pseudo'];
    $champs_obligatoires = ['pseudo', 'genre', 'mdp'];
    $champs_facultatifs = ['date_naissance', 'profession', 'pays', 'departement', 'ville', 'situation', 'nbenfants', 'taille', 'poids', 'bio', 'nom', 'prénom', 'adresse'];

    foreach ($champs_obligatoires as $champ) {
        if (empty($_POST[$champ])) {
            echo "Le champ '$champ' est obligatoire.";
            exit;
        }
    }

    $donnees_utilisateur = [];
    foreach ($champs_obligatoires as $champ) {
        $donnees_utilisateur[$champ] = $_POST[$champ];
    }
    foreach ($champs_facultatifs as $champ) {
        $donnees_utilisateur[$champ] = isset($_POST[$champ]) && $_POST[$champ] !== '' ? $_POST[$champ] : '?';
    }
    $donnees_utilisateur['date_inscription'] = date('Y-m-d');
    $donnees_utilisateur['role'] = 'user';

    if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_name = 'pdp_' . $_POST['pseudo'] . '.jpg'; 
        $photo_destination = __DIR__ . '/photos_profil/' . $photo_name; 

        if (move_uploaded_file($photo_tmp_name, $photo_destination)) {
            $donnees_utilisateur['photo'] = $photo_name;
            $message = "Photo de profil enregistrée avec succès.";
        } else {
            $message = "Une erreur s'est produite lors de l'enregistrement de la photo de profil.";
        }
    } elseif ($_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
        $message = "Aucune photo de profil téléchargée.";
    } else {
        $message = "Une erreur s'est produite lors du téléchargement de la photo de profil.";
    }

    $chemin_fichier = __DIR__ . '/bdd_users.txt';

    if (ajouterUtilisateur($donnees_utilisateur, $chemin_fichier)) {
        header("Location: Choix_abo.php");
        exit;
    } else {
        $_SESSION['error'] = "Ce pseudo est déjà utilisé. Veuillez en choisir un autre.";
        header("Location: Inscription.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="projet.css">
        <title>Bienvenue sur BricoMeet !</title>
    </head>
    <body>
        <a href="Accueil.php" class="nav-brand">
            <img src="./assets/logo-1.png">
        </a>  
        <div class="content">
        <form enctype="multipart/form-data" method="post" action="">
                <h1><b>Rejoins la team BricoMeet et inscris toi !</b></h1>
                <fieldset>
                    <p>Les champs marqués d'un astérisque (*) sont obligatoires.</p>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <p style="color: red; font-weight: bold;"><?php echo $_SESSION['error']; ?></p>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>


                <legend><h3>1. Informations publiques (qui peuvent être vues par les autres utilisateurs) :</h3></legend>
                <div class="fields">
                <div class="input_fields2">
                <label for="pseudo">Pseudo* :</label>
                <input type="text" id="pseudo" name="pseudo" maxlength="15" placeholder="Entre ton pseudo" required>
                <div id="pseudo-feedback"></div>
                </div>
                <div class="input_fields2">
                    <p> Genre*  :
                    <input type="radio" name="genre" value="Homme" required/> Homme
                    <input type="radio" name="genre" value="Femme" /> Femme
                    <input type="radio" name="genre" value="Autre" /> Autre
                </p></div></div>
                <div class="fields">
                <div class="input_fields2">
                <label for="date_naissance">Date de Naissance :</label><br>
                <input type="date" id="date_naissance" name="date_naissance" ><br><br></div>
                <div class="input_fields2">
                <label for="profession">Situation professionnelle :</label><br>
                <input type="text" id="profession" name="profession" maxlength="100" placeholder="Entre ta situation professionnelle"><br><br></div></div>
                <div class="fields">
                <div class="input_fields3">
                <label for="pays">Pays :</label>
                <input type="text" id="pays" name="pays" maxlength="50" placeholder="Entre ton pays"></div>
                <div class="input_fields3">
                <label for="département">Département :</label>
                <input type="text" id="departement" name="departement" maxlength="100" placeholder="Entre ton département"></div>
                <div class="input_fields3">
                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" maxlength="100" placeholder="Entre ta ville"></div>
                </div><br>
                <div class="input_fields2">
                <p> Situation amoureuse  :<br>
                    <input type="radio" name="situation" value="Célibataire" /> Célibataire
                    <input type="radio" name="situation" value="En couple" /> En couple
                    <input type="radio" name="situation" value="Marié(e)" /> Marié(e)
                    <input type="radio" name="situation" value="Divorcé(e)" /> Divorcé(e)
                <br></p></div>
                <div class="fields">
                <div class="input_fields3">
                <label for="nbenfants">Nombre d'enfants :</label>
                <input placeholder="Entre ton nombre d'enfants" type="number" id="nbenfants" name="nbenfants" min="0" max="10" maxlength="2" step="1"/></div>
                <div class="input_fields3">
                <label for="taille">Taille (en cm):</label>
                <input placeholder="Entre ta taille" type="number" id="taille" name="taille" min="100" max="220" maxlength="3" step="1"/></div>
                <div class="input_fields3">
                <label for="poids">Poids (en kg):</label>
                <input placeholder="Entre ton poids" type="number" id="poids" name="poids" min="0" max="150" maxlength="3" step="1"/></div>
                </div><br>
                <div class="input_fields3">
                <label for="bio">Bio :</label><br>
                <textarea id="bio" name="bio" rows="10" cols="100" placeholder="Entre ta bio ! C'est ce qui te définit du point de vue des autres utilisateurs, alors ne néglige pas cette étape !"></textarea><br><br></div>
                <div class="input_fields3">
                <label for="photo">Photo de profil :</label><br>
                <input type="file" id="photo" name="photo" accept="image/png, image/jpeg"><br><br></div>
            </fieldset>
            <fieldset>
                <legend><h3>2. Informations privées (tu seras le seul à pouvoir les voir) :</h3></legend>
                <div class="fields">
                <div class="input_fields2">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" maxlength="20" placeholder="Entre ton vrai nom"></div>
                <div class="input_fields2">
                <label for="prénom">Prénom</label>
                <input type="text" id="prénom" name="prénom" maxlength="15" placeholder="Entre ton vrai prénom"></div></div>
                <div class="fields">
                <div class="input_fields2">
                <label for="adresse">Adresse complète</label>
                <input type="text" id="adresse" name="adresse" placeholder="Entre ton adresse"></div>
                <div class="input_fields2">
                <label for="mdp">Mot de passe*</label>
                <input type="password" id="mdp" name="mdp" placeholder="Entre ton mot de passe" required></div></div>
                <h3><input id="submit" type="submit" value="S'inscrire"></h3>
            </fieldset>
        </form>
        <a href="Accueil.php">Retourner sur le site en tant que visiteur</a>
    </div>
    </body>
</html>