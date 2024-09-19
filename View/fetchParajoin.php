<?php
$host = 'localhost';
$db = 'typeracer';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $paraId = $_POST['paraIdpost'];

    $stmt = $pdo->prepare("select text from paragraphs where id  = ?");
    $stmt->execute([$paraId]);
    $paratext = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'message' => 'Race created successfully', 'paragraph' => $paratext["text"]]);
}
?>