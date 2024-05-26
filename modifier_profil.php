<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = isset($_GET['pseudo']) ? $_GET['pseudo'] : $_SESSION['pseudo'];
    $filename = 'bdd_users.txt';
    $lines = file($filename, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $key => $line) {
        $data = explode(",", $line);
        if ($data[0] === $pseudo) {
            // Pour chaque champ attendu, vérifiez s'il a été posté et traitez-le
            $fields = ['Pseudo', 'Sexe', 'ID', 'Date de naissance', 'Profession', 'Pays', 'Code postal', 'Ville', 'Statut marital', 'Enfants', 'Taille (cm)', 'Poids (kg)', 'Description', 'Nom', 'Prénom', 'Adresse', 'Date d\'inscription', 'Rôle', 'Photo'];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $index = array_search($field, $fields);
                    if ($index !== false) {
                        $data[$index] = $_POST[$field];
                    }
                }
            }

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
                $photo_name = 'pdp_' . $pseudo . '.jpg';
                $photo_destination = __DIR__ . '/photos_profil/' . $photo_name;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_destination)) {
                    $data[array_search('Photo', $fields)] = $photo_name;  // Mettre à jour le chemin de la photo dans les données
                } else {
                    echo "Erreur lors de l'enregistrement de la photo.";
                    exit;
                }
            }

            $lines[$key] = implode(",", $data);
            break;
        }
    }

    // Écrire le fichier
    file_put_contents($filename, implode("\n", $lines));

    // Redirection après la mise à jour
    header('Location: Profil.php');
    exit;
}
?>
