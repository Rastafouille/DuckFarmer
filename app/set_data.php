<?php

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$telegramUser = isset($_POST['telegramUser']) ? $_POST['telegramUser'] : null;
$telegramFirst = isset($_POST['telegramFirst']) ? $_POST['telegramFirst'] : null;


$score = isset($_POST['score']) ? intval($_POST['score']) : null;
$speedBoost = isset($_POST['speedBoost']) ? $_POST['speedBoost'] : null;
$speedBoost24h = isset($_POST['speedBoost24h']) ? $_POST['speedBoost24h'] : null;
$fatBoost = isset($_POST['fatBoost']) ? $_POST['fatBoost'] : null;
$fatBoost24h = isset($_POST['fatBoost24h']) ? $_POST['fatBoost24h'] : null;
$tg = isset($_POST['tg']) ? intval($_POST['tg']) : null;
$twitter = isset($_POST['twitter']) ? intval($_POST['twitter']) : null;
$site = isset($_POST['site']) ? intval($_POST['site']) : null;
$parrainUsername = isset($_POST['parrain_username']) ? $_POST['parrain_username'] : null;
$inviteCount = isset($_POST['invite_count']) ? intval($_POST['invite_count']) : null;
$invite_count_n2 = isset($_POST['invite_count_n2']) ? intval($_POST['invite_count_n2']) : null;
$invite_count_n3 = isset($_POST['invite_count_n3']) ? intval($_POST['invite_count_n3']) : null;
$spare3 = isset($_POST['spare3']) ? intval($_POST['spare3']) : null;

if ($telegramID && $telegramID !== 'Guest') {
    $sql = "UPDATE app SET telegramUser= ?,telegramFirst=?, score = ?, speed_boost = ?, speed_boost_24h = ?, fat_boost = ?, fat_boost_24h = ?, telegram = ?, twitter = ?, site = ?, parrain_username = ?, invite_count = ?, invite_count_n2 = ?, invite_count_n3 = ?, spare3 = ? WHERE telegramID = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssissssiiisiiiis", $telegramUser,$telegramFirst,$score, $speedBoost, $speedBoost24h, $fatBoost, $fatBoost24h, $tg, $twitter, $site, $parrainUsername, $inviteCount, $invite_count_n2, $invite_count_n3, $spare3, $telegramID);
        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Data updated successfully.");
        } else {
            $response = array("status" => "error", "message" => "Error updating data: " . $stmt->error);
        }
        $stmt->close();
    } else {
        $response = array("status" => "error", "message" => "Failed to prepare the SQL statement: " . $conn->error);
    }
} else {
    $response = array("status" => "error", "message" => "Invalid user, score, or speed boost.");
}

$conn->close();

// Retourner la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>

