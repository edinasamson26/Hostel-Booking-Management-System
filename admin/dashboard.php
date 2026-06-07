<?php
session_start();
include('../config/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_hostels = $conn->query("SELECT COUNT(*) as count FROM hostels")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'Pending'")->fetch_assoc()['count'];
$approved_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'Approved'")->fetch_assoc()['count'];

// Get recent bookings
$recent_stmt = $conn->query("
    SELECT b.*, h.name as hostel_name, u.name as user_name
    FROM bookings b
    JOIN hostels h ON b.hostel_id = h.id
    JOIN users u ON b.user_id = u.id
    ORDER BY b.booking_date DESC
    LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hostel Booking System</title>
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
                    <li><a href="bookings.php">Manage Bookings</a></li>
                </ul>
            </nav>
            <div class="auth-links">
                <span style="color: white; margin-right: 10px;">Admin: <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <!-- Sidebar -->
            <aside class="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="active"> Dashboard</a></li>
                    <li><a href="bookings.php"> Bookings</a></li>
                    <li><a href="approve.php">✅ Approve Bookings</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <section>
                <div class="card">
                    <div class="card-header">
                         Admin Dashboard
                    </div>
                    <div class="card-body">
                        <p>Welcome to the hostel booking admin panel. Here you can manage bookings, approve requests, and view system statistics.</p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-3" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--secondary-color); font-size: 2rem;"><?php echo $total_users; ?></h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--info-color); font-size: 2rem;"><?php echo $total_hostels; ?></h3>
                            <p>Total Hostels</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--warning-color); font-size: 2rem;"><?php echo $total_bookings; ?></h3>
                            <p>Total Bookings</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Status Cards -->
                <div class="grid grid-3" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--warning-color); font-size: 2rem;"><?php echo $pending_bookings; ?></h3>
                            <p>Pending Bookings</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="color: var(--success-color); font-size: 2rem;"><?php echo $approved_bookings; ?></h3>
                            <p>Approved Bookings</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center; padding: 30px;">
                            <h3 style="font-size: 2rem;"><?php echo $total_bookings - $approved_bookings - $pending_bookings; ?></h3>
                            <p>Other Bookings</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                         Recent Bookings
                    </div>
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Hostel</th>
                                    <th>Academic Year</th>
                                    <th>Status</th>
                                    <th>Booking Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($booking = $recent_stmt->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
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
                                        <td>
                                            <a href="approve.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.85rem;">Review</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                        ⚡ Quick Actions
                    </div>
                    <div class="card-body">
                        <a href="approve.php" class="btn btn-primary" style="margin-right: 10px;">Approve Pending Bookings</a>
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
