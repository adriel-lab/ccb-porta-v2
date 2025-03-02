
<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID da igreja foi passado na URL
if (!isset($_GET['id'])) {
    echo "<p style='color: red; text-align: center;'>ID da igreja não informado.</p>";
    exit;
}

$igreja_id = $_GET['id'];

// Busca os dados da igreja
$sql_igreja = "SELECT id, nome, endereco, telefone FROM igreja WHERE id = $igreja_id";
$result_igreja = $conn->query($sql_igreja);

if ($result_igreja->num_rows === 0) {
    echo "<p style='color: red; text-align: center;'>Igreja não encontrada.</p>";
    exit;
}

$igreja = $result_igreja->fetch_assoc();

// Busca os dias de culto da igreja
$sql_dias_culto = "SELECT id, dia_semana, horario FROM dias_culto WHERE igreja_id = $igreja_id";
$result_dias_culto = $conn->query($sql_dias_culto);
$dias_culto = $result_dias_culto->fetch_all(MYSQLI_ASSOC);

// Busca as portas da igreja
$sql_portas = "SELECT id, quantidade FROM portas WHERE igreja_id = $igreja_id";
$result_portas = $conn->query($sql_portas);
$portas = $result_portas->fetch_assoc();

// Busca os porteiros da igreja
$sql_porteiros = "SELECT id, nome, telefone, genero FROM porteiros WHERE igreja_id = $igreja_id";
$result_porteiros = $conn->query($sql_porteiros);
$porteiros = $result_porteiros->fetch_all(MYSQLI_ASSOC);

