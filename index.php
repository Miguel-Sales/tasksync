<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title></title>
  <link rel="stylesheet" href="./css/cadastr-user.css" />
</head>
<body class="body-login">
  <div class="div-principal">
    <div class="container-login">
      <div class="logo">
        <img id="img-logo" src="./img/TASKSYNC-LOGO-OG.png" alt="logo-cinelentes" />
      </div>
      <h2 class="texto-login">Cadastro</h2>

      <form method="POST" action="">
        <div class="input-inform">
          <input class="input-nome-email-senha" type="text" name="nome" placeholder="Nome" required />
        </div>

        <div class="input-inform">
          <input class="input-nome-email-senha" type="email" name="email" placeholder="Email" required />
        </div>

        <div class="input-inform">
          <input class="input-nome-email-senha" type="password" name="senha" placeholder="Senha" required />
        </div>

        <button href="cdastraTarefa.php" type="submit" class="botao-entrar">ENTRAR</button>
      </form>

      
    </div>
  </div>
</body>
</html>

 <?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_usuario = $_POST['nome'];
    $email_usuario = $_POST['email'];
    $senha_usuario = $_POST['senha'];

    $sql_verifica = "SELECT id_usuario FROM usuario WHERE email_usuario = ?";
    $stmt_verifica = $conexao->prepare($sql_verifica);
    $stmt_verifica->bind_param("s", $email_usuario);
    $stmt_verifica->execute();
    $stmt_verifica->store_result();

    if ($stmt_verifica->num_rows > 0) {
        echo "<!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'E-mail já cadastrado!'
                }).then(() => window.location.href = 'index.php');
            </script>
        </body>
        </html>";
        exit;
    }

    $sql = "INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $nome_usuario, $email_usuario, $senha_usuario);
        if ($stmt->execute()) {
            echo "<!DOCTYPE html>
            <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: 'Usuário cadastrado com sucesso!'
                    }).then(() => window.location.href = 'cadastraTarefa.php');
                </script>
            </body>
            </html>";
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Erro na preparação da consulta.";
    }

    $stmt_verifica->close();
    $conexao->close();
}
?>