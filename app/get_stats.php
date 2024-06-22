<?php
header('Content-Type: application/json');

// Configuration de la base de données
$host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
$username = "u333198694_duckyquackk";
$password = "9!DuckQuackQuackDucky!1";
$dbname = "u333198694_bdd_app";

if (isset($_GET['telegramid'])) {
    $telegram_id = $_GET['telegramid'];

    // Connexion à la base de données
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour obtenir le nombre total de lignes dans la table 'app'
        $sql_count = "SELECT COUNT(*) FROM app";
        $stmt_count = $conn->query($sql_count);
        $total_count = $stmt_count->fetchColumn();

        // Requête pour obtenir les 3 premiers joueurs par score
        $sql_top_players = "SELECT TELEGRAMFirst, SCORE FROM app ORDER BY SCORE DESC LIMIT 3";
        $stmt_top_players = $conn->query($sql_top_players);
        $top_players = $stmt_top_players->fetchAll(PDO::FETCH_ASSOC);
        
          // Requête pour obtenir le score
        $sql_score = "SELECT SCORE FROM app WHERE TELEGRAMID = :telegram_id";
        $stmt_score = $conn->prepare($sql_score);  // Utilisation de prepare() au lieu de query()
        $stmt_score->bindParam(':telegram_id', $telegram_id, PDO::PARAM_STR);
        $stmt_score->execute();
        $score = $stmt_score->fetch(PDO::FETCH_ASSOC)['SCORE'];



        // Requête pour obtenir le classement de l'utilisateur par score
        $sql_rank = "
            SELECT rnk FROM (
                SELECT TELEGRAMID, SCORE, RANK() OVER (ORDER BY SCORE DESC) as rnk
                FROM app
            ) as ranked
            WHERE TELEGRAMID = :telegram_id";
        $stmt_rank = $conn->prepare($sql_rank);
        $stmt_rank->bindParam(':telegram_id', $telegram_id, PDO::PARAM_STR);
        $stmt_rank->execute();
        $user_rank = $stmt_rank->fetch(PDO::FETCH_ASSOC)['rnk'];

        // Formater les résultats
        $results = [
            'total_count' => $total_count,
            'top_players' => $top_players,
            'user_rank' => $user_rank,
            'score' => $score
        ];

        // Envoyer les résultats en format JSON
        echo json_encode(['data' => $results]);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    }

    // Fermer la connexion
    $conn = null;
} else {
    echo json_encode(['error' => 'Paramètre telegramid manquant']);
}
?>
