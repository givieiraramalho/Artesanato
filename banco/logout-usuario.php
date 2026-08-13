<?php

session_start();
session_unset();
session_destroy();

echo "
<script>
    alert('Logout realizado com sucesso!');
    window.location.href = '../pagina-inicial/inicio.php';
</script>
";
exit();