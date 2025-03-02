<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Busca todas as igrejas cadastradas
$sql_igrejas = "SELECT id, nome, endereco, telefone FROM igreja";
$result_igrejas = $conn->query($sql_igrejas);

// Função para buscar dias de culto de uma igreja
function getDiasCulto($conn, $igreja_id) {
    $sql = "SELECT dia_semana, horario FROM dias_culto WHERE igreja_id = $igreja_id";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Função para buscar portas de uma igreja
function getPortas($conn, $igreja_id) {
    $sql = "SELECT quantidade FROM portas WHERE igreja_id = $igreja_id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Função para buscar porteiros de uma igreja
function getPorteiros($conn, $igreja_id) {
    $sql = "SELECT nome, telefone, genero FROM porteiros WHERE igreja_id = $igreja_id";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Igrejas</title>
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

        /* Estilos para os cards */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(33.333% - 40px); /* 3 cards por linha */
            box-sizing: border-box;
        }

        .card h3 {
            margin-top: 0;
            color: #67458b;
        }

        .card p {
            margin: 5px 0;
            color: #555;
        }

        .card table {
            width: 100%;
            margin: 10px 0;
        }

        .card table th {
            background-color: #67458b;
            color: white;
        }

        .card table td {
            padding: 8px;
        }

        .card table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .card table tr:hover {
            background-color: #ddd;
        }

        /* Estilos para o link de edição */
        .link-editar {
            color: #67458b;
            font-weight: bold;
        }

        .link-editar:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Gestão de Igrejas</h1>

    <!-- Links de navegação -->
    <div class="nav-links">
        <a href="cadastro_igreja.php">[Cadastro de Igreja]</a>
        <a href="cadastro_dias_culto.php">[Cadastro de Dias de Culto]</a>
        <a href="cadastro_portas.php">[Cadastro de Portas]</a>
        <a href="cadastro_porteiros.php">[Cadastro de Porteiros]</a>
        <a href="gerar_calendario.php">[Gerar Calendário de Escala]</a>
        <a href="restricoes_portas.php">[Restrições Portas]</a>
        <a href="restricao_porteiros.php">[Restrições Porteiros]</a>
    </div>

    <!-- Cards das igrejas cadastradas -->
    <div class="card-container">
        <?php
        if ($result_igrejas->num_rows > 0) {
            while ($row = $result_igrejas->fetch_assoc()) {
                $igreja_id = $row['id'];
                $dias_culto = getDiasCulto($conn, $igreja_id);
                $portas = getPortas($conn, $igreja_id);
                $porteiros = getPorteiros($conn, $igreja_id);

                echo "
                <div class='card'>
                    <h3>{$row['nome']}</h3>
                    <p><strong>Endereço:</strong> {$row['endereco']}</p>
                    <p><strong>Telefone:</strong> {$row['telefone']}</p>

                    <!-- Tabela de Dias de Culto -->
                    <h4>Dias de Culto</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Dia</th>
                                <th>Horário</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($dias_culto as $dia) {
                    echo "
                            <tr>
                                <td>{$dia['dia_semana']}</td>
                                <td>{$dia['horario']}</td>
                            </tr>";
                }
                echo "
                        </tbody>
                    </table>

                    <!-- Tabela de Portas -->
                    <h4>Portas</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{$portas['quantidade']}</td>
                            </tr>
                        </tbody>
                    </table>";
             
                echo "
                        </tbody>
                    </table>

                    <!-- Link para editar -->
                    <a class='link-editar' href='editar_igreja.php?id={$row['id']}'>Editar Igreja</a>
                </div>";
            }
        } else {
            echo "<p>Nenhuma igreja cadastrada.</p>";
        }
        ?>
    </div>
</body>
</html>