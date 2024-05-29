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


if ($telegramID && $telegramID != 'Guest' && $score !== null) {
    // Mettre à jour le score dans la base de données
    $sql = "UPDATE app SET score = ? WHERE telegramID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $score, $telegramID);

    if ($stmt->execute()) {
        $response = array("status" => "success", "message" => "Score updated successfully.");
    } else {
        $response = array("status" => "error", "message" => "Error updating score: " . $stmt->error);
    }

    $stmt->close();
} else {
    $response = array("status" => "error", "message" => "Invalid user or score.");
}

$conn->close();

// Retourner la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>