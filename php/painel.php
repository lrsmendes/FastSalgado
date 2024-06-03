<?php
session_start();

// Verificar se o usuário está autenticado
if (!isset($_SESSION["nomeUsuario"])) {
    header("Location: login.php");
    exit();
}

$nomeUsuario = $_SESSION["nomeUsuario"];
$isAdmin = isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == 1;

// Configurações de Paginação
$total_reg = 5; // Número de registros por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $total_reg;

// Conexão ao Banco de Dados
try {
    $conn = new PDO("mysql:host=localhost;dbname=fastimoveis;charset=utf8", "root", "");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
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
                <a class="navbar-brand mt-2 mt-lg-0" href="#">FastImóveis</a>
                <a class="navbar-brand mt-2 mt-lg-0" href="pesquisar.php">Pesquisar</a>
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
        <h2 class="mt-5">Listagem de Imóveis</h2>
        
        <?php if ($isAdmin) { ?>
            <!-- Botão Adicionar Novo -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Adicionar novo</button>
        <?php } ?>

        <br><br>

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="addRecord.php" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Adicionar Novo Imóvel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Campos do Formulário -->
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto:</label>
                                <input type="file" class="form-control" id="foto" name="foto">
                            </div>
                            <div class="mb-3">
                                <label for="endereco" class="form-label">Endereço:</label>
                                <input type="text" class="form-control" id="endereco" name="endereco">
                            </div>
                            <div class="mb-3">
                                <label for="cidade" class="form-label">Cidade:</label>
                                <input type="text" class="form-control" id="cidade" name="cidade">
                            </div>
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoria:</label>
                                <select class="form-select" id="categoria" name="categoria">
                                    <option value="Casa">Casa</option>
                                    <option value="Apartamento">Apartamento</option>
                                    <option value="Kitnet">Kitnet</option>
                                    <option value="Sobrado">Sobrado</option>
                                    <option value="Mansão">Mansão</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço:</label>
                                <input type="text" class="form-control" id="preco" name="preco">
                            </div>
                            <div class="mb-3">
                                <label for="nome_vendedor" class="form-label">Nome do Vendedor:</label>
                                <textarea class="form-control" id="nome_vendedor" name="nome_vendedor" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="telefone_vendedor" class="form-label">Telefone do Vendedor:</label>
                                <textarea class="form-control" id="telefone_vendedor" name="telefone_vendedor" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="email_vendedor" class="form-label">Email do Vendedor:</label>
                                <textarea class="form-control" id="email_vendedor" name="email_vendedor" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição do Imóvel:</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="à_venda">à venda</option>
                                    <option value="alugar">alugar</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Tabela de Imóveis -->
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
                <?php
                $query = "SELECT * FROM imoveis LIMIT $inicio, $total_reg";
                $stmt = $conn->query($query);
                $total_records = $conn->query("SELECT COUNT(*) FROM imoveis")->fetchColumn();
                $total_pages = ceil($total_records / $total_reg);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
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
                        <td>
                            <!-- Botão Visualizar -->
                            <a href="#" class="icon-button view-icon" data-bs-toggle="modal" data-bs-target="#viewModal-<?= $row['id'] ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                            <?php if ($isAdmin) { ?>
                                <!-- Botão Editar -->
                                <i class="edit-icon icon-button" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </i>
                                <!-- Botão Excluir -->
                                <i class="delete-icon" type="button" class="btn btn-icon btn-danger" onclick="deleteRecord(<?= $row['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </i>
                            <?php } ?>
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

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
    <!-- Edit Modal content -->
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="editRecord.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Editar Imóvel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário para editar imóvel -->
                    <input type="hidden" name="recordId" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto:</label>
                        <input type="file" class="form-control" id="foto" name="foto">
                        <img src="<?= $row['foto'] ?>" alt="<?= $row['foto'] ?>" width="100" class="mt-2">
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço:</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" value="<?= $row['endereco'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="cidade" class="form-label">Cidade:</label>
                        <input type="text" class="form-control" id="cidade" name="cidade" value="<?= $row['cidade'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria:</label>
                        <select class="form-select" id="categoria" name="categoria">
                            <option value="Casa" <?= $row['categoria'] == 'Casa' ? 'selected' : '' ?>>Casa</option>
                            <option value="Apartamento" <?= $row['categoria'] == 'Apartamento' ? 'selected' : '' ?>>Apartamento</option>
                            <option value="Kitnet" <?= $row['categoria'] == 'Kitnet' ? 'selected' : '' ?>>Kitnet</option>
                            <option value="Sobrado" <?= $row['categoria'] == 'Sobrado' ? 'selected' : '' ?>>Sobrado</option>
                            <option value="Mansão" <?= $row['categoria'] == 'Mansão' ? 'selected' : '' ?>>Mansão</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço:</label>
                        <input type="text" class="form-control" id="preco" name="preco" value="<?= $row['preco'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nome_vendedor" class="form-label">Nome do Vendedor:</label>
                        <input type="text" class="form-control" id="nome_vendedor" name="nome_vendedor" value="<?= $row['nome_vendedor'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="telefone_vendedor" class="form-label">Telefone do Vendedor:</label>
                        <input type="text" class="form-control" id="telefone_vendedor" name="telefone_vendedor" value="<?= $row['telefone_vendedor'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email_vendedor" class="form-label">Email do Vendedor:</label>
                        <input type="text" class="form-control" id="email_vendedor" name="email_vendedor" value="<?= $row['email_vendedor'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" id="status" name="status">
                            <option value="à_venda">à venda</option>
                            <option value="alugar">alugar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição:</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" value="<?= $row['descricao'] ?>">
                    </div>
                    <!-- Adicione outros campos de entrada aqui -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
                <?php } ?>
            </tbody>
        </table>

<!-- Paginação -->
<nav>
    <ul class="pagination">
        <!-- Botão para a página anterior -->
        <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=<?= $pagina - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Números das páginas -->
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php } ?>

        <!-- Botão para a próxima página -->
        <li class="page-item <?= $pagina >= $total_pages ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=<?= $pagina + 1 ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

 <!-- Scripts JavaScript -->
    <script>
        // Função JavaScript para exclusão de registro
        function deleteRecord(recordId) {
            if (confirm("Tem certeza de que deseja excluir este registro?")) {
                // Solicitação de exclusão AJAX
                $.ajax({
                    type: "POST",
                    url: "deleteRecord.php",
                    data: {recordId: recordId},
                    success: function (data) {
                        // Lidar com a resposta (por exemplo, mostrar uma mensagem de sucesso ou atualizar a página)
                        location.reload(); // Atualiza a página
                    }
                });
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"></script>
    
    </body>
</html>