<?php
include 'header.php';
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    //Verifica se o ID é válido
    if (!empty($id) && is_numeric($id)) {
        //SQL para deletar o boleto pago
        $sql_delete = "DELETE FROM BoletosPagos WHERE id = $id";

        if ($conn->query($sql_delete) === TRUE) {
            echo "<script>
                    alert('Dado excluído com sucesso.');
                    window.location.href = 'ver_dados.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Ocorreu um erro ao excluir: " . $conn->error . "');
                    window.location.href = 'ver_dados.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('ID inválido.');
                window.location.href = 'ver_dados.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Ação inválida.');
            window.location.href = 'ver_dados.php';
          </script>";
}
?>
