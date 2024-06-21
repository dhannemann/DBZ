<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('BASE', 'boletospagos');

$conn = new MySQLi(HOST, USER, PASS, BASE);

//Verifica se houve algum erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

?>
