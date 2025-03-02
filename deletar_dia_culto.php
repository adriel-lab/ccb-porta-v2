<?php
include 'conexao.php';

if (!isset($_GET['id'])) {
    echo "<p style='color: red; text-align: center;'>ID do dia de culto não informado.</p>";
    exit;
}

$dia_id = $_GET['id'];

$sql = "DELETE FROM dias_culto WHERE id = $dia_id";
if ($conn->query($sql) === TRUE) {
    echo "<p style='color: green; text-align: center;'>Dia de culto deletado com sucesso!</p>";
} else {
    echo "<p style='color: red; text-align: center;'>Erro ao deletar: " . $conn->error . "</p>";
}

$conn->close();
header("Location: editar_igreja.php?id=" . $_GET['igreja_id']); // Redireciona de volta para a página de edição
?>