<<<<<<< HEAD
<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'igreja';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
=======
<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'igreja';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
>>>>>>> 20e907d4b7032e3caa0f843b979173ed8b3768d1
?>