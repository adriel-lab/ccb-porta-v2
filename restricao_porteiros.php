<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Função para salvar ou atualizar uma restrição de porteiro
if (isset($_POST['salvar'])) {
    $id = $_POST['id'];
    $porteiro_id = $_POST['porteiro_id'];
    $dia_semana = $_POST['dia_semana'];
    $horario = $_POST['horario'];

    if ($id) {
        // Atualiza a restrição existente
        $sql = "UPDATE restricoes_porteiros 
                SET porteiro_id = '$porteiro_id', dia_semana = '$dia_semana', horario = '$horario' 
                WHERE id = '$id'";
    } else {
        // Insere uma nova restrição
        $sql = "INSERT INTO restricoes_porteiros (porteiro_id, dia_semana, horario) 
                VALUES ('$porteiro_id', '$dia_semana', '$horario')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Restrição salva com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao salvar restrição: " . $conn->error . "');</script>";
    }
}

// Função para excluir uma restrição de porteiro
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "DELETE FROM restricoes_porteiros WHERE id = '$id'";
    if ($conn->query($sql)) {
        echo "<script>alert('Restrição excluída com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao excluir restrição: " . $conn->error . "');</script>";
    }
}

// Busca todas as igrejas para o formulário
$sql_igrejas = "SELECT id, nome FROM igreja";
$result_igrejas = $conn->query($sql_igrejas);
$igrejas = $result_igrejas->fetch_all(MYSQLI_ASSOC);

// Verifica se uma igreja foi selecionada
$igreja_id = isset($_GET['igreja_id']) ? $_GET['igreja_id'] : null;

// Busca todas as restrições de porteiros cadastradas
$sql = "SELECT rp.id, rp.porteiro_id, p.nome AS porteiro_nome, rp.dia_semana, rp.horario 
        FROM restricoes_porteiros rp
        JOIN porteiros p ON rp.porteiro_id = p.id
        WHERE p.igreja_id = " . ($igreja_id ? $igreja_id : 0);
$result = $conn->query($sql);
$restricoes = $result->fetch_all(MYSQLI_ASSOC);

// Busca todos os porteiros para o formulário, filtrados por igreja
$sql_porteiros = "SELECT id, nome FROM porteiros" . ($igreja_id ? " WHERE igreja_id = $igreja_id" : "");
$result_porteiros = $conn->query($sql_porteiros);
$porteiros = $result_porteiros->fetch_all(MYSQLI_ASSOC);

// Verifica se estamos editando uma restrição
$editar_id = isset($_GET['editar']) ? $_GET['editar'] : null;
$restricao_editar = null;

if ($editar_id) {
    // Busca a restrição específica para edição
    $sql_editar = "SELECT rp.id, rp.porteiro_id, rp.dia_semana, rp.horario 
                   FROM restricoes_porteiros rp
                   WHERE rp.id = '$editar_id'";
    $result_editar = $conn->query($sql_editar);
    if ($result_editar && $result_editar->num_rows > 0) {
        $restricao_editar = $result_editar->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Restrições de Porteiros</title>
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
    <h1>Gerenciar Restrições de Porteiros</h1>

    <!-- Formulário para selecionar a igreja -->
    <div class="form-container">
        <form method="GET">
            <label for="igreja_id">Selecione a Igreja:</label>
            <select name="igreja_id" onchange="this.form.submit()" required>
                <option value="">Selecione uma igreja</option>
                <?php foreach ($igrejas as $igreja): ?>
                    <option value="<?php echo $igreja['id']; ?>" <?php echo ($igreja_id == $igreja['id']) ? 'selected' : ''; ?>>
                        <?php echo $igreja['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if ($igreja_id): ?>
        <!-- Formulário para adicionar/editar restrição -->
        <div class="form-container">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $restricao_editar ? $restricao_editar['id'] : ''; ?>">
                <label for="porteiro_id">Porteiro:</label>
                <select name="porteiro_id" required>
                    <?php foreach ($porteiros as $porteiro): ?>
                        <option value="<?php echo $porteiro['id']; ?>" <?php echo ($restricao_editar && $restricao_editar['porteiro_id'] == $porteiro['id']) ? 'selected' : ''; ?>>
                            <?php echo $porteiro['nome']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
                <button type="submit" name="salvar">Salvar</button>
            </form>
        </div>

        <!-- Tabela de restrições -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Porteiro</th>
                    <th>Dia da Semana</th>
                    <th>Horário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($restricoes as $restricao): ?>
                    <tr>
                        <td><?php echo $restricao['id']; ?></td>
                        <td><?php echo $restricao['porteiro_nome']; ?></td>
                        <td><?php echo $restricao['dia_semana']; ?></td>
                        <td><?php echo $restricao['horario']; ?></td>
                        <td>
                            <a href="?igreja_id=<?php echo $igreja_id; ?>&editar=<?php echo $restricao['id']; ?>">Editar</a> |
                            <a href="?igreja_id=<?php echo $igreja_id; ?>&excluir=<?php echo $restricao['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta restrição?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>