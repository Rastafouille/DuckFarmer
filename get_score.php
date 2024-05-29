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

// Récupérer les données du formulaire
$telegramID = $_POST['telegramID'] ?? '';

// Préparer et exécuter la requête de recherche
$sql = "SELECT score FROM app WHERE telegramID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $telegramID);
$stmt->execute();
$stmt->bind_result($score);
$stmt->store_result();

// Créer une réponse JSON
$response = array();

if ($stmt->num_rows > 0) {
    // Si le telegramID existe, récupérer le score
    $stmt->fetch();
    $response['status'] = 'success';
    $response['score'] = $score;
} else {
    // Ajouter le telegramID avec un score de 0 s'il n'existe pas
    $stmt->close();
    
    $score = 0;
    $sql = "INSERT INTO app (telegramID, score) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $telegramID, $score);

    if ($stmt->execute()) {
        $response['status'] = 'added';
        $response['score'] = $score;
    } else {
        $response['status'] = 'error';
        $response['message'] = $conn->error;
    }
}

// Fermer la connexion
$stmt->close();
$conn->close();

// Envoyer la réponse JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
