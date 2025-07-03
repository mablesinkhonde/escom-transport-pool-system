# ESCOM Transport Pool Management System 🚗

A simple web-based system to digitize transport request processing, vehicle tracking, driver assignment, fuel usage logging, and maintenance history for ESCOM (Electricity Supply Corporation of Malawi).

## 🔧 Technologies Used
- PHP
- MySQL (via WAMP)
- HTML, CSS, JavaScript (no framework)
- Role-based access (Admin, Staff, Fleet Controller, Manager, Driver)

## 🔑 Features
- Online transport request form (multi-stage approval)
- Vehicle and driver assignment
- Maintenance and fuel logging
- Role-based dashboards
- Return vehicle tracking
- Central landing page

## 🎨 Theme Colors
- Green ✅ (main background)
- Yellow 🌟 (action emphasis)
- White ⚪ (text and content areas)

## 📂 Folder Structure (Sample)
```
escom_transport/
├── login.php
├── register.php
├── admin_dashboard.php
├── manager_dashboard.php
├── style.css
├── fuel_log.php
└── ...
```

## 🚀 How to Run Locally
1. Clone the repo:
   ```
   git clone https://github.com/your-username/escom-transport-system.git
   ```
2. Move the folder to `C:\wamp64\www\`.
3. Open WAMP and start MySQL and Apache.
4. Import the SQL database using phpMyAdmin.
5. Open in browser: `http://localhost/escom_transport/`