//Criando um alert para verificar se o usuário está ou não logado
function mostrarAlerta() {

    //Se o usuário não estiver logado, vai aparecer um alert falando para ele realizar o login
    if (!usuarioLogado) {

        alert("Atenção! Para continuar, é necessário realizar o seu Login e/ou Cadastro!");

        window.location.href = "../login/login.php";

    } else {

        //Caso ele esteja logado, vai redirecionar para a página de produtos
        window.location.href = "../produtos/produtos.php";

    }
}

document.addEventListener('DOMContentLoaded', function () {

    const logo = document.getElementById('logo-menu');
    const menu = document.getElementById('menu-mobile');

    const categorias = document.getElementById('categorias-mobile');
    const dropdownCategorias = categorias.closest('.dropdown');


    /*
    ==========================================
    LOGO
    ==========================================
    */

    logo.addEventListener('click', function (event) {

        if (window.innerWidth <= 768) {

            event.preventDefault();

            menu.classList.toggle('menu-aberto');

        }

    });


    /*
    ==========================================
    CATEGORIAS
    ==========================================
    */

    categorias.addEventListener('click', function (event) {

        if (window.innerWidth <= 768) {

            event.preventDefault();

            dropdownCategorias.classList.toggle('dropdown-aberto');

        }

    });

});

/* PARTE DA ANIMAÇÃO DO CARROSSEL */
document.addEventListener("DOMContentLoaded", () => {

    const gallery = document.querySelector(".gallery");
    const cards = document.querySelectorAll(".content");
    const dotsContainer = document.querySelector(".carousel-dots");
    const container = document.querySelector(".carousel-container");

    let indiceAtual = 0;
    let cardsVisiveis = 5;
    let totalPaginas = 0;
    let autoplay;

    function atualizarCardsVisiveis() {

        if (window.innerWidth <= 768) {
            cardsVisiveis = 1;
        }
        else if (window.innerWidth <= 1024) {
            cardsVisiveis = 2;
        }
        else {
            cardsVisiveis = 5;
        }

        totalPaginas = cards.length - cardsVisiveis + 1;

        if (indiceAtual >= totalPaginas) {
            indiceAtual = 0;
        }
    }

    function criarDots() {

        dotsContainer.innerHTML = "";

        for (let i = 0; i < totalPaginas; i++) {

            const dot = document.createElement("span");

            dot.classList.add("dot");

            if (i === indiceAtual) {
                dot.classList.add("active");
            }

            dot.addEventListener("click", () => {
                indiceAtual = i;
                moverCarousel();
            });

            dotsContainer.appendChild(dot);
        }
    }

    function atualizarDots() {

        const dots = document.querySelectorAll(".dot");

        dots.forEach(dot => {
            dot.classList.remove("active");
        });

        if (dots[indiceAtual]) {
            dots[indiceAtual].classList.add("active");
        }
    }

    function moverCarousel() {

        const estilo = getComputedStyle(gallery);

        const gap =
            parseInt(estilo.gap);

        const larguraCard =
            cards[0].offsetWidth + gap;

        gallery.style.transform =
            `translateX(-${indiceAtual * larguraCard}px)`;

        atualizarDots();
    }

    function proximoSlide() {

        indiceAtual++;

        if (indiceAtual >= totalPaginas) {
            indiceAtual = 0;
        }

        moverCarousel();
    }

    function iniciarAutoplay() {

        autoplay = setInterval(() => {
            proximoSlide();
        }, 4000);

    }

    atualizarCardsVisiveis();
    criarDots();
    moverCarousel();
    iniciarAutoplay();

    container.addEventListener("mouseenter", () => {
        clearInterval(autoplay);
    });

    container.addEventListener("mouseleave", () => {
        iniciarAutoplay();
    });

    window.addEventListener("resize", () => {

        atualizarCardsVisiveis();
        criarDots();
        moverCarousel();

    });

});
