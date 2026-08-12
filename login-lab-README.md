# Login Lab – Test Web Application

## Overview

This project documents the creation of a small **PHP login lab application** used to generate realistic web authentication events for security monitoring and detection testing (e.g. with Wazuh).

The application includes:

* A simple **login form** (username / password)
* A **test account** for controlled login attempts
* **Validation logic** for submitted credentials
* **JSON-based logging** of every login attempt (success and failure)

This app is intentionally minimal — its only purpose is to produce clean, structured login events for downstream log collection and analysis.

---

## Deployment

### 1. Login Form

The application entry point was created at:

```text
/var/www/html/index.php
```

A basic HTML login form was implemented with `username` and `password` fields, submitted via POST to the same script.

```php
<form method="POST" action="index.php">
  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>
```

### 2. Test Account

A single hardcoded test account was configured for controlled testing purposes.

```php
$valid_user = "admin";
$valid_pass = "password123";
```

> ⚠️ For lab/testing purposes only — credentials are intentionally simple and not meant for production use.

### 3. Credential Validation

On form submission, the submitted username and password are compared against the test account.

```php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $valid_user && $password === $valid_pass) {
        $result = "success";
    } else {
        $result = "failed";
    }
}
```

### 4. Login Result Display

The outcome of the login attempt is displayed back to the user in the form.

```php
<?php if (isset($result)): ?>
  <p>
    <?= $result === "success" ? "✅ Login successful" : "❌ Invalid username or password" ?>
  </p>
<?php endif; ?>
```

---

## Logging

### 5. Login Log File

All login attempts are recorded to a dedicated log file:

```text
/var/log/login-lab.log
```

```bash
sudo touch /var/log/login-lab.log
```

### 6. Permissions

Since the login attempts are written by the web server process, permissions were set for the `www-data` user:

```bash
sudo chown www-data:www-data /var/log/login-lab.log
sudo chmod 640 /var/log/login-lab.log
```

### 7. JSON Log Format

Each login attempt is written as a single JSON line, making the log easy to parse for downstream tools.

```php
$log_entry = [
    "timestamp" => date("c"),
    "src_ip"    => $_SERVER['REMOTE_ADDR'],
    "username"  => $username,
    "result"    => $result
];

file_put_contents(
    "/var/log/login-lab.log",
    json_encode($log_entry) . PHP_EOL,
    FILE_APPEND | LOCK_EX
);
```

Example log entry:

```json
{"timestamp":"2026-08-12T10:15:32+00:00","src_ip":"192.168.1.50","username":"admin","result":"failed"}
```

### 8. Log Verification

Logging was verified in real time using:

```bash
tail -f /var/log/login-lab.log
```

Both successful and failed login attempts were confirmed to be written correctly to the log file.

---

## Current Result

The login lab application is fully functional and ready to be used as a log source for security monitoring.

| Component          | Status       |
| ------------------- | ------------ |
| Login form           | ✅ Working    |
| Credential validation | ✅ Working    |
| Result display        | ✅ Working    |
| JSON logging           | ✅ Working    |
| `www-data` permissions | ✅ Configured |

---

## Status

🚧 **Work in Progress**

Current milestone:

> Login lab web application built, logging login attempts in JSON format to `/var/log/login-lab.log`. Next step: forward this log to a Wazuh Agent for brute-force detection testing.
