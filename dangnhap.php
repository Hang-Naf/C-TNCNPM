<?php
session_start();

// Kết nối database
include("csdl/db.php");
include("src/func.php");

$message = "";

// Khi người dùng submit form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $message = "⚠️ Vui lòng nhập đầy đủ Email và Mật khẩu!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "⚠️ Email không hợp lệ!";
    } elseif (strlen($email) > 254) {
        $message = "⚠️ Email không đúng định dạng";
    } elseif (strlen($password) > 64) {
        $message = "⚠️ Mật khẩu không đúng định dạng";
    } else {
        if (loginUser($email, $password)) {
            // ✅ Đăng nhập thành công
            header("Location: index.php");
            exit;
        } else {
            $message = "❌ Email hoặc mật khẩu không đúng!";
        }
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
    <title>Đăng Nhập</title>
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
            overflow-x: hidden;
            transition: transform 0.6s ease-in-out;
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 1s ease forwards;
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(100px);
            animation: slideInLeft 1s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .container {
            display: flex;
            width: 100%;
            height: 100%;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        /* Bên trái */
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

        /* Bên phải */
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
        <!-- Bên trái -->
        <div class="left">
            <h2 style="font-size: 48px;">Hello, Welcome!</h2>
            <p style="font-size: 24px;">Bạn chưa có tài khoản?</p>
            <button class="btn-outline" id="btnDangKy">Đăng Ký</button>

            <script>
                document.getElementById("btnDangKy").addEventListener("click", function() {
                    document.body.classList.add("slide-out-left");
                    setTimeout(() => {
                        window.location.href = "dangky.php";
                    }, 600);
                });
            </script>

        </div>

        <!-- Bên phải -->
        <div class="right">
            <div class="right-container">
                <h2>Đăng Nhập</h2>
                <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
                <form method="POST">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Mật khẩu">
                    </div>
                    <a href="quenmatkhau.php" class="forgot">Quên mật khẩu</a>
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("load", () => {
            // Khi trang tải xong, thêm class hiệu ứng cho 2 khối
            const left = document.querySelector(".left");
            const right = document.querySelector(".right");

            if (right) right.classList.add("fade-in");
            if (left) left.classList.add("slide-in-left");
        });
    </script>

</body>

</html>
