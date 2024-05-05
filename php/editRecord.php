<?php
session_start();
try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8", "root", "");

    $recordIdStr = $_POST["recordId"] ?? null;
    if ($recordIdStr !== null) {
        $recordId = intval($recordIdStr);

        // Obter os dados do formulário
        $endereco = $_POST["endereco"] ?? null;
        $cidade = $_POST["cidade"] ?? null;
        $categoria = $_POST["categoria"] ?? null;
        // Adicione outros campos de entrada aqui

        // Verificar se os campos obrigatórios estão presentes
        if ($endereco !== null && $categoria !== null) {
            // Atualizar o registro no banco de dados
            $updateQuery = "UPDATE imoveis SET endereco=?, cidade=?, categoria=? WHERE id=?";
            $preparedStatement = $conn->prepare($updateQuery);
            $preparedStatement->bindParam(1, $endereco, PDO::PARAM_STR);
            $preparedStatement->bindParam(2, $cidade, PDO::PARAM_STR);
            $preparedStatement->bindParam(3, $categoria, PDO::PARAM_STR);
            $preparedStatement->bindParam(4, $recordId, PDO::PARAM_INT);

            // Execute a consulta preparada
            $preparedStatement->execute();

            // Verificar se a atualização foi bem-sucedida
            $rowsUpdated = $preparedStatement->rowCount();
            if ($rowsUpdated > 0) {
                echo json_encode(["success" => true, "message" => "Record updated successfully!"]);

                // Adicionar redirecionamento
                header("Location: painel.php");
                exit();
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update the record."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Missing required fields."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid record ID."]);
    }

    $conn = null;
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
