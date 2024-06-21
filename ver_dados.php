<?php
include 'header.php';
require 'config.php';
require 'config_opcoes.php';
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletos Pagos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap copy.css">
</head>
<body>
<div class="container mt-5">
    <h1>Boletos Pagos</h1>
    <button onclick="location.href='dashboard.php'" class="btn btn-secondary mt-3">Voltar</button>
    <br><br>
    <!-- Formulário de busca -->
    <form action="" method="GET" class="mb-3">
        <div class="form-group">
            <label for="busca">Buscar:</label>
            <input type="text" id="busca" name="busca" class="form-control" value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca'], ENT_QUOTES) : '' ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if (!empty($_GET['busca'])): ?>
                <button type="button" class="btn btn-secondary" onclick="limparBusca()">Limpar Busca</button>
            <?php endif; ?>
        </div>
    </form>
    <?php
    require_once 'config.php';

    $termo_busca = isset($_GET['busca']) ? $_GET['busca'] : '';
    $resultados_por_pagina = 20;  // Defina o número de resultados por página
    $pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    // Calcula o offset com base na página atual
    $offset = ($pagina_atual - 1) * $resultados_por_pagina;

    // Consulta SQL com LIMIT e OFFSET para paginar os resultados
    $sql = "SELECT * FROM BoletosPagos";

    // Adiciona cláusula WHERE para busca por termo em qualquer campo
    if (!empty($termo_busca)) {
        $sql .= " WHERE nome LIKE '%$termo_busca%' OR cpf LIKE '%$termo_busca%'";
    }

    // Adiciona LIMIT e OFFSET para limitar a quantidade de resultados por página
    $sql .= " LIMIT $resultados_por_pagina OFFSET $offset";

    // Consulta para contar o número total de resultados
    $sql_count = "SELECT COUNT(*) as total FROM BoletosPagos";
    if (!empty($termo_busca)) {
        $sql_count .= " WHERE nome LIKE '%$termo_busca%' OR cpf LIKE '%$termo_busca%'";
    }

    $res = $conn->query($sql);
    $res_count = $conn->query($sql_count);
    $qtd = $res_count->fetch_assoc()['total'];

    if ($qtd > 0) {
        if ($qtd == 1) echo "<p><b>$qtd</b> resultado</p>";
        else echo "<p><b>$qtd</b> resultados</p>";
        echo "<table class='table table-bordered table-striped table-hover'>";
        echo "<tr>";
        echo "<th>CPF</th>";
        echo "<th>Matrícula</th>";
        echo "<th>Vínculo</th>";
        echo "<th>Nome</th>";
        echo "<th>Grau de Dependência</th>";
        echo "<th>Data de Pagamento</th>";
        echo "<th>Valor Pago</th>";
        echo "<th>Tipo de Registro</th>";
        echo "<th>Data de Vencimento</th>";
        echo "<th>Referência de Pagamento</th>";
        echo "<th>Situação</th>";
        if ($_SESSION['tipo'] == '1') { // Botões Editar e Excluir visíveis apenas para tipo == 1
            echo "<th>Ações</th>";
        }
        echo "</tr>";
        // Loop de exibição dos resultados
        while ($row = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['cpf']}</td>";
            echo "<td>{$row['matricula']}</td>";
            echo "<td>{$row['vinculo']}</td>";
            echo "<td>{$row['nome']}</td>";
            echo "<td>{$opcoes_grau_dependencia[$row['grau_dependencia']]}</td>";
            echo "<td>" . date("d/m/Y", strtotime($row['data_pagamento'])) . "</td>";
            echo "<td>R$ " . number_format($row['valor_pago'], 2, ',', '.') . "</td>";
            echo "<td>{$row['tipo_registro']}</td>";
            echo "<td>" . date("d/m/Y", strtotime($row['data_vencimento'])) . "</td>";
            echo "<td>" . date("d/m/Y", strtotime($row['referencia_pagamento'])) . "</td>";
            echo "<td>" . ($row['situacao'] ? 'Pago' : 'Não Pago') . "</td>";
            if ($_SESSION['tipo'] == '1') { // Botões Editar e Excluir visíveis apenas para tipo == 1
                echo "<td>
                <style>
                    .btn-custom {
                        width: 72px;
                        height: 50px;
                        margin-right: 5px; /* Adicionando margem entre os botões */
                    }
                </style>
                <div class='btn-group' role='group' aria-label='Ações'>";
                echo "<button onclick=\"location.href='Editar.php?id={$row['id']}';\" class='btn btn-primary btn-custom'>Editar</button>";
                    // <button onclick=\"if(confirm('Tem certeza que deseja excluir?')){location.href='Excluir.php?id={$row['id']}';}else{false;}\" class='btn btn-danger btn-custom'>Excluir</button>";
                echo "</div></td>";
            }
            echo "</tr>";
        }
        echo "</table>";

        $total_paginas = ceil($qtd / $resultados_por_pagina);
        $anterior = $pagina_atual > 1 ? $pagina_atual - 1 : 1;
        $proximo = $pagina_atual < $total_paginas ? $pagina_atual + 1 : $total_paginas;

        echo "<nav aria-label='Page navigation'>";
        echo "<ul class='pagination'>";
        if ($pagina_atual > 1) {
            echo "<li class='page-item'><a class='page-link' href='?pagina=$anterior&busca=$termo_busca'>Anterior</a></li>";
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            $active = $i == $pagina_atual ? 'active' : '';
            echo "<li class='page-item $active'><a class='page-link' href='?pagina=$i&busca=$termo_busca'>$i</a></li>";
        }
        if ($pagina_atual < $total_paginas) {
            echo "<li class='page-item'><a class='page-link' href='?pagina=$proximo&busca=$termo_busca'>Próximo</a></li>";
        }
        echo "</ul>";
        echo "</nav>";
    } else {
        echo "<p>Nenhum dado encontrado.</p>";
    }
    ?>

    <button onclick="location.href='dashboard.php'" class="btn btn-secondary mt-3">Voltar</button>
</div>
<script>
    function limparBusca() {
        document.getElementById('busca').value = '';
        // Submeta o formulário após limpar a busca
        document.querySelector('form').submit();
    }
</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
