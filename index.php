<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sistema de Login</title>
    <!-- Layout com bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row" style="margin-top: 200px;">
            <div class="col-lg-4 offset-lg-4 jumbotron">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label>Usuário</label>
                        <input type="text" name="usuario" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="senha" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>