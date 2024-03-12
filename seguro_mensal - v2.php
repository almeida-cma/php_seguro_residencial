<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Seguro Residencial</title>
    <style>
		 body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f4f4f4;
		}

		.container {
			max-width: 600px;
			margin: 50px auto;
			background-color: #fff;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		h2 {
			color: #333;
			margin-bottom: 20px;
		}

		form {
			margin-top: 20px;
		}

		input[type="text"],
		input[type="number"],
		select {
			width: 100%;
			padding: 10px;
			margin-bottom: 15px;
			border: 1px solid #ccc;
			border-radius: 4px;
			box-sizing: border-box;
		}

		input[type="radio"],
		input[type="checkbox"] {
			margin-right: 10px;
		}

		input[type="submit"] {
			background-color: #4CAF50;
			color: white;
			border: none;
			padding: 10px 20px;
			border-radius: 4px;
			cursor: pointer;
		}

		input[type="submit"]:hover {
			background-color: #45a049;
		}

		/* Estilização para o resumo */
		.resumo {
			margin-top: 30px;
			padding: 20px;
			background-color: #eaeaea;
			border-radius: 8px;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
			line-height: 0.5; /* Defina o espaçamento entre as linhas aqui */
		}

		.resumo h3 {
			margin-top: 0;
			color: #333;
		}

		.resumo p {
			margin-bottom: 2px;
		}

		.resumo strong {
			font-weight: bold;
		}
    </style>
</head>
<body>
    <div class="container">
        <h2>Calculadora de Seguro Residencial</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            Valor do imóvel: <input type="text" name="valor_imovel" id="valor_imovel" placeholder="Digite o valor em reais" required><br>
            Localização:
            <select name="localizacao">
                <option value="urbana">Urbana</option>
                <option value="suburbana">Suburbana</option>
                <option value="rural">Rural</option>
            </select><br>
            Tamanho (m²): <input type="number" name="tamanho" placeholder="Digite a metragem quadrada" required><br>
            Tipo de construção:
            <select name="tipo_construcao">
                <option value="alvenaria">Alvenaria</option>
                <option value="madeira">Madeira</option>
                <option value="concreto">Concreto</option>
            </select><br>
            Nível de segurança:<br>
            <input type="radio" name="nivel_seguranca" value="baixo" checked> Baixo
            <input type="radio" name="nivel_seguranca" value="médio"> Médio
            <input type="radio" name="nivel_seguranca" value="alto"> Alto<br>
            Coberturas adicionais (opcional):<br>
            <input type="checkbox" name="cobertura[]" value="incendio"> Incêndio
            <input type="checkbox" name="cobertura[]" value="roubo"> Roubo
            <input type="checkbox" name="cobertura[]" value="danos"> Danos<br>
            <input type="submit" name="submit" value="Calcular">
        </form>

        <?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Dados do formulário
			$valor_imovel = $_POST['valor_imovel']; // Mantendo o valor como uma string inicialmente
			// Convertendo a entrada para um número de ponto flutuante (considerando centavos)
			$valor_imovel = floatval(str_replace(',', '.', str_replace('.', '', $valor_imovel)));
            $localizacao = $_POST['localizacao'];
            $tamanho = $_POST['tamanho'];
            $tipo_construcao = $_POST['tipo_construcao'];
            $nivel_seguranca = $_POST['nivel_seguranca'];
            $coberturas = isset($_POST['cobertura']) ? $_POST['cobertura'] : [];

            // Lógica para calcular o valor mensal do seguro
            $valor_base = str_replace('.', '', $valor_imovel) * 0.001; // Valor base (0,1% do valor do imóvel)

            // Fatores de ponderação
            $fator_localizacao = 1; // Fator padrão
            if ($localizacao === 'suburbana') {
                $fator_localizacao = 1.1;
            } elseif ($localizacao === 'rural') {
                $fator_localizacao = 1.2;
            }

            $fator_tamanho = ($tamanho <= 100) ? 1 : ($tamanho <= 200 ? 1.1 : 1.2); // Fator padrão
            $fator_construcao = ($tipo_construcao === 'madeira') ? 1.1 : ($tipo_construcao === 'concreto' ? 0.9 : 1); // Fator padrão
            $fator_seguranca = ($nivel_seguranca === 'baixo') ? 1.2 : ($nivel_seguranca === 'médio' ? 1.1 : 1); // Fator padrão

            // Calcular o valor mensal do seguro com base nos fatores
            $valor_mensal = $valor_base * $fator_localizacao * $fator_tamanho * $fator_construcao * $fator_seguranca;

            // Adicionar custo das coberturas adicionais
            foreach ($coberturas as $cobertura) {
                if ($cobertura === 'incendio') {
                    $valor_mensal += 10; // Exemplo de custo adicional para a cobertura de incêndio
                } elseif ($cobertura === 'roubo') {
                    $valor_mensal += 15; // Exemplo de custo adicional para a cobertura de roubo
                } elseif ($cobertura === 'danos') {
                    $valor_mensal += 20; // Exemplo de custo adicional para a cobertura de danos
                }
            }

            // Exibir o valor mensal do seguro
            echo "<h2>Valor Mensal do Seguro:</h2>";
            echo "R$ " . number_format($valor_mensal, 2, ',', '.');
			
			// Exibir o valor mensal do seguro
            echo "<div class='resumo'>";
            echo "<h3>Resumo da Apólice:</h3>";
            echo "<p><strong>Valor Mensal do Seguro:</strong> R$ " . number_format($valor_mensal, 2, ',', '.') . "</p>";
            echo "<p><strong>Valor do Imóvel:</strong> R$ " . $_POST['valor_imovel'] . "</p>";
            echo "<p><strong>Localização:</strong> " . $_POST['localizacao'] . "</p>";
            echo "<p><strong>Tamanho (m²):</strong> " . $_POST['tamanho'] . "</p>";
            echo "<p><strong>Tipo de Construção:</strong> " . $_POST['tipo_construcao'] . "</p>";
            echo "<p><strong>Nível de Segurança:</strong> " . $_POST['nivel_seguranca'] . "</p>";
            if (!empty($_POST['cobertura'])) {
                echo "<p><strong>Coberturas Adicionais:</strong> " . implode(", ", $_POST['cobertura']) . "</p>";
            } else {
                echo "<p><strong>Coberturas Adicionais:</strong> Nenhuma selecionada</p>";
            }
            echo "</div>";
					
        }
        ?>
		<button onclick="window.location.href = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>';">Novo Cálculo</button>
    </div>
	<script>
	document.addEventListener("DOMContentLoaded", function() {
		var inputValor = document.getElementById('valor_imovel');

		inputValor.addEventListener('input', function(event) {
			var valor = this.value.replace(/\D/g, '');
			valor = (valor / 100).toFixed(2);

			var valorFormatado = valor.replace(/\./g, ',')
									   .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

			this.value = valorFormatado;
		});
	});
	</script>
</body>
</html>
