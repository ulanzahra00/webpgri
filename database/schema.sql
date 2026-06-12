-- Schema database website resmi PGRI Kotamobagu.
CREATE DATABASE IF NOT EXISTS pgri_kotamobagu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pgri_kotamobagu;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin','admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    excerpt TEXT NULL,
    content LONGTEXT NOT NULL,
    image VARCHAR(255) NULL,
    video VARCHAR(255) NULL,
    status ENUM('draft','published') DEFAULT 'published',
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    level VARCHAR(50) NOT NULL,
    district VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    headmaster VARCHAR(120) NULL,
    phone VARCHAR(40) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE galleries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT NULL,
    event_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE organization_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    position VARCHAR(120) NOT NULL,
    photo VARCHAR(255) NULL,
    bio TEXT NULL,
    sort_order INT DEFAULT 0
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE member_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(160) NOT NULL,
    identity_number VARCHAR(80) NULL,
    email VARCHAR(160) NOT NULL,
    phone VARCHAR(40) NOT NULL,
    school_name VARCHAR(180) NOT NULL,
    job_title VARCHAR(120) NULL,
    district VARCHAR(120) NULL,
    address TEXT NULL,
    reason TEXT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE financial_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    period_month TINYINT NOT NULL,
    period_year YEAR NOT NULL,
    deposit_date DATE NULL,
    category VARCHAR(120) NOT NULL,
    income DECIMAL(15,2) DEFAULT 0,
    expense DECIMAL(15,2) DEFAULT 0,
    balance DECIMAL(15,2) GENERATED ALWAYS AS (income - expense) STORED,
    description TEXT NULL,
    document VARCHAR(255) NULL,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL
);
