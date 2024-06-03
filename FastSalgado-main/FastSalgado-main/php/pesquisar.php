<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION["nomeUsuario"])) {
    header("Location: login.php");
    exit();
}

$nomeUsuario = $_SESSION["nomeUsuario"];

$total_reg = 5; // Número de registros por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $total_reg;

// Conexão ao Banco de Dados
try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    exit();
}

// Verificar se o formulário de pesquisa foi submetido
if(isset($_POST['pesquisa'])) {
    $termo = $_POST['pesquisa'];

    // Preparar a consulta SQL para pesquisar em várias colunas
    $query = "SELECT * FROM imoveis WHERE 
                cidade LIKE :termo OR 
                endereco LIKE :termo OR 
                categoria LIKE :termo OR 
                preco LIKE :termo OR 
                nome_vendedor LIKE :termo OR 
                telefone_vendedor LIKE :termo OR 
                email_vendedor LIKE :termo OR 
                status LIKE :termo OR 
                descricao LIKE :termo";

    // Preparar a declaração SQL
    $stmt = $conn->prepare($query);

    // Bind do parâmetro
    $termoPesquisa = "%$termo%";
    $stmt->bindParam(':termo', $termoPesquisa, PDO::PARAM_STR);

    // Executar a consulta
    $stmt->execute();

    // Obter resultados
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FastImóveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <style>
        .edit-icon { color: blue; }
        .delete-icon { color: red; }
        .icon-button { cursor: pointer; transition: color 0.2s; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand mt-2 mt-lg-0" href="painel.php">FastImóveis</a>
                <a class="navbar-brand mt-2 mt-lg-0" href="#">Pesquisar</a>
            </div>
            <div class="d-flex align-items-center">
                <a class="text-reset me-3" href="#"><i class="fas fa-shopping-cart"></i></a>
                <div class="dropdown">
                    <a><h5 class="mt-3" style="color: white;">Bem-vindo, <?= $nomeUsuario ?>!</h5></a>
                    <div class="ms-3">
                        <a href="../arquivos/index.php" class="btn btn-outline-light">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Body content -->
    <div class="container">
        <h2 class="mt-5">Pesquisa de Imóveis</h2>

        <!-- Formulário de pesquisa -->
        <form method="POST">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Pesquisar..." name="pesquisa">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
        </form>

        <?php if(isset($resultados)): ?>
        <!-- Tabela de resultados -->
        <table id="imoveisTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Endereço</th>
                    <th>Cidade</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Nome do Vendedor</th>
                    <th>Telefone do Vendedor</th>
                    <th>Email do Vendedor</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($resultados as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['endereco'] ?></td>
                        <td><?= $row['cidade'] ?></td>
                        <td><?= $row['categoria'] ?></td>
                        <td><?= $row['preco'] ?></td>
                        <td><?= $row['nome_vendedor'] ?></td>
                        <td><?= $row['telefone_vendedor'] ?></td>
                        <td><?= $row['email_vendedor'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><img src="<?= $row['foto'] ?>" alt="<?= $row['foto'] ?>" width="100"></td>
                        <td><?= $row['descricao'] ?></td>
                        <!-- Adicione as ações aqui, se necessário -->
                        <td>
                            <!-- Botão Visualizar -->
                            <a href="#" class="icon-button view-icon" data-bs-toggle="modal" data-bs-target="#viewModal-<?= $row['id'] ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                        </td>
                    </tr>
                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal-<?= $row['id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel-<?= $row['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel-<?= $row['id'] ?>">Detalhes do Imóvel</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID:</strong> <?= $row['id'] ?></p>
                                    <p><strong>Endereço:</strong> <?= $row['endereco'] ?></p>
                                    <p><strong>Cidade:</strong> <?= $row['cidade'] ?></p>
                                    <p><strong>Categoria:</strong> <?= $row['categoria'] ?></p>
                                    <p><strong>Preço:</strong> <?= $row['preco'] ?></p>
                                    <p><strong>Nome do Vendedor:</strong> <?= $row['nome_vendedor'] ?></p>
                                    <p><strong>Telefone do Vendedor:</strong> <?= $row['telefone_vendedor'] ?></p>
                                    <p><strong>Email do Vendedor:</strong> <?= $row['email_vendedor'] ?></p>
                                    <p><strong>Status:</strong> <?= $row['status'] ?></p>
                                    <p><strong>Foto:</strong> <img src="<?= $row['foto'] ?>" alt="<?= $row['foto'] ?>" width="100"></p>
                                    <p><strong>Descrição:</strong> <?= $row['descricao'] ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"></script>

</body>
</html>