# Simple Task Management System

A lightweight, PHP-based web application developed to manage daily tasks. This project was completed as a 3-hour skill test to demonstrate core CRUD (Create, Read, Update, Delete) functionality and database integration.

## 🚀 Features
* **Add Tasks:** Simple input with a 100-character limit.
* **Task Overview:** A clean table displaying all current tasks.
* **Status Tracking:** One-click toggle to mark tasks as 'Completed'.
* **Delete Functionality:** Remove tasks permanently from the database.
* **Dashboard Stats:** Color-coded badges showing Total, Pending, and Completed counts.

## 🛠️ Tech Stack
* **Language:** PHP (Procedural)
* **Database:** MySQL (via XAMPP)
* **Frontend:** HTML5 & CSS3
* **Server:** Apache

## 🔐 User-Based Access Control

This system includes **user authentication and access control**. Only registered users who log in can access the dashboard. Each task is linked to a specific **user ID**, ensuring that users can **only view, add, update, or delete their own tasks**. Tasks from other users are not visible or accessible, providing **basic data privacy and multi-user support** within the application.


## 📋 Database Setup
1. Open **phpMyAdmin**.
2. Create a new database named `task_db`.
3. Run the following SQL command to create the table:

## 👤 Users Table

The `users` table stores login credentials and basic account information for each registered user.

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Table for tasks linked specifically to users
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_name VARCHAR(30) NOT NULL,
    status ENUM('Pending', 'Completed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
