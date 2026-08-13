# Login Lab – Test Web Application

## Overview

This project documents the creation of a small **PHP login lab application** used to generate realistic web authentication events for security monitoring and detection testing (e.g.[wazuh](wazuh_siem_project/README.md))


The application includes:

* A simple **login form** (username / password)
* A **test account** for controlled login attempts
* **Validation logic** for submitted credentials
* **JSON-based logging** of every login attempt (success and failure)

This app is intentionally minimal — its only purpose is to produce clean, structured login events for downstream log collection and analysis.

---

## Deployment

### 1. PHP Installation

Apache and PHP were installed on the web server so that `.php` files are executed correctly.

```bash
sudo apt update
sudo apt install -y apache2 php libapache2-mod-php
sudo systemctl restart apache2
```

Installation was verified by placing a `phpinfo()` test file and confirming it rendered correctly in the browser.

### 2. Login Form

The application entry point was created at:

```text
/var/www/html/index.php
```

A styled HTML login form was implemented with `username` and `password` fields, submitted via POST back to the same script.

```php
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
```

Basic CSS was added to center the form in the page and style it as a card (background, border, rounded corners, shadow).
<img width="469" height="310" alt="Screenshot 2026-08-12 111840" src="https://github.com/user-attachments/assets/53aacf85-b105-49a8-80e2-fef0f10b6d2d" />


### 3. Test Account

A single hardcoded test account was configured for controlled testing purposes.

```php
$valid_username = "admin";
$valid_password = "test123";
```

> ⚠️ For lab/testing purposes only — credentials are intentionally simple and not meant for production use.

### 4. Credential Validation

On form submission, the submitted username and password are compared against the test account, and the client IP is captured for logging.

```php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    $ip = $_SERVER["REMOTE_ADDR"] ?? "unknown";

    if ($username === $valid_username && $password === $valid_password) {
        $result = "success";
        $message = "Login successful";
        $messageClass = "success";
    } else {
        $result = "failure";
        $message = "Invalid username or password";
        $messageClass = "error";
    }
}
```

### 5. Login Result Display

The outcome of the login attempt is displayed back to the user in the form, styled green for success and red for failure.

```php
<?php if ($message !== ""): ?>
    <div class="message <?php echo $messageClass; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>
```

---

## Logging

### 6. Login Log File

All login attempts are recorded to a dedicated log file:

```text
/var/log/login-lab.log
```

```bash
sudo touch /var/log/login-lab.log
```

### 7. Permissions

Since the login attempts are written by the web server process, permissions were set for the `www-data` user:

```bash
sudo chown www-data:www-data /var/log/login-lab.log
sudo chmod 640 /var/log/login-lab.log
```

### 8. JSON Log Format

Each login attempt is written as a single JSON line, making the log easy to parse for downstream tools.

```php
$log = [
    "event" => "login",
    "result" => $result,
    "username" => $username,
    "src_ip" => $ip,
    "timestamp" => date("c")
];

file_put_contents(
    "/var/log/login-lab.log",
    json_encode($log) . PHP_EOL,
    FILE_APPEND
);
```

Example log entry:

```json
{"event":"login","result":"failure","username":"admin","src_ip":"192.168.1.50","timestamp":"2026-08-12T10:15:32+00:00"}
```

### 9. Log Verification

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
| PHP / Apache install  | ✅ Working    |
| Login form           | ✅ Working    |
| Credential validation | ✅ Working    |
| Result display        | ✅ Working    |
| JSON logging           | ✅ Working    |
| `www-data` permissions | ✅ Configured |
