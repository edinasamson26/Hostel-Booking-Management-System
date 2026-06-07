<?php 
session_start();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required';
    } else {
        // In a real application, you would send this email
        // For now, we'll just show a success message
        $success = 'Thank you for your message! We will get back to you soon.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Hostel Booking System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="index.php" class="logo">🏛️ Hostel Booking</a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <div class="auth-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="student/dashboard.php" class="btn btn-primary">Dashboard</a>
                    <a href="auth/logout.php" class="btn btn-danger">Logout</a>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <a href="admin/dashboard.php" class="btn btn-primary">Admin Panel</a>
                    <a href="admin/logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="auth/register.php" class="btn btn-primary">Register</a>
                    <a href="auth/login.php" class="btn btn-secondary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="hero">
            <h1>Contact Us</h1>
            <p>Get in touch with our support team</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="grid grid-2" style="margin: 30px 0; gap: 30px;">
            <!-- Contact Form -->
            <div>
                <div class="card">
                    <div class="card-header">Send Us a Message</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="name">Your Name *</label>
                                <input type="text" id="name" name="name" required 
                                       value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">Your Email *</label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <input type="text" id="subject" name="subject" required 
                                       value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <div class="card" style="margin-bottom: 20px;">
                    <div class="card-header">📞 Contact Information</div>
                    <div class="card-body">
                        <p style="margin-bottom: 15px;">
                            <strong>Email:</strong><br>
                            <a href="mailto:support@hostelbooking.com" style="color: var(--secondary-color); text-decoration: none;">
                                support@hostelbooking.com
                            </a>
                        </p>
                        <p style="margin-bottom: 15px;">
                            <strong>Phone:</strong><br>
                            +255 22 123 4567
                        </p>
                        <p style="margin-bottom: 15px;">
                            <strong>Address:</strong><br>
                            University of Dar es Salaam<br>
                            Dar es Salaam, Tanzania
                        </p>
                        <p>
                            <strong>Office Hours:</strong><br>
                            Monday - Friday: 8:00 AM - 5:00 PM<br>
                            Saturday: 9:00 AM - 1:00 PM<br>
                            Sunday: Closed
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">❓ FAQ</div>
                    <div class="card-body" style="font-size: 0.95rem;">
                        <p><strong>Q: How long does approval take?</strong><br>
                        A: Usually 1-3 business days.</p>

                        <p style="margin-top: 10px;"><strong>Q: Can I cancel my booking?</strong><br>
                        A: Yes, contact admin for cancellation.</p>

                        <p style="margin-top: 10px;"><strong>Q: Is payment required?</strong><br>
                        A: Payment details will be provided after approval.</p>

                        <p style="margin-top: 10px;"><strong>Q: Can I change my booking?</strong><br>
                        A: Contact support to discuss options.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Hostel Booking Management System. All rights reserved.</p>
        <p style="margin-top: 10px; font-size: 0.9rem; opacity: 0.8;">
            <a href="about.php" style="color: white; text-decoration: none; margin-right: 15px;">About</a>
            <a href="contact.php" style="color: white; text-decoration: none;">Contact</a>
        </p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
