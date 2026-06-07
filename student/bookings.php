<?php
session_start();
include('../config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all bookings for this user
$stmt = $conn->prepare("
    SELECT b.*, h.name as hostel_name, h.gender
    FROM bookings b
    JOIN hostels h ON b.hostel_id = h.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Hostel Booking System</title>
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
                <span style="color: white; margin-right: 10px;">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="dashboard">
            <!-- Sidebar -->
            <aside class="sidebar">
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php"> Dashboard</a></li>
                    <li><a href="hostels.php"> View Hostels</a></li>
                    <li><a href="bookings.php" class="active"> My Bookings</a></li>
                    <li><a href="../about.php"> About Us</a></li>
                    <li><a href="../contact.php">📞 Contact</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <section>
                <div class="card">
                    <div class="card-header">
                         My Bookings
                    </div>
                    <div class="card-body">
                        <?php if ($bookings->num_rows > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Hostel</th>
                                        <th>Gender</th>
                                        <th>Academic Year</th>
                                        <th>Semester</th>
                                        <th>Status</th>
                                        <th>Booking Date</th>
                                        <th>Approved Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = $bookings->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <a href="hostel_details.php?id=<?php echo $booking['hostel_id']; ?>" style="color: var(--secondary-color); text-decoration: none; font-weight: 500;">
                                                    <?php echo htmlspecialchars($booking['hostel_name']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($booking['gender']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['academic_year']); ?></td>
                                            <td><?php echo htmlspecialchars($booking['semester']); ?></td>
                                            <td>
                                                <span class="badge 
                                                    <?php 
                                                        if ($booking['status'] == 'Approved') echo 'badge-success';
                                                        elseif ($booking['status'] == 'Pending') echo 'badge-primary';
                                                        elseif ($booking['status'] == 'Rejected') echo 'badge-danger';
                                                        else echo 'badge-primary';
                                                    ?>">
                                                    <?php echo htmlspecialchars($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                            <td><?php echo $booking['approved_date'] ? date('M d, Y', strtotime($booking['approved_date'])) : 'Pending'; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>You haven't made any bookings yet.</p>
                            <a href="hostels.php" class="btn btn-primary" style="margin-top: 10px;">Browse Hostels</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Booking Instructions -->
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                         Booking Information
                    </div>
                    <div class="card-body">
                        <h4>Booking Status Explanation:</h4>
                        <ul style="margin-left: 20px; line-height: 2;">
                            <li><strong>Pending:</strong> Your booking request is waiting for admin approval.</li>
                            <li><strong>Approved:</strong> Your booking has been approved. You can move into the hostel.</li>
                            <li><strong>Rejected:</strong> Your booking request was rejected. Please contact admin for more info.</li>
                            <li><strong>Cancelled:</strong> Your booking has been cancelled.</li>
                        </ul>

                        <h4 style="margin-top: 20px;">Steps to Complete Your Booking:</h4>
                        <ol style="margin-left: 20px; line-height: 2;">
                            <li>Browse available hostels</li>
                            <li>Select your preferred hostel and complete the booking form</li>
                            <li>Wait for admin approval</li>
                            <li>Once approved, check in at the hostel</li>
                        </ol>
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
