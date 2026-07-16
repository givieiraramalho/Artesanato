<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-cadastro.css">
</head>
<body>
    <div class="header">
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
                    <button><a href="../pagina-inicial/inicio.php"><img src="../img/person.svg" alt="Login"></a></button>
                    <button><img src="../img/cart3.svg" alt="Carrinho"></button>
                </div>
            </div>

            <!-- PARTE DE BAIXO DO TOP BAR COM OS LINKS DE NAVEGAÇÃO -->
            <div class="bottom-bar">
               <a href="../pagina-inicial/inicio.php" class="link-animado">Início</a>
                    <a href="../produtos/produtos.php" class="link-animado">Produtos</a>
                    <div class="dropdown">
                    <a href="#" class="link-animado">Categorias</a>

                    <div class="dropdown-menu">
                        <a href="#">Todos</a>
                        <a href="#">Recomendados</a>
                        <a href="#">Destaques</a>
                        <a href="#">Promoções</a>
                        <a href="#">Mais Vendidos</a>
                    </div>
                </div>
                    <a href="../sobre/sobre.php" class="link-animado">Sobre</a>
            </div>
        </div>
    </div>

    <div class="container-login">
            <form action="../banco/cadastrar-action.php" method="POST" class="form-login">
                <img src="../img/logo-ae.png" alt="">
                <h1>Cadastrar</h1>
                <h2>Nome</h2>
                <input type="text" name="nome" class="input" placeholder="Nome Completo" required>
                <h2>Email</h2>
                <input type="email" name="email" class="input" placeholder="Digite seu Email" required>
                <h2>Senha</h2>
                <input type="password" name="senha" class="input" placeholder="Digite uma Senha" required>
                <h2>Repita sua Senha</h2>
                <input type="password" name="repetir_senha" class="input" placeholder="Digite uma Senha" required>
                <button type="submit" class="btn-submit">Cadastrar</button>
                <a href="../login/login.php">Já possui conta? Entrar</a>

            </form>
        </div>
    
</body>
</html>