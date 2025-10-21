<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["outil"]) || !isset($data["date"])) {
    http_response_code(400);
    echo json_encode(["message" => "Champs manquants."]);
    exit;
}

$outil = htmlspecialchars($data["outil"]);
$date = htmlspecialchars($data["date"]);

try {
    $pdo = getDB();

    $sql = "INSERT INTO reservation (outil_nom, date_reservation) VALUES (:outil, :date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":outil" => $outil,
        ":date" => $date
    ]);

    echo json_encode(["success" => true, "message" => "Réservation enregistrée."]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
