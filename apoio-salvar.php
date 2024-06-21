<?php
	switch ($_REQUEST["acao"]) {
		case 'cadastrar':
			$sql = "INSERT INTO apoio (
						CnpjCpf,
						Cadastro,
						Credenciado,
						Municipio,
						LimiteFin
					)VALUES(
						'".$_POST["CnpjCpf"]."',
						'".$_POST["Cadastro"]."',
						'".$_POST["Credenciado"]."',
						'".$_POST["Municipio"]."',
						".$_POST["LimiteFin"]."
					)";

			$res = $conn->query($sql);

			if($res==true){
				print "<script>alert('Cadastrou com sucesso!');</script>";
				print "<script>location.href='?page=apoio-listar';</script>";
			}else{
				print "<script>alert('Não foi possível!');</script>";
				print "<script>location.href='?page=apoio-listar';</script>";
			}
			break;
		
		case 'editar':
			$sql = "UPDATE apoio SET
						CnpjCpf='".$_POST['CnpjCpf']."',
						Cadastro='".$_POST['Cadastro']."',
						Credenciado='".$_POST['Credenciado']."',
						Municipio='".$_POST['Municipio']."',
						LimiteFin=".$_POST['LimiteFin']."
					WHERE
						id_Apoio=".$_POST['id_Apoio'];

			$res = $conn->query($sql);

			if($res==true){
				print "<script>alert('Editou com sucesso!');</script>";
				print "<script>location.href='?page=apoio-listar';</script>";
			}else{
				print "<script>alert('Não foi possível!');</script>";
				print "<script>location.href='?page=apoio-listar';</script>";
			}
			break;

		case 'excluir':
			$sql = "DELETE FROM apoio WHERE id_Apoio=".$_REQUEST['id_Apoio'];

			$res = $conn->query($sql);

			if($res==true){
				print "<script>alert('Excluiu com sucesso!');</script>";
				print "<script>location.href='?page=apoio-listar';</script>";
			}else{
				print "<script>alert('Não foi possível!');</script>";
				print "<script>location.href='?page=apoio-listar';</script>";
			}
			break;
	}
?>
