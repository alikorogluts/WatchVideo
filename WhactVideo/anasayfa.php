<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anasayfa</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <!-- Logo ve Menü Kısmı -->
    <div class="logo-container">
        <img src="layout/CyberSoldiersLOGO.png" alt="Logo" class="Logo">
        <div class="site-title">CYBERSOLDIERS</div>
    </div>

    <!-- Çıkış Butonu ve Sayfa Adı -->
    <div class="header-right">
        <!--<h2 class="current-page">Anasayfa</h2>  //Sayfa adını burada gösteriyoruz -->
        <button class="logout-button" onclick="logout()">
            <img src="layout/quit.png" alt="Çıkış" class="logout-icon">
        </button>
    </div>

    <!-- Menü Barı -->
    <div class="header">
        <a href="anasayfa.php" class="menu-item1"><b>ANASAYFA</b></a>
        <a href="izlenmeyen.php" class="menu-item"><b>İZLEMEDİĞİM VİDEOLAR</b></a>
        <a href="izlenen.php" class="menu-item"><b>İZLEDİĞİM VİDEOLAR</b></a>
    </div>

    <!-- Butonlar Yan Yana -->
    <div class="container">
        <a href="izlenmeyen.php" class="custom-button">
            <span class="icon">🎥</span>
            <span class="text"><b>İZLEMEDİĞİM VİDEOLAR</b></span>
        </a>
        <a href="izlenen.php" class="custom-button2">
            <span class="icon">🎥</span>
            <span class="text"><b>İZLEDİĞİM VİDEOLAR</b></span>
        </a>
    </div>

    <script>
        function logout() {           
            window.location.href = 'index.php';
        }
    </script> 
</body>
</html>
