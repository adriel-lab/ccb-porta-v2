<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Recupera os parâmetros da URL
$igreja_id = $_GET['igreja_id'];
$data_inicio = $_GET['data_inicio'];
$data_fim = $_GET['data_fim'];
$genero = $_GET['genero'];

// Busca o nome da igreja
$sql_igreja = "SELECT nome FROM igreja WHERE id = $igreja_id";
$result_igreja = $conn->query($sql_igreja);
$igreja = $result_igreja->fetch_assoc();
$nome_igreja = $igreja['nome'];

// Busca os porteiros do gênero selecionado para a igreja escolhida
$sql_porteiros = "SELECT id, nome, telefone FROM porteiros WHERE igreja_id = $igreja_id AND genero = '$genero'";
$result_porteiros = $conn->query($sql_porteiros);
$porteiros = $result_porteiros->fetch_all(MYSQLI_ASSOC);

// Busca o número de portas da igreja selecionada
$sql_portas = "SELECT quantidade FROM portas WHERE igreja_id = $igreja_id";
$result_portas = $conn->query($sql_portas);

if ($result_portas && $result_portas->num_rows > 0) {
    $portas = $result_portas->fetch_assoc();
    $quantidade_portas = $portas['quantidade'];
} else {
    // Se não houver portas cadastradas, define um valor padrão (ex: 1)
    $quantidade_portas = 1;
    echo "<p style='color: orange; text-align: center;'>Atenção: Nenhuma porta cadastrada para esta igreja. Usando valor padrão (1).</p>";
}

// Busca os dias de culto da igreja selecionada
$sql_dias_culto = "SELECT dia_semana, horario FROM dias_culto WHERE igreja_id = $igreja_id";
$result_dias_culto = $conn->query($sql_dias_culto);
$dias_culto = $result_dias_culto->fetch_all(MYSQLI_ASSOC);

// Função para converter dia da semana em número (0 = domingo, 1 = segunda, ..., 6 = sábado)
function diaSemanaParaNumero($dia_semana) {
    $dias = [
        'domingo' => 0,
        'segunda' => 1,
        'terca'   => 2,
        'quarta'  => 3,
        'quinta'  => 4,
        'sexta'   => 5,
        'sabado'  => 6,
    ];
    return $dias[$dia_semana];
}

// Função para gerar a escala mensal com base nos dias de culto
function gerarEscalaMensal($porteiros, $quantidade_portas, $mes, $ano, $dias_culto) {
    $escala = [];

    // Embaralha os porteiros para garantir aleatoriedade
    shuffle($porteiros);

    // Cria uma cópia da lista de porteiros para manipulação
    $porteiros_disponiveis = $porteiros;

    // Itera sobre os dias do mês
    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
        // Obtém o dia da semana (0 = domingo, 1 = segunda, ..., 6 = sábado)
        $dia_semana_numero = date('w', strtotime("$ano-$mes-$dia"));

        // Verifica se há culto nesse dia da semana
        foreach ($dias_culto as $dia_culto) {
            $dia_culto_numero = diaSemanaParaNumero($dia_culto['dia_semana']);

            if ($dia_semana_numero == $dia_culto_numero) {
                // Gera a escala para o dia de culto
                $escala_dia = [];
                for ($i = 0; $i < $quantidade_portas; $i++) {
                    // Se não houver mais porteiros disponíveis, reinicia a lista
                    if (empty($porteiros_disponiveis)) {
                        $porteiros_disponiveis = $porteiros;
                        shuffle($porteiros_disponiveis); // Embaralha novamente
                    }

                    // Remove o primeiro porteiro da lista e adiciona à escala do dia
                    $porteiro = array_shift($porteiros_disponiveis);
                    $escala_dia[] = "● " . $porteiro['nome']; // Adiciona a bolinha antes do nome
                }
                $escala[$dia][] = [
                    'horario' => $dia_culto['horario'],
                    'porteiros' => $escala_dia,
                ];
            }
        }
    }

    return $escala;
}

// Gera o calendário para cada mês no intervalo
$calendario = [];
$data_atual = new DateTime($data_inicio);
$data_final = new DateTime($data_fim);

