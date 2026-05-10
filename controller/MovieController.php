<?php
session_start();
require_once __DIR__ . '/../model/MovieModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$movieModel = new MovieModel();
$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$movie_id = intval($_POST['movie_id'] ?? $_GET['movie_id'] ?? 0);

switch ($action) {
    // --- FAVORITOS ---
    case 'add_favorito':
        $result = $movieModel->addFavorito($user_id, $movie_id);
        echo json_encode(['success' => $result]);
        break;

    case 'remove_favorito':
        $result = $movieModel->removeFavorito($user_id, $movie_id);
        echo json_encode(['success' => $result]);
        break;

    case 'get_favoritos':
        $ids = $movieModel->getFavoritos($user_id);
        echo json_encode(['movie_ids' => $ids]);
        break;

    case 'is_favorito':
        $result = $movieModel->isFavorito($user_id, $movie_id);
        echo json_encode(['is_favorito' => $result]);
        break;

    // --- PENDIENTES ---
    case 'add_pendiente':
        $result = $movieModel->addPendiente($user_id, $movie_id);
        echo json_encode(['success' => $result]);
        break;

    case 'remove_pendiente':
        $result = $movieModel->removePendiente($user_id, $movie_id);
        echo json_encode(['success' => $result]);
        break;

    case 'get_pendientes':
        $ids = $movieModel->getPendientes($user_id);
        echo json_encode(['movie_ids' => $ids]);
        break;

    // --- RESEÑAS ---
    case 'add_resena':
        $puntuacion = intval($_POST['puntuacion'] ?? 0);
        $comentario = trim($_POST['comentario'] ?? '');
        $result = $movieModel->addResena($user_id, $movie_id, $puntuacion, $comentario);
        echo json_encode(['success' => $result]);
        break;

    case 'get_resena':
        $resena = $movieModel->getResena($user_id, $movie_id);
        echo json_encode(['resena' => $resena]);
        break;

    case 'get_all_resenas':
    $resenas = $movieModel->getAllResenas($user_id);
    echo json_encode(['resenas' => $resenas]);
    break;
    
    case 'get_all_resenas':
    $resenas = $movieModel->getAllResenas($user_id);
    echo json_encode(['resenas' => $resenas]);
    break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
