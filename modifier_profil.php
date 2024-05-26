<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération du pseudo de l'utilisateur à modifier
    $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : null;
    $field = isset($_POST['field']) ? $_POST['field'] : null;
    $value = isset($_POST['value']) ? $_POST['value'] : null;
    $filename = 'bdd_users.txt';
    $lines = file($filename, FILE_IGNORE_NEW_LINES);

    if ($pseudo !== null) {
        foreach ($lines as $key => $line) {
            $data = explode(",", $line);
            if ($data[0] === $pseudo) {
                // Mise à jour du champ spécifique
                if ($field !== null && $value !== null) {
                    $fields = [
                        'Pseudo', 'Sexe', 'ID', 'Date de naissance', 'Profession', 'Pays', 'departement', 'Ville', 
                        'Statut marital', 'Enfants', 'Taille (cm)', 'Poids (kg)', 'Description', 'Nom', 'Prénom', 
                        'Adresse', 'Date d\'inscription', 'Rôle', 'Photo'
                    ];

                    $index = array_search($field, $fields);
                    if ($index !== false) {
                        $data[$index] = $value;
                    }
                }

                // Gestion de la photo de profil
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
                    $photo_name = 'pdp_' . $pseudo . '.jpg';
                    $photo_destination = __DIR__ . '/photos_profil/' . $photo_name;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_destination)) {
                        $data[array_search('Photo', $fields)] = $photo_name;
                    } else {
                        echo "Erreur lors de l'enregistrement de la photo.";
                        exit;
                    }
                }

                // Mise à jour de la ligne dans le fichier
                $lines[$key] = implode(",", $data);
                break;
            }
        }

        // Écriture des modifications dans le fichier
        file_put_contents($filename, implode("\n", $lines));

        // Redirection vers le profil
        header('Location: Profil.php?pseudo=' . urlencode($pseudo));
        exit;
    } else {
        echo "Pseudo non spécifié.";
    }
}
?>
