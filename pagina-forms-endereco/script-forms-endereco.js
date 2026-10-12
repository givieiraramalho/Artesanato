const checkboxApartamento = document.getElementById("moraApartamento");
const campoApartamento = document.getElementById("apartamento");
const labelApartamento = document.getElementById("labelApartamento");

checkboxApartamento.addEventListener("change", function() {

    if (this.checked) {

        campoApartamento.disabled = false;
        campoApartamento.required = true;

        labelApartamento.style.color = "#222";

    } else {

        campoApartamento.disabled = true;
        campoApartamento.required = false;
        campoApartamento.value = "";

        labelApartamento.style.color = "#999";

    }

});