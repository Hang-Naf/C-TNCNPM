<?php
// Kết nối database cnpm
$servername = "localhost";
$username = "root"; // thay bằng user MySQL của bạn
$password = "";     // thay bằng mật khẩu MySQL
$dbname = "cnpm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

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
    } elseif ($password !== $confirm) {
        $message = "⚠️ Mật khẩu nhập lại không khớp!";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $check = $conn->prepare("SELECT userId FROM User WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "⚠️ Email đã được sử dụng!";
        } else {
            // Mã hoá mật khẩu
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Thêm user vào bảng User
            $stmt = $conn->prepare("INSERT INTO User(userName, password, email, sdt, vaiTro) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssss", $userName, $hash, $email, $sdt, $vaiTro);

            if ($stmt->execute()) {
                $newUserId = $stmt->insert_id;

                // Nếu vai trò là Học sinh thì thêm vào bảng HocSinh
                if ($vaiTro === "HocSinh") {
                    $stmt2 = $conn->prepare("INSERT INTO HocSinh(lop, userId) VALUES (?, ?)");
                    $lop = $_POST["lop"] ?? NULL;
                    $stmt2->bind_param("si", $lop, $newUserId);
                    $stmt2->execute();
                }

                // Nếu vai trò là Giáo viên thì thêm vào bảng GiaoVien
                if ($vaiTro === "GiaoVien") {
                    $stmt3 = $conn->prepare("INSERT INTO GiaoVien(boMon, userId) VALUES (?, ?)");
                    $boMon = $_POST["boMon"] ?? NULL;
                    $stmt3->bind_param("si", $boMon, $newUserId);
                    $stmt3->execute();
                }

                // Nếu vai trò là Admin thì thêm vào bảng Admin
                // if ($vaiTro === "Admin") {
                //     $stmt4 = $conn->prepare("INSERT INTO Admin(userId) VALUES (?)");
                //     $stmt4->bind_param("i", $newUserId);
                //     $stmt4->execute();
                // }

                header("Location: dangnhap.php");
            } else {
                $message = "❌ Có lỗi xảy ra, vui lòng thử lại.";
            }
        }
        $check->close();
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
                        <input type="text" name="sdt" placeholder="Số điện thoại">
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
            <button class="btn-outline" style="font-size: 24px;" onclick="window.location.href='dangnhap.php'"><b>Đăng Nhập</b></button>
        </div>
    </div>

    <script>
        // JS để hiển thị thêm input khi chọn vai trò
        const roleRadios = document.querySelectorAll('input[name="role"]');
        const extraField = document.getElementById('extra-field');

        roleRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                extraField.innerHTML = '';
                if (radio.value === 'HocSinh') {
                    extraField.innerHTML = '<input type="text" name="lop" placeholder="Lớp">';
                } else if (radio.value === 'GiaoVien') {
                    extraField.innerHTML = '<input type="text" name="boMon" placeholder="Bộ môn">';
                }
            });
        });
    </script>
</body>

</html>