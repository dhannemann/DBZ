<?php
include 'header.php';
require 'config.php';

//Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Verifica se a ação é editar e se o ID está presente
    if (isset($_POST['acao']) && $_POST['acao'] == 'editar' && isset($_POST['id'])) {
        //Recupera os dados do formulário
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $termo = $_POST['termo'];
        $cpf = $_POST['cpf'];
        $data_nascimento = $_POST['data_nascimento'];
        $orgao = $_POST['orgao'];
        $grau = $_POST['grau'];
        $adesao = $_POST['adesao'];

        //Atualiza os dados no banco de dados
        $sql_update = "UPDATE credenciados SET nome='$nome', termo='$termo', cpf='$cpf', data_nascimento='$data_nascimento', orgao='$orgao', grau='$grau', adesao='$adesao' WHERE id=$id";

        if ($conn->query($sql_update) === TRUE) {
            //Redireciona para ver_dados.php com mensagem de sucesso
            echo "<script>
                    alert('Dados atualizados com sucesso.');
                    window.location.href = 'ver_dados.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Ocorreu um erro ao atualizar os dados: " . $conn->error . "');
                    window.location.href = 'ver_dados.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Ação inválida ou ID não especificado.');
                window.location.href = 'ver_dados.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Erro: Este script deve ser acessado via método POST.');
            window.location.href = 'ver_dados.php';
          </script>";
}
?>
