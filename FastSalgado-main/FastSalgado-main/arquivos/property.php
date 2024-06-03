<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Imóvel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Adicione seus estilos personalizados aqui */
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <?php
                // Verifica se o ID do imóvel foi passado na URL
                if(isset($_GET['id'])) {
                    // Recupera o ID do imóvel da URL
                    $imovel_id = $_GET['id'];

                    // Conexão com o banco de dados
                    $dbHost = "localhost";
                    $dbName = "fastimoveis";
                    $dbUser = "root";
                    $dbPass = "";

                    try {
                        $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Consulta SQL para buscar as informações do imóvel com base no ID
                        $stmt = $conn->prepare("SELECT * FROM imoveis WHERE id = ?");
                        $stmt->execute([$imovel_id]);

                        // Verifica se o imóvel foi encontrado
                        if($stmt->rowCount() > 0) {
                            $imovel = $stmt->fetch(PDO::FETCH_ASSOC);
                            // Exibe as informações do imóvel
                            echo "<h1 class='mt-4'>Informações do Imóvel</h1>";
                            echo "<p><center><strong>Cidade:</strong> {$imovel['cidade']}</center></p>";
                            echo "<p><center><strong>Endereço:</strong> {$imovel['endereco']}</center></p>";
                            echo "<p><center><strong>Categoria:</strong> {$imovel['categoria']}</center></p>";
                            echo "<p><center><strong>Preço:</strong> R$ {$imovel['preco']}</center></p>";
                            echo "<p><center><strong>Status:</strong> {$imovel['status']}</center></p>";
                            // Exibe a imagem do imóvel
                            echo "<img src='../php/{$imovel['foto']}' alt='{$imovel['foto']}' class='img-fluid mt-4'>";
                        } else {
                            echo "<p>Imóvel não encontrado.</p>";
                        }
                    } catch(PDOException $e) {
                        echo "Erro: " . $e->getMessage();
                    }
                    $conn = null; // Fecha a conexão
                } else {
                    // Se nenhum ID foi passado na URL, redireciona para a página inicial
                    header("Location: index.php");
                    exit();
                }
                ?>
            </div>
            <div class="col-md-6">
                <!-- Adicione informações adicionais aqui, se necessário -->
                <button class="btn btn-primary mt-4" onclick="window.location.href='index.php'">Voltar à Página Inicial</button>
            </div>
        </div>
    </div>
</body>
</html>
