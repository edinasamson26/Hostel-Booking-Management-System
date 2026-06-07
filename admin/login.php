<?php
session_start();
include('../config/db.php');

// If admin is already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } else {
        // Get admin from database
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Verify password
            if (hash('sha256', $password) === $admin['password']) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid username or password';
            }
        } else {
            $error = 'Invalid username or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hostel Booking System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="../index.php" class="logo">🏛️ Hostel Booking</a>
            <nav>
                <ul>
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../auth/login.php">Student Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="hero">
            <h1>Admin Login</h1>
            <p>Access the admin panel</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Login to Admin Panel</button>

            <p style="text-align: center; margin-top: 15px;">
                <a href="../auth/login.php" style="color: var(--secondary-color); font-weight: bold;">Student Login</a>
            </p>
        </form>

        <div class="card" style="margin-top: 30px;">
            <div class="card-header">Demo Credentials</div>
            <div class="card-body">
                <p><strong>Username:</strong> admin</p>
                <p><strong>Password:</strong> admin123</p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Hostel Booking Management System. All rights reserved.</p>
    </footer>
</body>
</html>
