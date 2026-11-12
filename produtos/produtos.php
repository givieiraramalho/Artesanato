<?php
// Realiza a conexão com o banco de dados
require '../banco/config.php';
session_start();

// Mapeamento das categorias (Ajustado ID 5 para Promoções conforme sua lista)
$categorias = [
    1 => 'Todos',
    2 => 'Recomendados',
    3 => 'Mais Vendidos',
    4 => 'Destaques',
    5 => 'Promoções'
];

// Pega a categoria da URL (padrão é 1 se vier vazia ou inválida)
$categoriaSelecionada = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT);
if (!$categoriaSelecionada || !isset($categorias[$categoriaSelecionada])) {
    $categoriaSelecionada = 1;
}

$listaProdutos = [];

// 1. CONSULTA SQL (Com tratamento de Pesquisa e Categorias)

if (!empty($_POST['search'])) {
    // Se o usuário fez uma pesquisa
    $data = "%" . trim($_POST['search']) . "%";

    $sql = $pdo->prepare("
        SELECT DISTINCT p.*
        FROM produtos p
        WHERE (p.pk_id_produtos LIKE ? OR p.nome_produto LIKE ? OR p.descricao LIKE ?)
        AND p.status_produto = 1
        ORDER BY p.pk_id_produtos ASC
    ");
    $sql->execute([$data, $data, $data]);
} else {

    // Se estiver filtrando por categoria
    if ($categoriaSelecionada == 5) {

        // Categoria 5: Promoções (Traz apenas produtos ativos E com desconto > 0)
        $sql = $pdo->prepare("
            SELECT DISTINCT p.*
            FROM produtos p
            INNER JOIN produto_categoria pc ON p.pk_id_produtos = pc.id_produto
            WHERE p.status_produto = 1 
            AND pc.id_categoria = ?
            AND p.desconto > 0
        ");
        $sql->execute([$categoriaSelecionada]);
    } elseif ($categoriaSelecionada > 1) {

        // Outras categorias específicas
        $sql = $pdo->prepare("
            SELECT DISTINCT p.*
            FROM produtos p
            INNER JOIN produto_categoria pc ON p.pk_id_produtos = pc.id_produto
            WHERE p.status_produto = 1 
            AND pc.id_categoria = ?
        ");
        $sql->execute([$categoriaSelecionada]);
    } else {

        // Categoria 1: Todos (Traz todos os produtos ativos)
        $sql = $pdo->query("
            SELECT *
            FROM produtos
            WHERE status_produto = 1
            ORDER BY pk_id_produtos ASC
        ");
    }
}

//Verifica todos os dados do array da listaProdutos
$listaProdutos = $sql->fetchAll(PDO::FETCH_ASSOC);

//Verifica a categoria selecionada
$nomeCategoria = $categorias[$categoriaSelecionada];

//Verifica se existe filtro
$temFiltro = ($categoriaSelecionada != 1);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-produtos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <form method="POST" class="search">

                <input
                    type="text"
                    name="search"
                    id="pesquisar"
                    placeholder="Buscar produtos..."
                    value="<?= isset($_POST['search'])
                                ? htmlspecialchars($_POST['search'], ENT_QUOTES, 'UTF-8')
                                : '' ?>">

                <button type="submit" class="search-btn">
                    <img src="../img/search.svg" alt="Pesquisar">
                </button>

            </form>

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

            <!-- Categorias -->
            <div class="dropdown">
                <a href="#" class="link-animado" id="categorias-mobile">
                    Categorias
                </a>

                <!-- Filtro - Categorias dos Produtos -->
                <div class="dropdown-menu">
                    <a href="../produtos/produtos.php?categoria=1">Todos</a>
                    <a href="../produtos/produtos.php?categoria=2">Recomendados</a>
                    <a href="../produtos/produtos.php?categoria=3">Mais Vendidos</a>
                    <a href="../produtos/produtos.php?categoria=4">Destaques</a>
                    <a href="../produtos/produtos.php?categoria=5">Promoções</a>
                </div>
            </div>

            <a href="../sobre/sobre.php" class="link-animado">
                Sobre
            </a>

        </div>
    </div>

    <!-- PARTE DE FILTROS DE PRODUTOS -->
    <section class="area-filtros">

        <!-- Trilha de Navegação (Breadcrumb) -->
        <nav class="breadcrumb" aria-label="Você está em:">
            <a href="index.php">Início</a>
            <span class="separador">›</span>
            <a href="produtos.php">Catálogo</a>
            <?php if ($temFiltro && $categoriaSelecionada != 1): ?>
                <span class="separador">›</span>
                <span class="atual"><?= htmlspecialchars($nomeCategoria); ?></span>
            <?php endif; ?>
        </nav>

        <!-- Cabeçalho Principal -->
        <div class="cabecalho-filtros">
            <h2>Produtos - Artesanatos Eloi</h2>
            <p>Peças exclusivas criadas à mão para transformar o seu ambiente</p>
        </div>

        <!-- Navegação por Abas / Filtros -->
        <div class="filtros" role="tablist">

            <!-- Filtro - Todos os Produtos -->
            <a href="produtos.php?categoria=1" role="tab" class="filtro <?= ($categoriaSelecionada == 1 || $categoriaSelecionada === null) ? 'ativo' : ''; ?>">
                <span>Todos os Produtos</span>
            </a>

            <!-- Filtro - Recomendados -->
            <a href="produtos.php?categoria=2" role="tab" class="filtro <?= ($categoriaSelecionada == 2) ? 'ativo' : ''; ?>">
                <span>Recomendados</span>
            </a>

            <!-- Filtro - Mais Vendidos -->
            <a href="produtos.php?categoria=3" role="tab" class="filtro <?= ($categoriaSelecionada == 3) ? 'ativo' : ''; ?>">
                <span>Mais Vendidos</span>
            </a>

            <!-- Filtro - Destaques -->
            <a href="produtos.php?categoria=4" role="tab" class="filtro <?= ($categoriaSelecionada == 4) ? 'ativo' : ''; ?>">
                <span>Destaques</span>
            </a>

            <!-- Filtro - Promoções -->
            <a href="produtos.php?categoria=5" role="tab" class="filtro filtro-promocao <?= ($categoriaSelecionada == 5) ? 'ativo' : ''; ?>">
                <span class="badge-tag">Oferta</span>
                <span>Promoções</span>
            </a>
        </div>

        <!-- Título da Seção Ativa (Exibido apenas quando filtrado) -->
        <?php if ($temFiltro && $categoriaSelecionada != 1): ?>
            <div class="titulo-categoria">
                <h1><?= htmlspecialchars($nomeCategoria); ?></h1>
            </div>
        <?php endif; ?>

    </section>


    <!-- PARTE DOS CARDS DOS PRODUTOS -->
    <div class="card-container">

        <?php if (empty($listaProdutos)): ?>
            <div class="sem-produtos">
                <p>Nenhum produto encontrado nesta categoria</p>
            </div>
        <?php else: ?>

            <?php foreach ($listaProdutos as $produtos): ?>

                <?php

                // CÁLCULO INDIVIDUAL DE DESCONTO POR PRODUTO
                $precoOriginal = $produtos['preco_produto'];
                $porcentagemDesconto = $produtos['desconto']; // Ex: 20.00

                if ($porcentagemDesconto > 0) {
                    $valorDesconto = $precoOriginal * ($porcentagemDesconto / 100);
                    $precoFinal = $precoOriginal - $valorDesconto;
                    $temPromocao = true;
                } else {
                    $precoFinal = $precoOriginal;
                    $temPromocao = false;
                }
                ?>

                <!-- CARDS DOS PRODUTOS -->
                <div class="product-card">

                    <div class="product-image-container">
                        <!-- Tag de desconto flutuante no topo da foto -->
                        <?php if ($temPromocao): ?>
                            <span class="badge-desconto-topo">
                                <?= number_format($porcentagemDesconto, 0) ?>% OFF
                            </span>
                        <?php endif; ?>

                        <img
                            src="mostrar-imagem.php?id=<?= $produtos['pk_id_produtos']; ?>"
                            alt="<?= htmlspecialchars($produtos['descricao_imagem'] ?? ''); ?>"
                            class="product-image">
                    </div>

                    <div class="product-info">

                        <h2 class="product-title">
                            <?= htmlspecialchars($produtos['nome_produto']) ?>
                        </h2>

                        <p class="product-description">
                            <?= htmlspecialchars($produtos['descricao']) ?>
                        </p>

                        <!-- Preços Lado a Lado -->
                        <div class="product-price-container">
                            <?php if ($temPromocao): ?>
                                <span class="preco-antigo">
                                    R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                                </span>
                                <span class="preco-atual">
                                    R$ <?= number_format($precoFinal, 2, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span class="preco-atual">
                                    R$ <?= number_format($precoOriginal, 2, ',', '.') ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="action-button">
                            <a class="btn-product" href="../ver-produtos/pagina-produto.php?id=<?= $produtos['pk_id_produtos'] ?>">Ver Produto</a>
                            <a class="btn-comprar" href="../carrinho-compras/carrinho-compras.php?adicionar=<?php echo $produtos['pk_id_produtos']; ?>">Comprar</a>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>
    </div>

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

    <!--PARTE DE LINKS JAVASCRIPT-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="../js/script-geral.js"></script>
</body>

</html>