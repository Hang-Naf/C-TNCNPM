<?php
// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "cnpm");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = "";

// Nếu người dùng submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Kiểm tra email trong bảng User
    $sql = "SELECT * FROM User WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Tạo mật khẩu mới ngẫu nhiên
        $newPass = substr(md5(time()), 0, 8);

        // Cập nhật mật khẩu mới (chưa mã hóa, bạn nên dùng password_hash để an toàn)
        $sqlUpdate = "UPDATE User SET password='$newPass' WHERE email='$email'";
        if ($conn->query($sqlUpdate) === TRUE) {
            $message = "Mật khẩu mới của bạn là: <b>$newPass</b>";
        } else {
            $message = "Có lỗi khi cập nhật mật khẩu.";
        }
    } else {
        $message = "Email không tồn tại trong hệ thống!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <title>Quên mật khẩu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Quicksand", sans-serif;
        }

        body {
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100%;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        .left {
            flex: 1;
            background: #003f91;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-radius: 0 25% 25% 0;
            padding: 40px;
        }

        .left h2 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .left p {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 2px solid #fff;
            padding: 12px 40px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
        }

        .btn-outline:hover {
            background: #fff;
            color: #003f91;
        }

        .right {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .right-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border: 1px solid #eee;
            padding: 40px;
            border-radius: 10px;
        }

        .right-container h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            padding: 12px 15px;
            border: none;
            border-radius: 6px;
            background: #eee;
            font-size: 15px;
        }

        .forgot {
            display: block;
            margin: 10px 0 20px;
            font-size: 14px;
            color: #003f91;
            text-decoration: none;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-primary {
            background: #003f91;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <h2 style="font-size: 48px;">Hello, Welcome!</h2>
            <p style="font-size: 24px;">Bạn chưa có tài khoản?</p>
            <button class="btn-outline" onclick="window.location.href='dangky.php'">Đăng Ký</button>
        </div>
        <div class="right">
            <div class="right-container">
                <h2>Quên mật khẩu</h2>
                <b>Nhập địa chỉ email để đặt lại mật khẩu</b>
                <br><br>
                <form action="" method="POST">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email">
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi</button>
                </form>
                 <?php if ($message != "") echo "<div class='message'>$message</div>"; ?>
            </div>
        </div>
    </div>
</body>

</html>