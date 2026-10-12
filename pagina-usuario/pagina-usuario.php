<?php

// Inicia a sessão
session_start();

// Faz conexão com o banco
require '../banco/config.php';

// Inicializa as variáveis
$pessoa = false;
$endereco = false;
$pedidos = [];

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {

    // Recupera o ID da conta conectada
    $idUsuario = (int) $_SESSION['user_id'];

    // ==========================================
    // BUSCANDO AS INFORMAÇÕES PESSOAIS
    // ==========================================

    $sqlPessoa = $pdo->prepare("
        SELECT cpf, dt_nasc, nome, telefone, email
        FROM pessoa
        WHERE id_usuario = :id_usuario
    ");

    $sqlPessoa->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $sqlPessoa->execute();

    $pessoa = $sqlPessoa->fetch(PDO::FETCH_ASSOC);

    // ==========================================
    // BUSCANDO O ENDEREÇO
    // ==========================================

    $sqlEndereco = $pdo->prepare("
        SELECT cep, cidade, estado, numero_casa,
               nome_rua, nome_bairro, apartamento, referencia
        FROM endereco
        WHERE id_usuario = :id_usuario
    ");

    $sqlEndereco->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $sqlEndereco->execute();

    $endereco = $sqlEndereco->fetch(PDO::FETCH_ASSOC);

    // ==========================================
    // BUSCANDO OS PEDIDOS DO USUÁRIO
    // ==========================================

    $sqlPedidos = $pdo->prepare("
        SELECT
            p.pk_id_pedidos,
            p.dt_pedido,
            p.total_pedido,
            p.status_pedido
        FROM pedidos p
        INNER JOIN pessoa pe
            ON pe.id = p.id_pessoa
        WHERE pe.id_usuario = :id_usuario
        ORDER BY p.dt_pedido DESC
    ");

    $sqlPedidos->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $sqlPedidos->execute();

    $pedidos = $sqlPedidos->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do Usuário - Artesanatos Eloi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../pagina-usuario/style-pagina-usuario.css">
</head>

<body>
    <div class="header">

        <!-- PARTE DE CIMA DO TOP BAR -->
        <div class="top-bar">

            <!-- LOGO -->
            <a href="../pagina-inicial/inicio.php" class="logo-link" id="logo-menu">
                <img src="../img/artes-removebg-preview.png" class="img-logo" alt="Logo">
            </a>


            <!-- BARRA DE PESQUISA -->
            <div class="search">
                <input type="text" placeholder="Buscar produtos...">

                <button class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar">
                </button>
            </div>

            <!-- BOTÃO DE ENTRAR/CADASTRAR -->
            <div class="actions">

                <div class="dropdown-button">

                    <button class="button-dropdown">
                        <img src="../img/person.svg" alt="Login">
                    </button>

                    <div class="conteudo-dropdown">
                        <a href="../cadastro/cadastro.php">Cadastro</a>
                        <a href="../login/login.php">Login</a>

                        <a href="../pagina-usuario/pagina-usuario.php">
                            Página do Usuário
                            <?= htmlspecialchars($_SESSION['user_nome'] ?? '') ?>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../banco/logout-usuario.php">Sair</a>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- CARRINHO -->
                <a href="../carrinho-compras/carrinho-compras.php">
                    <button>
                        <img src="../img/cart3.svg" alt="Carrinho">
                    </button>
                </a>

            </div>

        </div>

        <!-- MENU DE NAVEGAÇÃO -->
        <div class="bottom-bar" id="menu-mobile">

            <a href="../pagina-inicial/inicio.php" class="link-animado">
                Início
            </a>

            <a href="../produtos/produtos.php" class="link-animado">
                Produtos
            </a>

            <div class="dropdown">
                <a href="#" class="link-animado" id="categorias-mobile">
                    Categorias
                </a>

                <div class="dropdown-menu">
                    <a href="../produtos/produtos.php?categoria=1">Todos</a>
                    <a href="../produtos/produtos.php?categoria=2">Recomendados</a>
                    <a href="../produtos/produtos.php?categoria=3">Destaques</a>
                    <a href="../produtos/produtos.php?categoria=4">Promoções</a>
                    <a href="../produtos/produtos.php?categoria=5">Mais Vendidos</a>
                </div>
            </div>

            <a href="../sobre/sobre.php" class="link-animado">
                Sobre
            </a>

        </div>
    </div>

  <!-- CONTEÚDO DA PÁGINA DO USUÁRIO -->
<main class="pagina-usuario">
<?php if (!isset($_SESSION['user_id'])): ?>
    <section class="estado-conta">
        <span class="icone-estado"><i class="fa-regular fa-user"></i></span>
        <h1>Entre na sua conta</h1>
        <p>Faça login para consultar suas informações e acompanhar seus pedidos.</p>
        <a href="../login/login.php" class="btn-primario-conta">Fazer login</a>
    </section>
<?php else: ?>
    <div class="painel-conta">
        <header class="topo-conta">
            <div>
                <span class="sobretitulo-conta">ÁREA DO CLIENTE</span>
                <h1>Olá, <?= htmlspecialchars($_SESSION['user_nome']) ?>!</h1>
                <p>Gerencie seus dados e acompanhe suas compras em um só lugar.</p>
            </div>
            <a href="../banco/logout-usuario.php" class="btn-sair-conta"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sair da conta</a>
        </header>

        <?php if (!$pessoa || !$endereco): ?>
            <section class="aviso-cadastro-conta">
                <div class="aviso-icone"><i class="fa-solid fa-clipboard-list"></i></div>
                <div class="aviso-texto">
                    <h2>Complete seu cadastro</h2>
                    <p>Cadastre suas informações pessoais e seu endereço para facilitar suas próximas compras.</p>
                </div>
                <a href="../pagina-forms-pessoa/forms-info-pessoais.php" class="btn-primario-conta">Cadastrar informações</a>
            </section>
        <?php else: ?>
            <section class="grade-informacoes-conta" aria-label="Dados da conta">
                <article class="painel-info-conta">
                    <div class="titulo-painel-info">
                        <span class="icone-painel-info"><i class="fa-regular fa-id-card"></i></span>
                        <div><h2>Dados pessoais</h2><p>Informações da sua conta</p></div>
                    </div>
                    <dl class="lista-dados-conta">
                        <div><dt>Nome completo</dt><dd><?= htmlspecialchars($pessoa['nome']) ?></dd></div>
                        <div><dt>CPF</dt><dd><?= htmlspecialchars($pessoa['cpf']) ?></dd></div>
                        <div><dt>Nascimento</dt><dd><?= htmlspecialchars($pessoa['dt_nasc']) ?></dd></div>
                        <div><dt>E-mail</dt><dd><?= htmlspecialchars($pessoa['email']) ?></dd></div>
                        <div><dt>Telefone</dt><dd><?= htmlspecialchars($pessoa['telefone']) ?></dd></div>
                    </dl>
                    <a href="../banco/edit-pessoa.php?id=<?= (int) $_SESSION['user_id'] ?>" class="btn-secundario-conta"><i class="fa-solid fa-pen"></i> Editar dados</a>
                </article>

                <article class="painel-info-conta">
                    <div class="titulo-painel-info">
                        <span class="icone-painel-info"><i class="fa-solid fa-location-dot"></i></span>
                        <div><h2>Endereço de entrega</h2><p>Local para receber suas compras</p></div>
                    </div>
                    <dl class="lista-dados-conta">
                        <div><dt>Rua</dt><dd><?= htmlspecialchars($endereco['nome_rua']) ?>, <?= htmlspecialchars($endereco['numero_casa']) ?></dd></div>
                        <div><dt>Bairro</dt><dd><?= htmlspecialchars($endereco['nome_bairro']) ?></dd></div>
                        <div><dt>Cidade / Estado</dt><dd><?= htmlspecialchars($endereco['cidade']) ?> - <?= htmlspecialchars($endereco['estado']) ?></dd></div>
                        <div><dt>CEP</dt><dd><?= htmlspecialchars($endereco['cep']) ?></dd></div>
                        <?php if (!empty($endereco['apartamento'])): ?><div><dt>Complemento</dt><dd><?= htmlspecialchars($endereco['apartamento']) ?></dd></div><?php endif; ?>
                        <?php if (!empty($endereco['referencia'])): ?><div><dt>Referência</dt><dd><?= htmlspecialchars($endereco['referencia']) ?></dd></div><?php endif; ?>
                    </dl>
                    <a href="../banco/edit-endereco.php?id=<?= (int) $_SESSION['user_id'] ?>" class="btn-secundario-conta"><i class="fa-solid fa-pen"></i> Editar endereço</a>
                </article>
            </section>
        <?php endif; ?>

        <section class="area-pedidos-conta">
            <div class="cabecalho-pedidos-conta">
                <div><span class="sobretitulo-conta">HISTÓRICO</span><h2>Meus pedidos</h2><p>Consulte suas compras e os valores de cada pedido.</p></div>
                <a href="../produtos/produtos.php" class="link-continuar-comprando">Continuar comprando <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <?php if (empty($pedidos)): ?>
                <div class="sem-pedidos-conta"><i class="fa-solid fa-box-open"></i><h3>Você ainda não fez pedidos</h3><p>Quando você realizar uma compra, ela aparecerá aqui.</p><a href="../produtos/produtos.php" class="btn-primario-conta">Explorar produtos</a></div>
            <?php else: ?>
                <div class="grade-pedidos-conta">
                    <?php foreach ($pedidos as $pedido): ?>
                        <?php
                        $sqlItens = $pdo->prepare("SELECT pr.nome_produto, ip.quantidade, ip.preco_unitario, ip.subtotal FROM itens_pedido ip INNER JOIN produtos pr ON pr.pk_id_produtos = ip.fk_id_produtos WHERE ip.fk_id_pedidos = ?");
                        $sqlItens->execute([$pedido['pk_id_pedidos']]);
                        $itens = $sqlItens->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <article class="pedido-conta-card">
                            <div class="pedido-conta-topo">
                                <div><span class="pedido-label">PEDIDO</span><h3>#<?= (int) $pedido['pk_id_pedidos'] ?></h3><p><i class="fa-regular fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($pedido['dt_pedido'])) ?></p></div>
                                <span class="status-pedido-conta"><?= htmlspecialchars($pedido['status_pedido']) ?></span>
                            </div>
                            <div class="pedido-conta-itens">
                                <?php foreach ($itens as $item): ?>
                                    <div class="linha-item-conta"><div><strong><?= htmlspecialchars($item['nome_produto']) ?></strong><span>Quantidade: <?= (int) $item['quantidade'] ?></span></div><b>R$ <?= number_format((float) $item['subtotal'], 2, ',', '.') ?></b></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="pedido-conta-total"><span>Total do pedido</span><strong>R$ <?= number_format((float) $pedido['total_pedido'], 2, ',', '.') ?></strong></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
<?php endif; ?>
</main>



            <!-- AÇÕES DA CONTA -->
            <!--<section class="acoes-usuario">

                            <h2>Ações da conta</h2>

                            <div class="botoes-acoes">

                                <a href="#" class="btn-acao">
                                    Editar informações
                                </a>

                                <a href="#" class="btn-acao">
                                    Editar endereço
                                </a>

                            </div>

                        </section> -->

    </main>

    <!-- PARTE FINAL DO SITE / FOOTER -->
    <footer>
        <div class="content-footer">
            <div class="top">
                <div class="logo-details">
                    <span class="logo_name">Artesanatos Eloi</span>
                </div>
                <div class="media-icons">
                    <a href="https://www.facebook.com/brinqdcorserranegra/"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://wa.me/5519995233724"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.instagram.com/brinqdcor?stkn=MXJsanliOWFnc215NA=="><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="link-boxes">
                <ul class="boxes">
                    <li class="link_name">Sobre a Empresa</li>
                    <li><a href="../pagina-inicial/inicio.php">Início</a></li>
                    <li><a href="https://wa.me/5519995233724">Contatos</a></li>
                    <li><a href="https://maps.app.goo.gl/QoNvQpyp3u9aoydS8">Endereços</a></li>
                    <li><a href="../sobre/sobre.php">Nossa História</a></li>
                </ul>
                <div class="link-boxes">
                    <ul class="boxes">
                        <li class="link_name">Produtos</li>
                        <li><a href="../produtos/produtos.php">Todos os Produtos</a></li>
                        <li><a href="../produtos/produtos.php?categoria=2">Recomendados</a></li>
                        <li><a href="../produtos/produtos.php?categoria=3">Destaques</a></li>
                        <li><a href="../produtos/produtos.php?categoria=4">Promoções</a></li>
                        <li><a href="../produtos/produtos.php?categoria=5">Mais Vendidos</a></li>
                    </ul>
                    <ul class="boxes input-box">
                        <li class="link_name">Inscreva-se</li>
                        <li><input type="text" placeholder="Digite seu email"></li>
                        <li><input type="button" value="Inscreva-se"></li>
                    </ul>
                </div>
            </div>
            <div class="bottom-details">
                <div class="bottom_text">
                    <span class="copyright_text">
                        Copyright &#169; 2026 - Artesanatos Eloi. <br>
                        Todos os Direitos Reservados
                    </span>
                </div>
            </div>
    </footer>
</body>

<!-- PARTE DO JAVASCRIPT -->
<script src="../js/script-geral.js"></script>