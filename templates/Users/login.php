<div class="container mt-5" style="max-width: 400px;">
    <h2 class="text-center mb-4">Đăng Nhập</h2>

    <form id="loginForm" class="needs-validation">
        <div class="mb-3">
            <input type="email" id="email" class="form-control" placeholder="Nhập email của bạn" required>
        </div>

        <div class="mb-3">
            <input type="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </div>
    </form>

    <p class="mt-3 text-center">
        Chưa có tài khoản? <a href="/users/register">Đăng ký</a>
    </p>
</div>

<script>
    // if (localStorage.getItem('token')) {
    //     window.location.href = '/articles'; // Đổi theo trang bạn muốn chuyển hướng
    // }

    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        fetch('/users/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Lỗi đăng nhập: ' + response.statusText); // Xử lý nếu mã trạng thái không phải 2xx
                }
                return response.json(); // Chỉ phân tích cú pháp khi response ok
            })
            .then(data => {
                if (data.token) {
                    // Lưu token vào localStorage
                    localStorage.setItem('token', data.token);

                    // Kiểm tra token và chuyển hướng
                    console.log('Token đã được lưu:', data.token); // Kiểm tra xem token có thực sự được lưu
                    window.location.href = '/articles/'; // Chuyển hướng sau khi đăng nhập thành công
                } else {
                    alert('Đăng nhập không thành công.');
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Có lỗi xảy ra: ' + error.message);
            });
    });
</script>
