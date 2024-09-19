<?php
$host = 'localhost';
$db = 'typeracer';
$user = 'root';
$pass = '';

function getRandomParagraphId($conn) {
    // SQL query to select a random paragraph id
    $query = "SELECT id FROM paragraphs ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    // Fetch the random id
    $paragraph = $stmt->fetch(PDO::FETCH_ASSOC);
    return $paragraph['id'];
}

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raceNumber = rand(100000, 999999); // Generate a random 6-digit number

    $para_id = getRandomParagraphId($pdo);

    $stmt = $pdo->prepare("INSERT INTO races (race_number,paragraph_id) VALUES (?,?)");
    $stmt->execute([$raceNumber,$para_id]);

    $raceId = $pdo->lastInsertId();
    
    echo json_encode(['status' => 'success', 'message' => 'Race created successfully', 'race_number' => $raceNumber]);
}
?>