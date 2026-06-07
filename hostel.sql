-- Hostel Booking Management System Database
-- Create Database
CREATE DATABASE IF NOT EXISTS hostel_booking;
USE hostel_booking;

-- Users Table (for students)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    registration_number VARCHAR(50) UNIQUE,
    gender ENUM('Male', 'Female'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin Table
CREATE TABLE IF NOT EXISTS admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hostels Table
CREATE TABLE IF NOT EXISTS hostels (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    rooms INT NOT NULL,
    available_rooms INT NOT NULL,
    price_per_semester DECIMAL(10, 2) DEFAULT 0,
    description TEXT,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    hostel_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Approved', 'Rejected', 'Cancelled') DEFAULT 'Pending',
    academic_year VARCHAR(9),
    semester INT,
    approved_date TIMESTAMP NULL,
    approved_by INT NULL,
    remarks TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hostel_id) REFERENCES hostels(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES admin(id)
);

-- Insert Admin Users
INSERT INTO admin (username, password, email) VALUES
('admin', SHA2('admin123', 256), 'admin@hostel.com'),
('admin2', SHA2('admin123', 256), 'admin2@hostel.com'),
('admin3', SHA2('admin123', 256), 'admin3@hostel.com'),
('admin4', SHA2('admin123', 256), 'admin4@hostel.com'),
('admin5', SHA2('admin123', 256), 'admin5@hostel.com'),
('admin6', SHA2('admin123', 256), 'admin6@hostel.com'),
('admin7', SHA2('admin123', 256), 'admin7@hostel.com'),
('admin8', SHA2('admin123', 256), 'admin8@hostel.com'),
('admin9', SHA2('admin123', 256), 'admin9@hostel.com'),
('admin10', SHA2('admin123', 256), 'admin10@hostel.com'),
('admin11', SHA2('admin123', 256), 'admin11@hostel.com'),
('admin12', SHA2('admin123', 256), 'admin12@hostel.com'),
('admin13', SHA2('admin123', 256), 'admin13@hostel.com'),
('admin14', SHA2('admin123', 256), 'admin14@hostel.com'),
('admin15', SHA2('admin123', 256), 'admin15@hostel.com'),
('admin16', SHA2('admin123', 256), 'admin16@hostel.com'),
('admin17', SHA2('admin123', 256), 'admin17@hostel.com'),
('admin18', SHA2('admin123', 256), 'admin18@hostel.com'),
('admin19', SHA2('admin123', 256), 'admin19@hostel.com'),
('admin20', SHA2('admin123', 256), 'admin20@hostel.com');

-- Insert Hostels
INSERT INTO hostels (name, gender, rooms, available_rooms, price_per_semester, description, image_path) VALUES
('Matola', 'Female', 120, 119, 2500.00, 'Comfortable female hostel with modern facilities.', NULL),
('Kinjekitile', 'Female', 110, 109, 2400.00, 'Well-equipped female accommodation close to campus.', NULL),
('Maria Nyerere', 'Female', 150, 149, 2600.00, 'Spacious female hostel with excellent amenities.', NULL),
('Sophia', 'Female', 100, 99, 2300.00, 'Cozy female residence hall with quiet study areas.', NULL),
('Vikenge', 'Female', 105, 104, 2350.00, 'Modern female hostel facility with fast Wi-Fi.', NULL),
('Umoja', 'Female', 95, 94, 2450.00, 'Friendly female hostel with cafeteria included.', NULL),
('Serena', 'Female', 130, 129, 2550.00, 'Premium female hostel with spacious lounges.', NULL),
('Kilimanjaro', 'Female', 140, 140, 2700.00, 'Top-tier female hostel with recreation space.', NULL),
('Imara', 'Female', 80, 79, 2250.00, 'Affordable female hostel with supportive staff.', NULL),
('Amani', 'Female', 90, 89, 2380.00, 'Secure female hostel near the main library.', NULL),
('Kimweri', 'Male', 130, 130, 2500.00, 'Comfortable male hostel with modern facilities.', NULL),
('Mirambo', 'Male', 120, 119, 2400.00, 'Well-equipped male accommodation near campus.', NULL),
('Mkwawa', 'Male', 140, 139, 2600.00, 'Spacious male hostel with excellent amenities.', NULL),
('Cabral', 'Male', 110, 109, 2300.00, 'Cozy male residence hall with quiet study spaces.', NULL),
('Buguruni', 'Male', 100, 99, 2350.00, 'Modern male hostel facility with gym access.', NULL),
('Mandela', 'Male', 85, 85, 2450.00, 'New male hostel with comfortable private rooms.', NULL),
('Uhuru', 'Male', 130, 129, 2550.00, 'Premium male accommodation with laundry service.', NULL),
('Shabaan', 'Male', 115, 114, 2420.00, 'Reliable male hostel with speedy internet.', NULL),
('Rafiki', 'Male', 95, 94, 2280.00, 'Friendly male hostel with study halls.', NULL),
('Simba', 'Male', 105, 105, 2490.00, 'Secure male hostel with great community spaces.', NULL);

-- Insert Student Users
INSERT INTO users (name, email, password, phone, registration_number, gender) VALUES
('Amina Abdallah', 'amina.abdallah@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000001', '14325001/T.1', 'Female'),
('John Smith', 'john.smith@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000002', '14325002/T.2', 'Male'),
('Fatima Omar', 'fatima.omar@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000003', '14325003/T.3', 'Female'),
('Daniel Peterson', 'daniel.peterson@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000004', '14325004/T.4', 'Male'),
('Halima Yusuf', 'halima.yusuf@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000005', '14325005/T.5', 'Female'),
('Michael Brown', 'michael.brown@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000006', '14325006/T.6', 'Male'),
('Neema Hassan', 'neema.hassan@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000007', '14325007/T.7', 'Female'),
('Peter Kirua', 'peter.kirua@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000008', '14325008/T.8', 'Male'),
('Leila Mwinyi', 'leila.mwinyi@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000009', '14325009/T.9', 'Female'),
('Paul Kim', 'paul.kim@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000010', '14325010/T.10', 'Male'),
('Susan Mbwana', 'susan.mbwana@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000011', '14325011/T.11', 'Female'),
('Eric Njoroge', 'eric.njoroge@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000012', '14325012/T.12', 'Male'),
('Aisha Mohamed', 'aisha.mohamed@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000013', '14325013/T.13', 'Female'),
('Brian Ochieng', 'brian.ochieng@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000014', '14325014/T.14', 'Male'),
('Zahara Juma', 'zahara.juma@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000015', '14325015/T.15', 'Female'),
('Kevin Mensah', 'kevin.mensah@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000016', '14325016/T.16', 'Male'),
('Rashida Ali', 'rashida.ali@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000017', '14325017/T.17', 'Female'),
('David Kato', 'david.kato@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000018', '14325018/T.18', 'Male'),
('Mariam Said', 'mariam.said@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000019', '14325019/T.19', 'Female'),
('Martin Zhang', 'martin.zhang@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+255766000020', '14325020/T.20', 'Male');

-- Insert Bookings
INSERT INTO bookings (user_id, hostel_id, booking_date, status, academic_year, semester, approved_date, approved_by, remarks) VALUES
(1, 1, '2025-01-10 10:20:00', 'Approved', '2024/2025', 1, '2025-01-12 09:00:00', 1, NULL),
(2, 2, '2025-01-11 11:15:00', 'Approved', '2024/2025', 1, '2025-01-13 10:30:00', 2, NULL),
(3, 3, '2025-02-05 14:40:00', 'Pending', '2024/2025', 2, NULL, NULL, NULL),
(4, 4, '2025-02-08 09:25:00', 'Pending', '2024/2025', 2, NULL, NULL, NULL),
(5, 5, '2025-03-02 13:50:00', 'Pending', '2025/2026', 1, NULL, NULL, NULL),
(6, 6, '2025-03-06 15:10:00', 'Approved', '2025/2026', 1, '2025-03-08 12:20:00', 3, NULL),
(7, 7, '2025-03-20 08:10:00', 'Pending', '2025/2026', 2, NULL, NULL, NULL),
(8, 8, '2025-04-01 09:15:00', 'Rejected', '2025/2026', 1, NULL, 4, 'Requested dates conflict with maintenance'),
(9, 9, '2025-04-12 16:00:00', 'Approved', '2025/2026', 1, '2025-04-14 10:45:00', 5, NULL),
(10, 10, '2025-04-18 10:05:00', 'Pending', '2025/2026', 2, NULL, NULL, NULL),
(11, 11, '2025-05-06 14:20:00', 'Rejected', '2025/2026', 1, NULL, 6, 'Booking rejected due to overdue fees'),
(12, 12, '2025-05-14 11:30:00', 'Pending', '2025/2026', 2, NULL, NULL, NULL),
(13, 13, '2025-05-21 12:45:00', 'Approved', '2025/2026', 2, '2025-05-23 11:10:00', 7, NULL),
(14, 14, '2025-06-01 08:30:00', 'Pending', '2025/2026', 2, NULL, NULL, NULL),
(15, 15, '2025-06-12 13:00:00', 'Approved', '2025/2026', 1, '2025-06-14 09:55:00', 8, NULL),
(16, 16, '2025-06-20 15:25:00', 'Rejected', '2025/2026', 1, NULL, 9, 'Room not available for requested semester'),
(17, 17, '2025-07-02 09:10:00', 'Pending', '2025/2026', 2, NULL, NULL, NULL),
(18, 18, '2025-07-08 17:40:00', 'Approved', '2025/2026', 2, '2025-07-10 14:05:00', 10, NULL),
(19, 19, '2025-07-15 10:55:00', 'Pending', '2025/2026', 1, NULL, NULL, NULL),
(20, 20, '2025-07-22 16:30:00', 'Rejected', '2025/2026', 2, NULL, 11, 'Student did not meet eligibility requirements');
