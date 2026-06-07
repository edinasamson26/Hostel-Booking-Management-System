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
$user_stmt = $conn->prepare("SELECT gender FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Get filter parameters
$gender_filter = $_GET['gender'] ?? 'all';

// Build query
$query = "SELECT * FROM hostels";
$params = [];
$types = "";

if ($gender_filter !== 'all') {
    $query .= " WHERE gender = ?";
    $params[] = $gender_filter;
    $types = "s";
}

$query .= " ORDER BY name ASC";

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$hostels = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Hostels - Hostel Booking System</title>
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
                    <li><a href="dashboard.php">📊 Dashboard</a></li>
                    <li><a href="hostels.php" class="active">🏠 View Hostels</a></li>
                    <li><a href="bookings.php">📋 My Bookings</a></li>
                    <li><a href="../about.php">ℹ️ About Us</a></li>
                    <li><a href="../contact.php">📞 Contact</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <section>
                <div class="card">
                    <div class="card-header">
                        🏠 Available Hostels
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div style="margin-bottom: 20px;">
                            <label style="margin-right: 10px;">Filter by Gender:</label>
                            <a href="hostels.php?gender=all" class="btn btn-secondary" style="padding: 8px 12px;">All</a>
                            <a href="hostels.php?gender=Male" class="btn btn-secondary" style="padding: 8px 12px;">Male</a>
                            <a href="hostels.php?gender=Female" class="btn btn-secondary" style="padding: 8px 12px;">Female</a>
                        </div>
                    </div>
                </div>

                <!-- Hostels Grid -->
                <div class="grid grid-3" style="margin-top: 20px;" class="hostel-list">
                    <?php if ($hostels->num_rows > 0): ?>
                        <?php while ($hostel = $hostels->fetch_assoc()): ?>
                            <div class="hostel-card" data-gender="<?php echo htmlspecialchars($hostel['gender']); ?>" data-available="<?php echo $hostel['available_rooms']; ?>">
                                <div class="hostel-image">
                                    🏢 <?php echo htmlspecialchars($hostel['name']); ?>
                                </div>
                                <div class="hostel-info">
                                    <h3 class="hostel-name"><?php echo htmlspecialchars($hostel['name']); ?></h3>
                                    
                                    <div class="hostel-meta">
                                        <div>
                                            <strong>Gender:</strong> <?php echo htmlspecialchars($hostel['gender']); ?>
                                        </div>
                                        <div>
                                            <strong>Available Rooms:</strong> <?php echo $hostel['available_rooms']; ?>/<?php echo $hostel['rooms']; ?>
                                        </div>
                                    </div>

                                    <div class="hostel-meta">
                                        <div>
                                            <strong>Total Rooms:</strong> <?php echo $hostel['rooms']; ?>
                                        </div>
                                        <div>
                                            <strong>Price:</strong> TZS <?php echo number_format($hostel['price_per_semester'], 0); ?>
                                        </div>
                                    </div>

                                    <?php if ($hostel['description']): ?>
                                        <p style="margin: 10px 0; color: var(--dark-gray); font-size: 0.9rem;">
                                            <?php echo htmlspecialchars(substr($hostel['description'], 0, 100)); ?>...
                                        </p>
                                    <?php endif; ?>

                                    <?php if ($hostel['available_rooms'] > 0): ?>
                                        <span class="badge badge-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Full</span>
                                    <?php endif; ?>

                                    <div style="margin-top: 15px;">
                                        <a href="hostel_details.php?id=<?php echo $hostel['id']; ?>" class="btn btn-primary" style="width: 100%;">View Details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="grid-column: 1/-1; text-align: center;">
                            <p>No hostels available matching your criteria.</p>
                        </div>
                    <?php endif; ?>
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
