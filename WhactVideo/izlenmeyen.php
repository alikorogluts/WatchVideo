<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İzlediğim Videolar</title>
    <link rel="stylesheet" href="izlenmeyen2.css">
</head>
<body>
    <div class="logo-container">
        <img src="layout/CyberSoldiersLOGO.png" alt="Logo" class="Logo">
        <div class="site-title">CYBERSOLDIERS</div>
    </div>

    <!-- Çıkış Butonu -->
    <div class="header-right">
        <button class="back-button" onclick="history.back()">
            <img src="layout/comeback.png" alt="Geri Dön" class="back-icon">
            Geri Dön
        </button>
    </div>

    <!-- Menü Barı -->
    <div class="header">
        <a href="anasayfa.php" class="menu-item"><b>ANASAYFA</b></a>
        <a href="izlenmeyen.php" class="menu-item1"><b>İZLEMEDİĞİM VİDEOLAR</b></a>
        <a href="izlenen.php" class="menu-item"><b>İZLEDİĞİM VİDEOLAR</b></a>
    </div>

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

    // SQL sorgusu
    $sql = "SELECT * FROM data ORDER BY tarih DESC";
    $result = $conn->query($sql);

    // Başlangıçta videolar array'ini tanımla
    $videolar = array();

    // Sorgu sonucunu kontrol et
    if ($result->num_rows > 0) {
        // Her bir satırı videolar array'ine ekle
        while ($row = $result->fetch_assoc()) {
            $videolar[] = $row;
        }
    } else {
        echo "Kayıt bulunamadı.";
    }

    // Bağlantıyı kapat
    $conn->close();

    // Video verilerini ekrana yazdır
    if (!empty($videolar)) {
        foreach ($videolar as $video) {
            if($video['izlendi'] == "0") { 
                echo '<div class="videoBox">';
                echo '<div class="video">';
                echo '<img class="resim" src="layout/doga.jpg" width="100%">';
                echo '<h3 style="text-align: center;"> VİDEO-' . htmlspecialchars($video['id']) . '</h3>';
                echo '<h3 style="text-align: center;"> ' . htmlspecialchars($video['tarih']) . '</h3>';
                
                // Form oluştur ve POST metodu ile detay sayfasına veri gönder
                echo '<form action="izlenmeyen_video.php" method="post">';
                echo '<input type="hidden" name="video_id" value="' . htmlspecialchars($video['id']) . '">';
                echo '<button type="submit" class="git-button">';
                echo 'Detaylar';
                echo '</button>';
                echo '</form>';             
                echo '</div>';
                echo '</div>';
            }
        }
    } else {
        echo "Videolar bulunamadı.";
    }
    ?>

    <script type="text/javascript">
        function go(id) {
            window.location.href = "izlenmeyen_video.php?id=" + id;
        }
    </script>  
</body>
</html>
