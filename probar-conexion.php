
<?php
$host = 'localhost';
$dbname = 'dog_next';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    echo "CONEXION CORRECTA: PHP puede acceder a dog_next.";

} catch (PDOException $e) {
    http_response_code(500);
    echo "ERROR REAL DE CONEXION: ";
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
