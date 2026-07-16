<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos - Artesanatos Eloi</title>
    <link rel="stylesheet" href="style-edit-products.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

             <!-- IMAGENS/BOTÕES DAS NOTIFICAÇÕES E DAS CONFIGURAÇÕES -->
            <div class="actions">
                <button><a href=""><img src="../img/bell.svg" alt="Notificações"></a></button>
                <button><img src="../img/gear.svg" alt="Configurações"></button>
            </div>
        </div>

        <!-- PARTE DOS LINKS DE NAVEGAÇÃO -->
         <div class="bottom-bar">
            <a href="../admin/pagina-inicial-adm.php" class="link-animado">Início</a>
            <a href="../editar-produtos/edit-products.php" class="link-animado">Editar Produtos</a>
            <div class="dropdown">
                <a href="#" class="link-animado">Cadastro</a>

                <!-- PARTE DO DROPDOWN QUE SERÁ ACESSADO A PARTIR DO LINK DO CADASTRO -->
                <div class="dropdown-menu">
                    <a href="../cadastrar-produtos/cadastro-produtos.php">Produtos</a>
                    <a href="../categorias/categorias.php">Categorias</a>
                    <a href="../pagamento/forma-pagamento.php">Forma de Pagamento</a>
                    <a href="#">Funcionários</a>
                </div>
            </div>
            <a href="" class="link-animado">Analisar Vendas</a>
            <a href="" class="link-animado"></a>
        </div>
    </div>
    </div>

    <!-- PARTE DO FORMULÁRIO DE CADASTRO DOS PRODUTOS -->
    <div class="container-login">

        <form action="../banco/cadastro-products.php" method="POST" class="form-login" enctype="multipart/form-data">

            <h1>Cadastrar Produto</h1>
            <p class="subtitulo">
                Preencha as informações para adicionar um novo produto.
            </p>

           <!-- SEÇÃO: INFORMAÇÕES DO PRODUTO -->
        <div class="secao">

            <h3>Informações do Produto</h3>

            <div class="campo">
                <label>Nome do Produto</label>
                <input type="text" name="nome" placeholder="Digite o nome do produto" required>
            </div>

            <br>

             <div class="campo">

                    <label class="titulo-radio">Status do Produto</label>

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

        <!-- SEÇÃO: PREÇO -->
        <div class="secao">

            <h3>Valores</h3>

            <div class="linha">

                <div class="campo">
                    <label>Preço</label>
                    <input type="number" step="0.01" name="preco" placeholder="R$ 0,00" required>
                </div>

                <div class="campo">
                    <label>Desconto (%)</label>
                    <input type="number" step="0.01" name="desconto" placeholder="Ex: 15%" required>
                </div>

                <div class="campo">
                    <label>Estoque</label>
                    <input type="number" step="1" name="estoque" placeholder="Ex: 10" required>
                </div>

                    </div>
                </div>

        <!-- SEÇÃO: DESCRIÇÃO -->
        <div class="secao">

            <h3>Descrição</h3>

            <div class="campo">
                <label>Descrição do Produto</label>
                <textarea name="descricao" placeholder="Descreva o produto..." required></textarea>
            </div>

        </div>

        <!-- SEÇÃO: IMAGEM -->
        <div class="secao">

            <h3>Imagem do Produto</h3>

            <div class="campo">
                <label>Foto do Produto</label>
                <input type="file" class="img-produto" name="foto" accept=".jpg,.jpeg,.png" required>
            </div>

        </div>

         <!-- SEÇÃO: DESCRIÇÃO -->
        <div class="secao">

            <h3>Descrição da Imagem (Opcional)</h3>

            <div class="campo">
                <label>Descrição da Imagem</label>
                <textarea name="descricaoImage" placeholder="Descreva a imagem" ></textarea>
            </div>

        </div>

        <!-- BOTÃO -->
        <button type="submit" class="btn-submit">
            Cadastrar Produto
        </button>

    </div>

    </form>
    </div>