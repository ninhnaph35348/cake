<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Danh sách bài viết</title>
</head>

<body class="container mt-4">

    <?php if ($this->request->getAttribute('identity')): ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0">👋 Xin chào, <?= h($this->request->getAttribute('identity')->email) ?>!</p>
            <?= $this->Html->link('Đăng xuất', '/users/logout', ['class' => 'btn btn-outline-danger btn-sm']) ?>
        </div>
    <?php endif; ?>

    <h1>📝 Danh sách Bài viết</h1>

    <a href="/articles/add/" class="btn btn-primary mb-3">➕ Thêm bài viết</a>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= h($article->title) ?></td>
                    <td><?= h($article->created->format('d/m/Y')) ?></td>
                    <td>
                        <a href="/articles/view/<?= $article->id ?>" class="btn btn-info btn-sm">Xem</a>
                        <a href="/articles/edit/<?= $article->id ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <?= $this->Form->postLink(
                            'Xóa',
                            ['action' => 'delete', $article->id],
                            ['confirm' => 'Bạn có chắc chắn muốn xóa không?', 'class' => 'btn btn-danger btn-sm']
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>
