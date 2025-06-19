<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Darna | Admin Accounts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../pic/logo.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
    <?php include 'config/admin_layout.php'; ?>

    <div class="main-content p-4">
        <h4 class="mb-3">👤 Admin Accounts</h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $admins = mysqli_query($conn, "SELECT * FROM admin ORDER BY id ASC");
                    $count = 1;
                    while ($row = mysqli_fetch_assoc($admins)):
                    ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout() {
            alertify.confirm("Logout", "Are you sure you want to logout?",
                function() {
                    window.location.href = "logout.php";
                },
                function() {
                    alertify.error('Logout cancelled');
                });
        }
    </script>
</body>

</html>