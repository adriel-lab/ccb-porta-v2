
<?php
include 'conexao.php';

// Verifica se o ID do porteiro foi passado via GET
if (!isset($_GET['id'])) {
    echo "<p style='color: red; text-align: center;'>ID do porteiro não informado.</p>";
    exit;
}

// Obtém o ID do porteiro a ser deletado
$porteiro_id = $_GET['id'];

// Prepara a query SQL usando prepared statements para evitar SQL injection
$sql = "DELETE FROM porteiros WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

// Associa o ID do porteiro à query
$stmt->bind_param("i", $porteiro_id);

// Executa a query
if ($stmt->execute()) {
    echo "<p style='color: green; text-align: center;'>Porteiro deletado com sucesso!</p>";
} else {
    echo "<p style='color: red; text-align: center;'>Erro ao deletar porteiro: " . $stmt->error . "</p>";
}

// Fecha a declaração e a conexão
$stmt->close();
$conn->close();

// Redireciona de volta para a página de edição da igreja
if (isset($_GET['igreja_id'])) {
    header("Location: editar_igreja.php?id=" . $_GET['igreja_id']);
} else {
    echo "<p style='color: red; text-align: center;'>ID da igreja não informado para redirecionamento.</p>";
}
exit;

?>