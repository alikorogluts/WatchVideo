<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ucangoz";

// MySQL'e bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $database);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// POST verilerini al
$video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;
$report = isset($_POST['report']) ? $_POST['report'] : '';
$risk = isset($_POST['risk']) ? intval($_POST['risk']) : 0;

if ($video_id > 0) {
    // SQL sorgusu
    $sql = "UPDATE data SET izlendi = 1, risk = ?, rapor = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $risk, $report, $video_id);

    if ($stmt->execute()) {
        echo "Güncelleme işlemi başarılı";
    } else {
        echo "Güncelleme işlemi hatalı";
    }
    $stmt->close();
} else {
    echo "Geçersiz video ID.";
}

$conn->close();
?>
