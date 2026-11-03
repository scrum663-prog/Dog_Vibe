<?php
declare(strict_types=1);

/** Conexión central de DogVive 1.1 para XAMPP / MariaDB. */
function dogvive_pdo(): PDO {
    $host = 'localhost';
    $db = 'dog_next'; // Nombre exacto que aparece en phpMyAdmin
    $user = 'root';
    $pass = ''; // En XAMPP suele estar vacío, salvo que hayas configurado una contraseña.

    return new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}
