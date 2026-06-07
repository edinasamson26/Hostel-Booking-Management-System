<?php
session_start();
include('../config/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get filter parameters
$status_filter = $_GET['status'] ?? 'all';
$hostel_filter = $_GET['hostel'] ?? 'all';

// Build query
$query = "
    SELECT b.*, h.name as hostel_name, h.gender, u.name as user_name, u.email as user_email
    FROM bookings b
    JOIN hostels h ON b.hostel_id = h.id
    JOIN users u ON b.user_id = u.id
    WHERE 1=1
";

$params = [];
$types = "";

if ($status_filter !== 'all') {
    $query .= " AND b.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if ($hostel_filter !== 'all') {
    $query .= " AND b.hostel_id = ?";
    $params[] = $hostel_filter;
    $types .= "i";
}

$query .= " ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$bookings = $stmt->get_result();

// Get hostels for filter dropdown
$hostels_result = $conn->query("SELECT id, name FROM hostels ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Hostel Booking System</title>
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
                    <li><a href="dashboard.php"> Dashboard</a></li>
                    <li><a href="bookings.php" class="active"> Bookings</a></li>
                    <li><a href="approve.php">✅ Approve Bookings</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <section>
                <div class="card">
                    <div class="card-header">
                         All Bookings
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #ddd;">
                            <label style="margin-right: 10px;">Filter by Status:</label>
                            <a href="bookings.php" class="btn btn-secondary" style="padding: 8px 12px; margin-right: 5px;">All</a>
                            <a href="bookings.php?status=Pending" class="btn btn-secondary" style="padding: 8px 12px; margin-right: 5px;">Pending</a>
                            <a href="bookings.php?status=Approved" class="btn btn-secondary" style="padding: 8px 12px; margin-right: 5px;">Approved</a>
                            <a href="bookings.php?status=Rejected" class="btn btn-secondary" style="padding: 8px 12px;">Rejected</a>

                            <div style="margin-top: 10px;">
                                <label style="margin-right: 10px;">Filter by Hostel:</label>
                                <select onchange="window.location.href='bookings.php?status=<?php echo $status_filter; ?>&hostel=' + this.value;" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                                    <option value="all">All Hostels</option>
                                    <?php while ($hostel = $hostels_result->fetch_assoc()): ?>
                                        <option value="<?php echo $hostel['id']; ?>" <?php echo $hostel_filter == $hostel['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($hostel['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings Table -->
                <div class="card" style="margin-top: 20px;">
                    <div class="card-body">
                        <?php if ($bookings->num_rows > 0): ?>
                            <table style="font-size: 0.9rem;">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Email</th>
                                        <th>Hostel</th>
                                        <th>Academic Year</th>
                                        <th>Status</th>
                                        <th>Booking Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = $bookings->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['user_email']); ?></td>
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
                        <?php else: ?>
                            <p>No bookings found.</p>
                        <?php endif; ?>
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
