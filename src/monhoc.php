<?php
include_once("func.php");

$action = $_GET["action"] ?? "";

switch ($action) {
    // LẤY TOÀN BỘ MÔN HỌC
    case "getAll":
        $sql = "SELECT mh.maMon, mh.tenMon, mh.moTa, gv.maGV, u.userName AS giaoVien
                FROM monhoc mh
                LEFT JOIN giaovien gv ON mh.giaoVienPhuTrach = gv.maGV
                LEFT JOIN user u ON gv.userId = u.userId";
        $data = getData($sql);
        responseJSON($data);
        break;

    // LẤY MÔN HỌC THEO ID
    case "getById":
        $maMon = $_GET["maMon"] ?? null;
        if (!$maMon) responseJSON(["error" => "Thiếu mã môn học"], 400);

        $sql = "SELECT mh.maMon, mh.tenMon, mh.moTa, gv.maGV, u.userName AS giaoVien
                FROM monhoc mh
                LEFT JOIN giaovien gv ON mh.giaoVienPhuTrach = gv.maGV
                LEFT JOIN user u ON gv.userId = u.userId
                WHERE mh.maMon = ?";
        $data = getData($sql, [$maMon], "i");
        responseJSON($data);
        break;

    // THÊM MÔN HỌC
    case "add":
        $input = getJSONInput();
        $tenMon = $input["tenMon"] ?? null;
        $moTa = $input["moTa"] ?? null;
        $giaoVienPhuTrach = $input["giaoVienPhuTrach"] ?? null;

        if (!$tenMon) responseJSON(["error" => "Thiếu tên môn học"], 400);

        $sql = "INSERT INTO monhoc (tenMon, moTa, giaoVienPhuTrach) VALUES (?, ?, ?)";
        $ok = executeSQL($sql, [$tenMon, $moTa, $giaoVienPhuTrach], "ssi");

        if ($ok) {
            responseJSON(["message" => "Thêm môn học thành công"]);
        } else {
            responseJSON(["error" => "Không thể thêm môn học"], 500);
        }
        break;

    // CẬP NHẬT MÔN HỌC
    case "update":
        $input = getJSONInput();
        $maMon = $input["maMon"] ?? null;
        $tenMon = $input["tenMon"] ?? null;
        $moTa = $input["moTa"] ?? null;
        $giaoVienPhuTrach = $input["giaoVienPhuTrach"] ?? null;

        if (!$maMon) responseJSON(["error" => "Thiếu mã môn học"], 400);

        $sql = "UPDATE monhoc SET tenMon = ?, moTa = ?, giaoVienPhuTrach = ? WHERE maMon = ?";
        $ok = executeSQL($sql, [$tenMon, $moTa, $giaoVienPhuTrach, $maMon], "ssii");

        if ($ok) {
            responseJSON(["message" => "Cập nhật môn học thành công"]);
        } else {
            responseJSON(["error" => "Không thể cập nhật"], 500);
        }
        break;

    // XÓA MÔN HỌC
    case "delete":
        $input = getJSONInput();
        $maMon = $input["maMon"] ?? null;

        if (!$maMon) responseJSON(["error" => "Thiếu mã môn học"], 400);

        $ok = executeSQL("DELETE FROM monhoc WHERE maMon = ?", [$maMon], "i");

        if ($ok) {
            responseJSON(["message" => "Xóa môn học thành công"]);
        } else {
            responseJSON(["error" => "Không thể xóa"], 500);
        }
        break;

    default:
        responseJSON(["error" => "Hành động không hợp lệ"], 400);
}
?>
