const checkbox = document.getElementById("moraApartamento");
const inputApartamento = document.getElementById("apartamento");
const labelApartamento = document.getElementById("labelApartamento");

checkbox.addEventListener("change", () => {

    if (checkbox.checked) {

        inputApartamento.disabled = false;
        inputApartamento.required = true;

        labelApartamento.classList.remove("label-desabilitado");

    } else {

        inputApartamento.disabled = true;
        inputApartamento.required = false;
        inputApartamento.value = "";

        labelApartamento.classList.add("label-desabilitado");

    }

});

document.getElementById('cep').addEventListener('input', function (e) {
    let value = e.target.value;
    
    // Remove tudo o que não for dígito
    value = value.replace(/\D/g, "");
    
    // Captura apenas os primeiros 8 dígitos
    value = value.substring(0, 8);
    
    // Aplica a máscara: se passar de 5 dígitos, insere o traço
    if (value.length > 5) {
        value = value.replace(/^(\d{5})(\d)/, "$1-$2");
    }
    
    // Atualiza o valor do campo visualmente
    e.target.value = value;
});
