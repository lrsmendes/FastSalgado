<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Imóvel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #ffffff;
            color: #333;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .property-card {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .property-card h1, .property-card p {
            color: #333;
        }
        .property-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .property-info {
            flex: 1 1 55%;
            margin: 10px 0;
            word-wrap: break-word;
        }
        .property-info p {
            margin: 15px 0;
        }
        .property-info strong {
            display: block;
            margin-bottom: 5px;
        }
        .property-image {
            flex: 1 1 40%;
            margin: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .property-image img {
            max-width: 100%;
            border-radius: 10px;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .btn-back {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Início</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="sobre.php">Sobre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contato.php">Contato</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        
        <?php
        if (isset($_GET['id'])) {
            $imovel_id = $_GET['id'];

            $dbHost = "localhost";
            $dbName = "fastimoveis";
            $dbUser = "root";
            $dbPass = "";

            try {
                $conn = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT * FROM imoveis WHERE id = ?");
                $stmt->execute([$imovel_id]);

                if ($stmt->rowCount() > 0) {
                    $imovel = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    echo "<div class='property-card'>";
                    echo "<h1>Detalhes do Imóvel</h1>";
                    echo "<div class='property-content'>";
                    echo "<div class='property-info'>";
                    echo "<p><strong>Cidade:</strong> {$imovel['cidade']}</p>";
                    echo "<p><strong>Endereço:</strong> {$imovel['endereco']}</p>";
                    echo "<p><strong>Categoria:</strong> {$imovel['categoria']}</p>";
                    echo "<p><strong>Preço:</strong> R$ {$imovel['preco']}</p>";
                    echo "<p><strong>Status:</strong> {$imovel['status']}</p>";
                    echo "<p><strong>Descrição:</strong> {$imovel['descricao']}</p>";
                    echo "</div>";

                    $imagePath = "../php/{$imovel['foto']}";
                    echo "<div class='property-image'>";
                    if (file_exists($imagePath)) {
                        echo "<img src='{$imagePath}' alt='{$imovel['descricao']}'>";
                    } else {
                        echo "<p>Imagem não encontrada.</p>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p>Imóvel não encontrado.</p>";
                }
            } catch (PDOException $e) {
                echo "Erro: " . $e->getMessage();
            }
            $conn = null;
        } else {
            header("Location: index.php");
            exit();
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
