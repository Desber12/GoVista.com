<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Travel Website</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <h2>Daftar Akun Baru</h2>
            
            <?php
            require_once 'includes/config.php';
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = sanitize($_POST['username']);
                $email = sanitize($_POST['email']);
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $full_name = sanitize($_POST['full_name']);
                $phone = sanitize($_POST['phone']);
                
                // Validation
                if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
                    $error = "Semua field wajib harus diisi!";
                } elseif ($password !== $confirm_password) {
                    $error = "Password dan konfirmasi password tidak sama!";
                } elseif (strlen($password) < 6) {
                    $error = "Password minimal 6 karakter!";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Format email tidak valid!";
                } else {
                    // Check if username or email already exists
                    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                    $stmt->bind_param("ss", $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $error = "Username atau email sudah digunakan!";
                    } else {
                        // Insert new user
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $phone);
                        
                        if ($stmt->execute()) {
                            $success = "Pendaftaran berhasil! Silakan login.";
                        } else {
                            $error = "Terjadi kesalahan saat mendaftar!";
                        }
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
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="full_name">Nama Lengkap:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">No. Telepon:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Daftar</button>
            </form>
            
            <p class="auth-link">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </p>
            
            <p class="auth-link">
                <a href="index.php">Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</body>
</html>

