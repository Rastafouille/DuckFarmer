<?php


// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "u333198694_duckyquackk";
$password = "9!DuckQuackQuackDucky!1";
$dbname = "u333198694_bdd_app";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Récupérer les données POST
$telegramID = isset($_POST['telegramID']) ? $_POST['telegramID'] : null;

$response = array();

if ($telegramID) {
    // Préparer et exécuter la requête pour obtenir les données
    $sql = "SELECT score, speed_boost, fat_boost, telegram, twitter, site FROM app WHERE telegramID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $telegramID);
    $stmt->execute();
    $stmt->bind_result($score, $speed_boost, $fat_boost, $tg, $twitter, $site);
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        $response['status'] = 'success';
        $response['score'] = $score;
        $response['speed_boost'] = $speed_boost;
        $response['fat_boost'] = $fat_boost;
        $response['telegram'] = $tg;
        $response['twitter'] = $twitter;
        $response['site'] = $site;
    } else {
        // Ajouter un nouvel utilisateur si le telegramID n'existe pas encore
        $stmt->close();
        $score = 0;
        $speed_boost = 1;
        $fat_boost = 1;
        $tg = 0;
        $twitter = 0;
        $site = 0;
        $sql = "INSERT INTO app (telegramID, score, speed_boost, fat_boost, telegram, twitter, site) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiiiii", $telegramID, $score, $speed_boost, $fat_boost, $tg, $twitter, $site);
        
        if ($stmt->execute()) {
            $response['status'] = 'added';
            $response['score'] = $score;
            $response['speed_boost'] = $speed_boost;
            $response['fat_boost'] = $fat_boost;
            $response['telegram'] = $tg;
            $response['twitter'] = $twitter;
            $response['site'] = $site;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erreur lors de l\'ajout de l\'utilisateur : ' . $stmt->error;
        }
    }

    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'ID Telegram invalide.';
}

$conn->close();

// Retourner la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
