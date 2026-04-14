<?php

//Função para deslogar da pagina

session_start();
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destroi a sessão
header("Location: ../../public/index.php"); // Redireciona para a página de login
exit();


?>