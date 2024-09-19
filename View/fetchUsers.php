<?php
$host = 'localhost';
$db = 'typeracer';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $num = $_POST['numpost'];
    //$raceId = $_POST['paraIdpost'];

    $stmt = $pdo->prepare("select id from races where race_number  = ?");
    $stmt->execute([$num]);
    $racenum = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("select username from users where race_id  = ?");
    $stmt->execute([$racenum["id"]]);
    $connectedusers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Race created successfully', 'users' => $connectedusers]);
}
?>