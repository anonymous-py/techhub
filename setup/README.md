# Tech-Hub User Setup

This directory contains scripts to add sample users to the Tech-Hub database for testing login functionality.

## 🚀 Quick Setup Options

### Option 1: Web Interface (Recommended)
1. Open your browser
2. Navigate to: `http://localhost/Tech-hub/setup/add_users.php`
3. Click the "Add Sample Users" button
4. Done! You can now login

### Option 2: Command Line
1. Open terminal/command prompt
2. Navigate to the setup directory
3. Run: `php insert_users.php`
4. Done! Users are inserted

## 📋 Test Credentials

After running the setup, use these credentials to test login:

### Admin Account
- **Email:** `admin@techhub.com`
- **Password:** `admin123`
- **Access:** Full admin dashboard

### Customer Accounts
- **Email:** `john.doe@example.com`
- **Password:** `customer123`

- **Email:** `jane.smith@example.com`  
- **Password:** `customer123`

- **Email:** `michael.johnson@example.com`
- **Password:** `customer123`

## 🎯 What Gets Added

The scripts will add:
- **1 Admin user** - For backend management
- **3 Customer users** - For testing regular user functionality

## 🔐 Security Notes

- All passwords are hashed using PHP's `password_hash()` function
- The default passwords are only for development/testing
- **Change these passwords in production!**

## 🐛 Troubleshooting

### "Database connection failed"
1. Make sure XAMPP is running
2. Check that MySQL service is started
3. Verify database name in `includes/db_connection.php`

### "Table 'users' doesn't exist"
1. Run the database schema first: `config/database.sql`
2. Import it into your MySQL database

### "Email already exists"
- Users with the same email already exist in the database
- This is normal - the script skips existing users

## 📝 Database Schema

Make sure you have run the database setup script:
- File: `config/database.sql`
- This creates all necessary tables including the `users` table

## 🔄 Re-running the Setup

The scripts are safe to run multiple times:
- They check if users already exist
- Skipping existing users (no duplicates)
- Only adding new users

## 🌐 Access Points

After adding users, you can test login at:
- Login Page: `http://localhost/Tech-hub/login.php`
- Admin Dashboard: `http://localhost/Tech-hub/admin/dashboard.php`
- Home Page: `http://localhost/Tech-hub/index.php`

---

**Happy Testing! 🎉**

