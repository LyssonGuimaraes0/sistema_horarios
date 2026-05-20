<!DOCTYPE html>
<html lang="pt-BR">
<?php include_once COMPONENTS_PATH . "/head.php"; ?>

<body>
    <section class="login-section">
        <div class="section-container">
            <div class="container-login">
                <form method="post" class="login-form" id="form-forgotPassword">
                    <div class="head-login">
                        <h1>Recuperar Senha</h1>
                    </div>
                    <div class="body-login">
                        <label class="error-mensagem"></label>
                        <span>Digite seu email registrado no sistema</span>
                        <!--Method Patch ocultado--->
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="input-container">
                            <input type="email" placeholder="teste@gmail.com" name="email" class="input-login" required>
                        </div>
                        <button type="submit" class="btn-login" value="">Verificar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script type="module" src="public/assets/js/page/login.js"></script>
</body>

</html>