<div align="center">

# 🎓 MTIS

### Management Technology & Information Systems

### 🚀 Smart Digital Course Registration System

<p>
  <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/MySQL-8-orange?style=for-the-badge&logo=mysql">
  <img src="https://img.shields.io/badge/Bootstrap-5-purple?style=for-the-badge&logo=bootstrap">
  <img src="https://img.shields.io/badge/License-Educational-success?style=for-the-badge">
</p>

### 📄 From Paper Registration ➜ Digital Workflow

Electronic Course Registration System with:

✔ Digital Signatures
✔ QR Verification
✔ PDF Generation
✔ AES-256 Encryption
✔ Multi-Role Management

---

## 📸 System Preview

<img src="screenshots/login.jpeg" width="850">

> Complete screenshots are available below.

---

## ✨ Features

| Feature                      | Description                                  |
| ---------------------------- | -------------------------------------------- |
| 🔐 Multi Role Authentication | Student • Academic Advisor • Student Affairs |
| ✍ Electronic Signature       | Canvas Based Digital Signature               |
| 🔒 AES-256 Encryption        | Secure Signature Storage                     |
| 📄 Automatic PDF             | Official Registration Form                   |
| 📱 QR Verification           | Scan & Verify Anywhere                       |
| 📊 Live Tracking             | Real-time Registration Status                |
| 📥 Receipt Validation        | Prevent Duplicate Receipts                   |
| ⚡ Auto Fill                  | Academic Advisor Detection                   |
| 📚 Dynamic Templates         | Department & Level Subjects                  |

---

# 📷 Screenshots

### 🔐 Login

<img src="screenshots/login.jpeg">

---

### 💳 Payment Information

<img src="screenshots/payment-data.jpeg">

---

### 📚 Course Registration

<img src="screenshots/courses-registration.jpeg">

---

### ✍ Student Signature

<img src="screenshots/doctor-approval-signature.jpeg">

---

### 📊 Tracking System

<img src="screenshots/tracking-system.jpeg">

---

### 👨‍🏫 Advisor Dashboard

<img src="screenshots/advisor-dashboard.jpeg">

---

### 🏢 Student Affairs Dashboard

<img src="screenshots/admin-dashboard.jpeg">

---

### 📋 Request Review

<img src="screenshots/review-request.jpeg">

---

### 📄 Generated PDF

<img src="screenshots/final-pdf.jpeg">

---

### 📱 QR Verification

<img src="screenshots/qr-verification.png">

---

# ⚙ Workflow

```text
Student Payment
      │
      ▼
Upload Receipt
      │
      ▼
Course Registration
      │
      ▼
Student Signature
      │
      ▼
Advisor Review
      │
      ▼
Advisor Signature
      │
      ▼
Student Affairs Review
      │
      ▼
Approval
      │
      ▼
Generate PDF + QR
      │
      ▼
Student Downloads Official Document
```

---

# 🛠 Technology Stack

## Backend

* Laravel 12
* PHP 8.2
* MySQL 8
* Laravel Eloquent
* mPDF
* endroid/qr-code

---

## Frontend

* Blade
* Bootstrap RTL
* Bootstrap Icons
* JavaScript
* Canvas API
* Fetch API

---

## Security

* AES-256 Encryption
* bcrypt Password Hashing
* CSRF Protection
* Role Middleware
* Unique Receipt Validation
* Secure Storage
* QR Hash Verification

---

# 🚀 Installation

```bash
git clone https://github.com/YOUR_USERNAME/MTIS.git

cd MTIS

composer install

npm install

copy .env.example .env

php artisan key:generate

php artisan migrate

php artisan db:seed --class=AdminSeeder

php artisan storage:link

php artisan serve
```

---

# 🔑 Demo Accounts

| Role             | Email                                             | Password     |
| ---------------- | ------------------------------------------------- | ------------ |
| Student Affairs  | [shuoun@mtis.edu.eg](mailto:shuoun@mtis.edu.eg)   | Shuoun@2024  |
| Academic Advisor | [ahmed@mtis.edu.eg](mailto:ahmed@mtis.edu.eg)     | Doctor@2024  |
| Student          | [student@mtis.edu.eg](mailto:student@mtis.edu.eg) | Student@2024 |

---

# 📂 Project Structure

```text
app/
 ├── Http
 ├── Models
 ├── Middleware
 └── Providers

database/
 ├── migrations
 └── seeders

resources/
 ├── views
 ├── pdf
 └── layouts

routes/
 └── web.php
```

---

# 🔒 Security

✔ AES-256 Encryption

✔ Laravel CSRF Protection

✔ Role Based Authorization

✔ Secure PDF Generation

✔ QR Verification

✔ Duplicate Receipt Prevention

✔ Encrypted Digital Signatures

---

# 🤝 Contributing

```bash
Fork

Create Branch

Commit

Push

Open Pull Request
```

---

# 📜 License

Educational Use Only

---

<div align="center">

### Faculty of Management Technology & Information Systems

⭐ If you like this project, don't forget to Star the repository.

</div>
