================================================================
     PROJECT TITLE: Secure Multi-Layer Authentication System
================================================================

Author: Gunjan Verma
Date: 2026

----------------------------------------
1. PROJECT OVERVIEW
----------------------------------------
Short introduction of the project:

Question: What problem does it solve?
Answer: Can a vulnerable authentication system be progressively hardened into a secure, production-ready login architecture by systematically addressing common web security vulnerabilities such as SQL Injection, password exposure, CSRF attacks, session fixation, and brute force attacks?

Question: Why is it important?
Answer: Authentication vulnerabilities are among the most exploited attack vectors in web applications. Weak login systems lead to credential theft, account takeover, data breaches, and system compromise. Understanding both exploitation and mitigation is critical for secure software development and cybersecurity roles.

Question: What is the main objective?
Answer: To design and implement a 5-layer secure authentication system inside a Dockerized DVWA environment, demonstrating both offensive exploitation techniques and defensive security engineering aligned with OWASP Top 10 principles.

----------------------------------------
2. ENVIRONMENT DETAILS
----------------------------------------
Environment Description:
- Platform: Dockerized DVWA (Damn Vulnerable Web Application)
- Language: PHP
- Database: MySQL
- Server: Apache
- OS: Windows (Docker Desktop)

Database Table Used:
- users_auth
- Columns:
  - id
  - username
  - password
  - failed_attempts (added in Level 5)
  - lock_until (added in Level 5)

----------------------------------------
3. PROJECT STRUCTURE
----------------------------------------

secure-auth/
│── level1-vulnerable/      # Plain-text password + SQL injection vulnerable login
│── level2-hashing/         # Password hashing using bcrypt
│── level3-prepared/        # SQL Injection prevention using prepared statements
│── level4-session-csrf/    # Session hardening + CSRF protection
│── level5-rate-limit/      # Brute-force protection and account lockout
│── screenshots/            # Demonstration screenshots for each level
│── README.md               # Project documentation

----------------------------------------
4. METHODOLOGY
----------------------------------------

This project demonstrates progressive security hardening through five structured levels.

1. Level 1 – Vulnerable Authentication
- Plain-text password storage
- Dynamic SQL query concatenation
- SQL Injection bypass demonstrated
- Example payload used:
  ' OR 1=1 #

2. Level 2 – Password Hashing
- Implemented password_hash() using bcrypt
- Verified with password_verify()
- Database no longer stores plain-text passwords
- Protects against database leaks

3. Level 3 – SQL Injection Prevention
- Replaced dynamic queries with prepared statements
- Used parameter binding (bind_param)
- Eliminated authentication bypass via SQL injection

4. Level 4 – Session Hardening + CSRF Protection
- session_regenerate_id(true) to prevent session fixation
- HttpOnly cookie enforcement
- Disabled session ID in URLs
- CSRF token generation using random_bytes()
- Token validation using hash_equals()
- Protection against cross-site request forgery

5. Level 5 – Brute Force Protection
- Failed login attempt tracking
- Account lock after 5 failed attempts
- 5-minute temporary lockout
- Automatic reset after successful login
- Protection against credential stuffing and automated attacks

----------------------------------------
5. SECURITY CONCEPTS DEMONSTRATED
----------------------------------------

- SQL Injection (Exploitation & Mitigation)
- Authentication Bypass
- Password Hashing (bcrypt)
- Prepared Statements
- CSRF Protection
- Session Fixation Prevention
- HttpOnly Cookie Enforcement
- Brute Force Mitigation
- Account Lockout Mechanism
- Defense-in-Depth Architecture
- OWASP Top 10 Mitigation Techniques

----------------------------------------
6. RESULTS
----------------------------------------

The final system demonstrates:

- SQL Injection blocked completely
- Plain-text password exposure eliminated
- CSRF attack simulation successfully prevented
- Session fixation mitigated
- Brute force attacks blocked after 5 attempts
- Account lock functionality working as expected

Example Final Output (Level 5):
- After 5 failed attempts:
  "Too many attempts. Account locked for 5 minutes."
- Immediate correct password attempt:
  "Account locked. Try again later."

----------------------------------------
7. REQUIREMENTS
----------------------------------------

Software Required:
- Docker Desktop
- Git
- Web Browser

Technologies Used:
- PHP
- MySQL
- Apache
- Docker
- DVWA

----------------------------------------
8. HOW TO RUN THE PROJECT
----------------------------------------

1. Pull DVWA image:
   docker run -d -p 8080:80 --name dvwa vulnerables/web-dvwa

2. Access in browser:
   http://localhost:8080

3. Place secure-auth folder inside:
   /var/www/html/

4. Access levels:
   http://localhost:8080/secure-auth/level1-vulnerable/login.php
   http://localhost:8080/secure-auth/level2-hashing/login.php
   http://localhost:8080/secure-auth/level3-prepared/login.php
   http://localhost:8080/secure-auth/level4-session-csrf/login.php
   http://localhost:8080/secure-auth/level5-rate-limit/login.php

----------------------------------------
9. DISCLAIMER
----------------------------------------

This project was built strictly for educational purposes in a controlled Docker lab environment. All vulnerabilities were intentionally implemented and tested for learning secure coding practices.

----------------------------------------
10. LEARNING OUTCOME
----------------------------------------

This project demonstrates:

- Understanding of real-world authentication vulnerabilities
- Ability to exploit and mitigate security flaws
- Progressive secure coding implementation
- Defense-in-depth architecture design
- Practical application of OWASP security principles
- Enterprise-grade authentication hardening
