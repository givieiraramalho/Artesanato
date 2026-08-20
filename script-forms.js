//AQUI SERÁ FEITA UMA "AUTOMATIZAÇÃO" PARA O CAMPO DO CPF
document.getElementById('cpf').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, ''); //CASO AQUI TENHA NÚMEROS, ELE IRÁ ELIMINAR
    if (value.length > 3 && value.length <= 6)
        value = value.replace(/(\d{3})(\d+)/, '$1.$2');
    else if (value.length > 6 && value.length <= 9)
        value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1.$2.$3');
    else if (value.length > 9)
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d+)/, '$1.$2.$3-$4');
    e.target.value = value;
});

//AQUI É UMA FUNÇÃO QUE IRÁ "AUTOMATIZAR" O CAMPO DO TELEFONE
function aplicarMascara(campo) {
    let valor = campo.value.replace(/\D/g, '');

    // Limita a 11 dígitos
    valor = valor.substring(0, 11);

    if (valor.length > 10) {
        // Celular: (99) 99999-9999
        valor = valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } else if (valor.length > 6) {
        // Telefone fixo: (99) 9999-9999
        valor = valor.replace(/^(\d{2})(\d{4})(\d+)$/, '($1) $2-$3');
    } else if (valor.length > 2) {
        valor = valor.replace(/^(\d{2})(\d+)/, '($1) $2');
    } else if (valor.length > 0) {
        valor = valor.replace(/^(\d+)/, '($1');
    }

    campo.value = valor;
}