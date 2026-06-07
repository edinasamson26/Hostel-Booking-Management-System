<?php
session_start();
include('../config/db.php');

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

// DEBUG: Check if columns exist (remove after fixing)
$check_columns = $conn->query("SHOW COLUMNS FROM users");
$columns = [];
while($col = $check_columns->fetch_assoc()) {
    $columns[] = $col['Field'];
}
// If registration_number doesn't exist, show error
if (!in_array('registration_number', $columns)) {
    die("Database error: 'registration_number' column missing from users table. Please run: ALTER TABLE users ADD COLUMN registration_number VARCHAR(50) UNIQUE;");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $registration_number = trim($_POST['registration_number'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($phone)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Enforce password policy: at least eight digits and at least one letter or special character
        $password_regex = '/^(?=(?:.*\d){8,})(?=.*[A-Za-z\W]).{9,}$/';
        if (!preg_match($password_regex, $password)) {
            $error = 'Password must contain at least eight digits and at least one letter or special character';
        }
        // Validate registration number format if provided
        $reg_num = $registration_number;
        if (!$error && !empty($reg_num)) {
            $reg_regex = '/^\d{8}\/[A-Za-z]\.\d{1,2}$/';
            if (!preg_match($reg_regex, $reg_num)) {
                $error = 'Registration number must be in the format 14325138/T.26';
            }
        }
        
        if (empty($error)) {
            // Check if email already exists
            $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $result = $check_email->get_result();

            if ($result->num_rows > 0) {
                $error = 'Email already registered';
            } else {
                // Check registration number if provided
                if (!empty($registration_number)) {
                    $check_reg = $conn->prepare("SELECT id FROM users WHERE registration_number = ?");
                    $check_reg->bind_param("s", $registration_number);
                    $check_reg->execute();
                    $reg_result = $check_reg->get_result();
                    if ($reg_result->num_rows > 0) {
                        $error = 'Registration number already exists';
                    }
                }
                
                if (empty($error)) {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert user
                    $insert = $conn->prepare("INSERT INTO users (name, email, password, phone, registration_number, gender) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert->bind_param("ssssss", $name, $email, $hashed_password, $phone, $registration_number, $gender);

                    if ($insert->execute()) {
                        $success = 'Registration successful! You can now login.';
                        // Clear form fields on success
                        $name = $email = $phone = $registration_number = $gender = '';
                    } else {
                        $error = 'Registration failed: ' . $conn->error;
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hostel Booking System</title>
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
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="hero">
            <h1>Create Your Account</h1>
            <p>Join us to book your hostel today</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" id="registerForm">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" data-validate="text" required 
                       value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" data-validate="email" required 
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" data-validate="phone" placeholder="+255..." required 
                       value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="registration_number">Registration Number</label>
                <input type="text" id="registration_number" name="registration_number" data-validate="registration"
                       placeholder="e.g., 14325138/T.26"
                       value="<?php echo isset($registration_number) ? htmlspecialchars($registration_number) : ''; ?>">
                <small style="display:block; margin-top:6px; color:#666;">Format: 12345678/T.26 (optional)</small>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo isset($gender) && $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo isset($gender) && $gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" data-validate="password" required 
                       placeholder="Must include at least eight digits and one letter/special char">
                <small style="display:block; margin-top:6px; color:#666;">Example: <em>12345678a</em> or <em>12345678@</em></small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" data-match="password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>

            <p style="text-align: center; margin-top: 15px;">
                Already have an account? <a href="login.php" style="color: var(--secondary-color); font-weight: bold;">Login here</a>
            </p>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Hostel Booking Management System. All rights reserved.</p>
    </footer>

    <script src="../assets/js/validation.js"></script>
</body>
</html>