<div class="container mt-5" style="max-width: 400px;">
    <h2 class="text-center mb-4">Đăng Nhập</h2>

    <?= $this->Form->create(null, ['class' => 'needs-validation']) ?>

    <div class="mb-3">
        <?= $this->Form->control('email', [
            'label' => 'Email',
            'class' => 'form-control',
            'placeholder' => 'Nhập email của bạn',
            'required' => true,
        ]) ?>
    </div>

    <div class="mb-3">
        <?= $this->Form->control('password', [
            'label' => 'Mật khẩu',
            'type' => 'password',
            'class' => 'form-control',
            'placeholder' => 'Nhập mật khẩu',
            'required' => true,
        ]) ?>
    </div>

    <div class="d-grid">
        <?= $this->Form->button('Đăng nhập', ['class' => 'btn btn-primary']) ?>
    </div>

    <?= $this->Form->end() ?>

    <p class="mt-3 text-center">
        Chưa có tài khoản? <a href="/users/register">Đăng ký</a>
    </p>
</div>
