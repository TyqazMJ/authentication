# 📌 Laravel Risk Reporting System with Multi-Factor Authentication (MFA)

## 📖 Project Overview
This project is a **Risk Reporting System** built using **Laravel** with enhanced authentication and security features. The system implements **Multi-Factor Authentication (MFA)** using **email verification codes** to secure user access. Additional security layers include **rate limiting**, **password hashing with salting**, and **role-based access**.

---

## ✅ Features Implemented

### 1️⃣ **Multi-Factor Authentication (MFA) via Email**
- **MFA Flow**:
  - After a successful login attempt, the system **generates a 6-digit code** and **sends it to the user’s registered email**.
  - Users are **temporarily logged out** until they verify the code.
  - After verification, users are redirected to the dashboard (`/todo`).
- **Email Provider**: Mailtrap used for development/testing.
- **View Files**:
  - `resources/views/auth/login.blade.php`
  - `resources/views/auth/mfa-verify.blade.php`

---

### 2️⃣ **Password Encryption**
- **Algorithm Used**: `bcrypt` (Laravel default) or **Argon2id** (configurable in `config/hashing.php`).
- **Why**: Protects passwords by making them computationally difficult to reverse even if database is compromised.

---

### 3️⃣ **Password Salting**
- **Salt Implementation**:
  - **Column Added**: `salt` column added to the `users` table.
  - **How it Works**:
    - During registration, **random alphanumeric salt** (`Str::random(16)`) is generated for each user.
    - Passwords are stored as:  
      ```plaintext
      Hash::make(password + salt)
      ```
    - During login, the password is verified as:  
      ```plaintext
      Hash::check(input_password + salt, hashed_password)
      ```

---

### 4️⃣ **Rate Limiting for Login Attempts**
- **Library Used**: Laravel RateLimiter.
- **Rule Applied**:
  - Maximum **3 login attempts per minute per user/IP**.
  - Prevents brute-force attacks.

Example in `FortifyServiceProvider.php`:
```php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(3)->by($request->input('email') . $request->ip());
});
