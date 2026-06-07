<?php
session_start();
include('../config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user info
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Get booking statistics
$pending_stmt = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ? AND status = 'Pending'");
$pending_stmt->bind_param("i", $user_id);
$pending_stmt->execute();
$pending = $pending_stmt->get_result()->fetch_assoc()['count'];

$approved_stmt = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ? AND status = 'Approved'");
$approved_stmt->bind_param("i", $user_id);
$approved_stmt->execute();
$approved = $approved_stmt->get_result()->fetch_assoc()['count'];

$total_stmt = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ?");
$total_stmt->bind_param("i", $user_id);
$total_stmt->execute();
$total = $total_stmt->get_result()->fetch_assoc()['count'];

// Get recent bookings
$recent_stmt = $conn->prepare("
    SELECT b.*, h.name as hostel_name 
    FROM bookings b 
    JOIN hostels h ON b.hostel_id = h.id 
    WHERE b.user_id = ? 
    ORDER BY b.booking_date DESC 
    LIMIT 5
");
$recent_stmt->bind_param("i", $user_id);
$recent_stmt->execute();
$recent_bookings = $recent_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Hostel Booking System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="../index.php" class="logo">🏛️ Hostel Booking</a>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="hostels.php">Browse Hostels</a></li>
                    <li><a href="bookings.php">My Bookings</a></li>
                </ul>
            </nav>
            <div class="auth-links">
                <span style="color: white; margin-right: 10px;">Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <!-- Sidebar -->
            <aside class="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="active"> Dashboard</a></li>
                    <li><a href="hostels.php"> View Hostels</a></li>
                    <li><a href="bookings.php"> My Bookings</a></li>
                    <li><a href="../about.php"> About Us</a></li>
                    <li><a href="../contact.php">📞 Contact</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <section>
                <div class="card">
                    <div class="card-header">
                         Welcome to Your Dashboard
                    </div>
                    <div class="card-body">
                        <p>Hello <strong><?php echo htmlspecialchars($user['name']); ?></strong>! Here's your hostel booking overview.</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-3" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--secondary-color); font-size: 2rem;"><?php echo $total; ?></h3>
                            <p>Total Bookings</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--success-color); font-size: 2rem;"><?php echo $approved; ?></h3>
                            <p>Approved Bookings</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--warning-color); font-size: 2rem;"><?php echo $pending; ?></h3>
                            <p>Pending Bookings</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                         Recent Bookings
                    </div>
                    <div class="card-body">
                        <?php if ($recent_bookings->num_rows > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Hostel</th>
                                        <th>Academic Year</th>
                                        <th>Status</th>
                                        <th>Booking Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($booking['hostel_name']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['academic_year']); ?></td>
                                            <td>
                                                <span class="badge 
                                                    <?php 
                                                        if ($booking['status'] == 'Approved') echo 'badge-success';
                                                        elseif ($booking['status'] == 'Pending') echo 'badge-primary';
                                                        else echo 'badge-danger';
                                                    ?>">
                                                    <?php echo htmlspecialchars($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No bookings yet. <a href="hostels.php">Browse available hostels</a></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                        ⚡ Quick Actions
                    </div>
                    <div class="card-body">
                        <a href="hostels.php" class="btn btn-primary" style="margin-right: 10px;">Browse Hostels</a>
                        <a href="bookings.php" class="btn btn-secondary">View All Bookings</a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Hostel Booking Management System. All rights reserved.</p>
    </footer>

    <script src="../assets/js/script.js"></script>
</body>
</html>
