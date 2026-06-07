# Hostel Booking Management System

A comprehensive web application for managing hostel bookings, reservations, and guest information. This system streamlines the entire process of hostel operations from room management to booking administration.

## 📋 Table of Contents

- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Database](#database)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

- **Booking Management**: Create, view, and manage hostel bookings
- **Room Management**: Track available rooms and their availability
- **Guest Management**: Maintain guest profiles and information
- **Reservation System**: Efficient reservation handling and scheduling
- **User Authentication**: Secure login and user management
- **Dashboard**: Overview of bookings and hostel statistics
- **Responsive Design**: Mobile-friendly interface
- **Reporting**: Generate booking and occupancy reports

## 🛠️ Technology Stack

- **Backend**: PHP (75%)
  - Server-side logic and business operations
  - Database management and queries
  - API endpoints for frontend communication

- **Frontend**: 
  - JavaScript (14.8%) - Interactive features and dynamic updates
  - CSS (10.2%) - Styling and responsive design

- **Database**: MySQL/MariaDB (recommended)
- **Web Server**: Apache or Nginx
- **Additional**: HTML5

## 📁 Project Structure

```
Hostel-Booking-Management-System/
├── index.php              # Main entry point
├── config/               # Configuration files
├── classes/              # PHP classes and business logic
├── pages/                # Application pages
├── css/                  # Stylesheets
├── js/                   # JavaScript files
├── images/               # Image assets
├── includes/             # Reusable includes
└── database/             # Database files/schema
```

## 🚀 Installation

### Prerequisites

- PHP 7.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional, for dependency management)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/edinasamson26/Hostel-Booking-Management-System.git
   cd Hostel-Booking-Management-System
   ```

2. **Set up the database**
   - Create a new MySQL database
   - Import the database schema (typically found in `database/` folder)
   ```bash
   mysql -u root -p database_name < database/schema.sql
   ```

3. **Configure the application**
   - Copy the configuration template (if exists)
   - Update database connection details in config files
   - Set appropriate file permissions

4. **Start the web server**
   ```bash
   # For built-in PHP server (development only)
   php -S localhost:8000
   ```
   Then navigate to `http://localhost:8000` in your browser

5. **Access the application**
   - Open your browser and go to the application URL
   - Log in with default credentials (see Configuration section)

## 💻 Usage

### For Administrators
1. Log in with admin credentials
2. Manage hostel information and settings
3. Add and manage rooms
4. View and process bookings
5. Manage staff accounts

### For Staff
1. Log in to access the dashboard
2. View current bookings
3. Check-in/Check-out guests
4. Manage room assignments

### For Guests
1. Browse available rooms
2. Create bookings
3. View booking history
4. Manage reservations

## ⚙️ Configuration

Key configuration files (typically in `config/` directory):

- **Database Connection**: Update hostname, username, password, and database name
- **Application Settings**: Configure hostel name, contact info, and business rules
- **Email Settings**: Set up SMTP for booking confirmations
- **Payment Gateway**: Configure payment processing if integrated

Example configuration:
```php
<?php
// config/database.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'hostel_booking');
?>
```

## 🗄️ Database

The system uses MySQL/MariaDB with tables for:

- `users` - User accounts and authentication
- `guests` - Guest information
- `rooms` - Room details and configurations
- `bookings` - Booking records
- `reservations` - Reservation data
- `payments` - Payment transaction logs

Run database migrations/setup script to initialize the database:
```bash
php database/setup.php
```

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For issues, questions, or suggestions:
- Open an issue on GitHub
- Contact the development team
- Check existing documentation

---

**Last Updated**: June 2026

**Status**: Active Development
