# 🏆 Achievement Management System (IMS)

## 📖 Project Overview

The **Achievement Management System (IMS)** is a web-based application developed using **Laravel** that streamlines the process of managing student achievements within an educational institution.

The system provides separate portals for **Students** and **Faculty**. Students can submit their academic and extracurricular achievements, while faculty members can review, approve, reject, and manage submissions efficiently. The application also provides real-time status tracking and notifications, making the achievement verification process transparent and organized.

---

# ✨ Features

## 🎓 Student Portal

- Student Registration & Login
- Secure Authentication
- Submit New Achievement
- Upload Supporting Documents
- View Achievement History
- Track Approval Status
- Dashboard with Statistics
- Notifications & Recent Updates

## 👨‍🏫 Faculty Portal

- Faculty Registration & Login
- Faculty Dashboard
- View Student Achievement Submissions
- Approve or Reject Achievements
- Add Remarks/Feedback
- Manage Student Records
- Dashboard Statistics

## ⚙️ General Features

- Role-Based Authentication (Student & Faculty)
- Responsive Modern UI
- Secure Password Hashing
- MySQL Database Integration
- Clean Dashboard Interface
- Laravel MVC Architecture

---

# 🛠️ Technologies Used

## Backend

- Laravel 13
- PHP 8.4

## Frontend

- Blade Templates
- HTML5
- CSS3
- Bootstrap 5
- JavaScript

## Database

- MySQL

## Version Control

- Git
- GitHub

---

# ⚙️ Project Setup Steps

## 1. Clone the Repository

```bash
git clone https://github.com/Justinsunil2007/Laravel_Project_-IMS-.git
cd Laravel_Project_IMS
```

---

## 2. Install Dependencies

```bash
composer install
npm install
```

---

## 3. Configure Environment

Copy the environment file.

```bash
cp .env.example .env
```

Generate the application key.

```bash
php artisan key:generate
```

---

## 4. Database Setup

Create a MySQL database.

Example:

```text
Database Name: ims_db
```

Update your `.env` file with your MySQL credentials.

Example:

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ims_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run the migrations.

```bash
php artisan migrate
```

Seed the default demo accounts.

```bash
php artisan db:seed
```

---

## 5. Build Frontend Assets

```bash
npm run build
```

---

## 6. Start the Development Server

```bash
php artisan serve
```

Open your browser and visit:

```text
http://127.0.0.1:8000
```

---

# 👤 Default Test Credentials

## 🎓 Student Account

**Email**

```text
student@samp.edu
```

**Password**

```text
Password123
```

---

## 👨‍🏫 Faculty Account

**Email**

```text
faculty@samp.edu
```

**Password**

```text
Password123
```

---

# 📂 Project Structure

```text
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
```

---

# 📸 Application Workflow

1. Open the application.
2. Choose **Student Portal** or **Faculty Portal**.
3. Sign in using the default credentials or create a new account.
4. Students can submit achievements.
5. Faculty can review submissions and approve or reject them.
6. Students receive status updates through the dashboard.

---

# 🎥 Demo Video

**Google Drive / YouTube Link**

> https://drive.google.com/file/d/1Ir82LOBer3bk6WuKvMQRbwhcwezH59_H/view?usp=drive_link


```

---



# 🚀 Future Enhancements

- Email Notifications
- Admin Dashboard
- Export Reports (PDF/Excel)
- Advanced Search & Filters
- Analytics Dashboard
- Mobile Responsive Improvements
- Cloud File Storage

---

# 👨‍💻 Developer

**Justin Sunil**


Achievement Management System (IMS)

Built using **Laravel**, **PHP**, and **MySQL**.

---

## 📄 License

This project was developed as part of a college  project for academic purposes.