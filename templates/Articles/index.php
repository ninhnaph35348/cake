<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3" id="user-info" style="display: none;">
        <p class="mb-0" id="greeting"></p>
        <a href="/users/logout" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
    </div>

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
        <tbody id="articles-table-body">
            <tr>
                <td colspan="3">Đang tải dữ liệu...</td>
            </tr>
        </tbody>
    </table>

    <script>
        const token = localStorage.getItem('token');
        if (!token || token === 'undefined') {
            alert('Chưa đăng nhập hoặc token không hợp lệ.');
            window.location.href = '/users/login';
        }

        // Lấy danh sách bài viết từ API
        fetch('/articles/', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (res.status === 401) throw new Error('Unauthorized');
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    const tbody = document.getElementById('articles-table-body');
                    tbody.innerHTML = ''; // Xóa dòng "Đang tải"

                    if (data.data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3">Không có bài viết nào.</td></tr>';
                    } else {
                        data.data.forEach(article => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${article.title}</td>
                                <td>${new Date(article.created).toLocaleDateString('vi-VN')}</td>
                                <td>
                                    <a href="/articles/view/${article.id}" class="btn btn-info btn-sm">Xem</a>
                                    <a href="/articles/edit/${article.id}" class="btn btn-warning btn-sm">Sửa</a>
                                    <button class="btn btn-danger btn-sm" onclick="deleteArticle(${article.id})">Xóa</button>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }

                    // Hiển thị thông tin user nếu muốn (fake vì không có decode token)
                    document.getElementById('user-info').style.display = 'flex';
                    document.getElementById('greeting').innerText = '👋 Xin chào!';
                } else {
                    alert('Không thể tải danh sách bài viết.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Phiên đăng nhập hết hạn, vui lòng đăng nhập lại.');
                window.location.href = '/users/login';
            });

        // Xóa bài viết
        function deleteArticle(id) {
            if (confirm('Bạn có chắc chắn muốn xóa không?')) {
                fetch(`/articles/delete/${id}`, {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Bài viết đã được xóa.');
                            location.reload(); // Tải lại trang để cập nhật danh sách
                        } else {
                            alert('Không thể xóa bài viết.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Có lỗi xảy ra khi xóa bài viết.');
                    });
            }
        }
    </script>

</body>

</html>
