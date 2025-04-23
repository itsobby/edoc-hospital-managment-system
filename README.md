
# 🏥 Hospital Management System

A web-based Hospital Management System (HMS) built using **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**. The system streamlines hospital operations, offering modules for patient registration, doctor management, appointment scheduling, symptom checking, and analytics.

---

## 🚀 Features

### 👤 Patient Module
- User login and registration
- View all doctors and specialties
- Schedule appointments
- Check symptoms using an interactive checker
- View personal booking history

### 👨‍⚕️ Doctor Module
- Login and session management
- View scheduled appointments
- View assigned patients
- Access and manage session details

### 🛠️ Admin Module
- Admin authentication
- Add, edit, and delete doctor profiles
- Schedule doctor sessions
- View all appointments and registered patients
- Access analytics on doctor and specialty demand

---

## 🧪 Symptom Checker
A smart module where patients can enter symptoms separated by commas. The system suggests potential diagnoses based on backend mappings (connected to `symptom-tracker-api.php`).

---

## 🖥️ Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 8.x
- **Database**: MySQL
- **Web Server**: Apache (via XAMPP/WAMP)
- **Other Tools**: Bootstrap, Fetch API, Session Handling

---

## 📂 Project Structure

```
edoc-hospital-management-system/
├── admin/                 # Admin panel pages
├── doctor/                # Doctor module
├── patient/               # Patient module (including symptom checker)
├── backend/               # Core PHP logic and database handling
├── db/                    # SQL scripts and database config
├── symptom-tracker-api.php  # API for symptom checker
├── login.php              # Login page
├── logout.php             # Logout logic
├── index.php              # Landing page
└── README.md              # This file
```

---

## ⚙️ Installation Guide

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/edoc-hospital-management-system.git
   ```

2. **Set up the database**:
   - Open **phpMyAdmin**
   - Create a database (e.g., `hospital_db`)
   - Import the provided SQL file (usually inside `/db/hospital_db.sql`)

3. **Configure your environment**:
   - Update database credentials in your PHP config file (usually `config.php` or inside connection functions)
   - Ensure `Apache` and `MySQL` are running via **XAMPP**

4. **Launch the system**:
   - Open browser and go to:
     ```
     http://localhost/edoc-hospital-management-system/
     ```

---

## ✅ Requirements

- PHP 7.4 or higher
- MySQL or MariaDB
- Apache server (XAMPP, WAMP, or LAMP)
- Modern browser (Chrome, Firefox, Edge)

---

## 📌 Notes

- Patient symptom input is matched against predefined values in the backend.
- Role-based access is enforced using PHP sessions.
- Make sure the `symptom-tracker-api.php` is reachable via the same host (`localhost` or domain).

---

## 👨‍💻 Author

- Pascal Obala – [GitHub](https://github.com/itsobby) · [LinkedIn](https://www.linkedin.com/in/pascal-obala)

---
