<<<<<<< HEAD
<?php
include 'conexao.php';

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $igreja_id = $_POST['igreja_id'];
    $dia_semana = $_POST['dia_semana'];
    $horario = $_POST['horario'];

    $sql = "INSERT INTO dias_culto (igreja_id, dia_semana, horario) VALUES ('$igreja_id', '$dia_semana', '$horario')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green; text-align: center;'>Dia de culto cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Erro ao cadastrar: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Dias de Culto</title>
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
    </style>
</head>
<body>
    <h1>Cadastro de Dias de Culto</h1>
    <form method="post">
        <table>
            <tr>
                <th>Igreja</th>
                <td>
                    <select name="igreja_id" required>
                        <?php
                        $sql = "SELECT id, nome FROM igreja";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".$row['nome']."</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Dia da Semana</th>
                <td>
                    <select name="dia_semana" required>
                        <option value="segunda">Segunda-feira</option>
                        <option value="terca">Terça-feira</option>
                        <option value="quarta">Quarta-feira</option>
                        <option value="quinta">Quinta-feira</option>
                        <option value="sexta">Sexta-feira</option>
                        <option value="sabado">Sábado</option>
                        <option value="domingo">Domingo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Horário</th>
                <td>
                    <select name="horario" required>
                        <option value="manha">Manhã</option>
                        <option value="tarde">Tarde</option>
                        <option value="noite">Noite</option>
                    </select>
                </td>
            </tr>
        </table>
        <button type="submit">Cadastrar Dia de Culto</button>
        <br>
        <center><a href="index.php">[Back]</a></center>
    </form>
</body>

</html>