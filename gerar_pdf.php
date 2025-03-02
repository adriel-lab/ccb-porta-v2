<?php
// Inclui o autoload do Composer para carregar o TCPDF
require_once('vendor/autoload.php');

// Recupera os parâmetros da URL
$igreja_id = $_GET['igreja_id'];
$data_inicio = $_GET['data_inicio'];
$data_fim = $_GET['data_fim'];
$genero = $_GET['genero'];

// Inclui a lógica de geração do calendário
include 'conexao.php'; // Conexão com o banco de dados

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
}

// Busca os dias de culto da igreja selecionada
$sql_dias_culto = "SELECT dia_semana, horario FROM dias_culto WHERE igreja_id = $igreja_id";
$result_dias_culto = $conn->query($sql_dias_culto);
$dias_culto = $result_dias_culto->fetch_all(MYSQLI_ASSOC);

// Função para converter dia da semana em número
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

// Função para gerar a escala mensal
function gerarEscalaMensal($porteiros, $quantidade_portas, $mes, $ano, $dias_culto) {
    $escala = [];
    shuffle($porteiros);
    $porteiros_disponiveis = $porteiros;

    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
        $dia_semana_numero = date('w', strtotime("$ano-$mes-$dia"));
        foreach ($dias_culto as $dia_culto) {
            $dia_culto_numero = diaSemanaParaNumero($dia_culto['dia_semana']);
            if ($dia_semana_numero == $dia_culto_numero) {
                $escala_dia = [];
                for ($i = 0; $i < $quantidade_portas; $i++) {
                    if (empty($porteiros_disponiveis)) {
                        $porteiros_disponiveis = $porteiros;
                        shuffle($porteiros_disponiveis);
                    }
                    $porteiro = array_shift($porteiros_disponiveis);
                    $escala_dia[] = "• " . $porteiro['nome']; // Adiciona o símbolo ●
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

// Cria um novo PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Define as informações do documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Escala de Porteiros');
$pdf->SetTitle('Calendário de Escala - ' . $nome_igreja);
$pdf->SetSubject('Calendário de Escala');
$pdf->SetKeywords('TCPDF, PDF, escala, porteiros, igreja');

// Define margens
$pdf->SetMargins(10, 10, 10); // Margens menores para aproveitar melhor o espaço
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

$pdf->SetCellHeightRatio(0.9); // Ajuste conforme necessário o tamanho da celulas do calendario

// Adiciona uma página
$pdf->AddPage();

// Inclui o CSS personalizado
$css = '
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Fonte menor */
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 14px; /* Título um pouco maior */
        }
        h2 {
            color: #67458b;
            font-size: 12px; /* Subtítulo menor */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            table-layout: fixed; /* Tabela com tamanho fixo */
        }
        th, td {
            width: 14.28%; /* Divide a tabela em 7 colunas iguais (100% / 7) */
            height: 30px; /* Altura fixa para as células */
            padding: 0px; /* Espaçamento interno reduzido */
            text-align: left;
            border: 1px solid #ddd;
            font-size: 8px; /* Fonte menor para tabelas */
            overflow: hidden; /* Esconde o conteúdo que ultrapassar */
            white-space: nowrap; /* Impede a quebra de linha */
            text-overflow: ellipsis; /* Adiciona "..." se o texto for muito longo */
        }
        th {
            background-color: #67458b;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .dia-numero {
            font-weight: bold;
            font-size: 8px; /* Fonte menor para o número do dia */
            margin-bottom: 1px;
        }
        .horario {
            font-size: 12px; /* Fonte menor para o horário */
            color: #67458b;
        }
        .porteiros {
            font-size: 10px; /* Fonte menor para os porteiros */
            color: #555;
        }
    </style>
';

// Define o conteúdo do PDF
$html = $css . '<h1>Calendário de Escala - ' . $nome_igreja . '</h1>';

// Adiciona o calendário ao PDF
foreach ($calendario as $mes_ano => $escala_mes) {
    list($mes, $ano) = explode('-', $mes_ano);
    $nome_mes = DateTime::createFromFormat('!m', $mes)->format('F');
    $nome_mes_pt_br = $meses_pt_br[$nome_mes];

    $html .= '<h2>' . $nome_mes_pt_br . ' ' . $ano . '</h2>';
    $html .= '<table border="1" cellpadding="5">';
    $html .= '<thead><tr><th>Dom</th><th>Seg</th><th>Ter</th><th>Qua</th><th>Qui</th><th>Sex</th><th>Sáb</th></tr></thead>';
    $html .= '<tbody>';

    $primeiro_dia = date('w', strtotime("$ano-$mes-01"));
    $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    $dia_atual = 1;

    for ($i = 0; $i < 6; $i++) {
        $html .= '<tr>';
        for ($j = 0; $j < 7; $j++) {
            if (($i === 0 && $j < $primeiro_dia) || $dia_atual > $dias_no_mes) {
                $html .= '<td></td>';
            } else {
                $escala_dia = $escala_mes[$dia_atual] ?? null;
                $html .= '<td>';
                $html .= '<div class="dia-numero">' . $dia_atual . '</div>';
                if ($escala_dia) {
                    foreach ($escala_dia as $culto) {
                        $html .= '<div class="horario">' . ucfirst($culto['horario']) . '</div>';
                        $html .= '<div class="porteiros">' . implode('<br>', $culto['porteiros']) . '</div>';
                    }
                }
                $html .= '</td>';
                $dia_atual++;
            }
        }
        $html .= '</tr>';
        if ($dia_atual > $dias_no_mes) {
            break;
        }
    }
    $html .= '</tbody></table>';

    // Adiciona uma nova página para o próximo mês
    if ($mes_ano !== array_key_last($calendario)) {
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->AddPage(); // Nova página para o próximo mês
        $html = $css . '<h1>Calendário de Escala - ' . $nome_igreja . '</h1>'; // Reinicia o conteúdo
    }
}

// Adiciona a tabela de telefones dos porteiros
$html .= '<h2>Telefones dos Porteiros</h2>';
$html .= '<table border="1" cellpadding="5">';
$html .= '<thead><tr><th>Nome</th><th>Telefone</th></tr></thead>';
$html .= '<tbody>';
foreach ($porteiros as $porteiro) {
    $html .= '<tr>';
    $html .= '<td>' . $porteiro['nome'] . '</td>';
    $html .= '<td>' . $porteiro['telefone'] . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

// Escreve o conteúdo HTML no PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Gera o PDF e o exibe no navegador
$pdf->Output('calendario_escala.pdf', 'I');