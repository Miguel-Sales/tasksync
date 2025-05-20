<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title></title>
  <link rel="stylesheet" href="./css/cadTarefa.css">
</head>
<body class="body-cad-tarefa">
  <h2 class="titulo-cad-tarefa">Criar Nova Tarefa</h2>
  <form method="POST">
    <input class="input-tarefa-usuario" type="number" name="usuario_id" placeholder="ID do Usuário" required>
    <textarea class="text-descricao" name="descricao" placeholder="Descrição da Tarefa" required></textarea>
    <input class="input-setor" class="" type="text" name="setor" placeholder="Setor da Empresa" required>
    <select class="prioridade-tarefa" name="prioridade" required>
      <option  class="prioridade"  value="Baixa">Baixa</option>
      <option  class="prioridade"  value="Média">Média</option>
      <option  class="prioridade"  value="Alta">Alta</option>
    </select>
    <button class="bot-criar-tarefa" type="submit">Criar Tarefa</button>
  </form>
  <a href="gerenciaTarefa.php" class="gerenciar-tarefas">Gerenciar Tarefas</a>
</body>
</html>



  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexao = mysqli_connect("localhost", "root", "", "tasksync");

    if (!$conexao) {
      echo "<p class='erro'>Erro ao conectar ao banco de dados.</p>";
    } else {
      $usuario_id = $_POST['usuario_id'];
      $descricao = $_POST['descricao'];
      $setor = $_POST['setor'];
      $prioridade = $_POST['prioridade'];
      $data_cadastro = date("Y-m-d H:i:s");

      $sql = "INSERT INTO tarefas (usuario_id, descricao, setor, prioridade, data_cadastro)
              VALUES ('$usuario_id', '$descricao', '$setor', '$prioridade', '$data_cadastro')";


      if (mysqli_query($conexao, $sql)) {
        echo "<p class='sucesso'>Tarefa cadastrada com sucesso!</p>";
      } else {
        echo "<p class='erro'>Erro ao cadastrar tarefa.</p>";
      }

      mysqli_close($conexao);
    }
  }

  
  ?>

