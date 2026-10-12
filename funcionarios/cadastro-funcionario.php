<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Funcionário - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-cadastro.css">
</head>

<body>
    <div class="header">

         <!-- MENU LATERAL MOBILE -->
        <div class="menu-mobile" id="menuMobile">
            <div class="menu-mobile-conteudo">

                <button class="fechar-menu" onclick="fecharMenu()">
                    ✕
                </button>

                <a href="../admin/pagina-inicial-adm.php">Início</a>
                <a href="../editar-produtos/edit-products.php">Editar Produtos</a>

                <div class="menu-mobile-titulo">
                    Cadastro
                </div>

                <a href="../cadastrar-produtos/cadastro-produtos.php">
                    Produtos
                </a>

                <a href="../categorias/categorias.php">
                    Categorias
                </a>

                <a href="../pagamento/forma-pagamento.php">
                    Forma de Pagamento
                </a>

                <a href="#">Analisar Vendas</a>

            </div>
        </div>

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">
            <a href="javascript:void(0)" onclick="abrirMenu()">
                <img src="../img/artes-removebg-preview.png"
                    class="img-logo"
                    alt="Logo">
            </a>

            <!-- BARRA DE PESQUISA -->
            <div class="search">
                <input type="text" placeholder="Buscar produtos...">
                <button class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar"></button>
            </div>

             <!-- IMAGENS/BOTÕES DAS NOTIFICAÇÕES E DAS CONFIGURAÇÕES -->
            <div class="actions">
                <div class="dropdown-button">
                    <button class="button-dropdown">
                        <img src="../img/person.svg" alt="Login">
                    </button>

                    <!-- CONTEÚDO DENTRO DO BOTÃO DE LOGIN -->
                    <div class="conteudo-dropdown">
                        <a href="#">Página do Admin <?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?></a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../banco/logout-admin.php">Sair</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <button><a href=""><img src="../img/bell.svg" alt="Notificações"></a></button>
            </div>
        </div>

        <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
        <div class="bottom-bar">
            <a href="../admin/pagina-inicial-adm.php" class="link-animado">Início</a>
            <a href="../editar-produtos/edit-products.php" class="link-animado">Editar Produtos</a>

            <!-- MENU DO DROPDOWN -->
            <div class="dropdown">
                <a href="#" class="link-animado">Cadastro</a>

                <div class="dropdown-menu">
                    <a href="../cadastrar-produtos/cadastro-produtos.php">Produtos</a>
                    <a href="../categorias/categorias.php">Categorias</a>
                    <a href="../pagamento/forma-pagamento.php">Forma de Pagamento</a>
                    <a href="../funcionarios/cadastro-funcionario.php">Funcionários</a>
                </div>
            </div>

            <a href="#" class="link-animado">Analisar Vendas</a>
        </div>
    </div>
    </div>

    <div class="container-login">

        <form id="form-funcionario" class="form-login" action="../banco/cadastro-func-adm.php" method="POST">

            <h1>Cadastro de Funcionário</h1>
            <p class="subtitulo">
                Preencha as informações abaixo para cadastrar um novo funcionário.
            </p>

            <!-- DADOS PESSOAIS -->
            <div class="secao">

                <h2>Dados Pessoais</h2>

                <br>

                <div class="linha">

                    <div class="campo">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" placeholder="Digite o nome completo" autocomplete="off" required>
                    </div>

                </div>

                <br>

                <div class="linha">

                    <div class="campo">
                        <label>E-mail</label>
                        <input type="email" name="email" placeholder="Digite o e-mail" autocomplete="off" required>
                    </div>

                </div>

                <br>

                <div class="campo">
                    <label>Telefone</label>
                    <input type="tel" name="telefone" id="telefone" maxlength="15" oninput="aplicarMascara(this)" placeholder="(19) 99999-9999" required>
                </div>

                <br>

                <div class="linha">

                    <div class="campo">
                        <label>CPF</label>
                        <input type="text"
                            name="cpf"
                            id="cpf"
                            maxlength="14"
                            placeholder="000.000.000-00"
                            autocomplete="off"
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
                        <label class="titulo-radio">Cargo</label>

                        <div class="grupo-radio">

                            <label class="opcao-radio">
                                <input type="radio" name="cargo" value="Montador" checked>
                                Montador
                            </label>

                            <label class="opcao-radio">
                                <input type="radio" name="cargo" value="Pintor">
                                Pintor
                            </label>

                            <label class="opcao-radio">
                                <input type="radio" name="cargo" value="Embalador">
                                Embalador
                            </label>

                            <label class="opcao-radio">
                                <input type="radio" name="cargo" value="Jovem Aprendiz">
                                Jovem Aprendiz
                            </label>

                            <label class="opcao-radio">
                                <input type="radio" name="cargo" value="Administração">
                                Administração
                            </label>

                        </div>
                    </div>
                </div>

                    <br>

                    <div class="campo">
                        <label for="dtnasc">Data de Nascimento</label>
                        <input type="date" id="dtnasc" name="dtnasc" required>
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

                    <br>

                    <div class="linha">

                        <div class="campo">
                            <label>Senha</label>
                            <input type="password"
                                name="senha"
                                placeholder="Digite a senha"
                                pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}"
                                title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial."
                                required>
                        </div>

                    </div>

                    <br>

                    <div class="campo">
                        <label>Confirmar Senha</label>
                        <input type="password"
                            name="repetir_senha"
                            placeholder="Repita a senha"
                            pattern="(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}"
                            title="A senha deve conter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial."
                            required>

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
                            <input type="text" name="nomeEmergencia" placeholder="Nome completo" autocomplete="off">
                        </div>

                    </div>

                    <br>

                    <div class="linha">

                        <div class="campo">
                            <label>Telefone do Contato</label>
                            <input type="tel" name="telEmergencia" id="tel-emergencia" maxlength="15" oninput="aplicarMascara(this)" placeholder="(19) 99999-9999">
                        </div>

                    </div>

                </div>

                <button type="submit" class="btn-submit">
                    Cadastrar Funcionário
                </button>

        </form>

    </div>

    <!-- PARTE DO SCRIPT DO SITE -->
     <script src="../js/script-adm.js"></script>
    <script src="../funcionarios/script-cadastro.js"></script>

</body>

</html>