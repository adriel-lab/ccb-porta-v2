<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Função para salvar ou atualizar uma restrição
if (isset($_POST['salvar'])) {
    $id = $_POST['id'];
    $igreja_id = $_POST['igreja_id'];
    $dia_semana = $_POST['dia_semana'];
    $horario = $_POST['horario'];
    $quantidade_portas = $_POST['quantidade_portas'];

    if ($id) {
        // Atualiza a restrição existente
        $sql = "UPDATE restricoes_portas 
                SET igreja_id = '$igreja_id', dia_semana = '$dia_semana', horario = '$horario', quantidade_portas = '$quantidade_portas' 
                WHERE id = '$id'";
    } else {
        // Insere uma nova restrição
        $sql = "INSERT INTO restricoes_portas (igreja_id, dia_semana, horario, quantidade_portas) 
                VALUES ('$igreja_id', '$dia_semana', '$horario', '$quantidade_portas')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Restrição salva com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao salvar restrição: " . $conn->error . "');</script>";
    }
}

// Função para excluir uma restrição
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "DELETE FROM restricoes_portas WHERE id = '$id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Restrição excluída com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao excluir restrição: " . $conn->error . "');</script>";
    }
}

// Busca todas as restrições cadastradas
$sql = "SELECT * FROM restricoes_portas";
$result = $conn->query($sql);
$restricoes = $result->fetch_all(MYSQLI_ASSOC);

// Verifica se estamos editando uma restrição
$editar_id = isset($_GET['editar']) ? $_GET['editar'] : null;
$restricao_editar = null;

if ($editar_id) {
    foreach ($restricoes as $restricao) {
        if ($restricao['id'] == $editar_id) {
            $restricao_editar = $restricao;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Restrições de Portas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #67458b;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        button {
            background-color: #67458b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        button:hover {
            background-color: #9362C6;
        }

        a {
            color: #9362C6;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .submenu {
            margin-left: 20px;
            font-size: 14px;
            color: #555;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .form-container input,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            width: auto;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Gerenciar Restrições de Portas</h1>

    <!-- Formulário para adicionar/editar restrição -->
    <div class="form-container">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $restricao_editar ? $restricao_editar['id'] : ''; ?>">
            <label for="igreja_id">ID da Igreja:</label>
            <input type="number" name="igreja_id" value="<?php echo $restricao_editar ? $restricao_editar['igreja_id'] : ''; ?>" required>
            <label for="dia_semana">Dia da Semana:</label>
            <select name="dia_semana" required>
                <option value="domingo" <?php echo ($restricao_editar && $restricao_editar['dia_semana'] == 'domingo') ? 'selected' : ''; ?>>Domingo</option>
                <option value="segunda" <?php echo ($restricao_editar && $restricao_editar['dia_semana'] == 'segunda') ? 'selected' : ''; ?>>Segunda</option>
                <option value="terca" <?php echo ($restricao_editar && $restricao_editar['dia_semana'] == 'terca') ? 'selected' : ''; ?>>Terça</option>
                <option value="quarta" <?php echo ($restricao_editar && $restricao_editar['dia_semana'] == 'quarta') ? 'selected' : ''; ?>>Quarta</option>
                <option value="quinta" <?php echo ($restricao_editar && $restricao_editar['dia_semana'] == 'quinta') ? 'selected' : ''; ?>>Quinta</option>
                <option value="sexta" <?php echo ($restricao_editar && $restricao_editar['dia_semana'] == 'sexta') ? 'selected' : ''; ?>>Sexta</option>
                <option value="sabado" <?php echo ($restricao_editar && $restricao_editar['dia_semana'] == 'sabado') ? 'selected' : ''; ?>>Sábado</option>
            </select>
            <label for="horario">Horário:</label>
            <select name="horario" required>
                <option value="manha" <?php echo ($restricao_editar && $restricao_editar['horario'] == 'manha') ? 'selected' : ''; ?>>Manhã</option>
                <option value="tarde" <?php echo ($restricao_editar && $restricao_editar['horario'] == 'tarde') ? 'selected' : ''; ?>>Tarde</option>
                <option value="noite" <?php echo ($restricao_editar && $restricao_editar['horario'] == 'noite') ? 'selected' : ''; ?>>Noite</option>
            </select>
            <label for="quantidade_portas">Quantidade de Portas:</label>
            <input type="number" name="quantidade_portas" value="<?php echo $restricao_editar ? $restricao_editar['quantidade_portas'] : ''; ?>" required>
            <button type="submit" name="salvar">Salvar</button>
        </form>
    </div>

    <!-- Tabela de restrições -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID da Igreja</th>
                <th>Dia da Semana</th>
                <th>Horário</th>
                <th>Quantidade de Portas</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($restricoes as $restricao): ?>
                <tr>
                    <td><?php echo $restricao['id']; ?></td>
                    <td><?php echo $restricao['igreja_id']; ?></td>
                    <td><?php echo $restricao['dia_semana']; ?></td>
                    <td><?php echo $restricao['horario']; ?></td>
                    <td><?php echo $restricao['quantidade_portas']; ?></td>
                    <td>
                        <a href="?editar=<?php echo $restricao['id']; ?>">Editar</a> |
                        <a href="?excluir=<?php echo $restricao['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta restrição?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>