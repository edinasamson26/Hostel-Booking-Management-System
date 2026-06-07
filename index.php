<?php
session_start();
include('config/db.php');

// Get statistics for homepage
$total_hostels = $conn->query("SELECT COUNT(*) as count FROM hostels")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$total_students = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Get hostels
$hostels_result = $conn->query("SELECT * FROM hostels LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Hostel Booking System</title>
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

    <main>
        <!-- Hero Section -->
        <div class="hero">
            <h1>Welcome to Hostel Booking System</h1>
            <p>Find and book your ideal hostel with ease</p>
            <div style="margin-top: 30px;">
                <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])): ?>
                    <a href="auth/register.php" class="btn btn-primary" style="margin-right: 10px;">Get Started</a>
                    <a href="auth/login.php" class="btn btn-secondary">Login</a>
                <?php else: ?>
                    <a href="student/hostels.php" class="btn btn-primary">Browse Hostels</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="container">
            <div class="grid grid-3" style="margin-top: 40px; margin-bottom: 40px;">
                <div class="card">
                    <div class="card-body" style="text-align: center; padding: 40px;">
                        <h3 style="color: var(--secondary-color); font-size: 2.5rem;"><?php echo $total_hostels; ?></h3>
                        <p style="font-size: 1.1rem;">Available Hostels</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" style="text-align: center; padding: 40px;">
                        <h3 style="color: var(--success-color); font-size: 2.5rem;"><?php echo $total_students; ?></h3>
                        <p style="font-size: 1.1rem;">Active Students</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" style="text-align: center; padding: 40px;">
                        <h3 style="color: var(--info-color); font-size: 2.5rem;"><?php echo $total_bookings; ?></h3>
                        <p style="font-size: 1.1rem;">Total Bookings</p>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div style="margin-top: 50px; margin-bottom: 50px;">
                <h2 style="text-align: center; margin-bottom: 30px;">Why Choose Us?</h2>
                <div class="grid grid-3">
                    <div class="card">
                        <div class="card-body" style="text-align: center;">
                            <h3 style="font-size: 2rem; margin-bottom: 10px;">🔒</h3>
                            <h4>Secure Booking</h4>
                            <p>Your booking information is safe and secure with us.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center;">
                            <h3 style="font-size: 2rem; margin-bottom: 10px;">⚡</h3>
                            <h4>Fast Processing</h4>
                            <p>Get your booking approved quickly by our admin team.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center;">
                            <h3 style="font-size: 2rem; margin-bottom: 10px;">💰</h3>
                            <h4>Affordable</h4>
                            <p>Competitive prices for quality hostel accommodation.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Hostels -->
            <div style="margin-top: 50px; margin-bottom: 50px;">
                <h2 style="text-align: center; margin-bottom: 30px;">Featured Hostels</h2>
                <div class="grid grid-3">
                        <?php if ($hostels_result && $hostels_result->num_rows > 0): ?>
                            <?php while ($hostel = $hostels_result->fetch_assoc()):
                                $hostel_id = isset($hostel['id']) ? (int)$hostel['id'] : 0;
                                $name = $hostel['name'] ?? 'Unknown Hostel';
                                $gender = $hostel['gender'] ?? 'N/A';
                                $rooms = isset($hostel['rooms']) ? (int)$hostel['rooms'] : 0;
                                $available = isset($hostel['available_rooms']) ? (int)$hostel['available_rooms'] : 0;
                                $price = isset($hostel['price_per_semester']) ? (float)$hostel['price_per_semester'] : 0.0;
                            ?>

                                <div class="hostel-card">
                                <div class="hostel-image">
                                     <?= htmlspecialchars($name) ?>
                                </div>

                                <div class="hostel-info">

                                    <h3 class="hostel-name">
                                        <?= htmlspecialchars($name) ?>
                                    </h3>

                                    <div class="hostel-meta">
                                        <div><strong>Gender:</strong> <?= htmlspecialchars($gender) ?></div>
                                        <div><strong>Rooms:</strong> <?= $rooms ?></div>
                                    </div>

                                    <div class="hostel-meta">
                                        <div><strong>Available:</strong> <?= $available ?></div>
                                        <div><strong>Price:</strong> TZS <?= number_format($price, 0) ?></div>
                                    </div>

                                    <?php if ($available > 0): ?>
                                        <span class="badge badge-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Full</span>
                                    <?php endif; ?>

                                    <div style="margin-top: 15px;">
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <a href="student/hostel_details.php?id=<?= $hostel_id ?>" class="btn btn-primary" style="width: 100%;">
                                                View Details
                                            </a>
                                        <?php else: ?>
                                            <a href="auth/login.php" class="btn btn-primary" style="width: 100%;">
                                                Login to Book
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="grid-column: 1/-1; text-align:center; color:var(--dark-gray);">No hostels available at the moment.</p>
                <?php endif; ?>
                </div>
            </div>

            <!-- Booking Process -->
            <div style="margin-top: 50px; margin-bottom: 50px; background-color: var(--light-gray); padding: 40px; border-radius: var(--border-radius);">
                <h2 style="text-align: center; margin-bottom: 30px;">How It Works</h2>
                <div class="grid grid-4">
                    <div style="text-align: center;">
                        <h3 style="font-size: 2rem; color: var(--secondary-color); margin-bottom: 10px;">1️⃣</h3>
                        <h4>Register</h4>
                        <p>Create your account and provide your information.</p>
                    </div>
                    <div style="text-align: center;">
                        <h3 style="font-size: 2rem; color: var(--secondary-color); margin-bottom: 10px;">2️⃣</h3>
                        <h4>Browse</h4>
                        <p>Explore available hostels and compare options.</p>
                    </div>
                    <div style="text-align: center;">
                        <h3 style="font-size: 2rem; color: var(--secondary-color); margin-bottom: 10px;">3️⃣</h3>
                        <h4>Book</h4>
                        <p>Select your hostel and submit booking request.</p>
                    </div>
                    <div style="text-align: center;">
                        <h3 style="font-size: 2rem; color: var(--secondary-color); margin-bottom: 10px;">4️⃣</h3>
                        <h4>Confirmed</h4>
                        <p>Get approval from admin and move in.</p>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div style="margin-top: 50px; margin-bottom: 50px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 50px; border-radius: var(--border-radius); text-align: center;">
                <h2>Ready to Find Your Hostel?</h2>
                <p style="font-size: 1.1rem; margin-top: 10px; margin-bottom: 20px;">Start your hostel booking journey with us today!</p>
                <?php if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])): ?>
                    <a href="auth/register.php" class="btn btn-primary" style="margin-right: 10px; background-color: white; color: var(--primary-color);">Register Now</a>
                    <a href="auth/login.php" class="btn btn-secondary">Login</a>
                <?php else: ?>
                    <a href="student/hostels.php" class="btn btn-primary">Browse Hostels</a>
                <?php endif; ?>
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
