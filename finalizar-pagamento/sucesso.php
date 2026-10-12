
<?php

session_start();

require_once '../banco/config.php';
require_once '../stripe/init.php';

// ==========================================
// 1. VERIFICAR SE O USUÁRIO ESTÁ LOGADO
// ==========================================

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$idUsuario = (int) $_SESSION['user_id'];

// ==========================================
// 2. CONFIGURAR O STRIPE
// ==========================================

// Coloque sua chave secreta de TESTE
$stripe_secret_key = 'sk_test_51ULgfl2Lfqa7yyMXvXUlTSMLVf7r3qg2aCiZrGv9JxJrtQIQMUsU9aMm2lpSe0lxUZEHs5iYCgLvxMKwSpVTFBHi00lp3HCp3Y';

\Stripe\Stripe::setApiKey($stripe_secret_key);

// ==========================================
// 3. RECEBER O ID DA SESSÃO STRIPE
// ==========================================

$sessionId = $_GET['session_id'] ?? '';

if (!is_string($sessionId) || $sessionId === '') {
    http_response_code(400);
    exit("Sessão de pagamento não informada.");
}

try {

    // Consulta a sessão diretamente no Stripe
    $sessao = \Stripe\Checkout\Session::retrieve($sessionId);

    // Confere se a sessão pertence ao usuário logado
    if ((string) $sessao->client_reference_id !== (string) $idUsuario) {
        http_response_code(403);
        exit("Esta sessão de pagamento não pertence à sua conta.");
    }

    // Confirma que o pagamento foi realizado
    if ($sessao->payment_status !== 'paid') {
        exit(
            "O pagamento ainda não foi confirmado. "
            . "O pedido permanece aguardando pagamento."
        );
    }

    // ==========================================
    // 4. LOCALIZAR O PEDIDO
    // ==========================================

    $sqlPedido = $pdo->prepare("
        SELECT
            p.pk_id_pedidos,
            p.total_pedido,
            p.status_pedido,
            p.stripe_session_id
        FROM pedidos p
        INNER JOIN pessoa pe ON pe.id = p.id_pessoa
        WHERE p.stripe_session_id = ?
          AND pe.id_usuario = ?
        LIMIT 1
    ");

    $sqlPedido->execute([
        $sessao->id,
        $idUsuario
    ]);

    $pedido = $sqlPedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        http_response_code(404);
        exit("Não foi possível localizar seu pedido.");
    }

    // ==========================================
    // 5. CONFERIR O VALOR DO PAGAMENTO
    // ==========================================

    $valorEsperado = (int) round(
        (float) $pedido['total_pedido'] * 100
    );

    if (
        $sessao->currency !== 'brl' ||
        (int) $sessao->amount_total !== $valorEsperado
    ) {
        http_response_code(400);
        exit("O valor pago não corresponde ao valor do pedido.");
    }

    // ==========================================
    // 6. ATUALIZAR O PEDIDO E LIMPAR O CARRINHO
    // ==========================================

    // Evita repetir a limpeza se a página for atualizada
    if ($pedido['status_pedido'] !== 'Pago') {

        if ($pedido['status_pedido'] !== 'Aguardando pagamento') {
            exit("O pedido não está aguardando pagamento.");
        }

        $pdo->beginTransaction();

        try {

            // Atualiza o status somente se ainda estiver pendente
            $sqlAtualiza = $pdo->prepare("
                UPDATE pedidos
                SET status_pedido = 'Pago'
                WHERE pk_id_pedidos = ?
                  AND stripe_session_id = ?
                  AND status_pedido = 'Aguardando pagamento'
            ");

            $sqlAtualiza->execute([
                $pedido['pk_id_pedidos'],
                $sessao->id
            ]);

            // Se o pedido não foi atualizado, não limpa o carrinho
            if ($sqlAtualiza->rowCount() !== 1) {
                throw new Exception(
                    "Não foi possível confirmar a atualização do pedido."
                );
            }

            // Remove somente os produtos selecionados,
            // se essa lista existir na sessão
            $selecionados = $_SESSION['produtos_selecionados'] ?? [];

            if (is_array($selecionados) && count($selecionados) > 0) {

                $sqlExcluir = $pdo->prepare("
                    DELETE FROM carrinho
                    WHERE id_usuario = ?
                      AND id_produtos = ?
                ");

                foreach ($selecionados as $idProduto) {

                    $idProduto = (int) $idProduto;

                    if ($idProduto > 0) {
                        $sqlExcluir->execute([
                            $idUsuario,
                            $idProduto
                        ]);

                        unset($_SESSION['carrinho'][$idProduto]);
                    }
                }

            } else {

                // Se não há seleção específica, limpa o carrinho inteiro
                $sqlExcluir = $pdo->prepare("
                    DELETE FROM carrinho
                    WHERE id_usuario = ?
                ");

                $sqlExcluir->execute([$idUsuario]);

                $_SESSION['carrinho'] = [];
            }

            unset($_SESSION['produtos_selecionados']);

            $pdo->commit();

            // Atualiza o status usado para exibir a confirmação
            $pedido['status_pedido'] = 'Pago';

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

} catch (\Stripe\Exception\ApiErrorException $e) {

    error_log($e->getMessage());

    http_response_code(502);
    exit("Não foi possível consultar o pagamento no Stripe. Tente novamente.");

} catch (Throwable $e) {

    error_log($e->getMessage());

    http_response_code(500);
    exit("Ocorreu um erro ao confirmar o pedido.");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Compra Confirmada - Artesanatos Eloi</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <main class="container py-5 text-center">

        <div class="card shadow-sm mx-auto p-4" style="max-width: 600px;">

            <h1 class="text-success mb-3">
                Compra confirmada!
            </h1>

            <p>
                O Stripe confirmou seu pagamento e seu pedido foi registrado.
            </p>

            <p>
                Número do pedido:
                <strong>
                    <?= htmlspecialchars(
                        (string) $pedido['pk_id_pedidos']
                    ); ?>
                </strong>
            </p>

            <p>
                Total pago:
                <strong>
                    R$ <?= number_format(
                        (float) $pedido['total_pedido'],
                        2,
                        ',',
                        '.'
                    ); ?>
                </strong>
            </p>

            <a
                href="../pagina-usuario/pagina-usuario.php"
                class="btn btn-success mt-3"
            >
                Voltar para minha conta
            </a>

        </div>

    </main>

</body>
</html>