<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = hash('sha256', $_POST['password']);

    $query = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = 'Incorrect password!';
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Username not found!';
        $_SESSION['message_type'] = 'error';
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Darna | Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="shortcut icon" href="../pic/logo.png" type="image/x-icon">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .body-overlay {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            border-top: 5px solid maroon;
            z-index: 2;
        }

        .btn-maroon {
            background-color: maroon;
            color: white;
        }

        .btn-maroon:hover {
            background-color: #800000;
        }

        .darna-header {
            background: maroon;
            color: gold;
            font-weight: bold;
            text-align: center;
        }

        .toggle-pass {
            cursor: pointer;
            position: absolute;
            top: 38px;
            right: 15px;
            color: maroon;
        }
    </style>
</head>

<body>

    <div class="body-overlay">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow">
                        <div class="card-header text-center darna-header">
                            <img src="../pic/logo.png" alt="Darna Logo" style="width: 60px; height: 60px;" class="mb-2">
                        </div>

                        <div class="card-body position-relative">
                            <form action="" method="POST">
                                <div class="form-group mb-3">
                                    <label for="username" class="text-dark">Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                                </div>
                                <div class="form-group mb-3 position-relative">
                                    <label for="password" class="text-dark">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                    <span class="toggle-pass" onclick="togglePassword()">👁️</span>
                                </div>
                                <button type="submit" name="login" class="btn btn-maroon w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertify JS -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <?php if (isset($_SESSION['message'])): ?>
        <script>
            alertify.<?= $_SESSION['message_type'] ?>('<?= $_SESSION['message'] ?>');
        </script>
    <?php unset($_SESSION['message'], $_SESSION['message_type']);
    endif; ?>

    <script>
        function togglePassword() {
            const passField = document.getElementById('passwordInput');
            if (passField.type === "password") {
                passField.type = "text";
            } else {
                passField.type = "password";
            }
        }
    </script>

</body>

</html>