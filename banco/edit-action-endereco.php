<?php

require 'config.php';

$id = filter_input(INPUT_POST, 'id');
$rua = filter_input(INPUT_POST, 'rua');
$bairro = filter_input(INPUT_POST, 'bairro');
$cidade = filter_input(INPUT_POST, 'cidade');
$cep = filter_input(INPUT_POST, 'cep');
$estado = filter_input(INPUT_POST, 'estado');
$numeroCasa = filter_input(INPUT_POST, 'casa');
$numeroApartamento = filter_input(INPUT_POST, 'apartamento');
$referencia = filter_input(INPUT_POST, 'referencia');

if ($id && $rua && $bairro && $cidade && $cep && $estado && $numeroCasa) {

    $sql = $pdo->prepare("UPDATE endereco SET cep = :cep, cidade = :cidade, estado = :estado,
     numero_casa = :numeroCasa, nome_rua = :rua, nome_bairro = :bairro, apartamento = :apartamento, referencia = :referencia
      WHERE id_usuario = :id");

    // Associando os valores aos parâmetros
    $sql->bindValue(':id', $id);
    $sql->bindValue(':cep', $cep);
    $sql->bindValue(':cidade', $cidade);
    $sql->bindValue(':estado', $estado);
    $sql->bindValue(':numeroCasa', $numeroCasa);
    $sql->bindValue(':rua', $rua);
    $sql->bindValue(':bairro', $bairro);
    $sql->bindValue(':apartamento', $numeroApartamento ?: null);
    $sql->bindValue(':referencia', $referencia ?: null);

    // Executando os comandos
    $sql->execute();

     echo "
    <script>
        alert('Endereço atualizado com sucesso!');
        window.location.href = '../pagina-usuario/pagina-usuario.php';
    </script>
    ";
    exit;

} else {

    echo "
    <script>
        alert('Erro ao atualizar o endereço!');
        window.history.back();
    </script>
    ";
    exit;
}