while ($data_atual <= $data_final) {
    $mes = $data_atual->format('m');
    $ano = $data_atual->format('Y');
    $calendario["$mes-$ano"] = gerarEscalaMensal($porteiros, $quantidade_portas, $mes, $ano, $dias_culto);
    $data_atual->modify('+1 month');
}

// Tradução dos meses para PT-BR
$meses_pt_br = [
    'January'  => 'Janeiro',
    'February' => 'Fevereiro',
    'March'    => 'Março',
    'April'    => 'Abril',
    'May'      => 'Maio',
    'June'     => 'Junho',
    'July'     => 'Julho',
    'August'   => 'Agosto',
    'September'=> 'Setembro',
    'October'  => 'Outubro',
    'November' => 'Novembro',
    'December' => 'Dezembro',
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Escala</title>
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

        /* Estilos para o calendário */
        .calendario-mes {
            margin-bottom: 40px;
        }

        .calendario-mes h2 {
            color: #67458b;
        }

        .calendario-table {
            width: 100%;
            table-layout: fixed;
        }

        .calendario-table td {
            height: 120px; /* Aumenta a altura para acomodar múltiplos horários */
            vertical-align: top;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .dia-numero {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .horario {
            font-size: 14px;
            color: #67458b;
            margin-bottom: 5px;
        }

        .porteiros {
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Calendário de Escala - <?php echo $nome_igreja; ?></h1>

    <!-- Link para gerar PDF -->
    <a href="gerar_pdf.php?igreja_id=<?php echo $igreja_id; ?>&data_inicio=<?php echo $data_inicio; ?>&data_fim=<?php echo $data_fim; ?>&genero=<?php echo $genero; ?>" target="_blank">
        <button>Gerar PDF</button>
    </a>

    <?php foreach ($calendario as $mes_ano => $escala_mes): ?>
        <?php
        list($mes, $ano) = explode('-', $mes_ano);
        $nome_mes = DateTime::createFromFormat('!m', $mes)->format('F'); // Nome do mês em inglês
        $nome_mes_pt_br = $meses_pt_br[$nome_mes]; // Traduz para PT-BR
        ?>
        <div class="calendario-mes">
            <h2><?php echo "$nome_mes_pt_br $ano"; ?></h2>
            <table class="calendario-table">
                <thead>
                    <tr>
                        <th>Dom</th>
                        <th>Seg</th>
                        <th>Ter</th>
                        <th>Qua</th>
                        <th>Qui</th>
                        <th>Sex</th>
                        <th>Sáb</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Obtém o primeiro dia do mês e o número de dias no mês
                    $primeiro_dia = date('w', strtotime("$ano-$mes-01")); // Dia da semana do primeiro dia do mês
                    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

                    // Inicializa o contador de dias
                    $dia_atual = 1;

                    // Loop para criar as linhas do calendário
                    for ($i = 0; $i < 6; $i++) { // Máximo de 6 semanas
                        echo "<tr>";
                        for ($j = 0; $j < 7; $j++) { // 7 dias na semana
                            if (($i === 0 && $j < $primeiro_dia) || $dia_atual > $dias_no_mes) {
                                // Célula vazia antes do primeiro dia do mês ou após o último dia
                                echo "<td></td>";
                            } else {
                                // Célula com o dia e a escala
                                $escala_dia = $escala_mes[$dia_atual] ?? null;
                                echo "<td>";
                                echo "<div class='dia-numero'>$dia_atual</div>";
                                if ($escala_dia) {
                                    foreach ($escala_dia as $culto) {
                                        echo "<div class='horario'>" . ucfirst($culto['horario']) . "</div>";
                                        echo "<div class='porteiros'>" . implode('<br>', $culto['porteiros']) . "</div>";
                                    }
                                }
                                echo "</td>";
                                $dia_atual++;
                            }
                        }
                        echo "</tr>";
                        if ($dia_atual > $dias_no_mes) {
                            break; // Sai do loop após o último dia do mês
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>

    <!-- Tabela com os telefones dos porteiros -->
    <h2>Telefones dos Porteiros</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($porteiros as $porteiro): ?>
                <tr>
                    <td><?php echo $porteiro['nome']; ?></td>
                    <td><?php echo $porteiro['telefone']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php">Voltar à Página Inicial</a>
</body>
</html>