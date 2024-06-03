<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION["nomeUsuario"])) {
    header("Location: login.php");
    exit();
}

// Conexão ao Banco de Dados
try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['recordId']) && is_numeric($_POST['recordId'])) {
        $recordId = $_POST['recordId'];
        
        $endereco = $_POST['endereco'];
        $cidade = $_POST['cidade'];
        $categoria = $_POST['categoria'];
        $preco = $_POST['preco'];
        $nome_vendedor = $_POST['nome_vendedor'];
        $telefone_vendedor = $_POST['telefone_vendedor'];
        $email_vendedor = $_POST['email_vendedor'];
        $descricao = $_POST['descricao'];
        $status = $_POST['status'];

        // Verifica se todos os campos obrigatórios foram preenchidos
        if ($endereco && $cidade && $categoria && $preco && $nome_vendedor && $telefone_vendedor && $email_vendedor && $descricao && $status) {
            
            // Inicialmente, $targetFile é null
            $targetFile = null;

            // Verifica se foi enviada uma nova foto
            if (!empty($_FILES['foto']['name'])) {
                $targetDir = "uploads/";
                $targetFile = $targetDir . basename($_FILES["foto"]["name"]);

                // Tenta mover o arquivo para o diretório de destino
                if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFile)) {
                    $targetFile = null; // Se o upload falhar, $targetFile deve ser null
                }
            }

            // Atualizar os dados no banco de dados
            $query = "UPDATE imoveis SET endereco = ?, cidade = ?, categoria = ?, preco = ?, nome_vendedor = ?, telefone_vendedor = ?, email_vendedor = ?, descricao = ?, status = ?";

            // Se uma nova foto foi enviada, incluí-la na query
            if ($targetFile !== null) {
                $query .= ", foto = ?";
            }

            $query .= " WHERE id = ?";

            $preparedStatement = $conn->prepare($query);

            $preparedStatement->bindParam(1, $endereco, PDO::PARAM_STR);
            $preparedStatement->bindParam(2, $cidade, PDO::PARAM_STR);
            $preparedStatement->bindParam(3, $categoria, PDO::PARAM_STR);
            $preparedStatement->bindParam(4, $preco, PDO::PARAM_STR);
            $preparedStatement->bindParam(5, $nome_vendedor, PDO::PARAM_STR);
            $preparedStatement->bindParam(6, $telefone_vendedor, PDO::PARAM_STR);
            $preparedStatement->bindParam(7, $email_vendedor, PDO::PARAM_STR);
            $preparedStatement->bindParam(8, $descricao, PDO::PARAM_STR);
            $preparedStatement->bindParam(9, $status, PDO::PARAM_STR);

            if ($targetFile !== null) {
                $preparedStatement->bindParam(10, $targetFile, PDO::PARAM_STR);
                $preparedStatement->bindParam(11, $recordId, PDO::PARAM_INT);
            } else {
                $preparedStatement->bindParam(10, $recordId, PDO::PARAM_INT);
            }

            $preparedStatement->execute();

            // Redirecionar para a página do painel
            header("Location: painel.php");
            exit();
        } else {
            // Campos obrigatórios não foram preenchidos
            echo "Erro: Preencha todos os campos obrigatórios.";
        }
    } else {
        // ID inválido
        echo "Erro: ID inválido.";
    }
} catch (PDOException $e) {
    echo "Erro na conexão ou consulta: " . $e->getMessage();
}
?>
