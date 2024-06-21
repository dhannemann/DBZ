<?php
session_start();
//Verifica se o usuário está logado
if (empty($_SESSION['nome'])) {
    header("Location: index.php");
    exit;
}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Boletos Pagos IASEP</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent"></div>
        <?php //Esse php é novidade do login
            print "Olá, ".$_SESSION["nome"]."&nbsp;&nbsp;";
            print "<a href='logout.php'>Sair</a>";
        ?>
    </div>
</nav>