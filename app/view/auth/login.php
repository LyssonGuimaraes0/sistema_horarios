
<!DOCTYPE html>
<html lang="pt-BR">
    <?php include_once COMPONENTS_PATH . "/head.php";?>
<body>
    <section class="login-section">
    <div class="section-container">
        <div class="container-login">
            <form method="post" class="login-form" id="form-login">
                <h1>Sistema de Frequencia</h1>
                <input type="text" placeholder="usuario" name="usuario" class="input-login"  required>
                <input type="password" placeholder="Senha" name="password" class="input-login"  required>
                <button type="submit" class="btn-login" value="">Login</button>
                <a class="btn-esqueceu-senha" href="">Esqueceu a senha?</a>
            </form>
        </div>
    </div>
</section>

<script type="module" src="public/assets/js/page/login.js"></script>
</body>
</html>




