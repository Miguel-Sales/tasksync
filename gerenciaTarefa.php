<?php
include("conexao.php");

// Mensagens de feedback
$message = "";
$message_class = "";

// Excluir tarefa
if (isset($_GET['delete'])) {
    $idDelete = intval($_GET['delete']);
    $stmtDel = $conexao->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmtDel->bind_param("i", $idDelete);
    if ($stmtDel->execute()) {
        $message = "Tarefa excluída com sucesso.";
        $message_class = "success";
    } else {
        $message = "Erro ao excluir tarefa: " . $stmtDel->error;
        $message_class = "error";
    }
    header("Location: gerenciaTarefa.php?msg=" . urlencode($message) . "&type=" . $message_class);
    exit;
}

// Inserir ou atualizar tarefa
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = intval($_POST["usuario_id"]);
    $descricao = $_POST["descricao"];
    $setor = $_POST["setor"];
    $prioridade = $_POST["prioridade"];
    $status = $_POST["status"];
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

    if ($id > 0) {
        // Atualizar tarefa
        $sql = "UPDATE tarefas SET usuario_id=?, descricao=?, setor=?, prioridade=?, status=? WHERE id=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("issssi", $usuario_id, $descricao, $setor, $prioridade, $status, $id);
        if ($stmt->execute()) {
            $message = "Tarefa atualizada com sucesso.";
            $message_class = "success";
        } else {
            $message = "Erro ao atualizar tarefa: " . $stmt->error;
            $message_class = "error";
        }
    } else {
        // Inserir nova tarefa
        $sql = "INSERT INTO tarefas (usuario_id, descricao, setor, prioridade, data_cadastro, status) VALUES (?, ?, ?, ?, NOW(), ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("issss", $usuario_id, $descricao, $setor, $prioridade, $status);
        if ($stmt->execute()) {
            $message = "Tarefa cadastrada com sucesso.";
            $message_class = "success";
        } else {
            $message = "Erro ao cadastrar tarefa: " . $stmt->error;
            $message_class = "error";
        }
    }
    header("Location: gerenciaTarefa.php?msg=" . urlencode($message) . "&type=" . $message_class);
    exit;
}

// Pegar dados para edição
$edit_tarefa = null;
if (isset($_GET['edit'])) {
    $idEdit = intval($_GET['edit']);
    $stmt = $conexao->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $idEdit);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_tarefa = $result->fetch_assoc();
}

// Buscar usuários para o select
$sql_usuarios = "SELECT id_usuario, nome_usuario FROM usuario ORDER BY nome_usuario";
$result_usuarios = $conexao->query($sql_usuarios);

// Buscar todas as tarefas, agrupadas por status
$sql_tarefas = "SELECT t.*, u.nome_usuario FROM tarefas t JOIN usuario u ON t.usuario_id = u.id_usuario ORDER BY t.data_cadastro DESC";
$result_tarefas = $conexao->query($sql_tarefas);

// Preparar arrays para os status
$tarefas_status = [
    'A Fazer' => [],
    'Fazendo' => [],
    'Concluído' => []
];

while ($row = $result_tarefas->fetch_assoc()) {
    $status = $row['status'];
    if (isset($tarefas_status[$status])) {
        $tarefas_status[$status][] = $row;
    }
}

// Mensagem GET
$msg_get = $_GET['msg'] ?? "";
$type_get = $_GET['type'] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Gerenciar Tarefas - TaskSync</title>
    <link rel="stylesheet" href="./css/gerencTarefa.css" />
</head>
<body>
    <div class="container">
        <h1>Gerenciar Tarefas</h1>

        <?php if($msg_get): ?>
            <p class="message <?= htmlspecialchars($type_get) ?>"><?= htmlspecialchars($msg_get) ?></p>
        <?php endif; ?>
        <div class="tarefas-wrapper">
            <?php foreach ($tarefas_status as $status => $tarefas): ?>
                <div class="coluna">
                    <h2><?= $status ?></h2>
                    <?php if(count($tarefas) === 0): ?>
                        <p class="vazio">Nenhuma tarefa.</p>
                    <?php else: ?>
                        <?php foreach ($tarefas as $tarefa): ?>
                            <div class="tarefa-card">
                                <strong><?= htmlspecialchars($tarefa['nome_usuario']) ?></strong><br/>
                                <small><?= htmlspecialchars($tarefa['setor']) ?> - <?= htmlspecialchars($tarefa['prioridade']) ?></small>
                                <p><?= nl2br(htmlspecialchars($tarefa['descricao'])) ?></p>
                                <small>Cadastro: <?= $tarefa['data_cadastro'] ?></small><br/>
                                <a href="?edit=<?= $tarefa['id'] ?>" class="btn-editar">Editar</a> | 
                                <a href="?delete=<?= $tarefa['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir essa tarefa?')">Excluir</a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
