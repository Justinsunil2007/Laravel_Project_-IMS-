🏆Student Achievement Management System (IMS)
📖 Project Overview
The Student Achievement Management System (IMS) is a web-based application developed using Laravel that streamlines the process of managing student achievements within an educational institution.
The system provides separate portals for Students and Faculty. Students can submit their academic and extracurricular achievements, while faculty members can review, approve, reject, and manage submissions efficiently. The application also provides real-time status tracking and notifications, making the achievement verification process transparent and organized.


✨ Features
Student Portal
Student Registration & Login
Secure Authentication
Submit New Achievement
Upload Supporting Documents
View Achievement History
Track Approval Status
Dashboard with Statistics
Notifications & Recent Updates
Faculty Portal
Faculty Registration & Login
Faculty Dashboard
View Student Achievement Submissions
Approve or Reject Achievements
Add Remarks/Feedback
Manage Student Records
Dashboard Statistics
General Features
Role-Based Authentication (Student & Faculty)
Responsive Modern UI
Secure Password Hashing
MySQL Database Integration
Clean Dashboard Interface
Laravel MVC Architecture

🛠️ Technologies Used
Backend
Laravel 13
PHP 8.4
Frontend
Blade Templates
HTML5
CSS3
Bootstrap 5
JavaScript
Database
MySQL
Version Control
Git
GitHub

⚙️ Project Setup Steps
1. Clone the Repository
git clone https://github.com/Justinsunil2007/Laravel_Project_-IMS-.git
cd Laravel_Project_IMS

2. Install Dependencies
composer install
npm install

3. Configure Environment
Copy the environment file.
cp .env.example .env
Generate the application key.
php artisan key:generate

4. Database Setup
Create a MySQL database.
Example:
Database Name: ims_db
Update your .env file with your MySQL credentials.
Example:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ims_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
Run the migrations.
php artisan migrate
Seed the default demo accounts.
php artisan db:seed


5. Build Frontend Assets
npm run build

6. Start the Development Server
php artisan serve
Open your browser and visit:
http://127.0.0.1:8000

👤 Default Test Credentials
Student Account
Email
student@samp.edu
Password
Password123

Faculty Account
Email
faculty@samp.edu
Password
Password123

📂 Project Structure
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/


📸 Application Workflow
Open the application.
Choose Student Portal or Faculty Portal.
Sign in using the default credentials or create a new account.
Students can submit achievements.
Faculty can review submissions and approve or reject them.
Students receive status updates through the dashboard.

🎥 Demo Video
Demo Video Link:
https://drive.google.com/file/d/1Ir82LOBer3bk6WuKvMQRbwhcwezH59_H/view?usp=drive_link


👨‍💻 Developer
Justin Sunil
IMS Project
Student Achievement Management System (IMS)
Built using Laravel & MySQL.