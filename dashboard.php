<?php
include 'header.php';
require 'config.php';
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletos Pagos</title>
    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap copy.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            text-align: center;
        }
        .card {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            margin-bottom: 20px;
        }
        /* Estilo para o botão Ver Dados Salvos */
        .btn-ver-dados {
            background-color: #28a745; /* Verde */
            color: white;
        }
        .btn-ver-dados:hover {
            background-color: #218838; /* Tom mais escuro de verde ao passar o mouse */
        }
        /* Estilo para o botão Importar Dados */
        .btn-importar {
            background-color: #dc3545; /* Vermelho */
            color: white;
        }
        .btn-importar:hover {
            background-color: #c82333; /* Tom mais escuro de vermelho ao passar o mouse */
        }
    </style>
</head>
<body>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>

    <div class="container">
        <div class="card bg-light">
            <h1 class="card-title">Boletos Pagos</h1>
            <div class="mb-3">
                <a href="gerar_boleto.php" class="btn btn-primary btn-block">Cadastrar Boleto</a>
            </div>
            <div class="mb-3">
                <a href="ver_dados.php" class="btn btn-ver-dados btn-block">Ver Boletos Salvos</a>
            </div>
            <?php if ($_SESSION['tipo'] == '1'): ?>
            <div class="mb-3">
                <button onclick="location.href='Importar.php'" class="btn btn-importar btn-block">Importar Dados</button>
            </div>
            <div class="mb-3">
                <button onclick="location.href='Exportar.php'" class="btn btn-importar btn-block">Exportar Dados</button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
