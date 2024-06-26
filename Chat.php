<?php
session_start();
date_default_timezone_set('Europe/Paris');


$user = $_SESSION['pseudo'];
if($_SESSION['role'] == 'admin') {
    $is_admin= true;
}else{$is_admin= false;
}
$chatter = $_SESSION['pseudo2'];
$filename = 'messages.txt';
$signalementsFilename = 'signalements.txt';

function ajouterMessage($donnees_message, $chemin_fichier) {
    $nouvel_utilisateur = implode(',', $donnees_message);
    file_put_contents($chemin_fichier, $nouvel_utilisateur . PHP_EOL, FILE_APPEND);
    return true; 
}

function supprimerMessage($id, $chemin_fichier) {
    $messages = file($chemin_fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updatedMessages = array_filter($messages, function($line) use ($id) {
        $data = explode(',', trim($line));
        return $data[4] !== $id;
    });

    file_put_contents($chemin_fichier, implode(PHP_EOL, $updatedMessages) . PHP_EOL);
    return true;
}

function ajouterSignalement($donnees_signalement, $chemin_fichier) {
    $nouveau_signalement = implode(',', $donnees_signalement);
    file_put_contents($chemin_fichier, $nouveau_signalement . PHP_EOL, FILE_APPEND);
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['send']) && !empty($_POST['chat'])) {
        $messageId = uniqid();
        $donnees_message = [$user, $chatter, htmlspecialchars($_POST['chat']), date('Y-m-d H:i:s'), $messageId];
        ajouterMessage($donnees_message, $filename);
    } elseif (isset($_POST['delete']) && !empty($_POST['message_id'])) {
        $messageId = htmlspecialchars($_POST['message_id']);
        supprimerMessage($messageId, $filename);
    } elseif (isset($_POST['report']) && !empty($_POST['message_id']) && !empty($_POST['reason'])) {
        $signalementId = uniqid();
        $donnees_signalement = [$user, htmlspecialchars($_POST['message_id']), htmlspecialchars($_POST['reason']), date('Y-m-d H:i:s'), $signalementId];
        ajouterSignalement($donnees_signalement, $signalementsFilename);
    }
}

$messages = [];
if ($file = fopen($filename, "r")) {
    while (($line = fgets($file)) !== false) {
        $data = explode(",", trim($line));
        if (($data[0] === $user && $data[1] === $chatter) || ($data[0] === $chatter && $data[1] === $user)) {
          $data[3] = date('H:i', strtotime($data[3]));  
          $messages[] = $data;
        }
    }
    fclose($file);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BricoMeet</title>
    <link rel="stylesheet" href="Accueil.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-content h2{
          color : black;
        }
        .modal-content label{
          color : black;
        }

        #reason {
          width: 20%;
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
                <li><a href="Accueil_Utilisateur.php" class="nav-link">Accueil</a></li>
                <li><a href="Choice.php" class="nav-link">Messages</a></li>
                <li><a href="Recherche.php" class="nav-link">Rechercher</a></li>
            </ul>
        </div>
        <div class="nav-right">
            <div class="dropdown-container">
            <a href="#" class="nav-link btn-profile">
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
                    <a href="Choice.php">
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
    <h1 class="name_chatted"><a href="Profil.php?pseudo=<?php echo urlencode($chatter); ?>"><?php echo htmlspecialchars($chatter); ?></a></h1> <!-- Lien vers la page de profil -->
    <div class='framechat'>
        <?php foreach ($messages as $message): ?>
            <div class="<?php echo $message[0] === $user ? 'first' : 'second'; ?>">
                <?php echo htmlspecialchars($message[2]); ?><br>
                <?php echo htmlspecialchars($message[3]); ?>
                <?php if ($message[0] === $user || $is_admin): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="message_id" value="<?php echo htmlspecialchars($message[4]); ?>">
                        <input type="submit" name="delete" value="Supprimer le message">
                    </form>
                <?php else: ?>
                    <button onclick="openModal('<?php echo htmlspecialchars($message[4]); ?>')">Signaler</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if(isset($_SESSION['pseudo'])): ?>
    <div id='result'></div>
    <div class='chatbody'>
        <form method="post">
            <input type='text' name='chat' id='msgbox' placeholder="Tapez votre message..." required />
            <input type='submit' name='send' id='send' class='btn btn-send' value='Envoyer' />
        </form>
    </div>
    <?php endif; ?>

    <?php if($is_admin === false): ?>
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Signaler un message</h2>
            <form method="post">
                <input type="hidden" name="message_id" id="reportMessageId">
                <label for="reason">Raison du signalement:</label>
                <input type="text" name="reason" id="reason" placeholder="Entrez le motif du signalement" required rows="10">
                <input type="submit" name="report" value="Signaler">
            </form>
        </div>
    </div>
    <?php endif; ?>


    <script>
        function openModal(messageId) {
            document.getElementById('reportMessageId').value = messageId;
            document.getElementById('reportModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('reportModal').style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById('reportModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
