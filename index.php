<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Imóveis</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .imagem {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
<a href="login.php">Login</a>

<h2>Lista de Imóveis</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Endereço</th>
        <th>Categoria</th>
        <th>Preço</th>
        <th>Nome do Vendedor</th>
        <th>Telefone do Vendedor</th>
        <th>Email do Vendedor</th>
        <th>Foto</th>
        <th>Status</th>
    </tr>
    <?php
    try {
        $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8mb4", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM imoveis";
        $stmt = $conn->query($query);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['endereco'] . "</td>";
            echo "<td>" . $row['categoria'] . "</td>";
            echo "<td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>";
            echo "<td>" . $row['nome_vendedor'] . "</td>";
            echo "<td>" . $row['telefone_vendedor'] . "</td>";
            echo "<td>" . $row['email_vendedor'] . "</td>";
            echo "<td><img src='" . $row['foto'] . "' alt='Foto do Imóvel' class='imagem'></td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "</tr>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
    ?>
</table>

</body>
</html>
