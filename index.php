<?php
session_start();

$page = $_GET['page'] ?? 'home';
$logged = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spisok Films - Buscador de Películas</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body>

    <header class="header">
        <nav class="navbar">
        <a href="index.php" class="logo"><img src="view/img/logo-spisok.png" alt="Spisok Films" class="logo-img"></a>
                <?php if ($logged): ?>
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Nombre película..." autocomplete="off">
                        <button id="searchBtn">Buscar</button>
                    </div>
                    <div class="nav-links">
                        <a href="index.php?page=favoritos" class="btn-red">Mi Lista</a>
                        <a href="index.php?page=pendientes" class="btn-red">Pendientes por ver</a>
                        <a href="index.php?page=asistente" class="btn-red">Asistente IA</a>
                        <span class="user-name">Hola, <?= htmlspecialchars($_SESSION['user_nombre']) ?></span>
                        <a href="controller/AuthController.php?action=logout" class="btn-logout">Salir</a>
                    </div>
                <?php else: ?>
                <div class="nav-links">
                    <a href="index.php?page=login" class="btn-red">Iniciar sesión</a>
                    <a href="index.php?page=register" class="btn-red">Registrarse</a>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <main class="main-content">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <div id="searchResults" style="display:none;">
            <h2>Resultados de búsqueda</h2>
            <div id="resultsContainer" class="movie-grid"></div>
        </div>
        <?php 
        switch ($page) {
            case 'login':
                include 'view/login.php';
                break;
            case 'register':
                include 'view/register.php';
                break;
            case 'favoritos':
                if (!$logged) { header("Location: index.php?page=login"); exit; }
                include 'view/favoritos.php';
                break;
            case 'pendientes':
                if (!$logged) { header("Location: index.php?page=login"); exit; }
                include 'view/pendientes.php';
                break;
            case 'pelicula':
                if (!$logged) { header("Location: index.php?page=login"); exit; }
                include 'view/pelicula.php';
                break;

                case 'asistente':
            if (!$logged) { header("Location: index.php?page=login"); exit; }
            include 'view/asistente.php';
            break;
            default:
                if ($logged) {
                    include 'view/home.php';
                } else {
                    include 'view/login.php';
                }
                break;
        }
        ?>
    </main>

    <script>
        const TMDB_API_KEY = '2b80c07246746d51daf39c6ade0529e8'; 
        const TMDB_BASE = 'https://api.themoviedb.org/3';
        const TMDB_IMG = 'https://image.tmdb.org/t/p/w500';
    </script>
    <script src="view/js/app.js"></script>

</body>
</html>
