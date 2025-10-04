<?php
include_once("func.php");

$action = $_GET["action"] ?? "";

switch ($action) {
    // LẤY TOÀN BỘ GIÁO VIÊN
    case "getAll":
        $sql = "SELECT gv.maGV, gv.boMon, u.userId, u.userName, u.email, u.sdt
                FROM giaovien gv
                JOIN user u ON gv.userId = u.userId";
        $data = getData($sql);
        responseJSON($data);
        break;

    // LẤY GIÁO VIÊN THEO ID
    case "getById":
        $maGV = $_GET["maGV"] ?? null;
        if (!$maGV) responseJSON(["error" => "Thiếu mã giáo viên"], 400);

        $sql = "SELECT gv.maGV, gv.boMon, u.userId, u.userName, u.email, u.sdt
                FROM giaovien gv
                JOIN user u ON gv.userId = u.userId
                WHERE gv.maGV = ?";
        $data = getData($sql, [$maGV], "i");
        responseJSON($data);
        break;

    // THÊM GIÁO VIÊN
    case "add":
        $input = getJSONInput();
        $userId = $input["userId"] ?? null;
        $boMon = $input["boMon"] ?? null;

        if (!$userId) responseJSON(["error" => "Thiếu userId"], 400);

        $sql = "INSERT INTO giaovien (boMon, userId) VALUES (?, ?)";
        $ok = executeSQL($sql, [$boMon, $userId], "si");

        if ($ok) {
            responseJSON(["message" => "Thêm giáo viên thành công"]);
        } else {
            responseJSON(["error" => "Không thể thêm giáo viên"], 500);
        }
        break;

    // CẬP NHẬT GIÁO VIÊN
    case "update":
        $input = getJSONInput();
        $maGV = $input["maGV"] ?? null;
        $boMon = $input["boMon"] ?? null;

        if (!$maGV) responseJSON(["error" => "Thiếu mã giáo viên"], 400);

        $sql = "UPDATE giaovien SET boMon = ? WHERE maGV = ?";
        $ok = executeSQL($sql, [$boMon, $maGV], "si");

        if ($ok) {
            responseJSON(["message" => "Cập nhật giáo viên thành công"]);
        } else {
            responseJSON(["error" => "Không thể cập nhật"], 500);
        }
        break;

    // XÓA GIÁO VIÊN
    case "delete":
        $input = getJSONInput();
        $maGV = $input["maGV"] ?? null;

        if (!$maGV) responseJSON(["error" => "Thiếu mã giáo viên"], 400);

        $ok = executeSQL("DELETE FROM giaovien WHERE maGV = ?", [$maGV], "i");

        if ($ok) {
            responseJSON(["message" => "Xóa giáo viên thành công"]);
        } else {
            responseJSON(["error" => "Không thể xóa"], 500);
        }
        break;

    default:
        responseJSON(["error" => "Hành động không hợp lệ"], 400);
}
?>
