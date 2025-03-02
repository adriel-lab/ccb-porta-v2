<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO igreja (nome, endereco, telefone) VALUES ('$nome', '$endereco', '$telefone')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green; text-align: center;'>Igreja cadastrada com sucesso!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Erro ao cadastrar: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Igreja</title>
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
            width: 50%;
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
    <h1>Cadastro de Igreja</h1>
    <center>
        <form method="post">
            <table>
                <tr>
                    <th>Nome</th>
                    <td><input type="text" name="nome" required></td>
                </tr>
                <tr>
                    <th>Endereço</th>
                    <td><input type="text" name="endereco" required></td>
                </tr>
                <tr>
                    <th>Telefone</th>
                    <td><input type="text" name="telefone"></td>
                </tr>
            </table>
            <button type="submit">Cadastrar Igreja</button>
        </form>
    </center>
</body>

</html>