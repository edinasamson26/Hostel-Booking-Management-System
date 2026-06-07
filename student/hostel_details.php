<?php
session_start();
include('../config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$hostel_id = $_GET['id'] ?? null;

if (!$hostel_id) {
    header('Location: hostels.php');
    exit();
}

// Get hostel details
$hostel_stmt = $conn->prepare("SELECT * FROM hostels WHERE id = ?");
$hostel_stmt->bind_param("i", $hostel_id);
$hostel_stmt->execute();
$hostel = $hostel_stmt->get_result()->fetch_assoc();

if (!$hostel) {
    header('Location: hostels.php');
    exit();
}

// Check if user has already booked this hostel
$check_booking = $conn->prepare("SELECT id FROM bookings WHERE user_id = ? AND hostel_id = ? AND status != 'Rejected'");
$check_booking->bind_param("ii", $user_id, $hostel_id);
$check_booking->execute();
$existing_booking = $check_booking->get_result()->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($hostel['available_rooms'] <= 0) {
        $error = 'Sorry, no rooms available in this hostel.';
    } elseif ($existing_booking) {
        $error = 'You have already booked this hostel.';
    } else {
        $academic_year = trim($_POST['academic_year'] ?? '');
        $semester = trim($_POST['semester'] ?? '');

        if (empty($academic_year) || empty($semester)) {
            $error = 'Please select academic year and semester.';
        } else {
            // Insert booking
            $insert = $conn->prepare("INSERT INTO bookings (user_id, hostel_id, academic_year, semester) VALUES (?, ?, ?, ?)");
            $insert->bind_param("iiss", $user_id, $hostel_id, $academic_year, $semester);

            if ($insert->execute()) {
                // Update available rooms
                $update = $conn->prepare("UPDATE hostels SET available_rooms = available_rooms - 1 WHERE id = ?");
                $update->bind_param("i", $hostel_id);
                $update->execute();

                $success = 'Booking request submitted successfully! Admin will review your request.';
                
                // Redirect after success
                header('Refresh: 2; url=bookings.php');
            } else {
                $error = 'Failed to submit booking: ' . $conn->error;
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
    <title><?php echo htmlspecialchars($hostel['name']); ?> - Hostel Booking System</title>
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
                    <li><a href="hostels.php" class="active"> View Hostels</a></li>
                    <li><a href="bookings.php"> My Bookings</a></li>
                    <li><a href="../about.php"> About Us</a></li>
                    <li><a href="../contact.php">📞 Contact</a></li>
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

                <div class="card">
                    <div class="card-header">
                        🏢 <?php echo htmlspecialchars($hostel['name']); ?>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 20px;">
                            <div class="hostel-image" style="height: 300px; font-size: 5rem;">
                                🏢
                            </div>
                        </div>

                        <div class="grid grid-2">
                            <div>
                                <h4>Information</h4>
                                <p><strong>Gender:</strong> <?php echo htmlspecialchars($hostel['gender']); ?></p>
                                <p><strong>Total Rooms:</strong> <?php echo $hostel['rooms']; ?></p>
                                <p><strong>Available Rooms:</strong> <?php echo $hostel['available_rooms']; ?></p>
                                <p><strong>Price per Semester:</strong> TZS <?php echo number_format($hostel['price_per_semester'], 0); ?></p>
                                
                                <?php if ($hostel['available_rooms'] > 0): ?>
                                    <span class="badge badge-success" style="margin-top: 10px;">Available</span>
                                <?php else: ?>
                                    <span class="badge badge-danger" style="margin-top: 10px;">Full</span>
                                <?php endif; ?>
                            </div>

                            <div>
                                <h4>Description</h4>
                                <p><?php echo htmlspecialchars($hostel['description']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <?php if (!$existing_booking && $hostel['available_rooms'] > 0): ?>
                    <div class="card" style="margin-top: 30px;">
                        <div class="card-header">
                             Book This Hostel
                        </div>
                        <div class="card-body">
                            <form method="POST" id="bookingForm">
                                <div class="form-group">
                                    <label for="academic_year">Academic Year *</label>
                                    <select id="academic_year" name="academic_year" required>
                                        <option value="">Select Academic Year</option>
                                        <option value="2023/2024">2023/2024</option>
                                        <option value="2024/2025">2024/2025</option>
                                        <option value="2025/2026">2025/2026</option>
                                        <option value="2026/2027">2026/2027</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="semester">Semester *</label>
                                    <select id="semester" name="semester" required>
                                        <option value="">Select Semester</option>
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-success" style="width: 100%;">Submit Booking Request</button>
                                <a href="hostels.php" class="btn btn-secondary" style="width: 100%; margin-top: 10px;">Back to Hostels</a>
                            </form>
                        </div>
                    </div>
                <?php elseif ($existing_booking): ?>
                    <div class="card" style="margin-top: 30px;">
                        <div class="card-body">
                            <p style="color: var(--dark-gray);">You have already booked this hostel. <a href="bookings.php">View your bookings</a></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card" style="margin-top: 30px;">
                        <div class="card-body">
                            <p style="color: var(--danger-color);">This hostel is currently full.</p>
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
    <script src="../assets/js/validation.js"></script>
</body>
</html>
