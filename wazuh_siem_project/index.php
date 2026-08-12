<?php

$message = "";
$messageClass = "";

$valid_username = "admin";
$valid_password = "test123";

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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        .login-box {
            width: 320px;
            padding: 30px;

            background-color: #ffffff;
            border: 1px solid #bdbdbd;
            border-radius: 8px;

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .login-box h2 {
            margin-top: 0;
            text-align: center;
            color: #333333;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;

            border: 1px solid #bdbdbd;
            border-radius: 4px;

            box-sizing: border-box;
            font-size: 14px;
        }

        .login-box button {
            width: 100%;
            padding: 10px;

            border: none;
            border-radius: 4px;

            background-color: #666666;
            color: white;

            font-size: 14px;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color: #555555;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .error {
            color: #c62828;
        }

        .success {
            color: #2e7d32;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>Login</h2>

    <form method="POST">

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

    <?php if ($message !== ""): ?>

        <div class="message <?php echo $messageClass; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>