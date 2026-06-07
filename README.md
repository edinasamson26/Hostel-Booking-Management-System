
<div align="center">


| | |
| :--- | :--- |
| **STUDENT NAME** | **EDINA JULIUS SAMSON** |
| **REGISTRATION NUMBER** | **14325081/1.24** |
| **PROGRAMME** | **BSc. EDU-(MICT)** |

<br>
<hr>
<br>

#  HOSTEL BOOKING MANAGEMENT SYSTEM


### *A comprehensive web application for managing hostel bookings, reservations, and guest information*


</div>

##  Table of Contents

- [Features](#features)
- [Technology Stack](#technology)
- [Usage](#usage)
- [Configuration](#configuration)
- [Database](#database)

<br>

##  Features

- **Booking Management**: Create, view, and manage hostel bookings
- **Room Management**: Track available rooms and their availability
- **Guest Management**: Maintain guest profiles and information
- **Reservation System**: Efficient reservation handling and scheduling
- **User Authentication**: Secure login and user management
- **Dashboard**: Overview of bookings and hostel statistics
- **Responsive Design**: Mobile-friendly interface
- **Reporting**: Generate booking and occupancy reports

<br>

##  Technology

- **Backend**: PHP (75%)
  - Server-side logic and business operations
  - Database management and queries
  - API endpoints for frontend communication

- **Frontend**: 
  - JavaScript (14.8%) - Interactive features and dynamic updates
  - CSS (10.2%) - Styling and responsive design

- **Database**: MySQL/MariaDB
- **Web Server**: Apache or Nginx
- **Additional**: HTML5

<br>

##  Usage

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

<br>

##  Configuration

Key configuration files (typically in `config/` directory):

- **Database Connection**: Update hostname, username, password, and database name
- **Application Settings**: Configure hostel name, contact info, and business rules
- **Email Settings**: Set up SMTP for booking confirmations
- **Payment Gateway**: Configure payment processing if integrated

<br>

##  Database

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
