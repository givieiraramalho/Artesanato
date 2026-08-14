
    // =========================================
    // ELEMENTOS
    // =========================================

    const botaoDiminuir =
        document.getElementById('diminuir-produto');

    const botaoAumentar =
        document.getElementById('aumentar-quantidade');

    const quantidadeProduto =
        document.getElementById('quantidade-produto');

    const quantidadeContainer =
        document.querySelector('.quantidade-container');

    const botaoCarrinho =
        document.getElementById('btn-add-cart');


    // =========================================
    // ESTOQUE
    // =========================================

    const estoque =
        parseInt(quantidadeContainer.dataset.estoque);


    // =========================================
    // QUANTIDADE INICIAL
    // =========================================

    let quantidade = 1;


    // =========================================
    // ATUALIZAR QUANTIDADE
    // =========================================

    function atualizarQuantidade() {

        quantidadeProduto.textContent = quantidade;


        // Atualiza a quantidade enviada ao PHP
        botaoCarrinho.href =
            "../carrinho-compras/carrinho-compras.php?adicionar=<?= $produto['pk_id_produtos'] ?>&quantidade="
            + quantidade;


        // =====================================
        // BOTÃO -
        // =====================================

        if (quantidade <= 1) {

            botaoDiminuir.disabled = true;

        } else {

            botaoDiminuir.disabled = false;

        }


        // =====================================
        // BOTÃO +
        // =====================================

        if (quantidade >= estoque) {

            botaoAumentar.disabled = true;

        } else {

            botaoAumentar.disabled = false;

        }

    }


    // =========================================
    // AUMENTAR
    // =========================================

    botaoAumentar.addEventListener('click', function () {

        if (quantidade < estoque) {

            quantidade++;

            atualizarQuantidade();

        }

    });


    // =========================================
    // DIMINUIR
    // =========================================

    botaoDiminuir.addEventListener('click', function () {

        if (quantidade > 1) {

            quantidade--;

            atualizarQuantidade();

        }

    });


    // Atualiza o estado inicial
    atualizarQuantidade();