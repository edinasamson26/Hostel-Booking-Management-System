<?php
session_start();
include('../config/db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$booking_id = $_GET['id'] ?? null;
$error = '';
$success = '';

if ($booking_id) {
    // Get booking details
    $stmt = $conn->prepare("
        SELECT b.*, h.name as hostel_name, h.gender, h.id as hostel_id, u.name as user_name, u.email as user_email
        FROM bookings b
        JOIN hostels h ON b.hostel_id = h.id
        JOIN users u ON b.user_id = u.id
        WHERE b.id = ?
    ");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        header('Location: bookings.php');
        exit();
    }

    // Handle approval/rejection
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'] ?? '';
        $remarks = trim($_POST['remarks'] ?? '');
        $admin_id = $_SESSION['admin_id'];
        $current_date = date('Y-m-d H:i:s');

        if ($action === 'approve') {
            // Update booking status
            $update = $conn->prepare("UPDATE bookings SET status = 'Approved', approved_date = ?, approved_by = ? WHERE id = ?");
            $update->bind_param("sii", $current_date, $admin_id, $booking_id);
            
            if ($update->execute()) {
                $success = 'Booking approved successfully!';
                // Redirect after success
                header('Refresh: 2; url=bookings.php');
            } else {
                $error = 'Failed to approve booking: ' . $conn->error;
            }
        } elseif ($action === 'reject') {
            // Update booking status
            $update = $conn->prepare("UPDATE bookings SET status = 'Rejected', remarks = ?, approved_by = ? WHERE id = ?");
            $update->bind_param("sii", $remarks, $admin_id, $booking_id);
            
            if ($update->execute()) {
                // Update available rooms (add back the room)
                $add_room = $conn->prepare("UPDATE hostels SET available_rooms = available_rooms + 1 WHERE id = ?");
                $add_room->bind_param("i", $booking['hostel_id']);
                $add_room->execute();

                $success = 'Booking rejected successfully!';
                // Redirect after success
                header('Refresh: 2; url=bookings.php');
            } else {
                $error = 'Failed to reject booking: ' . $conn->error;
            }
        }
    }
} else {
    // Show list of pending bookings
    $stmt = $conn->query("
        SELECT b.*, h.name as hostel_name, h.gender, u.name as user_name, u.email as user_email
        FROM bookings b
        JOIN hostels h ON b.hostel_id = h.id
        JOIN users u ON b.user_id = u.id
        WHERE b.status = 'Pending'
        ORDER BY b.booking_date ASC
    ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $booking_id ? 'Approve Booking' : 'Pending Bookings'; ?> - Hostel Booking System</title>
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
                    <li><a href="bookings.php"> Bookings</a></li>
                    <li><a href="approve.php" class="active">Approve Bookings</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <section>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($booking_id): ?>
                    <!-- Single Booking Review -->
                    <div class="card">
                        <div class="card-header">
                             Review Booking Request
                        </div>
                        <div class="card-body">
                            <div class="grid grid-2">
                                <div>
                                    <h4>Student Information</h4>
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['user_name']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['user_email']); ?></p>
                                </div>
                                <div>
                                    <h4>Booking Information</h4>
                                    <p><strong>Hostel:</strong> <?php echo htmlspecialchars($booking['hostel_name']); ?></p>
                                    <p><strong>Gender:</strong> <?php echo htmlspecialchars($booking['gender']); ?></p>
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <p><strong>Academic Year:</strong> <?php echo htmlspecialchars($booking['academic_year']); ?></p>
                                <p><strong>Semester:</strong> <?php echo htmlspecialchars($booking['semester']); ?></p>
                                <p><strong>Status:</strong> <span class="badge badge-primary"><?php echo htmlspecialchars($booking['status']); ?></span></p>
                                <p><strong>Booking Date:</strong> <?php echo date('M d, Y H:i', strtotime($booking['booking_date'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Approve/Reject Form -->
                    <div class="card" style="margin-top: 20px;">
                        <div class="card-header">
                             Decision
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="grid grid-2" style="gap: 20px;">
                                    <div>
                                        <button type="submit" name="action" value="approve" class="btn btn-success" style="width: 100%;">✅ Approve Booking</button>
                                    </div>
                                    <div>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger" style="width: 100%;">❌ Reject Booking</button>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 20px;">
                                    <label for="remarks">Remarks (for rejection):</label>
                                    <textarea id="remarks" name="remarks" placeholder="Enter rejection reason..."></textarea>
                                </div>
                            </form>

                            <a href="approve.php" class="btn btn-secondary" style="width: 100%; margin-top: 10px;">Back to Pending Bookings</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- List of Pending Bookings -->
                    <div class="card">
                        <div class="card-header">
                            ✅ Pending Bookings for Approval
                        </div>
                        <div class="card-body">
                            <?php if ($stmt->num_rows > 0): ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Email</th>
                                            <th>Hostel</th>
                                            <th>Academic Year</th>
                                            <th>Booking Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($booking = $stmt->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['user_email']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['hostel_name']); ?></td>
                                                <td><?php echo htmlspecialchars($booking['academic_year']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                                                <td>
                                                    <a href="approve.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.85rem;">Review</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No pending bookings. All requests have been reviewed!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Hostel Booking Management System. All rights reserved.</p>
    </footer>

    <script src="../assets/js/script.js"></script>
</body>
</html>