// Processamento do formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualiza os dados da igreja
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];

    $sql = "UPDATE igreja SET nome = '$nome', endereco = '$endereco', telefone = '$telefone' WHERE id = $igreja_id";
    $conn->query($sql);

    // Atualiza dias de culto
    if (isset($_POST['dias_culto'])) {
        foreach ($_POST['dias_culto'] as $dia_id => $dia) {
            $dia_semana = $dia['dia_semana'];
            $horario = $dia['horario'];
            $sql = "UPDATE dias_culto SET dia_semana = '$dia_semana', horario = '$horario' WHERE id = $dia_id";
            $conn->query($sql);
        }
    }

    // Atualiza portas
    if (isset($_POST['portas'])) {
        $quantidade = $_POST['portas']['quantidade'];
        $sql = "UPDATE portas SET quantidade = $quantidade WHERE id = {$portas['id']}";
        $conn->query($sql);
    }

    // Atualiza porteiros
    if (isset($_POST['porteiros'])) {
        foreach ($_POST['porteiros'] as $porteiro_id => $porteiro) {
            $nome = $porteiro['nome'];
            $telefone = $porteiro['telefone'];
            $genero = $porteiro['genero'];
            $sql = "UPDATE porteiros SET nome = '$nome', telefone = '$telefone', genero = '$genero' WHERE id = $porteiro_id";
            $conn->query($sql);
        }
    }

    echo "<p style='color: green; text-align: center;'>Dados atualizados com sucesso!</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Igreja</title>
    <style>
        /* CSS padrão fornecido */
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

        /* Estilos para o formulário de edição */
        .form-editar {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-editar input[type="text"],
        .form-editar input[type="number"],
        .form-editar select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-editar input[type="text"]:focus,
        .form-editar input[type="number"]:focus,
        .form-editar select:focus {
            border-color: #67458b;
            outline: none;
        }

        .form-editar .remover {
            color: red;
            cursor: pointer;
        }

        .form-editar .remover:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Editar Igreja</h1>

    <div class="form-editar">
        <form method="post">
            <!-- Dados da Igreja -->
            <h2>Dados da Igreja</h2>
            <table>
                <tr>
                    <th>Nome</th>
                    <td><input type="text" name="nome" value="<?php echo $igreja['nome']; ?>"></td>
                </tr>
                <tr>
                    <th>Endereço</th>
                    <td><input type="text" name="endereco" value="<?php echo $igreja['endereco']; ?>"></td>
                </tr>
                <tr>
                    <th>Telefone</th>
                    <td><input type="text" name="telefone" value="<?php echo $igreja['telefone']; ?>"></td>
                </tr>
            </table>

            <!-- Dias de Culto -->
            <h2>Dias de Culto</h2>
            <table>
                <thead>
                    <tr>
                        <th>Dia da Semana</th>
                        <th>Horário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dias_culto as $dia): ?>
                        <tr>
                            <td>
                                <select name="dias_culto[<?php echo $dia['id']; ?>][dia_semana]">
                                    <option value="segunda" <?php echo ($dia['dia_semana'] === 'segunda') ? 'selected' : ''; ?>>Segunda-feira</option>
                                    <option value="terca" <?php echo ($dia['dia_semana'] === 'terca') ? 'selected' : ''; ?>>Terça-feira</option>
                                    <option value="quarta" <?php echo ($dia['dia_semana'] === 'quarta') ? 'selected' : ''; ?>>Quarta-feira</option>
                                    <option value="quinta" <?php echo ($dia['dia_semana'] === 'quinta') ? 'selected' : ''; ?>>Quinta-feira</option>
                                    <option value="sexta" <?php echo ($dia['dia_semana'] === 'sexta') ? 'selected' : ''; ?>>Sexta-feira</option>
                                    <option value="sabado" <?php echo ($dia['dia_semana'] === 'sabado') ? 'selected' : ''; ?>>Sábado</option>
                                    <option value="domingo" <?php echo ($dia['dia_semana'] === 'domingo') ? 'selected' : ''; ?>>Domingo</option>
                                </select>
                            </td>
                            <td>
                                <select name="dias_culto[<?php echo $dia['id']; ?>][horario]">
                                    <option value="manha" <?php echo ($dia['horario'] === 'manha') ? 'selected' : ''; ?>>Manhã</option>
                                    <option value="tarde" <?php echo ($dia['horario'] === 'tarde') ? 'selected' : ''; ?>>Tarde</option>
                                    <option value="noite" <?php echo ($dia['horario'] === 'noite') ? 'selected' : ''; ?>>Noite</option>
                                </select>
                            </td>
                            <td>
                                <a href="deletar_dia_culto.php?id=<?php echo $dia['id']; ?>" class="remover">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Portas -->
            <h2>Portas</h2>
            <table>
                <tr>
                    <th>Quantidade</th>
                    <td><input type="number" name="portas[quantidade]" value="<?php echo $portas['quantidade']; ?>"></td>
                    <td><a href="deletar_portas.php?id=<?php echo $portas['id']; ?>" class="remover">Deletar</a></td>
                </tr>
            </table>

            <!-- Porteiros -->
            <h2>Porteiros</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Gênero</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porteiros as $porteiro): ?>
                        <tr>
                            <td><input type="text" name="porteiros[<?php echo $porteiro['id']; ?>][nome]" value="<?php echo $porteiro['nome']; ?>"></td>
                            <td><input type="text" name="porteiros[<?php echo $porteiro['id']; ?>][telefone]" value="<?php echo $porteiro['telefone']; ?>"></td>
                            <td>
                                <select name="porteiros[<?php echo $porteiro['id']; ?>][genero]">
                                    <option value="homem" <?php echo ($porteiro['genero'] === 'homem') ? 'selected' : ''; ?>>Homem</option>
                                    <option value="mulher" <?php echo ($porteiro['genero'] === 'mulher') ? 'selected' : ''; ?>>Mulher</option>
                                </select>
                            </td>
                            <td>
                                <a href="deletar_porteiro.php?id=<?php echo $porteiro['id']; ?>&igreja_id=<?php echo $igreja_id; ?>" class="remover">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit">Salvar Alterações</button>
            <br>
            <center><a href="index.php">[Back]</a></center>

        </form>
    </div>
</body>

