<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="video_detay.css">
    <style>
        /* Box-shadow stilini burada da ekleyebilirsin */
        .risk-button, .no-risk-button {
            box-shadow: 10px 10px 15px #888;
        }
        .risk-button.active, .no-risk-button.active {
            box-shadow: 10px 10px 15px #000;
        }
    </style>
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
                if($row['risk']=='0'){   
                   
                echo '<div class="video-container">';
                echo '<video width="640" height="360" controls>
                    <source src="' . htmlspecialchars(str_replace('\\', '/', $row['video_yolu'])) . '" type="video/mp4">
                    Your browser does not support the video tag.
                </video>';
                echo '<div  class="button-group">
                <button id="d-var" class="risk-button"  onclick="setBoxShadowOnClick(\'risk\')">DÜŞME RİSKİ VAR</button>
                <button id="d-yok" class="no-risk-button" style="box-shadow: 10px 10px 15px rgb(26, 255, 0);
                    cursor: pointer;" onclick="setBoxShadowOnClick(\'risk\')">DÜŞME RİSKİ YOK</button>
                    </div>';
                echo '<textarea id="report" readonly>' . htmlspecialchars($row['rapor']) . '</textarea>';
                echo '<button id="kaydet-buton" class="kaydet-button" onclick="saveReport()" style="display:none;">KAYDET</button>';
                echo '<button id="düzenle-buton" class="kaydet-button" onclick="clickReport()">Düzenlemeleri etkinleştir</button>';
                echo '</div>';
                }
                else{

                    echo '<div class="video-container">';
                    echo '<video width="640" height="360" controls>
                        <source src="' . htmlspecialchars(str_replace('\\', '/', $row['video_yolu'])) . '" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>';
                    echo '<div class="button-group">
                        <button id="d-var" class="risk-button" style="box-shadow: 10px 10px 15px rgb(255, 0, 0);
                            cursor: pointer;" onclick="setBoxShadowOnClick(\'risk\')">DÜŞME RİSKİ VAR</button>
                        <button id="d-yok" class="no-risk-button" onclick="setBoxShadowOnClick(\'risk\')">DÜŞME RİSKİ YOK</button>
                        </div>';
                    echo '<textarea id="report" readonly>' . htmlspecialchars($row['rapor']) . '</textarea>';
                    echo '<button id="kaydet-buton" class="kaydet-button" onclick="saveReport()" style="display:none;">KAYDET</button>';
                    echo '<button id="düzenle-buton" class="kaydet-button" onclick="clickReport()">Düzenlemeleri etkinleştir</button>';
                    echo '</div>';
                }
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
        let onaylandi = false;
        function handleRisk(riskType) {
            if (riskType === 'risk') {
                selectedRisk = 1;
                alert("Düşme riski var seçildi.");
            } else {
                selectedRisk = 0;
                alert("Düşme riski yok seçildi.");
            }
        }

        window.onload = function() {
            document.getElementById("d-yok").disabled = true;
            document.getElementById("d-var").disabled = true;
            document.getElementById("report").readOnly = true;
        }

        function clickReport(riskType) {
            if (!onaylandi) {
                if (confirm("Değişiklik yapmak isteğinize emin misiniz?")) {
                    document.getElementById("kaydet-buton").style.display = "inline";
                    document.getElementById("düzenle-buton").style.display = "none";
                    document.getElementById("d-yok").disabled = false;
                    document.getElementById("d-var").disabled = false;
                    document.getElementById("report").readOnly = false;

                    // Box-shadow'u geri getir
                    document.getElementById("d-var").classList.add('active');
                    document.getElementById("d-yok").classList.add('active');

                    selectedRisk = (riskType === 'var') ? 1 : 0;
                    onaylandi = true;
                } else {
                    onaylandi = false;
                }
            }
        }

        function saveReport() {
            if (onaylandi) {
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
        }
        function handleButtonClick(buttonId, boxShadowValue, riskType) {
    // Tüm butonların box-shadow değerini sıfırla
    let buttons = ['d-var', 'd-yok'];
    buttons.forEach(id => {
        let button = document.getElementById(id);
        if (button && button.id !== buttonId) {
            button.style.boxShadow = 'none'; // Diğer butonların box-shadow'unu sıfırla
        }
    });

    // Tıklanan butona box-shadow değerini ayarla
    let clickedButton = document.getElementById(buttonId);
    if (clickedButton) {
        clickedButton.style.boxShadow = boxShadowValue;
    }

    // Risk türünü ayarla
    handleRisk(riskType);
}


// Butonları seçip, box-shadow değerlerini ayarlayın
document.getElementById('d-var').addEventListener('click', function() {
    handleButtonClick('d-var', '10px 10px 15px rgb(255, 0, 0)', 'risk');
});

document.getElementById('d-yok').addEventListener('click', function() {
    handleButtonClick('d-yok', '10px 10px 15px rgb(26, 255, 0)', 'no-risk');
});






    </script>
</body>
</html>
