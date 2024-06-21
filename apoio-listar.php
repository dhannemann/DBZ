<h1>Listar Apoio</h1>

<!-- Formulário de busca -->
<form action="?page=apoio-listar" method="GET">
    <div class="mb-3">
        <label for="busca">Buscar:</label>
        <input type="text" id="busca" name="busca" class="form-control">
    </div>
    <input type="hidden" name="page" value="apoio-listar">
    <button type="submit" class="btn btn-primary">Buscar</button>
</form>

<?php
    $termo_busca = isset($_GET['busca']) ? $_GET['busca'] : '';

    $resultados_por_pagina = 500;

    $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

    //Calcula o offset com base na página atual
    $offset = ($pagina_atual - 1) * $resultados_por_pagina;

    //Consulta SQL com LIMIT e OFFSET para paginar os resultados
    $sql = "SELECT * FROM Apoio";

    //Adiciona cláusula WHERE para busca por termo em qualquer campo
    if (!empty($termo_busca)) {
        $sql .= " WHERE CnpjCpf LIKE '%$termo_busca%' OR Cadastro LIKE '%$termo_busca%' OR Credenciado LIKE '%$termo_busca%' OR Municipio LIKE '%$termo_busca%' OR LimiteFin LIKE '%$termo_busca%'";
    }

    //Adiciona LIMIT e OFFSET para limitar a quantidade de resultados por página
    $sql .= " LIMIT $resultados_por_pagina OFFSET $offset";

    $sql_count = "SELECT COUNT(*) as total FROM ($sql) as total";

    $res = $conn->query($sql);
    $res_count = $conn->query($sql_count);
    $qtd = $res_count->fetch_assoc()['total'];

    if ($qtd > 0) {
        print "<p>Mostrando <b>$qtd</b> resultado(s)</p>";
        print "<table class='table table-bordered table-striped table-hover'>";
        print "<tr>";
        print "<th>#</th>";
        print "<th>CnpjCpf</th>";
        print "<th>Cadastro</th>";
        print "<th>Credenciado</th>";
        print "<th>Municipio</th>";
        print "<th>LimiteFin</th>";
        print "<th>Ações</th>";
        print "</tr>";
        while ($row = $res->fetch_object()) {
            $limite_fin_formatado = number_format($row->LimiteFin, 2, ',', '.');
            print "<tr>";
            print "<td>".$row->id_Apoio."</td>";
            print "<td>".$row->CnpjCpf."</td>";
            print "<td>".$row->Cadastro."</td>";
            print "<td>".$row->Credenciado."</td>";
            print "<td>".$row->Municipio."</td>";
            print "<td>".$limite_fin_formatado."</td>";
            print "<td>
                      <button onclick=\"location.href='?page=apoio-editar&id_Apoio=".$row->id_Apoio."';\" class='btn btn-primary'>Editar</button>
                      <button onclick=\"if(confirm('Tem certeza que deseja excluir?')){location.href='?page=apoio-salvar&acao=excluir&id_Apoio=".$row->id_Apoio."';}else{false;}\"  class='btn btn-danger'>Excluir</button>
                   </td>";
            print "</tr>";
        }
        print "</table>";

        $anterior = $pagina_atual - 1;
        $proximo = $pagina_atual + 1;

        print "<div>";
        if ($pagina_atual > 1) {
            print "<a href='?page=apoio-listar&pagina=$anterior&busca=$termo_busca' class='btn btn-primary'>Anterior</a>";
            print "&nbsp;";
        }
        if ($qtd >= $resultados_por_pagina) {
            print "&nbsp;";
            print "<a href='?page=apoio-listar&pagina=$proximo&busca=$termo_busca' class='btn btn-primary'>Próximo</a>";
        }
        print "</div>";
    } else {
        print "Não encontrou resultado";
    }
?>
