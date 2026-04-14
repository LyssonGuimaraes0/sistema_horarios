<?php

require_once '../models/UsuarioModel.php';

function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        $RegistroUsuario = buscar_usuario($usuario, $password);

        if ($RegistroUsuario) {
            session_start();
            $_SESSION['user_id'] = $RegistroUsuario['id'];
            header("location: ./shared/home.php");
            exit;

        } else {
            $mensagem = "Usuario ou senha errada<br>Tente Novamente";
            MontarRespostaModal("falha", $mensagem);
            header("location: ./index.php");
            exit;
        }
    }

}


?>