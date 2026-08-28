$.get('https://viacep.com.br/ws/13902280/json/', function (dados) {
    console.log(dados);
});

$('#cep').blur(function () {

    var vl = this.value;

    $.get('https://viacep.com.br/ws/' + vl + '/json/', function (dados) {
        $('#rua').val(dados.logradouro);
        $('#bairro').val(dados.bairro);
        $('#cidade').val(dados.localidade);
        $('#estado').val(dados.uf);
    });

    // console.log(vl);
})