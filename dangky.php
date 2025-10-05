<?php
// Kết nối database cnpm
include("csdl/db.php");
include("src/func.php");

$message = "";

// Khi người dùng submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $sdt = trim($_POST["sdt"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];
    $vaiTro = $_POST["role"] ?? "user"; // mặc định user

    // Kiểm tra dữ liệu nhập
    if (empty($userName) || empty($email) || empty($password) || empty($confirm)) {
        $message = "⚠️ Vui lòng nhập đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "⚠️ Email không hợp lệ!";
    } elseif (strlen($password) < 8) {
        $message = "⚠️ Mật khẩu phải chứa ít nhất 8 ký tự!";
    } elseif ($password !== $confirm) {
        $message = "⚠️ Mật khẩu nhập lại không khớp!";
    } else {
        // Mã hoá mật khẩu
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Thêm user vào bảng User
        $sql = "INSERT INTO user(userName, password, email, sdt, vaiTro) VALUES (?,?,?,?,?)";
        $ok = executeSQL($sql, [$userName, $hash, $email, $sdt, $vaiTro], "sssss");

        if ($ok) {
            $newUserId = $conn->insert_id;

            // Nếu vai trò là Học sinh thì thêm vào bảng HocSinh
            if ($vaiTro === "HocSinh") {
                $lop = $_POST["lop"] ?? NULL;
                $sql2 = "INSERT INTO hocsinh(lop, userId) VALUES (?, ?)";
                executeSQL($sql2, [$lop, $newUserId], "si");
            }

            // Nếu vai trò là Giáo viên thì thêm vào bảng GiaoVien
            if ($vaiTro === "GiaoVien") {
                $boMon = $_POST["boMon"] ?? NULL;
                $sql3 = "INSERT INTO giaovien(boMon, userId) VALUES (?, ?)";
                executeSQL($sql3, [$boMon, $newUserId], "si");
            }

            // Nếu vai trò là Admin thì thêm vào bảng Admin (nếu cần)
            // if ($vaiTro === "Admin") {
            //     $sql4 = "INSERT INTO admin(userId) VALUES (?)";
            //     executeSQL($sql4, [$newUserId], "i");
            // }

            header("Location: dangnhap.php");
            exit;
        } else {
            $message = "❌ Có lỗi xảy ra, vui lòng thử lại.";
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
    <title>Đăng Ký</title>
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

        .left {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .left-container {
            width: 100%;
            max-width: 700px;
            padding: 40px;
            border: 1px solid #eee;
            border-radius: 10px;
            background: #fff;
        }

        .left h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .options {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .options label {
            margin-left: 50px;
            margin-right: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            margin-bottom: 18px;
            padding: 12px 15px;
            border: none;
            border-radius: 6px;
            background: #eee;
            font-size: 15px;
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

        .right {
            border-radius: 25% 0 0 25%;
            flex: 1;
            background: #003f91;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }

        .right h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .right p {
            margin-bottom: 20px;
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 2px solid #fff;
            padding: 12px 80px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-outline:hover {
            background: #fff;
            color: #003f91;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <div class="left-container">
                <h2>Đăng Ký</h2>
                <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
                <form method="POST">
                    <div class="options">
                        Đăng ký dành cho:
                        <label><input type="radio" name="role" value="GiaoVien"> Giáo viên</label>
                        <label><input type="radio" name="role" value="HocSinh" checked> Học sinh</label>
                        <!-- <label><input type="radio" name="role" value="Admin"> Admin</label> -->
                    </div>
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Họ tên">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Mật khẩu">
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirm" placeholder="Nhập lại mật khẩu">
                    </div>
                    <div class="form-group" id="extra-field"></div>
                    <button type="submit" class="btn btn-primary">Đăng Ký</button>
                </form>
            </div>
        </div>

        <div class="right">
            <h2>Welcome Back!</h2>
            <b style="font-size: 24px;">Bạn đã có tài khoản?</b>
            <br>
            <button class="btn-outline" id="btnDangNhap" style="font-size: 24px;"><b>Đăng Nhập</b></button>

            <script>
                document.getElementById("btnDangNhap").addEventListener("click", function() {
                    document.body.classList.add("slide-out-right");
                    setTimeout(() => {
                        window.location.href = "dangnhap.php";
                    }, 600);
                });
            </script>

        </div>
    </div>

    <script>
        // JS để hiển thị thêm input khi chọn vai trò
        // const roleRadios = document.querySelectorAll('input[name="role"]');
        // const extraField = document.getElementById('extra-field');

        // roleRadios.forEach(radio => {
        //     radio.addEventListener('change', () => {
        //         extraField.innerHTML = '';
        //         if (radio.value === 'HocSinh') {
        //             extraField.innerHTML = '<input type="text" name="lop" placeholder="Lớp">';
        //         } else if (radio.value === 'GiaoVien') {
        //             extraField.innerHTML = '<input type="text" name="boMon" placeholder="Bộ môn">';
        //         }
        //     });
        // });
    </script>
    <script>
        window.addEventListener("load", () => {
            // Khi trang tải xong, thêm class hiệu ứng cho 2 khối
            const left = document.querySelector(".left");
            const right = document.querySelector(".right");

            if (left) left.classList.add("fade-in");
            if (right) right.classList.add("slide-in-left");
        });
    </script>

</body>
<!-- <script>
    document.body.style.transform = "translateX(100%)";
    window.addEventListener("load", () => {
        document.body.style.transition = "transform 0.6s ease-in-out";
        document.body.style.transform = "translateX(0)";
    });
</script> -->

</html>
