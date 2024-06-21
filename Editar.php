<?php
include 'header.php'; // Cabeçalho
require 'config.php'; // Arquivo de conexão com o banco de dados
require 'config_opcoes.php';

$data_atual = date("Y-m-d");
$erro_data_pagamento = ""; // Inicializa variável para mensagem de erro

$id = isset($_GET['id']) ? $_GET['id'] : ''; // Pega o ID do boleto pago passado via GET

// Consulta os dados do boleto pago no banco de dados
$sql = "SELECT * FROM BoletosPagos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) { // Verifica se o boleto pago foi encontrado
    $row = $res->fetch_assoc();
} else {
    // Exibe mensagem de erro e redireciona se o ID for inválido
    echo "<script>
        alert('ID inválido.');
        window.location.href = 'ver_dados.php';
          </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica se os dados foram enviados via método POST
    // Recebe e armazena os dados enviados pelo formulário
    $cpf = $_POST["cpf"];
    $matricula = $_POST["matricula"];
    $vinculo = $_POST["vinculo"];
    $nome = $_POST["nome"];
    $grau_dependencia = $_POST["grau_dependencia"];
    $data_pagamento = $_POST["data_pagamento"];
    $valor_pago = $_POST["valor_pago"];
    $tipo_registro = $_POST["tipo_registro"];
    $data_vencimento = $_POST["data_vencimento"];
    $referencia_pagamento = $_POST["referencia_pagamento"];
    $situacao = isset($_POST["situacao"]) ? 1 : 0;

    $valid = true; // Variável de controle para validação

    // Verifica se a data de pagamento é maior que a data atual
    if ($data_pagamento > $data_atual) {
        $erro_data_pagamento = "A data de pagamento não pode ser superior à data atual.";
        $valid = false;
    }

    if ($valid) {
        // Prepara a consulta para atualizar os dados do boleto pago no banco de dados
        $sql_update = "UPDATE BoletosPagos SET cpf = ?, matricula = ?, vinculo = ?, nome = ?, grau_dependencia = ?, data_pagamento = ?, valor_pago = ?, tipo_registro = ?, data_vencimento = ?, referencia_pagamento = ?, situacao = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssssdssssi", $cpf, $matricula, $vinculo, $nome, $grau_dependencia, $data_pagamento, $valor_pago, $tipo_registro, $data_vencimento, $referencia_pagamento, $situacao, $id);

        // Executa a consulta
        if ($stmt_update->execute()) {
            // Redireciona para a página de visualização dos dados com mensagem de sucesso
            echo "<script>
                    alert('Dados atualizados com sucesso.');
                    window.location.href = 'ver_dados.php';
                  </script>";
            exit;
        } else {
            // Exibe mensagem de erro caso a atualização falhe
            echo "Erro ao atualizar os dados: " . $conn->error;
        }

        $stmt_update->close();
    }
} else {
    // Carrega os valores iniciais do banco de dados para exibição
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
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Boleto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script>
        function formatarCPF(cpf) {
            cpf = cpf.replace(/\D/g, ""); //Remove caracteres não numéricos
            cpf = cpf.substring(0, 11); //Limita a 11 caracteres
            return cpf;
        }
    </script>
    <script>
        function formatarMatricula(matricula) {
            matricula = matricula.replace(/\D/g, ""); //Remove caracteres não numéricos
            matricula = matricula.substring(0, 20); //Limita a 20 caracteres
            return matricula;
        }
    </script>
    <script>
        function formatarVinculo(vinculo) {
            vinculo = vinculo.replace(/\D/g, ""); //Remove caracteres não numéricos
            vinculo = vinculo.substring(0, 2); //Limita a 2 caracteres
            return vinculo;
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h1>Editar Boleto</h1>
    <form action="" method="POST">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <div class="mb-3">
            <label>CPF</label>
            <input type="text" id="cpf" name="cpf" pattern=".{11}" maxlength="11" oninput="this.value = formatarCPF(this.value)" value="<?php echo $cpf; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Matrícula</label>
            <input type="text" id="matricula" name="matricula" pattern=".{7,20}" maxlength="20" oninput="this.value = formatarMatricula(this.value)" value="<?php echo $matricula; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Vínculo</label>
            <input type="text" id="vinculo" name="vinculo" pattern=".{1,2}" maxlength="2" oninput="this.value = formatarVinculo(this.value)" value="<?php echo $vinculo; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" maxlength="100" value="<?= htmlspecialchars($nome, ENT_QUOTES); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Grau de Dependência</label>
            <select name="grau_dependencia" class="form-control" required>
                <?php foreach ($opcoes_grau_dependencia as $opcao => $valor) : ?>
                    <option value="<?= $valor; ?>" <?= ($grau_dependencia ?? '') == $valor ? 'selected' : ''; ?>><?= $opcao; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Data de Pagamento</label>
            <input type="date" name="data_pagamento" value="<?= htmlspecialchars($data_pagamento); ?>" class="form-control" required>
            <div class="text-danger">
                <?= $erro_data_pagamento; ?>
            </div>
        </div>
        <div class="mb-3">
            <label>Valor Pago</label>
            <input type="text" name="valor_pago" pattern="[0-9]+([\.,][0-9]{1,2})?" maxlength="13" value="<?= htmlspecialchars($valor_pago, ENT_QUOTES); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tipo de Registro</label>
            <select name="tipo_registro" class="form-control" required>
                <?php foreach ($opcoes_tipo_registro as $opcao) : ?>
                    <option value="<?= $opcao; ?>" <?= ($tipo_registro == $opcao) ? 'selected' : ''; ?>><?= $opcao; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Data de Vencimento</label>
            <input type="date" name="data_vencimento" value="<?= htmlspecialchars($data_vencimento); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Referência de Pagamento</label>
            <input type="date" name="referencia_pagamento" value="<?= htmlspecialchars($referencia_pagamento); ?>" class="form-control" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="situacao" value="1" <?= ($situacao == 1) ? 'checked' : ''; ?> class="form-check-input" id="situacao">
            <label class="form-check-label" for="situacao">Pago</label>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="ver_dados.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
