<?php

session_start();
session_unset();
session_destroy();

echo "
<script>
    alert('Logout realizado com sucesso!');
    window.location.href = '../admin/login-adm.php';
</script>
";
exit();