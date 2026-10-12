<?php

// ======================================================
// INICIA A SESSÃO
// ======================================================

session_start();


// ======================================================
// CONEXÃO COM O BANCO DE DADOS
// ======================================================

require '../banco/config.php';


// ======================================================
// FUNÇÃO PARA SALVAR O CARRINHO NO BANCO
// ======================================================

function salvarCarrinhoBanco()
{
    global $pdo;

    // Se o usuário não estiver logado,
    // não temos como associar o carrinho a uma conta.
    if (!isset($_SESSION['user_id'])) {
        return;
    }

    // Se não existir carrinho na sessão,
    // não há nada para salvar.
    if (!isset($_SESSION['carrinho'])) {
        return;
    }

    // ID do usuário logado
    $id_usuario = $_SESSION['user_id'];

    // Percorre todos os produtos que estão na sessão
    foreach ($_SESSION['carrinho'] as $id_produto => $quantidade) {

        // Verifica se esse produto já existe
        // no carrinho desse usuário no banco
        $sql = $pdo->prepare("
            SELECT pk_id_carrinho
            FROM carrinho
            WHERE id_usuario = :id_usuario
            AND id_produtos = :id_produto
        ");

        $sql->bindValue(
            ':id_usuario',
            $id_usuario,
            PDO::PARAM_INT
        );

        $sql->bindValue(
            ':id_produto',
            $id_produto,
            PDO::PARAM_INT
        );

        $sql->execute();

        $item = $sql->fetch(PDO::FETCH_ASSOC);


        // ==================================================
        // PRODUTO JÁ EXISTE NO BANCO
        // ==================================================

        if ($item) {

            $sql = $pdo->prepare("
                UPDATE carrinho
                SET quantidade = :quantidade
                WHERE id_usuario = :id_usuario
                AND id_produtos = :id_produto
            ");

            $sql->bindValue(
                ':quantidade',
                $quantidade,
                PDO::PARAM_INT
            );

            $sql->bindValue(
                ':id_usuario',
                $id_usuario,
                PDO::PARAM_INT
            );

            $sql->bindValue(
                ':id_produto',
                $id_produto,
                PDO::PARAM_INT
            );

            $sql->execute();
        }


        // ==================================================
        // PRODUTO AINDA NÃO EXISTE NO BANCO
        // ==================================================

        else {

            $sql = $pdo->prepare("
                INSERT INTO carrinho
                (
                    id_usuario,
                    id_produtos,
                    quantidade
                )
                VALUES
                (
                    :id_usuario,
                    :id_produto,
                    :quantidade
                )
            ");

            $sql->bindValue(
                ':id_usuario',
                $id_usuario,
                PDO::PARAM_INT
            );

            $sql->bindValue(
                ':id_produto',
                $id_produto,
                PDO::PARAM_INT
            );

            $sql->bindValue(
                ':quantidade',
                $quantidade,
                PDO::PARAM_INT
            );

            $sql->execute();
        }
    }
}


// ======================================================
// ADICIONAR PRODUTO AO CARRINHO
// ======================================================

if (isset($_GET['adicionar'])) {

    // ID do produto
    $id_produto = (int) $_GET['adicionar'];


    // Quantidade escolhida
    $quantidade_adicionar = isset($_GET['quantidade'])
        ? (int) $_GET['quantidade']
        : 1;


    // Impede quantidade inválida
    if ($quantidade_adicionar < 1) {
        $quantidade_adicionar = 1;
    }


    // --------------------------------------------------
    // BUSCA O PRODUTO NO BANCO
    // --------------------------------------------------

    $sql = $pdo->prepare("
        SELECT *
        FROM produtos
        WHERE pk_id_produtos = :id
    ");

    $sql->bindValue(
        ':id',
        $id_produto,
        PDO::PARAM_INT
    );

    $sql->execute();

    $produto = $sql->fetch(PDO::FETCH_ASSOC);


    // --------------------------------------------------
    // VERIFICA SE O PRODUTO EXISTE
    // --------------------------------------------------

    if ($produto) {

        // Quantidade que já está no carrinho
        $quantidade_atual =
            $_SESSION['carrinho'][$id_produto] ?? 0;


        // Estoque disponível
        $estoque = (int) $produto['estoque'];


        // Soma a quantidade escolhida
        $nova_quantidade =
            $quantidade_atual + $quantidade_adicionar;


        // --------------------------------------------------
        // VERIFICA O ESTOQUE
        // --------------------------------------------------

        if ($nova_quantidade <= $estoque) {

            // Salva na sessão
            $_SESSION['carrinho'][$id_produto] =
                $nova_quantidade;


            // Se estiver logado,
            // salva também no banco
            if (isset($_SESSION['user_id'])) {

                salvarCarrinhoBanco();
            }
        } else {

            // Mensagem de estoque
            $_SESSION['aviso_estoque'] =
                "A quantidade solicitada ultrapassa o estoque disponível.";
        }
    }


    // Volta para o carrinho
    header("Location: carrinho-compras.php");
    exit;
}


// ======================================================
// DIMINUIR QUANTIDADE
// ======================================================

if (isset($_GET['diminuir'])) {

    $id_produto = (int) $_GET['diminuir'];


    // Verifica se o produto está no carrinho
    if (isset($_SESSION['carrinho'][$id_produto])) {

        // Diminui uma unidade
        $_SESSION['carrinho'][$id_produto]--;


        // --------------------------------------------------
        // SE CHEGAR A ZERO
        // --------------------------------------------------

        if ($_SESSION['carrinho'][$id_produto] <= 0) {

            // Remove da sessão
            unset($_SESSION['carrinho'][$id_produto]);


            // Se estiver logado,
            // remove também do banco
            if (isset($_SESSION['user_id'])) {

                $sql = $pdo->prepare("
                    DELETE FROM carrinho
                    WHERE id_usuario = :id_usuario
                    AND id_produtos = :id_produto
                ");

                $sql->bindValue(
                    ':id_usuario',
                    $_SESSION['user_id'],
                    PDO::PARAM_INT
                );

                $sql->bindValue(
                    ':id_produto',
                    $id_produto,
                    PDO::PARAM_INT
                );

                $sql->execute();
            }
        }


        // --------------------------------------------------
        // AINDA POSSUI QUANTIDADE
        // --------------------------------------------------

        else {

            // Atualiza a quantidade no banco
            if (isset($_SESSION['user_id'])) {

                $sql = $pdo->prepare("
                    UPDATE carrinho
                    SET quantidade = :quantidade
                    WHERE id_usuario = :id_usuario
                    AND id_produtos = :id_produto
                ");

                $sql->bindValue(
                    ':quantidade',
                    $_SESSION['carrinho'][$id_produto],
                    PDO::PARAM_INT
                );

                $sql->bindValue(
                    ':id_usuario',
                    $_SESSION['user_id'],
                    PDO::PARAM_INT
                );

                $sql->bindValue(
                    ':id_produto',
                    $id_produto,
                    PDO::PARAM_INT
                );

                $sql->execute();
            }
        }
    }


    header("Location: carrinho-compras.php");
    exit;
}


// ======================================================
// REMOVER PRODUTO
// ======================================================

if (isset($_GET['remover'])) {

    $id_produto = (int) $_GET['remover'];


    // Remove da sessão
    if (isset($_SESSION['carrinho'][$id_produto])) {

        unset($_SESSION['carrinho'][$id_produto]);
    }


    // Se estiver logado,
    // remove também do banco
    if (isset($_SESSION['user_id'])) {

        $sql = $pdo->prepare("
            DELETE FROM carrinho
            WHERE id_usuario = :id_usuario
            AND id_produtos = :id_produto
        ");

        $sql->bindValue(
            ':id_usuario',
            $_SESSION['user_id'],
            PDO::PARAM_INT
        );

        $sql->bindValue(
            ':id_produto',
            $id_produto,
            PDO::PARAM_INT
        );

        $sql->execute();
    }


    header("Location: carrinho-compras.php");
    exit;
}


// ======================================================
// MENSAGEM DE ALERTA DO ESTOQUE
// ======================================================

if (isset($_SESSION['aviso_estoque'])) {

    echo "
    <script>
        alert('" .
        addslashes($_SESSION['aviso_estoque'])
        . "');
    </script>
    ";

    unset($_SESSION['aviso_estoque']);
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras - Artesanatos Eloi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">-->
    <link rel="stylesheet" href="../carrinho-compras/style-carrinho.css">
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

    <!-- CARRINHO DE COMPRAS -->
    <div class="content-carrinho">
        <div class="card-carrinho">

            <h1 class="title-carrinho">Carrinho de Compras</h1>

            <a href="../produtos/produtos.php" class="link-redirect">
                ← Voltar para a Vitrine
            </a>

            <?php if (empty($_SESSION['carrinho'])): ?>

                <div class="carrinho-vazio">
                    <p>Seu carrinho está totalmente vazio.</p>

                    <a href="../produtos/produtos.php" class="btn-continuar">
                        Ver Produtos
                    </a>
                </div>

            <?php else: ?>

                <div class="card-content">

                    <div class="selecionar-todos">
                        <input
                            type="checkbox"
                            id="selecionar-todos">

                        <label for="selecionar-todos">
                            Selecionar todos
                        </label>
                    </div>

                    <?php
                    $total_da_compra = 0;

                    foreach ($_SESSION['carrinho'] as $id_produto => $quantidade):

                        $sql = $pdo->prepare("
                        SELECT *
                        FROM produtos
                        WHERE pk_id_produtos = :id
                    ");

                        $sql->bindValue(":id", $id_produto);
                        $sql->execute();

                        $produto = $sql->fetch(PDO::FETCH_ASSOC);

                        if (!$produto) {
                            continue;
                        }

                        $subtotal = $produto['preco_produto'] * $quantidade;
                        $total_da_compra += $subtotal;
                    ?>

                        <!-- PRODUTO DO CARRINHO -->
                        <div class="item-carrinho">

                            <!-- SELECIONAR PRODUTO -->
                            <div class="selecionar-produto">
                                <input
                                    type="checkbox"
                                    name="produtos_selecionados[]"
                                    value="<?= $produto['pk_id_produtos']; ?>"
                                    data-subtotal="<?= $subtotal; ?>">
                            </div>

                            <!-- IMAGEM -->
                            <div class="imagem-produto-carrinho">
                                <img
                                    src="mostrar-imagem.php?id=<?= $produto['pk_id_produtos']; ?>"
                                    alt="<?php echo $produto['nome_produto']; ?>">
                            </div>

                            <!-- INFORMAÇÕES -->
                            <div class="info-produto-carrinho">

                                <h2>
                                    <?php echo $produto['nome_produto']; ?>
                                </h2>

                                <p class="preco-produto">
                                    R$
                                    <?php
                                    echo number_format(
                                        $produto['preco_produto'],
                                        2,
                                        ',',
                                        '.'
                                    );
                                    ?>
                                </p>

                                <!-- QUANTIDADE -->
                                <div class="quantidade-carrinho">

                                    <a
                                        class="btn-quantidade diminuir"
                                        href="carrinho-compras.php?diminuir=<?php echo $produto['pk_id_produtos']; ?>">
                                        -
                                    </a>

                                    <strong>
                                        <?php echo $quantidade; ?>
                                    </strong>

                                    <a
                                        class="btn-quantidade aumentar"
                                        href="carrinho-compras.php?adicionar=<?php echo $produto['pk_id_produtos']; ?>">
                                        +
                                    </a>

                                </div>

                            </div>

                            <!-- SUBTOTAL -->
                            <div class="subtotal-carrinho">

                                <span>Subtotal</span>

                                <strong>
                                    R$
                                    <?php
                                    echo number_format(
                                        $subtotal,
                                        2,
                                        ',',
                                        '.'
                                    );
                                    ?>
                                </strong>

                            </div>

                            <!-- REMOVER -->
                            <div class="acao-carrinho">

                                <a
                                    class="btn-remover"
                                    href="carrinho-compras.php?remover=<?php echo $produto['pk_id_produtos']; ?>">
                                    Remover
                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <!-- PARTE FINAL DO CARRINHO -->
                <div class="resumo-carrinho">

                    <div class="total-carrinho">

                        <span>Total da compra</span>

                        <strong id="total-compra">
                            R$
                            <?php
                            echo number_format(
                                $total_da_compra,
                                2,
                                ',',
                                '.'
                            );
                            ?>
                        </strong>

                    </div>
                    
                    <a href="../produtos/produtos.php" class="btn-produtos">Adicionar mais Produtos</a>

                    <form id="formulario-continuar-compra" action="../banco/verificar-checkout.php" method="POST">

                        <button
                            type="submit"
                            class="btn-continuar">
                            Continuar Compra
                        </button>

                    </form>

                </div>

            <?php endif; ?>

        </div>
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

    <script>
        const checkboxes = document.querySelectorAll(
            '.selecionar-produto input[type="checkbox"]'
        );

        const totalCompra = document.getElementById('total-compra');

        const formularioCompra = document.getElementById(
            'formulario-continuar-compra'
        );

        const selecionarTodos = document.getElementById(
            'selecionar-todos'
        );

        // RECUPERANDO OS PRODUTOS SELECIONADOS

        let produtosSelecionados = JSON.parse(
            localStorage.getItem('produtosSelecionados')
        ) || [];


        // MARCANDO AS CHECKBOXES SALVAS

        checkboxes.forEach(function(checkbox) {

            if (produtosSelecionados.includes(checkbox.value)) {
                checkbox.checked = true;
            }

        });

        // ATUALIZA O TOTAL

        function atualizarTotal() {

            let total = 0;

            checkboxes.forEach(function(checkbox) {

                if (checkbox.checked) {

                    total += parseFloat(
                        checkbox.dataset.subtotal
                    );

                }

            });


            totalCompra.textContent = 'R$ ' + total.toLocaleString(
                'pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );
        }

        // SELECIONAR OU DESMARCAR TODOS

        selecionarTodos.addEventListener('change', function() {

            checkboxes.forEach(function(checkbox) {

                checkbox.checked = selecionarTodos.checked;

            });

            // Atualiza a lista de produtos selecionados
            produtosSelecionados = [];

            checkboxes.forEach(function(checkbox) {

                if (checkbox.checked) {
                    produtosSelecionados.push(checkbox.value);
                }

            });

            // Salva a seleção no navegador
            localStorage.setItem(
                'produtosSelecionados',
                JSON.stringify(produtosSelecionados)
            );

            // Atualiza o total
            atualizarTotal();

        });

        // QUANDO MARCAR/DESMARCAR

        checkboxes.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                if (checkbox.checked) {

                    if (!produtosSelecionados.includes(checkbox.value)) {
                        produtosSelecionados.push(checkbox.value);
                    }

                } else {

                    produtosSelecionados =
                        produtosSelecionados.filter(function(id) {
                            return id !== checkbox.value;
                        });

                }


                // Salva a nova seleção
                localStorage.setItem(
                    'produtosSelecionados',
                    JSON.stringify(produtosSelecionados)
                );


                // Atualiza o total
                atualizarTotal();

            });

        });

        // ATUALIZA O TOTAL AO ABRIR A PÁGINA

        atualizarTotal();


        // CONTINUAR COMPRA

        formularioCompra.addEventListener('submit', function(event) {

            event.preventDefault();

            let selecionados = [];

            checkboxes.forEach(function(checkbox) {

                if (checkbox.checked) {
                    selecionados.push(checkbox.value);
                }

            });


            if (selecionados.length === 0) {

                alert('Selecione pelo menos um produto para continuar.');

                return;

            }


            // Remove campos antigos
            document.querySelectorAll(
                '.produto-selecionado-hidden'
            ).forEach(function(campo) {
                campo.remove();
            });


            // Cria os campos hidden
            selecionados.forEach(function(idProduto) {

                const campo = document.createElement('input');

                campo.type = 'hidden';
                campo.name = 'produtos_selecionados[]';
                campo.value = idProduto;

                campo.classList.add(
                    'produto-selecionado-hidden'
                );

                formularioCompra.appendChild(campo);

            });


            formularioCompra.submit();

        });
    </script>

    <script src="../js/script-geral.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>