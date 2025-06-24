<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Travel Website</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <h2>Login</h2>
            
            <?php
            require_once 'includes/config.php';
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = sanitize($_POST['username']);
                $password = $_POST['password'];
                
                if (empty($username) || empty($password)) {
                    $error = "Username dan password harus diisi!";
                } else {
                    $stmt = $conn->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ? OR email = ?");
                    $stmt->bind_param("ss", $username, $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows == 1) {
                        $user = $result->fetch_assoc();
                        
                        if (password_verify($password, $user['password'])) {
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['full_name'] = $user['full_name'];
                            $_SESSION['role'] = $user['role'];
                            
                            if ($user['role'] == 'admin') {
                                header('Location: admin/dashboard.php');
                            } else {
                                header('Location: user/dashboard.php');
                            }
                            exit();
                        } else {
                            $error = "Password salah!";
                        }
                    } else {
                        $error = "Username atau email tidak ditemukan!";
                    }
                }
            }
            
            function sanitize($data) {
                return htmlspecialchars(strip_tags(trim($data)));
            }
            ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username atau Email:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <p class="auth-link">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </p>
            
            <p class="auth-link">
                <a href="index.php">Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</body>
</html>

