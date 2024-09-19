<?php
$host = 'localhost';
$db = 'typeracer';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $raceNumber = $_POST['race_number'];

    // Check if the race exists
    $stmt = $pdo->prepare("SELECT id, user_limit FROM races WHERE race_number = ?");
    $stmt->execute([$raceNumber]);
    $race = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$race) {
        echo json_encode(['status' => 'error', 'message' => 'Race not found']);
        exit;
    }

    

    $raceId = $race['id'];
    $userLimit = $race['user_limit'];

    // Check if the race has reached the user limit
    $stmt = $pdo->prepare("SELECT COUNT(*) as user_count FROM users WHERE race_id = ?");
    $stmt->execute([$raceId]);
    $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['user_count'];

    if ($userCount >= $userLimit) {
        echo json_encode(['status' => 'error', 'message' => 'Race is full']);
        exit;
    }

    // Check if the user is already in the race
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND race_id = ?");
    $stmt->execute([$username, $raceId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'User already in this race']);
        exit;
    }

    // Add user to the race
    $stmt = $pdo->prepare("select paragraph_id from races where race_number = ?");
    $stmt->execute([$raceNumber]);
    $para_id = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("INSERT INTO users (username, race_id,paragraph_id) VALUES (?, ?,?)");
    $stmt->execute([$username, $raceId,$para_id["paragraph_id"]]);
    

    echo json_encode(['status' => 'success', 'message' => 'Joined race successfully','paraId' => $para_id["paragraph_id"]]);
}
?>
