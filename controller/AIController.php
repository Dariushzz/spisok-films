<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$config = json_decode(file_get_contents(__DIR__ . '/../config/appsettings.json'), true);
define('ANTHROPIC_API_KEY', $config['anthropic_api_key']);
define('ANTHROPIC_MODEL',   'claude-haiku-4-5');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'similares':
        handleSimilares();
        break;
    case 'mood':
        handleMood();
        break;
    default:
        echo json_encode(['error' => 'Accion no valida']);
}

function handleSimilares() {
    $titulo = trim($_POST['titulo'] ?? '');
    if (strlen($titulo) < 2) {
        echo json_encode(['reply' => 'Escribe el titulo de una pelicula.']);
        return;
    }
    $prompt = "Eres un experto en cine. El usuario quiere peliculas parecidas a: \"$titulo\"\n\n"
            . "Lista entre 6 y 8 peliculas similares. Para cada una indica solo:\n"
            . "- **Titulo (año)** - Una frase corta explicando por que es similar\n\n"
            . "Sin introducciones ni texto extra. Solo la lista. Responde en español.";
    echo json_encode(['reply' => callClaude($prompt)]);
}

function handleMood() {
    $mood = trim($_POST['mood'] ?? '');
    if (strlen($mood) < 3) {
        echo json_encode(['reply' => 'Describe mejor lo que te apetece ver.']);
        return;
    }
    $prompt = "Eres un experto en cine. El usuario quiere ver una pelicula esta noche y describe su mood: \"$mood\"\n\n"
            . "Recomienda entre 4 y 6 peliculas que encajen perfectamente. Para cada una indica solo:\n"
            . "- **Titulo (año)** - Una frase corta explicando por que encaja con lo que pide\n\n"
            . "Sin introducciones ni texto extra. Solo la lista. Responde en español.";
    echo json_encode(['reply' => callClaude($prompt)]);
}

function callClaude(string $prompt): string {
    $payload = json_encode([
        'model'      => ANTHROPIC_MODEL,
        'max_tokens' => 1024,
        'messages'   => [['role' => 'user', 'content' => $prompt]],
    ]);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: ' . ANTHROPIC_API_KEY,
            'anthropic-version: 2023-06-01',
        ],
    ]);

    $json = curl_exec($ch);
    $err  = curl_error($ch);
    curl_close($ch);

    if (!$json || $err) return 'Error de conexion: ' . $err;

    $data = json_decode($json, true);
    if (isset($data['content'][0]['text'])) return $data['content'][0]['text'];
    if (isset($data['error']['message']))   return 'Error IA: ' . $data['error']['message'];
    return 'Respuesta inesperada del asistente.';
}