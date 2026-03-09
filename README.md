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

## 📋 Database Setup
1. Open **phpMyAdmin**.
2. Create a new database named `task_db`.
3. Run the following SQL command to create the table:

```sql
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(30) NOT NULL,
    status ENUM('Pending', 'Completed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
