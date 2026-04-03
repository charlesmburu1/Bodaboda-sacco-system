CREATE DATABASE IF NOT EXISTS bodaboda_sacco;
USE bodaboda_sacco;

-- ================= USERS (for authentication) =================
CREATE TABLE users( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    membership_id VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active','inactive','suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

-- ================= PASSWORD RESETS =================

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL
);

-- ================= SAVINGS =================
CREATE TABLE savings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ================= TRANSACTIONS =================

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('deposit','withdraw') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    reference VARCHAR(50) UNIQUE,
    balance_after DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ================= LOANS =================
CREATE TABLE loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL CHECK (amount > 0),
    repayment_period INT NOT NULL,
    interest_rate DECIMAL(5,2) DEFAULT 10.00,
    total_payable DECIMAL(10,2) DEFAULT 0,
    monthly_payment DECIMAL(10,2) DEFAULT 0,
    remaining_balance DECIMAL(10,2) DEFAULT 0,
    status ENUM('pending','approved','rejected','paid') DEFAULT 'pending',
    date_applied TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

-- ================= REPAYMENTS =================

CREATE TABLE repayments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loan_id INT NOT NULL,
    user_id INT NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================= ADMINS =================

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);



Project Structure:
|-- assets/
|   |-- css/
      |-- savings.css
      |-- admin.css
      |-- dashboard.css
      |-- loans.css
      |-- style.css
      |-- auth.css
|   |-- js/
      |-- main.js
      |-- script.css
|   |-- images/
|-- database/
|   |-- schema.sql  
|-- index.php
|-- dashboard.php
|-- register.php
|-- login.php
|-- logout.php
|-- deposit.php
|-- withdraw.php
|-- apply_loan.php
|-- repay_loan.php
|-- payment_history.php
|-- transactions.php
|-- loans.php
|-- contact.php
|-- forgot_password.php
|-- reset_password.php
|--process_contact.php
|-- savings.php
|-- admin/
    |-- admin_loans.php
    |-- dashboard.php
    |-- 
|-- includes/
    |-- constants.php
    |-- header.php
    |-- footer.php
    |-- auth.php
    |-- config.php
    |-- session.php
    |-- dashboard_data.php

