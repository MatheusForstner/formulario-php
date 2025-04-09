s<?php
$servername = 'seu servidor';
$username = 'seu usuario';
$password = 'seu senha';
$database = 'tabela dados';

header('Content-Type: text/html; charset=UTF-8');

$connectionInfo = array("Database" => $database, "UID" => $username, "PWD" => $password, "CharacterSet" => "UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = sqlsrv_connect($servername, $connectionInfo);
    

    if ($conn === false) {
        die("<script>alert('Erro na conexão com o banco.');</script>");
    }

    function limparNumero($valor) {
        if ($valor === null || $valor === '') return 0;
        return floatval(str_replace(['.', ','], ['', '.'], $valor));
    }


    function limparDocumento($valor) {
        return preg_replace('/\D/', '', $valor);
    }

    $valor_patrimonio = limparNumero($_POST['valor_patrimonio'] ?? '');
    $renda_mensal = limparNumero($_POST['renda_mensal'] ?? '');
    $valor_projeto = intval(limparNumero($_POST['valor_projeto'] ?? ''));
    $parcela = intval($_POST['parcela'] ?? 0);
    $carencia = intval($_POST['carencia'] ?? 0);    
    $tempo_empresa_anos = intval($_POST['tempo_empresa_anos'] ?? 0);
    $tempo_empresa_meses = intval($_POST['tempo_empresa_meses'] ?? 0);

    date_default_timezone_set('America/Sao_Paulo');
    $data_input = date('Y-m-d H:i:s');

    $sql = "INSERT INTO clientes (
        cpf_cnpj, nome, celular, email, rg, dt_nascimento, nacionalidade, genero, estado_civil,
        valor_patrimonio, nome_mae, cep, endereco, numero, bairro, cidade, estado, tipo_imovel,
        natureza_ocupacao, profissao, tempo_empresa_anos, tempo_empresa_meses, renda_mensal, integrador, agente, gerente, valor_projeto, parcela, carencia, data_input
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        limparDocumento($_POST['cpf_cnpj'] ?? ''),
        $_POST['nome'] ?? '',
        limparDocumento($_POST['celular'] ?? ''),
        $_POST['email'] ?? '',
        limparDocumento($_POST['rg'] ?? ''),
        $_POST['dt_nascimento'] ?? '',
        $_POST['nacionalidade'] ?? '',
        $_POST['genero'] ?? '',
        $_POST['estado_civil'] ?? '',
        $valor_patrimonio,
        $_POST['nome_mae'] ?? '',
        limparDocumento($_POST['cep'] ?? ''),
        $_POST['endereco'] ?? '',
        $_POST['numero'] ?? '',
        $_POST['bairro'] ?? '',
        $_POST['cidade'] ?? '',
        $_POST['estado'] ?? '',
        $_POST['tipo_imovel'] ?? '',
        $_POST['natureza_ocupacao'] ?? '',
        $_POST['profissao'] ?? '',
        $tempo_empresa_anos,
        $tempo_empresa_meses,
        $renda_mensal,
        $_POST['integrador'] ?? '',
        $_POST['agente'] ?? '',
        $_POST['gerente'] ?? '',
        $valor_projeto,
        $parcela,
        $carencia,
        $data_input
    ];

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo "<script>alert('Erro ao inserir dados. Verifique o console.');</script>";
        echo "<pre>";
        print_r(sqlsrv_errors());
        echo "</pre>";
    } else {
        sqlsrv_next_result($stmt);
        sqlsrv_fetch($stmt);
        $lastIdQuery = sqlsrv_query($conn, "SELECT SCOPE_IDENTITY() AS last_id");
        $lastId = sqlsrv_fetch_array($lastIdQuery, SQLSRV_FETCH_ASSOC)['last_id'];
        // Opcional: armazenar ou usar esse ID como quiser
        header("Location: " . $_SERVER['PHP_SELF'] . "?sucesso=1");
        exit;
    }

    sqlsrv_close($conn);
}
?>

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <script>
        alert('✅ Dados inseridos com sucesso!');
        setTimeout(function () {
            window.location.href = window.location.pathname;
        }, 1000);
    </script>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="AptosDisplay.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="icon" href="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" sizes="32x32">
    <title>Formulário de Clientes</title>
    <style>
         @font-face {
            font-family: 'Aptos Display';
            src: url('AptosDisplay.woff2') format('woff2'),
                url('AptosDisplay.woff') format('woff');
        }

        * {
            margin: 0;
            padding: 0;
            outline: none;
            box-sizing: border-box;  
        }

        body {
            font-family: 'Aptos Display';
            background-image: linear-gradient(to right, #003676, #00aae3);
        }

        .box{
            display: inline-block;
            margin: 20px;
        }

        .box-text{
            margin: 10px;
            margin-left: 550px;
        }

        .box-text a{
            color: #001d40;
            font-size: 26px;
        }

        .box-imagem{
            margin-left: 400px;
        }

        navbar {
            display: flex;
            align-items: center;
            background-color: #ffffff;
        }

        .hideblk{
            display: none;
        }
        .container {
            display: flex;
            justify-content: center;
            padding: 40px;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 900px;
            box-shadow: 0 0 10px #00000030;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .input-row {
            flex: 1 1 45%; /* ajusta para 2 colunas */
            display: flex;
            flex-direction: column;
        }

        .input-row label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-row input {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

                .input-row input,
        .input-row select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .full {
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        .button {
            background-color: #001d40;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0091E4;
        }
        h1 {
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        a {
            color: white;
            text-decoration: none; 
            margin: 0 15px;
            font-size: 16px;
        }

        a:hover {
            color: #0091E4;
        }


        .select2::-ms-expand {
            display: none;
        }
        .select2::after {
            content: '\25BC';
            position: absolute;
            top: 0;
            right: 0;
            padding: 1em;
            background-color: #34495e;
            transition: .25s all ease;
            pointer-events: none;
        }


        .select2{
            border: 0;
            box-shadow: none;
            padding: 0 1em;
            color: #fff;
            background-color: transparent;
            background-image: none;
            height: 3em;
            border-radius: .25em;
            color: black;
            overflow: hidden;
        }

        footer {
            background-color: #000000;
        }

        .container-footer {
            display: flex;
            justify-content: space-between;
            color: white;
            padding: 10px;
        }

        .input-row {
            display: grid;
            grid-template-columns: 150px 1fr; /* Label e Input lado a lado */
            align-items: center;
            gap: 10px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="preload" href="AptosDisplay.woff2" as="font" type="font/woff2" crossorigin>
</head>
<body>
    <header>
        <navbar>
                <div class="box">
                    <img src="https://www.jng.com.br/Assinaturas/logo_JNG_azul.png" width="100px" class="box-imagem">
                </div>
        </navbar>
    </header>
    <h1>Formulário Cadastro Financiamento</h1>
    <div class="container">
        <form method="POST" id="clienteForm">
            <?php
            $camposSelect = [
                "genero" => ["Masculino", "Feminino", "Outro"],
                "estado_civil" => ["Solteiro(a)", "Casado(a)", "Divorciado(a)", "Viúvo(a)"],
                "nacionalidade" => ["Brasileira", "Estrangeira"],
                "estado" => ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG",
                             "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"]
            ];

            $fields = [
                "cpf_cnpj", "nome", "celular", "email", "rg", "dt_nascimento", "nacionalidade", "genero",
                "estado_civil", "valor_patrimonio", "nome_mae", "cep", "endereco", "numero", "bairro", "cidade",
                "estado", "tipo_imovel", "natureza_ocupacao", "profissao", "renda_mensal", "integrador", "parcela", "carencia", "agente", "gerente", "valor_projeto"
            ];

            $labels = [
                "cpf_cnpj" => "CPF/CNPJ:",
                "nome" => "Nome:",
                "celular" => "Celular:",
                "email" => "E-mail:",
                "rg" => "RG:",
                "dt_nascimento" => "Data de Nascimento:",
                "nacionalidade" => "Nacionalidade:",
                "genero" => "Gênero:",
                "estado_civil" => "Estado Civil:",
                "valor_patrimonio" => "Valor do Patrimônio:",
                "nome_mae" => "Nome da Mãe:",
                "cep" => "CEP:",
                "endereco" => "Endereço:",
                "numero" => "Número:",
                "bairro" => "Bairro:",
                "cidade" => "Cidade:",
                "estado" => "Estado:",
                "tipo_imovel" => "Tipo de Imóvel:",
                "natureza_ocupacao" => "Natureza da Ocupação:",
                "profissao" => "Profissão:",
                "renda_mensal" => "Renda Mensal:",
                "integrador" => "Integrador:",
                "agente" => "Agente:",
                "gerente" => "Gerente:",
                "valor_projeto" => "Valor Projeto:",
                "parcela" => "Parcela:",
                "carencia" => "Carência:"
            ];

            $camposSelect = [
                "genero" => ["MASCULINO", "FEMININO", "OUTROS"],
                "estado_civil" => ["SOLTEIRO", "CASADO", "DIVORCIADO", "VIÚVO"],
                "nacionalidade" => ["BRASILEIRO", "ESTRANGEIRO", "NATURALIZADO"],
                "estado" => ["AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"],
                "tipo_imovel" => ["PROPRIO", "FAMILIAR", "ALUGADO", "PROPRIO", "FINANCIADO", "CEDIDO"],
                "natureza_ocupacao" => ["APOSENTADO", "ASSALARIADO", "AUTONOMO", "EMPRESARIO", "FUNCIONARIO PUBLICO", "LIBERAL"],
                "agente" => ["ANDRIELE REGINA BENETTI", "LAW HWAN HUEI"],
                "gerente" => ["INGRID BRITO BARBOZA", "BRUNA BRASIL DA SILVA", "CARLA CRISTINA FERREIRA ALEDES", "SIMONE MACENO DA SILVA", "ADRIANA PEREIRA DE LIMA", "AILTON LIRA", "CAVALCANTI", "THIAGO SOUZA DOS REIS", "JOSMAR MENESES LIMA", "MANOELA BRITO", "ARTHUR MOREIRA"]
            ];

            $valoresPadrao = [
                'gerente' => 'BRUNA BRASIL DA SILVA',
                'agente' => 'LAW HWAN HUEI'
            ];

            foreach ($fields as $field) {
                echo "<div class='input-row'>";
                echo "<label for='$field'>{$labels[$field]}</label>";

                $isRequired = ($field !== 'integrador') ? "required" : "";
                
                if (array_key_exists($field, $camposSelect)) {
                    echo "<select name='$field' id='$field' $isRequired>";
                    echo "<option value=''>Selecione</option>";

                    foreach ($camposSelect[$field] as $option) {
                        $selected = (isset($valoresPadrao[$field]) && $option === $valoresPadrao[$field]) ? 'selected' : '';
                        echo "<option value='$option' $selected>$option</option>";
                    } 
                    echo "</select>";
                } else {
                    echo "<input type='text' name='$field' id='$field' $isRequired>";
                }
            
                echo "</div>";
            }
            
            // CAMPO ESPECIAL: Tempo na Empresa
            echo "<div class='input-row' style='grid-column: span 2;'>";
            echo "<label for='tempo_empresa_anos'>Tempo na Empresa:</label>";
            echo "<div style='display: flex; gap: 10px; width: 100%;'>";
            echo "<input type='text' id='tempo_empresa_anos' name='tempo_empresa_anos' placeholder='Anos' required style='width: 100%;'>";
            echo "<input type='text' id='tempo_empresa_meses' name='tempo_empresa_meses' placeholder='Meses' required style='width: 100%;'>";
            echo "</div>";
            echo "</div>";
            ?>

            <div class="full">
                <button type="submit" class="button">Enviar</button>
                <button type="reset" class="button">Limpar</button>
            </div>
        </form>
    </div>

    <footer>
        <div class="container-footer">

            <p class="credits-left">
                © 2024 <a href="/home.html">Intranet | JNG</a>
            </p>
            
            <p class="credits-right">
                <span>Desenvolvido por Tecnologia <a href="http://jng.com.br">JNG</a></span>
            </p>
        </div> 
    </footer>

    <script>
        $(document).ready(function () {
            var cpfCnpjField = $('#cpf_cnpj');

            cpfCnpjField.on('input', function () {
                var value = cpfCnpjField.val().replace(/\D/g, '');

                if (value.length >= 11) {
                    cpfCnpjField.mask('00.000.000/0000-00', { reverse: true });
                } else {
                    cpfCnpjField.mask('000.000.000-00', { reverse: true });
                }
            });

            $("#celular").mask("(00) 00000-0000");
            $("#rg").mask("00.000.000-0");
            $("#cep").mask("00000-000");
            $('#renda_mensal').mask('000.000.000,00', {reverse: true});
            $('#valor_patrimonio').mask('000.000.000,00', {reverse: true});
            $('#valor_projeto').mask('000.000.000,00', {reverse: true});
        });

        document.getElementById('tempo_empresa_meses').addEventListener('input', function () {
            let value = parseInt(this.value);
            if (value > 12) {
                this.value = 12;
            } else if (value < 0 || isNaN(value)) {
                this.value = '';
            }
        });

        document.getElementById('tempo_empresa_anos').addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 4);
        });
    </script>
</body>
</html>
