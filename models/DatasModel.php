<?php 

require_once  __DIR__ . '/../settings/conexao.php';

//Buscar Horas Registradas pelo usuario
function BuscarHorasRegistradas($id_user)
{

    $conn = conexao_banco();

    $stmt = $conn->prepare(
        'SELECT * FROM ponto_diario WHERE usuario_id = ?'
    );
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Inicializa o array
    $datas_registradas = [];

    while ($row = $result->fetch_assoc()) {
        // data_completo deve estar no formato YYYY-MM-DD
        $datas_registradas[$row['data_completo']] = $row;
    }

    $conn->close();

    return $datas_registradas;
}





?>