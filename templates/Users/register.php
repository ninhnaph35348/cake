<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Đăng ký tài khoản</h2>

    <?= $this->Form->create($user, ['class' => 'needs-validation']) ?>

    <div class="mb-3">
        <?= $this->Form->control('email', [
            'label' => 'Email',
            'class' => 'form-control',
            'placeholder' => 'Nhập email của bạn',
            'required' => true
        ]) ?>
    </div>

    <div class="mb-3">
        <?= $this->Form->control('password', [
            'label' => 'Mật khẩu',
            'type' => 'password',
            'class' => 'form-control',
            'placeholder' => 'Nhập mật khẩu',
            'required' => true
        ]) ?>
    </div>

    <div class="d-grid">
        <?= $this->Form->button('Đăng ký', ['class' => 'btn btn-success']) ?>
    </div>

    <?= $this->Form->end() ?>

    <div class="mt-2 text-center">
       Đã có tài khoản? <?= $this->Html->link('Đăng nhập', ['controller' => 'Users', 'action' => 'login']) ?>
    </div>
</div>
