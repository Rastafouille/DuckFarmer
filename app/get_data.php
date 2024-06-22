<?php

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//REMPLACER LE NOM DE LA TABLE APP_TEST PAR APP pour passer en production


// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "u333198694_duckyquackk";
$password = "9!DuckQuackQuackDucky!1";
$dbname = "u333198694_bdd_app";



// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    error_log("Échec de la connexion: " . $conn->connect_error); // Log de l'erreur
    die("Échec de la connexion: " . $conn->connect_error);
}

// Récupérer les données POST
$telegramID = isset($_POST['telegramID']) ? $_POST['telegramID'] : null;
$telegramUser = isset($_POST['telegramUser']) ? $_POST['telegramUser'] : null;
$telegramFirst = isset($_POST['telegramFirst']) ? $_POST['telegramFirst'] : null;
$new_parrain_username = isset($_POST['parrain_username']) ? $_POST['parrain_username'] : null;

$response = array();

if ($telegramID) {
    // Préparer et exécuter la requête pour obtenir les données
    $sql = "SELECT score, speed_boost, speed_boost_24h, fat_boost, fat_boost_24h, telegram, twitter, site, parrain_username, invite_count, invite_count_n2, invite_count_n3, spare3 FROM app WHERE telegramID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Erreur de préparation de la requête: " . $conn->error); // Log de l'erreur
        die("Erreur de préparation de la requête: " . $conn->error);
    }
    $stmt->bind_param("s", $telegramID);
    $stmt->execute();
    $stmt->bind_result($score, $speed_boost, $speed_boost_24h, $fat_boost, $fat_boost_24h, $tg, $twitter, $site, $parrain_username, $invite_count, $invite_count_n2, $invite_count_n3, $spare3);
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        $response['status'] = 'success';
        $response['score'] = $score;
        $response['speed_boost'] = $speed_boost;
        $response['speed_boost_24h'] = $speed_boost_24h;
        $response['fat_boost'] = $fat_boost;
        $response['fat_boost_24h'] = $fat_boost_24h;
        $response['telegram'] = $tg;
        $response['twitter'] = $twitter;
        $response['site'] = $site;
        $response['parrain_username'] = $parrain_username;
        $response['invite_count'] = $invite_count;
        $response['invite_count_n2'] = $invite_count_n2;
        $response['invite_count_n3'] = $invite_count_n3;
        $response['spare3'] = $spare3;
        $stmt->close();

    } else {
                // rechercher si id pas enregistrer avec username...

        $sql = "SELECT score, speed_boost, speed_boost_24h, fat_boost, fat_boost_24h, telegram, twitter, site, parrain_username, invite_count, invite_count_n2, invite_count_n3, spare3 FROM app WHERE telegramID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Erreur de préparation de la requête: " . $conn->error); // Log de l'erreur
            die("Erreur de préparation de la requête: " . $conn->error);
        }
        $stmt->bind_param("s", $telegramUser);
        $stmt->execute();
        $stmt->bind_result($score, $speed_boost, $speed_boost_24h, $fat_boost, $fat_boost_24h, $tg, $twitter, $site, $parrain_username, $invite_count, $invite_count_n2, $invite_count_n3, $spare3);
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            $response['status'] = 'success';
            $response['score'] = $score;
            $response['speed_boost'] = $speed_boost;
            $response['speed_boost_24h'] = $speed_boost_24h;
            $response['fat_boost'] = $fat_boost;
            $response['fat_boost_24h'] = $fat_boost_24h;
            $response['telegram'] = $tg;
            $response['twitter'] = $twitter;
            $response['site'] = $site;
            $response['parrain_username'] = $parrain_username;
            $response['invite_count'] = $invite_count;
            $response['invite_count_n2'] = $invite_count_n2;
            $response['invite_count_n3'] = $invite_count_n3;
            $response['spare3'] = $spare3;
            $stmt->close();
            
             $sql = "UPDATE app SET telegramID = ? WHERE telegramID = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    error_log("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error); // Log de l'erreur
                    die("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error);
                }
                $stmt->bind_param("ss", $telegramID, $telegramUser);
                $stmt->execute();
                $stmt->close();
        

        } else {
        
        // Ajouter un nouvel utilisateur si le telegramID n'existe pas encore
        $stmt->close();
        $score = 0;
        $speed_boost = '2024-01-01T11:00:00.000Z';
        $speed_boost_24h = '2024-01-01T11:00:00.000Z';
        $fat_boost = '2024-01-01T11:00:00.000Z';
        $fat_boost_24h = '2024-01-01T11:00:00.000Z';
        $tg = 0;
        $twitter = 0;
        $site = 0;
        if ($new_parrain_username) {$parrain_username = $new_parrain_username;}
        else {$parrain_username = 0;}
        $invite_count = 0;
        $invite_count_n2 = 0;
        $invite_count_n3 = 0;
        $spare3 = 0;
        $sql = "INSERT INTO app (telegramID, telegramUser, telegramFirst, score, speed_boost, speed_boost_24h, fat_boost, fat_boost_24h, telegram, twitter, site, parrain_username, invite_count, invite_count_n2, invite_count_n3, spare3) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Erreur de préparation de la requête: " . $conn->error); // Log de l'erreur
            die("Erreur de préparation de la requête: " . $conn->error);
        }
        $stmt->bind_param("sssissssiiisiiii", $telegramID, $telegramUser, $telegramFirst, $score, $speed_boost, $speed_boost_24h, $fat_boost, $fat_boost_24h, $tg, $twitter, $site, $parrain_username, $invite_count, $invite_count_n2, $invite_count_n3, $spare3);

        if ($stmt->execute()) {
            $response['status'] = 'added';
            $response['score'] = $score;
            $response['speed_boost'] = $speed_boost;
            $response['speed_boost_24h'] = $speed_boost_24h;
            $response['fat_boost'] = $fat_boost;
            $response['fat_boost_24h'] = $fat_boost_24h;
            $response['telegram'] = $tg;
            $response['twitter'] = $twitter;
            $response['site'] = $site;
            $response['parrain_username'] = $parrain_username;
            $response['invite_count'] = $invite_count;
            $response['invite_count_n2'] = $invite_count_n2;
            $response['invite_count_n3'] = $invite_count_n3;
            $response['spare3'] = $spare3;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Erreur lors de l\'ajout de l\'utilisateur : ' . $stmt->error;
            error_log($response['message']); // Log de l'erreur
        }
        $stmt->close();

        // Vérifier si $new_parrain_username est non nul NIVEAU 1
        if ($new_parrain_username) {
            // Rechercher le parrain par son telegramID
            $sql = "SELECT score, invite_count, parrain_username FROM app WHERE telegramID = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Erreur de préparation de la requête (recherche parrain): " . $conn->error); // Log de l'erreur
                die("Erreur de préparation de la requête (recherche parrain): " . $conn->error);
            }
            $stmt->bind_param("s", $new_parrain_username);
            $stmt->execute();
            $stmt->bind_result($parrain_score, $parrain_invite_count, $parrain_username_n2);
            if ($stmt->fetch()) {
                $stmt->close();

                // Mettre à jour le score et le invite_count du parrain
                $parrain_score += 5000;
                $parrain_invite_count += 1;

                $sql = "UPDATE app SET score = ?, invite_count = ? WHERE telegramID = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    error_log("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error); // Log de l'erreur
                    die("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error);
                }
                $stmt->bind_param("iis", $parrain_score, $parrain_invite_count, $new_parrain_username);
                $stmt->execute();
                $stmt->close();
                
                
                        // Vérifier si $parrain_username_n2 est non nul NIVEAU 2
                        if ($parrain_username_n2) {
                            // Rechercher le parrain par son telegramID
                            $sql = "SELECT score, invite_count_n2, parrain_username FROM app WHERE telegramID = ?";
                            $stmt = $conn->prepare($sql);
                            if (!$stmt) {
                                error_log("Erreur de préparation de la requête (recherche parrain): " . $conn->error); // Log de l'erreur
                                die("Erreur de préparation de la requête (recherche parrain): " . $conn->error);
                            }
                            $stmt->bind_param("s", $parrain_username_n2);
                            $stmt->execute();
                            $stmt->bind_result($parrain_score_n2, $parrain_invite_count_n2, $parrain_username_n3);
                            if ($stmt->fetch()) {
                                $stmt->close();
                
                                // Mettre à jour le score et le invite_count du parrain
                                $parrain_score_n2 += 1000;
                                $parrain_invite_count_n2 += 1;
                
                                $sql = "UPDATE app SET score = ?, invite_count_n2 = ? WHERE telegramID = ?";
                                $stmt = $conn->prepare($sql);
                                if (!$stmt) {
                                    error_log("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error); // Log de l'erreur
                                    die("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error);
                                }
                                $stmt->bind_param("iis", $parrain_score_n2, $parrain_invite_count_n2, $parrain_username_n2);
                                $stmt->execute();
                                $stmt->close();
                                
                                            // Vérifier si $parrain_username_n3 est non nul NIVEAU 3
                                            if ($parrain_username_n3) {
                                                // Rechercher le parrain par son telegramID
                                                $sql = "SELECT score, invite_count_n3 FROM app WHERE telegramID = ?";
                                                $stmt = $conn->prepare($sql);
                                                if (!$stmt) {
                                                    error_log("Erreur de préparation de la requête (recherche parrain): " . $conn->error); // Log de l'erreur
                                                    die("Erreur de préparation de la requête (recherche parrain): " . $conn->error);
                                                }
                                                $stmt->bind_param("s", $parrain_username_n3);
                                                $stmt->execute();
                                                $stmt->bind_result($parrain_score_n3, $parrain_invite_count_n3);
                                                if ($stmt->fetch()) {
                                                    $stmt->close();
                                    
                                                    // Mettre à jour le score et le invite_count du parrain
                                                    $parrain_score_n3 += 100;
                                                    $parrain_invite_count_n3 += 1;
                                    
                                                    $sql = "UPDATE app SET score = ?, invite_count_n3 = ? WHERE telegramID = ?";
                                                    $stmt = $conn->prepare($sql);
                                                    if (!$stmt) {
                                                        error_log("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error); // Log de l'erreur
                                                        die("Erreur de préparation de la requête (mise à jour parrain): " . $conn->error);
                                                    }
                                                    $stmt->bind_param("iis", $parrain_score_n3, $parrain_invite_count_n3, $parrain_username_n3);
                                                    $stmt->execute();
                                                    $stmt->close();
                    
                                                } else {
                                                    $stmt->close();
                                                    error_log("Parrain niveau 3 non trouvé: " . $new_parrain_username); // Log de l'erreur
                                                }
                                            }
                                
                                
                            } else {
                                $stmt->close();
                                error_log("Parrain niveau 2 non trouvé: " . $new_parrain_username); // Log de l'erreur
                            }
                        }


            } else {
                $stmt->close();
                error_log("Parrain niveau 1 non trouvé: " . $new_parrain_username); // Log de l'erreur
            }
        }

    }
} } else {
    $response['status'] = 'error';
    $response['message'] = 'ID Telegram invalide.';
    error_log($response['message']); // Log de l'erreur
}

$conn->close();

// Retourner la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
