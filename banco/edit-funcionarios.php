<?php

require 'config.php';

$funcionario = [];
$id = filter_input(INPUT_GET, 'id');

if ($id) {
    $sql = $pdo->prepare("SELECT * FROM funcionario WHERE idfuncionario = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        $funcionario = $sql->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "
    <script>
        alert('Não existe um funcionário com o id indicado');
        window.location.href = '../admin/pagina-inicial-adm.php';
    </script>
    ";

        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário - Artesanatos Eloi</title>
    <link rel="stylesheet" href="../funcionarios/style-cadastro.css">
</head>

<body>
    <div class="header">

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">
            <a href=""><img src="../img/artes-removebg-preview.png" class="img-logo" alt="Logo"></a>

            <!-- BARRA DE PESQUISA -->
            <div class="search">
                <input type="text" placeholder="Buscar produtos...">
                <button class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar"></button>
            </div>

            <!-- IMAGENS/BOTÕES DO ENTRAR E DO CARRINHO -->
            <div class="actions">
                <button><a href=""><img src="../img/bell.svg" alt="Notificações"></a></button>
                <button><img src="../img/gear.svg" alt="Configurações"></button>
            </div>
        </div>

        <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
        <div class="bottom-bar">
            <a href="../admin/pagina-inicial-adm.php" class="link-animado">Início</a>
            <a href="../editar-produtos/edit-products.php" class="link-animado">Editar Produtos</a>
            <div class="dropdown">
                <a href="#" class="link-animado">Cadastro</a>

                <div class="dropdown-menu">
                    <a href="../cadastrar-produtos/cadastro-produtos.php">Produtos</a>
                    <a href="../categorias/categorias.php">Categorias</a>
                    <a href="#">Forma de Pagamento</a>
                    <a href="../funcionarios/cadastro-funcionario.php">Funcionários</a>
                </div>
            </div>

            <a href="" class="link-animado">Analisar Vendas</a>
            <a href="" class="link-animado"></a>
        </div>
    </div>
    </div>

    <div class="container-login">

        <form id="form-funcionario" class="form-login" action="../banco/edit-action-funcionario.php" method="POST">

            <h1>Edição de Funcionário</h1>
            <p class="subtitulo">
                Preencha as informações abaixo para editar um funcionário cadastrado..
            </p>

            <!-- DADOS PESSOAIS -->
            <div class="secao">

                <h2>Dados Pessoais</h2>

                <br>

                <div class="linha">
                    <div class="campo">
                        <input type="hidden" name="id" value="<?= $funcionario['idfuncionario'] ?>">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" value="<?= $funcionario['nomefuncionario'] ?>" required>
                    </div>

                </div>

                <br>

                <div class="linha">
                    <div class="campo">
                        <label>E-mail</label>
                        <input type="email" name="email" value="<?= $funcionario['emailfuncionario'] ?>" required>
                    </div>

                </div>

                <br>

                <div class="campo">
                    <label>Telefone</label>
                    <input type="tel" name="telefone" id="telefone" maxlength="15" oninput="aplicarMascara(this)" value="<?= $funcionario['telefonefuncionario'] ?>" required>
                </div>

                <br>

                <div class="linha">
                    <div class="campo">
                        <label>CPF</label>
                        <input type="text"
                            name="cpf"
                            id="cpf"
                            maxlength="14"
                            value="<?= $funcionario['cpffuncionario'] ?>"
                            required>
                    </div>

                </div>

            </div>

            <!-- DADOS PROFISSIONAIS -->
            <div class="secao">

                <h2>Dados Profissionais</h2>

                <br>

                <div class="linha">

                    <div class="campo">
                        <label>Cargo</label>
                        <input type="text" name="cargo" value="<?= $funcionario['cargofuncionario'] ?>" required>
                    </div>

                    <div class="campo">
                        <label>Data de Nascimento</label>
                        <input type="date" name="dtnasc" value="<?= $funcionario['datanascimento'] ?>" required>
                    </div>
                </div>

                <br>

                <div class="campo">

                    <label class="titulo-radio">Status do Funcionário</label>

                    <div class="grupo-radio">

                        <label class="opcao-radio">
                            <input type="radio" name="status" value="1" checked>
                            Ativo
                        </label>

                        <label class="opcao-radio">
                            <input type="radio" name="status" value="0">
                            Desativo
                        </label>

                    </div>
                </div>
            </div>

            <!-- PARTE DO CADASTRO DA SENHA DO FUNCIONÁRIO -->
            <div class="secao">

                <h2>Cadastro da Senha</h2>

                <p class="subtitulo" style="text-align:left; margin-top:10px;">
                    Nesta parte, será uma senha temporária que o funcionário irá utilizar, é recomendado que em seu primeiro acesso,
                    ele clique em Esqueci a Senha para a criação de uma nova senha
                </p>

                <br>

                <div class="linha">
                    <div class="campo">
                        <label>Senha</label>
                        <input type="password"
                            name="senha"
                            pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}">
                    </div>

                </div>

                <br>

                <div class="campo">
                    <label>Confirmar Senha</label>
                    <input type="password"
                        name="repetir_senha"
                        pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}">

                </div>
            </div>

            <!-- CONTATO DE EMERGÊNCIA -->
            <div class="secao">

                <h2>Contato de Emergência</h2>

                <p class="subtitulo" style="text-align:left; margin-top:10px;">
                    Caso ocorra alguma emergência, entraremos em contato com a pessoa cadastrada abaixo.
                </p>

                <div class="linha">

                    <div class="campo">
                        <label>Nome do Contato</label>
                        <input type="text" name="nomeEmergencia" value="<?= $funcionario['nomecontato'] ?>">
                    </div>

                </div>

                <br>

                <div class="linha">

                    <div class="campo">
                        <label>Telefone do Contato</label>
                        <input type="tel" name="telEmergencia" id="tel-emergencia" maxlength="15" oninput="aplicarMascara(this)" value="<?= $funcionario['telefonecontato'] ?>">
                    </div>

                </div>

            </div>

            <button type="submit" class="btn-submit">
                Editar Funcionário
            </button>

        </form>

    </div>

    <!-- PARTE DO SCRIPT DO JAVASCRIPT PARA A VALIDAÇÃO DO CPF -->
    <script>
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove não números
            if (value.length > 3 && value.length <= 6)
                value = value.replace(/(\d{3})(\d+)/, '$1.$2');
            else if (value.length > 6 && value.length <= 9)
                value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
            else if (value.length > 9)
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
            e.target.value = value;
        });

        function aplicarMascara(campo) {
            let valor = campo.value.replace(/\D/g, '');

            // Limita a 11 dígitos
            valor = valor.substring(0, 11);

            if (valor.length > 10) {
                // Celular: (99) 99999-9999
                valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
            } else if (valor.length > 6) {
                // Telefone fixo: (99) 9999-9999
                valor = valor.replace(/^(\d{2})(\d{4})(\d+)$/, '($1) $2-$3');
            } else if (valor.length > 2) {
                valor = valor.replace(/^(\d{2})(\d+)/, '($1) $2');
            } else if (valor.length > 0) {
                valor = valor.replace(/^(\d+)/, '($1');
            }

            campo.value = valor;
        }
    </script>

</body>

</html>