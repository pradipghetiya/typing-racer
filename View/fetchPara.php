<?php
$host = 'localhost';
$db = 'typeracer';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $race_number = $_POST['race_number'];

    $stmt = $pdo->prepare("select paragraph_id from races where race_number = ?");
    $stmt->execute([$race_number]);
    $para_id = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("select text from paragraphs where id = ?");
    $stmt->execute([$para_id["paragraph_id"]]);
    $para_text = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'message' => 'Race created successfully', 'paragraph' => $para_text["text"]]);
}
?>