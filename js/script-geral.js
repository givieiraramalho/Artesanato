

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