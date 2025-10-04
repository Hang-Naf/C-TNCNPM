<?php
include_once("func.php");

$action = $_GET["action"] ?? "";

switch ($action) {
    // LẤY TOÀN BỘ HỌC SINH
    case "getAll":
        $sql = "SELECT hs.maHS, hs.lop, u.userId, u.userName, u.email, u.sdt
                FROM hocsinh hs
                JOIN user u ON hs.userId = u.userId";
        $data = getData($sql);
        responseJSON($data);
        break;

    // LẤY HỌC SINH THEO ID
    case "getById":
        $maHS = $_GET["maHS"] ?? null;
        if (!$maHS) responseJSON(["error" => "Thiếu mã học sinh"], 400);

        $sql = "SELECT hs.maHS, hs.lop, u.userId, u.userName, u.email, u.sdt
                FROM hocsinh hs
                JOIN user u ON hs.userId = u.userId
                WHERE hs.maHS = ?";
        $data = getData($sql, [$maHS], "i");
        responseJSON($data);
        break;

    // THÊM HỌC SINH
    case "add":
        $input = getJSONInput();
        $userId = $input["userId"] ?? null;
        $lop = $input["lop"] ?? null;

        if (!$userId) responseJSON(["error" => "Thiếu userId"], 400);

        $sql = "INSERT INTO hocsinh (lop, userId) VALUES (?, ?)";
        $ok = executeSQL($sql, [$lop, $userId], "si");

        if ($ok) {
            responseJSON(["message" => "Thêm học sinh thành công"]);
        } else {
            responseJSON(["error" => "Không thể thêm học sinh"], 500);
        }
        break;

    // CẬP NHẬT HỌC SINH
    case "update":
        $input = getJSONInput();
        $maHS = $input["maHS"] ?? null;
        $lop = $input["lop"] ?? null;

        if (!$maHS) responseJSON(["error" => "Thiếu mã học sinh"], 400);

        $sql = "UPDATE hocsinh SET lop = ? WHERE maHS = ?";
        $ok = executeSQL($sql, [$lop, $maHS], "si");

        if ($ok) {
            responseJSON(["message" => "Cập nhật học sinh thành công"]);
        } else {
            responseJSON(["error" => "Không thể cập nhật"], 500);
        }
        break;

    // XÓA HỌC SINH
    case "delete":
        $input = getJSONInput();
        $maHS = $input["maHS"] ?? null;

        if (!$maHS) responseJSON(["error" => "Thiếu mã học sinh"], 400);

        $ok = executeSQL("DELETE FROM hocsinh WHERE maHS = ?", [$maHS], "i");

        if ($ok) {
            responseJSON(["message" => "Xóa học sinh thành công"]);
        } else {
            responseJSON(["error" => "Không thể xóa"], 500);
        }
        break;

    default:
        responseJSON(["error" => "Hành động không hợp lệ"], 400);
}
?>
