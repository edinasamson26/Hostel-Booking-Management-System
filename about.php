<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Hostel Booking System</title>
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
            <h1>About Us</h1>
            <p>Learn more about our hostel booking system</p>
        </div>

        <div class="card" style="margin: 30px 0;">
            <div class="card-header">About Hostel Booking System</div>
            <div class="card-body">
                <h3>Our Mission</h3>
                <p>
                    The Hostel Booking Management System is designed to streamline the process of finding, booking, and managing hostel accommodations 
                    for students. Our platform connects students with quality hostel facilities while providing administrators with efficient tools 
                    to manage bookings and occupancy.
                </p>

                <h3 style="margin-top: 20px;">Our Vision</h3>
                <p>
                    To become the leading platform for hostel management in educational institutions, making accommodation booking simple, 
                    transparent, and efficient for both students and administrators.
                </p>

                <h3 style="margin-top: 20px;">What We Offer</h3>
                <ul style="margin-left: 20px; line-height: 2;">
                    <li><strong>Easy Registration:</strong> Simple and quick account creation process</li>
                    <li><strong>Hostel Browsing:</strong> View available hostels with detailed information</li>
                    <li><strong>Online Booking:</strong> Submit booking requests from anywhere, anytime</li>
                    <li><strong>Real-time Status:</strong> Track your booking status in real-time</li>
                    <li><strong>Admin Panel:</strong> Efficient booking management and approval system</li>
                    <li><strong>Secure System:</strong> Safe and secure user data management</li>
                </ul>

                <h3 style="margin-top: 20px;">Key Features</h3>
                <div class="grid grid-2" style="margin-top: 20px;">
                    <div>
                        <h4>For Students</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li>Create account easily</li>
                            <li>Browse all hostels</li>
                            <li>Filter by gender</li>
                            <li>View detailed info</li>
                            <li>Submit booking</li>
                            <li>Track status</li>
                        </ul>
                    </div>
                    <div>
                        <h4>For Administrators</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li>Secure login</li>
                            <li>View dashboard</li>
                            <li>Manage bookings</li>
                            <li>Approve/Reject</li>
                            <li>Filter options</li>
                            <li>System reports</li>
                        </ul>
                    </div>
                </div>

                <h3 style="margin-top: 30px;">Available Hostels</h3>
                <p>We have 11 quality hostels available on our platform:</p>
                <div class="grid grid-2" style="margin-top: 20px;">
                    <div>
                        <h5 style="color: var(--primary-color); margin-bottom: 10px;">Female Hostels</h5>
                        <ul style="margin-left: 20px; line-height: 1.8;">
                            <li>Matola - 120 rooms</li>
                            <li>Kinjekitile - 110 rooms</li>
                            <li>Maria Nyerere - 150 rooms</li>
                            <li>Sophia - 100 rooms</li>
                            <li>Vikenge - 105 rooms</li>
                        </ul>
                    </div>
                    <div>
                        <h5 style="color: var(--primary-color); margin-bottom: 10px;">Male Hostels</h5>
                        <ul style="margin-left: 20px; line-height: 1.8;">
                            <li>Kimweri - 130 rooms</li>
                            <li>Mirambo - 120 rooms</li>
                            <li>Mkwawa - 140 rooms</li>
                            <li>Cabral - 110 rooms</li>
                            <li>Buguruni - 100 rooms</li>
                            <li>Tangeni - 115 rooms</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">Technology Stack</div>
            <div class="card-body">
                <div class="grid grid-3">
                    <div>
                        <h4>Frontend</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li>HTML5</li>
                            <li>CSS3</li>
                            <li>JavaScript</li>
                            <li>Responsive Design</li>
                        </ul>
                    </div>
                    <div>
                        <h4>Backend</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li>PHP</li>
                            <li>MySQL</li>
                            <li>Session Management</li>
                            <li>Security Features</li>
                        </ul>
                    </div>
                    <div>
                        <h4>Development</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li>XAMPP</li>
                            <li>phpMyAdmin</li>
                            <li>VS Code</li>
                            <li>Git</li>
                        </ul>
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
