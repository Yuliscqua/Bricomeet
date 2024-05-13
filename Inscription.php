<?php
function ajouterUtilisateur($donnees_utilisateur, $chemin_fichier) {
    $utilisateurs = file($chemin_fichier, FILE_IGNORE_NEW_LINES);
    foreach ($utilisateurs as $utilisateur) {
        $donnees = explode(',', $utilisateur);
        if ($donnees[0] === $donnees_utilisateur["pseudo"]) {
            echo "Ce pseudo est déjà utilisé. Veuillez en choisir un autre.";
            exit;
        }
    }
    echo "Ce pseudo est disponible.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donnees_utilisateur = [
        $pseudo = $_POST["pseudo"],
        $genre = $_POST["genre"],
        $mdp = $_POST["mdp"],
        $date_naissance = isset($_POST["date_naissance"]) ? $_POST["date_naissance"] : "",
        $profession = isset($_POST["profession"]) ? $_POST["profession"] : "",
        $pays = isset($_POST["pays"]) ? $_POST["pays"] : "",
        $departement = isset($_POST["departement"]) ? $_POST["departement"] : "",
        $ville = isset($_POST["ville"]) ? $_POST["ville"] : "",
        $situation = isset($_POST["situation"]) ? $_POST["situation"] : "",
        $nbenfants = isset($_POST["nbenfants"]) ? $_POST["nbenfants"] : "",
        $taille = isset($_POST["taille"]) ? $_POST["taille"] : "",
        $poids = isset($_POST["poids"]) ? $_POST["poids"] : "",
        $bio = isset($_POST["bio"]) ? $_POST["bio"] : "",
    ];
    $chemin_fichier = __DIR__ . '/bdd_users.txt';
    if (ajouterUtilisateur($donnees_utilisateur, $chemin_fichier)) {
            // Utilisateur ajouté avec succès
            // Rediriger ou afficher un message de confirmation
    } else {
            // Pseudo déjà utilisé, afficher un message d'erreur
    }
}
?>