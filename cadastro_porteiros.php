<?php
include 'conexao.php';

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $igreja_id = $_POST['igreja_id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $genero = $_POST['genero'];

    $sql = "INSERT INTO porteiros (igreja_id, nome, telefone, genero) VALUES ('$igreja_id', '$nome', '$telefone', '$genero')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green; text-align: center;'>Porteiro cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Erro ao cadastrar: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Porteiros</title>
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
            width: 50%; /* Reduzi a largura da tabela para não ficar muito grande */
            border-collapse: collapse;
            margin: 20px auto; /* Centraliza a tabela */
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

        /* Adaptação para o formulário */
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Garante que o padding não aumente a largura */
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus {
            border-color: #67458b;
            outline: none;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Porteiros</h1>
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
                <th>Nome</th>
                <td><input type="text" name="nome" required></td>
            </tr>
            <tr>
                <th>Telefone</th>
                <td><input type="text" name="telefone"></td>
            </tr>
            <tr>
                <th>Gênero</th>
                <td>
                    <select name="genero" required>
                        <option value="homem">Homem</option>
                        <option value="mulher">Mulher</option>
                    </select>
                </td>
            </tr>
        </table>
        <button type="submit">Cadastrar Porteiro</button>
        <br>
        <center><a href="index.php">[Back]</a></center>
    </form>
</body>
</html>