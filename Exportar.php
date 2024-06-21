<?php
include 'header.php';
require 'config.php';

function exportarSQL($conn) {
    $filename = 'exportado.sql';
    $file = fopen($filename, 'w');

    if (!$file) {
        return 'Erro ao criar o arquivo SQL.';
    }

    // Consulta para obter todos os dados da tabela BoletosPagos
    $sql = "SELECT * FROM BoletosPagos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $insertStatements = [];

        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $cpf = $row['cpf'];
            $matricula = $row['matricula'];
            $vinculo = $row['vinculo'];
            $nome = $row['nome'];
            $grau_dependencia = $row['grau_dependencia'];
            $data_pagamento = $row['data_pagamento'];
            $valor_pago = $row['valor_pago'];
            $tipo_registro = $row['tipo_registro'];
            $data_vencimento = $row['data_vencimento'];
            $referencia_pagamento = $row['referencia_pagamento'];
            $situacao = $row['situacao'];

            $insertStatements[] = "($id, '$cpf', '$matricula', '$vinculo', '$nome', '$grau_dependencia', '$data_pagamento', $valor_pago, '$tipo_registro', '$data_vencimento', '$referencia_pagamento', $situacao)";
        }

        $insertSQL = "INSERT INTO `BoletosPagos` (`id`, `cpf`, `matricula`, `vinculo`, `nome`, `grau_dependencia`, `data_pagamento`, `valor_pago`, `tipo_registro`, `data_vencimento`, `referencia_pagamento`, `situacao`) VALUES\n";
        $insertSQL .= implode(",\n", $insertStatements) . ";\n";

        fwrite($file, $insertSQL);
        fclose($file);

        return 'Dados exportados com sucesso para ' . $filename;
    } else {
        return 'Nenhum dado encontrado para exportar.';
    }
}

$message = exportarSQL($conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Exportação</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Resultado da Exportação</h1>
        <div class="alert alert-info">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <button onclick="location.href='dashboard.php'" class="btn btn-secondary mt-3">Voltar</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
