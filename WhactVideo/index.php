<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <img src="layout/drone1.png" alt="Drone" class="Drone">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Kullanıcı adı ve şifreyi kontrol et
            if ($username == 'admin' && $password == 'admin') {
                // Anasayfaya yönlendir
                header("Location: anasayfa.php");
                exit();
            } else {
                // Hata mesajı göster
                echo "<p style='color:red;'>Kullanıcı adı veya şifreyi kontrol ediniz!</p>";
            }
        }
        ?>
        <form method="post" action="">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
