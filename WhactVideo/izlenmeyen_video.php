<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İzlemediğim Videolar</title>
    <link rel="stylesheet" href="izlenmeyen.css">
</head>
<body>
    <button class="back-button" onclick="history.back()">
        <img src="layout/comeback.png" alt="Geri Dön" class="back-icon">
        Geri Dön
    </button>
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

    // POST ile gönderilen video_id'yi al
    $video_id = isset($_POST['video_id']) ? intval($_POST['video_id']) : 0;

    if ($video_id) {
        // SQL sorgusu
        $sql = "SELECT * FROM data WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $video_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Sorgu sonucunu kontrol et
        if ($result->num_rows > 0) {
            // Her bir satırı ekrana yazdır
            while ($row = $result->fetch_assoc()) {
                echo '<div class="video-container">';
                echo '<video width="640" height="360" controls>
                    <source src="' . htmlspecialchars( str_replace('\\', '/',$row['video_yolu'])) . '" type="video/mp4">
                    Your browser does not support the video tag.
                </video>';
                echo '<div class="button-group">
                    <button id="d-var" class="risk-button" onclick="handleButtonClick(\'d-var\', \'risk\')">DÜŞME RİSKİ VAR</button>
                    <button id="d-yok" class="no-risk-button" onclick="handleButtonClick(\'d-yok\', \'no-risk\')">DÜŞME RİSKİ YOK</button>
                </div>';
                echo '<textarea id="report" placeholder="Rapor yazınız..."></textarea>';
                echo '<button class="kaydet-button" onclick="saveReport()">KAYDET</button>';
                echo '</div>';
            }
        } else {
            echo "Kayıt bulunamadı.";
        }
        $stmt->close();
    } else {
        echo "Video ID bulunamadı.";
    }
    $conn->close();
    ?>

    <script>
          let selectedRisk = 3;

function handleRisk(riskType) {
    if (riskType === 'risk') {
        selectedRisk = 1;
        alert("Düşme riski var seçildi.");
    } else {
        selectedRisk = 0;
        alert("Düşme riski yok seçildi.");
    }
}

function saveReport() {
    let report = document.getElementById('report').value;

    if (selectedRisk === 3) {
        alert("Lütfen düşme riski olup olmadığını seçin.");
        return;
    }

    if (!report) {
        let result = confirm("Raporunuz boş. Devam etmek istiyor musunuz?");
        if (!result) {
            return;  // Eğer kullanıcı hayır derse, işlem durdurulur
        }
    }

    let confirmation = confirm(`Rapor: ${report}\nRisk Durumu: ${selectedRisk}\nBu bilgileri kaydetmek istiyor musunuz?`);

    if (confirmation) {
        // AJAX ile form verilerini sunucuya gönder
        let formData = new FormData();
        formData.append('video_id', <?php echo json_encode($video_id); ?>);
        formData.append('report', report);
        formData.append('risk', selectedRisk);

        fetch('save_report.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert("Kayıt edildi.");
            // İsteğe bağlı olarak sayfayı yeniden yükleyebilirsiniz
        })
        .catch(error => {
            console.error('Hata:', error);
        });
    } else {
        alert("Kayıt edilmedi!!");
    }
}

function handleButtonClick(buttonId, riskType) {
    // Tüm butonların active sınıfını kaldır
    let buttons = ['d-var', 'd-yok'];
    buttons.forEach(id => {
        let button = document.getElementById(id);
        if (button && button.id !== buttonId) {
            button.classList.remove('active'); // Diğer butonlardan active sınıfını kaldır
        }
    });

    // Tıklanan butona active sınıfını ekle
    let clickedButton = document.getElementById(buttonId);
    if (clickedButton) {
        clickedButton.classList.add('active'); // Tıklanan butona active sınıfını ekle
    }

    // Risk türünü ayarla
    handleRisk(riskType);
}

        
    </script>
</body>
</html>
