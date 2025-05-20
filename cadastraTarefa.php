<?php
include("conexao.php");

// Busca usuários para popular o select
$sql_usuarios = "SELECT id_usuario, nome_usuario FROM usuario";
$result_usuarios = $conexao->query($sql_usuarios);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Tarefa - TaskSync</title>
  <link rel="stylesheet" href="./css/cadTarefa.css">
</head>
<body class="body-cad-tarefa">
  <h2 class="titulo-cad-tarefa">Criar Nova Tarefa</h2>

  <form method="POST">
    <label>Nome do Usuário:</label>
    <select name="usuario_id" required>
      <option value="">Selecione um usuário</option>
      <?php while($usuario = $result_usuarios->fetch_assoc()): ?>
        <option value="<?= $usuario['id_usuario'] ?>"><?= htmlspecialchars($usuario['nome_usuario']) ?></option>
      <?php endwhile; ?>
    </select><br>

    <label>Descrição:</label>
    <textarea name="descricao" required></textarea><br>

    <label>Setor:</label>
    <input type="text" name="setor" required><br>

    <label>Prioridade:</label>
    <select name="prioridade" required>
        <option value="Baixa">Baixa</option>
        <option value="Média">Média</option>
        <option value="Alta">Alta</option>
    </select><br>

    <!-- Status fixado como "A Fazer" por regra de negócio -->
    <input type="hidden" name="status" value="A Fazer">

    <button type="submit">Cadastrar Tarefa</button>
  </form>

  <a href="gerenciaTarefa.php" class="gerenciar-tarefas">Gerenciar Tarefas</a>

  <?php
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $usuario_id = $_POST["usuario_id"];
      $descricao = $_POST["descricao"];
      $setor = $_POST["setor"];
      $prioridade = $_POST["prioridade"];
      $status = "A Fazer"; // Força regra de negócio

      $sql_tarefa = "INSERT INTO tarefas (usuario_id, descricao, setor, prioridade, data_cadastro, status) 
                     VALUES (?, ?, ?, ?, NOW(), ?)";
      $stmt = $conexao->prepare($sql_tarefa);
      $stmt->bind_param("issss", $usuario_id, $descricao, $setor, $prioridade, $status);

      if ($stmt->execute()) {
          echo "<p style='color: green;'> Tarefa cadastrada com sucesso!</p>";
      } else {
          echo "<p style='color: red;'> Erro ao cadastrar tarefa: " . $stmt->error . "</p>";
      }
  }
  ?>
</body>
</html>
