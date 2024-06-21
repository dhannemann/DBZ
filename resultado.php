<?php
include 'header.php';
require 'config.php';

if (!extension_loaded('gd')) {
    die('A');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['ids'];

    if (empty($ids)) {
        exit('Nenhum cartão selecionado.');
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT nome, termo, cpf, data_nascimento, orgao, grau, adesao FROM credenciados WHERE id IN ($placeholders)");

    $types = str_repeat('i', count($ids));
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();

    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }

    $_SESSION['dados'] = $dados;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script>
        function formatarCPF(cpf) {
            return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }

        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', [85.5, 53.5]);

            const dados = <?php echo json_encode($_SESSION['dados']); ?>;

            dados.forEach((item, index) => {
                doc.setFontSize(10);
                doc.setFont('helvetica', 'bold');
                doc.text(item.nome.toUpperCase(), 5, 30);
                doc.text(item.termo, 5, 39);
                doc.text(formatarCPF(item.cpf), 5, 49);
                doc.text(new Date(item.data_nascimento).toLocaleDateString('pt-BR'), 53, 39);
                doc.text(item.orgao.toUpperCase(), 53, 49);
                
                if (index < dados.length - 1) {
                    doc.addPage();
                }
            });

            var blob = doc.output('blob');
            var url = URL.createObjectURL(blob);
            window.open(url);
        }
    </script>
</head>
<body>
    <div class="result-container">
        <button onclick="generatePDF()">Gerar Cartões</button>
    </div>
</body>
</html>

<?php
$conn->close();
?>
