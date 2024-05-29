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
        $preco = $_POST["preco"] ?? null;
        $nome_vendedor = $_POST["nome_vendedor"] ?? null;
        $telefone_vendedor = $_POST["telefone_vendedor"] ?? null;
        $email_vendedor = $_POST["email_vendedor"] ?? null;
        $status = $_POST["status"] ?? null;
        $descricao = $_POST["descricao"] ?? null;
        // Adicione outros campos de entrada aqui

        // Verificar se os campos obrigatórios estão presentes
        if ($endereco !== null && $categoria !== null) {
            // Atualizar o registro no banco de dados
            $updateQuery = "UPDATE imoveis SET endereco=?, cidade=?, categoria=?, preco=?, nome_vendedor=?, telefone_vendedor=?, email_vendedor=?, status=?, descricao=? WHERE id=?";
            $preparedStatement = $conn->prepare($updateQuery);
            $preparedStatement->bindParam(1, $endereco, PDO::PARAM_STR);
            $preparedStatement->bindParam(2, $cidade, PDO::PARAM_STR);
            $preparedStatement->bindParam(3, $categoria, PDO::PARAM_STR);
            $preparedStatement->bindParam(4, $preco, PDO::PARAM_STR);
            $preparedStatement->bindParam(5, $nome_vendedor, PDO::PARAM_STR);
            $preparedStatement->bindParam(6, $telefone_vendedor, PDO::PARAM_STR);
            $preparedStatement->bindParam(7, $email_vendedor, PDO::PARAM_STR);
            $preparedStatement->bindParam(8, $status, PDO::PARAM_STR);
            $preparedStatement->bindParam(9, $descricao, PDO::PARAM_STR);
            $preparedStatement->bindParam(10, $recordId, PDO::PARAM_INT);

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
