<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Busca todas as igrejas cadastradas
$sql_igrejas = "SELECT id, nome FROM igreja";
$result_igrejas = $conn->query($sql_igrejas);

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $igreja_id = $_POST['igreja_id'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $genero = $_POST['genero'];

    // Validação das datas
    if (strtotime($data_fim) < strtotime($data_inicio)) {
        echo "<p style='color: red; text-align: center;'>A data final deve ser maior que a data inicial.</p>";
    } else {
        // Redireciona para a página de exibição do calendário
        header("Location: exibir_calendario.php?igreja_id=$igreja_id&data_inicio=$data_inicio&data_fim=$data_fim&genero=$genero");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Calendário de Escala</title>
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

        /* Estilos para o formulário */
        .form-calendario {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-calendario input[type="date"],
        .form-calendario select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-calendario input[type="date"]:focus,
        .form-calendario select:focus {
            border-color: #67458b;
            outline: none;
        }
    </style>
</head>
<body>
    <h1>Gerar Calendário de Escala</h1>

    <div class="form-calendario">
        <form method="post">
            <label for="igreja_id">Igreja:</label>
            <select name="igreja_id" required>
                <?php while ($row = $result_igrejas->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label for="data_inicio">Data de Início:</label>
            <input type="date" name="data_inicio" required><br><br>

            <label for="data_fim">Data de Término:</label>
            <input type="date" name="data_fim" required><br><br>

            <label for="genero">Gênero dos Porteiros:</label>
            <select name="genero" required>
                <option value="homem">Homens</option>
                <option value="mulher">Mulheres</option>
            </select><br><br>

            <button type="submit">Gerar Calendário</button>
            <br>
            <center><a href="index.php">[Back]</a></center>
        </form>
    </div>
</body>
</html>