<?php
session_start();

if (!isset($_SESSION['pseudo'])) {
    echo "Accès interdit.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];

    // Lire le fichier
    $filename = 'bdd_users.txt';
    $lines = file($filename, FILE_IGNORE_NEW_LINES);

    foreach ($lines as $key => $line) {
        $data = explode(",", $line);
        if ($data[0] === $pseudo) {
            // Supprimer la ligne correspondante
            unset($lines[$key]);

            break;
        }
    }
    $photo_to_delete = 'pdp_' . $pseudo;
    $photo_path = __DIR__ . '/photos_profil/' . $photo_to_delete . '.jpg';
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }

    // Écrire le fichier
    file_put_contents($filename, implode("\n", $lines));

    // Redirection après la suppression
    header('Location: Recherche.php');
    exit;
}
?>
