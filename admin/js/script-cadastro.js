const btnAdm = document.getElementById('btn-adm');
const btnFuncionario = document.getElementById('btn-funcionario');

const formAdm = document.getElementById('form-adm');
const formFuncionario = document.getElementById('form-funcionario');

const telefone = document.getElementById('telefone');

btnAdm.addEventListener('click', function () {
    btnAdm.hidden = true;
    btnFuncionario.hidden = true;

    formAdm.hidden = false;
    formFuncionario.hidden = true;
});

btnFuncionario.addEventListener('click', function () {
    btnAdm.hidden = true;
    btnFuncionario.hidden = true;

    formAdm.hidden = true;
    formFuncionario.hidden = false;
});

telefone.addEventListener('input', function (e) {
    let valor = e.target.value.replace(/\D/g, '');

    valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2');
    valor = valor.replace(/(\d{5})(\d)/, '$1-$2');

    e.target.value = valor;
});

