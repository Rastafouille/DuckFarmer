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
$score = isset($_POST['score']) ? intval($_POST['score']) : null;
$speedBoost = isset($_POST['speedBoost']) ? intval($_POST['speedBoost']) : null;
$fatBoost = isset($_POST['fatBoost']) ? intval($_POST['fatBoost']) : null;
$tg = isset($_POST['tg']) ? intval($_POST['tg']) : null;
$twitter = isset($_POST['twitter']) ? intval($_POST['twitter']) : null;
$site = isset($_POST['site']) ? intval($_POST['site']) : null;

 
if ($telegramID && $telegramID != 'Guest'){ 
    //&& $score !== null && $speedBoost !== null && $fatBoost !== null && $tg !== null && $twitter !== null && $site !== null) {
    // Mettre à jour le score et speed_boost dans la base de données
    $sql = "UPDATE app SET score = ?, speed_boost = ?, fat_boost = ?, telegram = ?, twitter = ?, site = ? WHERE telegramID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiis", $score, $speedBoost, $fatBoost, $tg, $twitter, $site, $telegramID);

    if ($stmt->execute()) {
        $response = array("status" => "success", "message" => "data updated successfully.");
    } else {
        $response = array("status" => "error", "message" => "Error updating data: " . $stmt->error);
    }

    $stmt->close();
} else {
    $response = array("status" => "error", "message" => "Invalid user, score, or speed boost.");
}

$conn->close();

// Retourner la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);


?>