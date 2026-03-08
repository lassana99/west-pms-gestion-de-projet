<?php
$apiKey = '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';
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$prompt = $input['prompt'] ?? '';

if (empty($prompt)) {
    echo json_encode(['reply' => 'Veuillez entrer une question.']);
    exit;
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);

$data = [
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'system', 'content' => 'Tu es un assistant utile.'],
        ['role' => 'user', 'content' => $prompt]
    ],
    'temperature' => 0.7,
    'max_tokens' => 150
];

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// En cas d'erreur cURL
if ($error) {
    echo json_encode(['reply' => "Erreur de connexion: $error"]);
    exit;
}

// Vérification du code HTTP
if ($httpcode !== 200) {
    echo json_encode(['reply' => "Erreur API OpenAI (HTTP $httpcode): " . $response]);
    exit;
}

$responseData = json_decode($response, true);

if (isset($responseData['choices'][0]['message']['content'])) {
    $reply = $responseData['choices'][0]['message']['content'];
    echo json_encode(['reply' => $reply]);
} else {
    echo json_encode(['reply' => "Erreur de réponse inattendue: " . $response]);
}
