<?php

$conexao = new mysqli(
"localhost", 
"root", 
"", 
"tasksync"); 


if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

?>