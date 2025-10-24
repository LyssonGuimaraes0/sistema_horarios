<?php
session_start();
//Chamada das configurações do banco de dados e logout
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
$conn = conexao_banco();

if ($_SESSION['user_id'] == null) {
    header("location: index.php");
    exit();
}

time_out();

//conexão com o banco de dados para Busca dados do usuário

$user_id = $_SESSION['user_id'];
$consulta_user = $conn->prepare("SELECT * FROM user WHERE id = ?");
$consulta_user->bind_param("i", $user_id);
$consulta_user->execute();
$result_user = $consulta_user->get_result();
$dados_user = $result_user->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="pt-br">
<!-- Cabeçalho comum incluído -->
<?php include('./snippets/head.html'); ?>

<body>

    <!-- Cabeçalho comum incluído -->
    <?php include('./snippets/navbar.html'); ?>

    <main class="main-content">
        <section>
            <header class="main-header">
                <h1>Bem-vindo, <?php echo $dados_user['nome'] ?> !</h1>
            </header>

            <div class="cards-grid">
                <section class="stats-cards">
                    <div class="card stat-card">
                        <h3>Registros Este Mês</h3>
                        <span class="stat-value">-</span>
                        <div class="stat-indicator indicator-blue"></div>
                    </div>
                    <div class="card stat-card">
                        <h3>Presentes</h3>
                        <span class="stat-value">-</span>
                        <div class="stat-indicator indicator-green"></div>
                    </div>
                    <div class="card stat-card">
                        <h3>Faltas</h3>
                        <span class="stat-value">-</span>
                        <div class="stat-indicator indicator-red"></div>
                    </div>
                    <div class="card stat-card">
                        <h3>Licenças</h3>
                        <span class="stat-value">-</span>
                        <div class="stat-indicator indicator-yellow"></div>
                    </div>
                </section>

                <section class="action-cards">
                    <div class="card action-card">
                        <i class="fa-solid fa-clock card-icon"></i>
                        <h3>Registrar Ponto</h3>
                        <p>Registre seu horário de entrada ou saída.</p>
                        <a href="registrar-ponto.php" class="btn">Ir para Registro de Ponto</a>
                    </div>
                    <div class="card action-card">
                        <i class="fa-solid fa-file-lines card-icon"></i>
                        <h3>Justificativas</h3>
                        <p>Justifique ausências ou anexe documentos.</p>
                        <a href="#" class="btn">Ir para Justificativas</a>
                    </div>
                    <div class="card action-card">
                        <i class="fa-solid fa-print card-icon"></i>
                        <h3>Imprimir Frequência</h3>
                        <p>Imprima seu registro para assinatura do coordenador.</p>
                        <a href="#" class="btn">Ir para Impressão</a>
                    </div>
                </section>
            </div>
        </section>


        <!-- Chamada dos Scripts -->
        <?php include('./snippets/script.html'); ?>
    </main>

</body>

</html>