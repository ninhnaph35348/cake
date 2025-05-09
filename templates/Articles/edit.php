<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">
    <h1>Sửa Bài Viết</h1>

    <?= $this->Form->create($article, ['class' => 'mt-4']) ?>

    <div class="mb-3">
        <?= $this->Form->label('title', 'Tiêu đề', ['class' => 'form-label']) ?>
        <?= $this->Form->control('title', [
            'label' => false,
            'class' => 'form-control',
            'placeholder' => 'Nhập tiêu đề'
        ]) ?>
    </div>

    <div class="mb-3">
        <?= $this->Form->label('body', 'Nội dung', ['class' => 'form-label']) ?>
        <?= $this->Form->control('body', [
            'type' => 'textarea',
            'label' => false,
            'class' => 'form-control',
            'rows' => 5,
            'placeholder' => 'Nhập nội dung'
        ]) ?>
    </div>

    <?= $this->Form->button('Cập nhật bài viết', ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</body>

</html>
