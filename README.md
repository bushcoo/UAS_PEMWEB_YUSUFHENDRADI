# 📝 Modern Todo List Application

A sleek and responsive todo list application built with PHP, MySQL, and Bootstrap 5. Manage your daily tasks with ease through an intuitive interface.

## ✨ Features

- 📱 Fully responsive design
- ✅ Create, Read, Update, Delete (CRUD) tasks
- 🎨 Clean and modern UI with Bootstrap 5
- 🔒 Secure data handling with prepared statements
- 🔔 Interactive notifications
- 💾 Session-based messaging system

## 🚀 Quick Setup

### Prerequisites

- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

### Database Configuration

```sql
CREATE DATABASE todo_app;
USE todo_app;

CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    status ENUM('Belum Selesai', 'Selesai') DEFAULT 'Belum Selesai'
);
```

### Application Setup

1. Configure database connection in `index.php`:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "todo_app";
```

2. Start your web server and access the application

## 🛠️ Tech Stack

- PHP (Backend)
- MySQL (Database)
- Bootstrap 5.3.3 (Frontend)
- Font Awesome 6.7.2 (Icons)
- Custom CSS (Styling)


## 📝 Made BY

Yusuf Hendradi

## 👤 Author

Made with ❤️ in Indonesia

---

**Note:** This project is for educational purposes and demonstrates basic CRUD operations with PHP and MySQL.
