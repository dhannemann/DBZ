<?php
include 'header.php';
require 'config.php';

function importarSQL($filename, $conn) {
    //Lê o conteúdo do arquivo SQL
    $sql = file_get_contents($filename);

    if ($sql === false) {
        return 'Erro ao ler o arquivo SQL.';
    }

    //Obtém os IDs existentes antes da importação
    $existing_ids = [];
    $result = $conn->query("SELECT id FROM BoletosPagos");
    while ($row = $result->fetch_assoc()) {
        $existing_ids[] = $row['id'];
    }

    //Executa o SQL
    $conn->begin_transaction();
    try {
        if ($conn->multi_query($sql)) {
            do {
                //Limpa os resultados armazenados
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());

            $conn->commit();

            //Obtém os novos IDs após a importação
            $new_ids = [];
            $result = $conn->query("SELECT id FROM BoletosPagos");
            while ($row = $result->fetch_assoc()) {
                if (!in_array($row['id'], $existing_ids)) {
                    $new_ids[] = $row['id'];
                }
            }

            return $new_ids;
        } else {
            throw new Exception('Erro ao importar os dados: ' . $conn->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        return $e->getMessage();
    }
}

$new_ids = importarSQL('importar.sql', $conn);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Importação</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Resultado da Importação</h1>
        <?php if (is_array($new_ids)): ?>
            <?php if (count($new_ids) > 0): ?>
                <div class="alert alert-info">
                    Dados importados com sucesso.
                </div>
                <?php
                //Exibe os novos dados importados
                $sql = "SELECT id, cpf, matricula, vinculo, nome, grau_dependencia, data_pagamento, valor_pago, tipo_registro, data_vencimento, referencia_pagamento, situacao FROM BoletosPagos WHERE id IN (" . implode(',', $new_ids) . ")";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                    if ($res->num_rows == 1) echo "<p><b>{$res->num_rows}</b> dado importado</p>";
                    else echo "<p><b>{$res->num_rows}</b> dados importados</p>";
                    echo "<table class='table table-bordered table-striped table-hover'>";
                    echo "<tr>";
                    echo "<th>#</th>";
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
                    echo "<th>Ações</th>";
                    echo "</tr>";
                    while ($row = $res->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['cpf']}</td>";
                        echo "<td>{$row['matricula']}</td>";
                        echo "<td>{$row['vinculo']}</td>";
                        echo "<td>{$row['nome']}</td>";
                        echo "<td>{$row['grau_dependencia']}</td>";
                        echo "<td>" . date("d/m/Y", strtotime($row['data_pagamento'])) . "</td>";
                        echo "<td>{$row['valor_pago']}</td>";
                        echo "<td>{$row['tipo_registro']}</td>";
                        echo "<td>" . date("d/m/Y", strtotime($row['data_vencimento'])) . "</td>";
                        echo "<td>{$row['referencia_pagamento']}</td>";
                        echo "<td>" . ($row['situacao'] ? 'Pago' : 'Não Pago') . "</td>";
                        echo "<td>
                                <div class='btn-group' role='group' aria-label='Ações'>
                                    <button onclick=\"location.href='editar_boleto.php?id={$row['id']}';\" class='btn btn-primary'>Editar</button>&nbsp;
                                    <button onclick=\"if(confirm('Tem certeza que deseja excluir?')){location.href='excluir_boleto.php?id={$row['id']}';}else{return false;}\" class='btn btn-danger'>Excluir</button>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>Nenhum dado encontrado.</p>";
                }
                ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    Nenhum dado novo foi importado.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($new_ids, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <button onclick="location.href='dashboard.php'" class="btn btn-secondary mt-3">Voltar</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
