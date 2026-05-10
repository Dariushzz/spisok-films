-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Temps de generació: 10-05-2026 a les 23:36:32
-- Versió del servidor: 10.4.32-MariaDB
-- Versió de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `spisok_films`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tmdb_movie_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `favoritos`
--

INSERT INTO `favoritos` (`id`, `user_id`, `tmdb_movie_id`, `created_at`) VALUES
(5, 1, 1217584, '2026-04-09 14:52:55'),
(8, 1, 83533, '2026-05-06 16:51:34'),
(9, 2, 83533, '2026-05-10 15:20:58'),
(11, 1, 393, '2026-05-10 18:15:14'),
(12, 1, 24, '2026-05-10 18:35:19'),
(13, 1, 936075, '2026-05-10 18:52:15'),
(14, 1, 19995, '2026-05-10 20:09:48'),
(15, 1, 111, '2026-05-10 21:09:20');

-- --------------------------------------------------------

--
-- Estructura de la taula `pendientes`
--

CREATE TABLE `pendientes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tmdb_movie_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de la taula `resenas`
--

CREATE TABLE `resenas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tmdb_movie_id` int(11) NOT NULL,
  `puntuacion` tinyint(4) DEFAULT NULL CHECK (`puntuacion` between 1 and 10),
  `comentario` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `resenas`
--

INSERT INTO `resenas` (`id`, `user_id`, `tmdb_movie_id`, `puntuacion`, `comentario`, `created_at`) VALUES
(1, 1, 1226863, 9, '', '2026-04-07 15:01:42'),
(2, 1, 1327819, 8, '', '2026-04-09 14:17:15'),
(3, 1, 393, 9, 'Mi favorita', '2026-05-10 18:15:03'),
(5, 1, 931285, 9, 'mejor que la 1', '2026-05-10 18:34:27'),
(6, 1, 24, 7, 'number 1', '2026-05-10 18:35:29'),
(8, 1, 936075, 5, 'hhhj', '2026-05-10 18:51:13'),
(10, 1, 19995, 8, '', '2026-05-10 20:09:53'),
(11, 1, 111, 8, '', '2026-05-10 21:09:22');

-- --------------------------------------------------------

--
-- Estructura de la taula `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bolcament de dades per a la taula `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `created_at`) VALUES
(1, 'Dariush', 'dariush34@hotmail.com', '$2y$10$7tH8O1KDb/.02LzDeqbu8u0XzsaT7nta2PDqh1Nx7HasTE4zXlTvm', '2026-04-07 14:59:16'),
(2, 'spisok', 'spisok@gmail.com', '$2y$10$yBuHVyxgGyRg1nTUi5VI0Ogpqub18srLkQvyttaTpM6uPj5BGOHG.', '2026-05-10 15:18:18');

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`tmdb_movie_id`);

--
-- Índexs per a la taula `pendientes`
--
ALTER TABLE `pendientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`tmdb_movie_id`);

--
-- Índexs per a la taula `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`tmdb_movie_id`);

--
-- Índexs per a la taula `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la taula `pendientes`
--
ALTER TABLE `pendientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la taula `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la taula `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restriccions per a les taules bolcades
--

--
-- Restriccions per a la taula `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `pendientes`
--
ALTER TABLE `pendientes`
  ADD CONSTRAINT `pendientes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restriccions per a la taula `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
