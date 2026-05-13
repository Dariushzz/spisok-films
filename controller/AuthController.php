<?php
session_start();
require_once __DIR__ . '/../model/UserModel.php';

$userModel = new UserModel();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'register') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nombre) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: /spisok_films/index.php?page=register");
        exit;
    }

    if ($userModel->register($nombre, $email, $password)) {
        $_SESSION['success'] = "Registro exitoso. Inicia sesión.";
        header("Location: /spisok_films/index.php?page=login");
    } else {
        $_SESSION['error'] = "El email ya está registrado.";
        header("Location: /spisok_films/index.php?page=register");
    }
    exit;
}

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = $userModel->login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        header("Location: /spisok_films/index.php");
    } else {
        $_SESSION['error'] = "Email o contraseña incorrectos.";
        header("Location: /spisok_films/index.php?page=login");
    }
    exit;
}

if ($action === 'logout') {
    session_destroy();
    header("Location: /spisok_films/index.php");
    exit;
}