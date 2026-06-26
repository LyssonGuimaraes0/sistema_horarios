<!DOCTYPE html>
<html lang="pt-BR">
<?php include_once COMPONENTS_PATH . "/head.php"; ?>

<body>
    <section class="login-section">
        <div class="section-container">
            <div class="container-login">
                <div class="loading-overlay hidden"></div>
                <form method="post" class="login-form" id="form-login">
                    <div class="head-login">
                        <h1>Sistema de Frequencia</h1>
                    </div>
                    <div class="body-login">
                        <label class="error-mensagem"></label>
                        <div class="input-container">
                            <label>Usuario:</label>
                            <input type="text" placeholder="usuario" name="usuario" class="input-login" required>
                        </div>
                        <div class="input-container">
                            <label>Senha:</label>
                            <input type="password" placeholder="Senha" name="password" class="input-login" required>
                        </div>
                        <button type="submit" class="btn-login">
                            Login
                        </button>
                        <a class="btn-esqueceu-senha" href="./forgotpassword">Esqueceu a senha?</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script type="module" src="public/assets/js/page/login.js"></script>
</body>

</html>