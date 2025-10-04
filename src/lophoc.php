<?php
include_once("func.php");

$action = $_GET["action"] ?? "";

switch ($action) {
    // LẤY TOÀN BỘ LỚP HỌC
    case "getAll":
        $sql = "SELECT lh.maLop, lh.tenLop, lh.namHoc, gv.maGV, u.userName AS giaoVien
                FROM lophoc lh
                LEFT JOIN giaovien gv ON lh.giaoVienPhuTrach = gv.maGV
                LEFT JOIN user u ON gv.userId = u.userId";
        $data = getData($sql);
        responseJSON($data);
        break;

    // LẤY LỚP HỌC THEO ID
    case "getById":
        $maLop = $_GET["maLop"] ?? null;
        if (!$maLop) responseJSON(["error" => "Thiếu mã lớp học"], 400);

        $sql = "SELECT lh.maLop, lh.tenLop, lh.namHoc, gv.maGV, u.userName AS giaoVien
                FROM lophoc lh
                LEFT JOIN giaovien gv ON lh.giaoVienPhuTrach = gv.maGV
                LEFT JOIN user u ON gv.userId = u.userId
                WHERE lh.maLop = ?";
        $data = getData($sql, [$maLop], "i");
        responseJSON($data);
        break;

    // THÊM LỚP HỌC
    case "add":
        $input = getJSONInput();
        $tenLop = $input["tenLop"] ?? null;
        $namHoc = $input["namHoc"] ?? null;
        $giaoVienPhuTrach = $input["giaoVienPhuTrach"] ?? null;

        if (!$tenLop) responseJSON(["error" => "Thiếu tên lớp học"], 400);

        $sql = "INSERT INTO lophoc (tenLop, namHoc, giaoVienPhuTrach) VALUES (?, ?, ?)";
        $ok = executeSQL($sql, [$tenLop, $namHoc, $giaoVienPhuTrach], "ssi");

        if ($ok) {
            responseJSON(["message" => "Thêm lớp học thành công"]);
        } else {
            responseJSON(["error" => "Không thể thêm lớp học"], 500);
        }
        break;

    // CẬP NHẬT LỚP HỌC
    case "update":
        $input = getJSONInput();
        $maLop = $input["maLop"] ?? null;
        $tenLop = $input["tenLop"] ?? null;
        $namHoc = $input["namHoc"] ?? null;
        $giaoVienPhuTrach = $input["giaoVienPhuTrach"] ?? null;

        if (!$maLop) responseJSON(["error" => "Thiếu mã lớp học"], 400);

        $sql = "UPDATE lophoc SET tenLop = ?, namHoc = ?, giaoVienPhuTrach = ? WHERE maLop = ?";
        $ok = executeSQL($sql, [$tenLop, $namHoc, $giaoVienPhuTrach, $maLop], "ssii");

        if ($ok) {
            responseJSON(["message" => "Cập nhật lớp học thành công"]);
        } else {
            responseJSON(["error" => "Không thể cập nhật"], 500);
        }
        break;

    // XÓA LỚP HỌC
    case "delete":
        $input = getJSONInput();
        $maLop = $input["maLop"] ?? null;

        if (!$maLop) responseJSON(["error" => "Thiếu mã lớp học"], 400);

        $ok = executeSQL("DELETE FROM lophoc WHERE maLop = ?", [$maLop], "i");

        if ($ok) {
            responseJSON(["message" => "Xóa lớp học thành công"]);
        } else {
            responseJSON(["error" => "Không thể xóa"], 500);
        }
        break;

    default:
        responseJSON(["error" => "Hành động không hợp lệ"], 400);
}
?>
