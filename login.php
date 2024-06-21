<?php
session_start();

if(empty($_POST) || empty($_POST["usuario"]) || empty($_POST["senha"])) {
    print "<script>location.href='index.php';</script>";
    exit();
}

include("config.php");

$usuario = $conn->real_escape_string($_POST["usuario"]);
$senha = md5($_POST["senha"]);

$sql = "SELECT * FROM usuarios WHERE usuario = '{$usuario}' AND senha = '{$senha}'";

$res = $conn->query($sql) or die($conn->error);
$row = $res->fetch_object();
$qtd = $res->num_rows;

if($qtd > 0) {
    $_SESSION["usuario"] = $usuario;
    $_SESSION["nome"] = $row->nome;
    $_SESSION["tipo"] = $row->tipo;
    print "<script>location.href='dashboard.php';</script>";
} else {
    unset($usuario);
    print "<script>alert('Usuário e/ou Senha incorretos!');</script>";
    print "<script>location.href='index.php';</script>";
}
?>