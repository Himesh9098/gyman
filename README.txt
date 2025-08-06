Gym Member Management System - Setup Instructions
===============================================

1. Requirements:
   - XAMPP or WAMP (PHP 7+ recommended)
   - Web browser

2. Installation:
   - Copy the entire 'Stoic' folder to your XAMPP/WAMP 'htdocs' (XAMPP) or 'www' (WAMP) directory.
   - Example: C:\xampp\htdocs\Stoic

3. File Permissions:
   - Ensure the web server can write to 'members.json' and the 'assets/img/' directory for photo uploads.
   - On Windows, this is usually not an issue. On Linux, use: chmod 777 Stoic/members.json Stoic/assets/img/

4. Running the App:
   - Start Apache from XAMPP/WAMP control panel.
   - Open your browser and go to: http://localhost/Stoic/login.php
   - Login with:
     Username: admin
     Password: 1234

5. Features:
   - Dashboard: View stats and expiring members
   - Member Management: Add, edit, delete members, assign workout/diet plans
   - Progress Tracker: Track monthly weight, BMI, attendance
   - Digital ID Card: View and upload member photo
   - Language Toggle: Switch between English and Hindi

6. Notes:
   - All data is stored in 'members.json'.
   - Photos are stored in 'assets/img/'.
   - For any issues, check file permissions and PHP error logs.

7. Customization:
   - To change login credentials, edit 'login.php'.
   - To add more languages, update 'includes/functions.php'.

Enjoy managing your gym members digitally!