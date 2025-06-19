<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Add power
if (isset($_POST['add_power'])) {
    $icon = mysqli_real_escape_string($conn, $_POST['icon_class']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);

    $insert = mysqli_query($conn, "INSERT INTO powers (icon_class, title) VALUES ('$icon', '$title')");

    $_SESSION['message'] = $insert ? "Power added successfully!" : "Failed to add power.";
    $_SESSION['message_type'] = $insert ? "success" : "error";
    header("Location: powers.php");
    exit();
}

// Update power
if (isset($_POST['update_power'])) {
    $id = $_POST['power_id'];
    $icon = mysqli_real_escape_string($conn, $_POST['icon_class']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);

    $update = mysqli_query($conn, "UPDATE powers SET icon_class='$icon', title='$title' WHERE power_id=$id");

    $_SESSION['message'] = $update ? "Power updated successfully!" : "Failed to update power.";
    $_SESSION['message_type'] = $update ? "success" : "error";
    header("Location: powers.php");
    exit();
}

// Delete power
if (isset($_POST['delete_power'])) {
    $id = $_POST['power_id'];
    $delete = mysqli_query($conn, "DELETE FROM powers WHERE power_id=$id");

    $_SESSION['message'] = $delete ? "Power deleted successfully!" : "Failed to delete power.";
    $_SESSION['message_type'] = $delete ? "success" : "error";
    header("Location: powers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Darna | Powers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="shortcut icon" href="../pic/logo.png" type="image/x-icon">
</head>

<body>
    <?php include 'config/admin_layout.php'; ?>

    <div class="main-content">
        <div class="container-fluid">
            <h4 class="mb-3">⚡ Powers Section</h4>

            <!-- Add Power -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">Add New Power</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label>Icon Class (e.g., <code>bi bi-infinity</code>)</label>
                                <input type="text" name="icon_class" class="form-control" placeholder="Enter Bootstrap Icon Class" required>
                            </div>
                            <div class="col-md-6">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Title" required>
                            </div>
                        </div>

                        <button type="submit" name="add_power" class="btn btn-success mt-3">
                            <i class="bi bi-plus-circle"></i> Add Power
                        </button>
                    </form>
                </div>
            </div>

            <!-- Powers Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $powers = mysqli_query($conn, "SELECT * FROM powers ORDER BY created_at DESC");
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($powers)):
                        ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><i class="<?= $row['icon_class'] ?> fs-4"></i></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $row['power_id'] ?>">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    <!-- Hidden Delete Form -->
                                    <form id="deleteForm<?= $row['power_id'] ?>" method="POST" class="d-inline">
                                        <input type="hidden" name="power_id" value="<?= $row['power_id'] ?>">
                                        <input type="hidden" name="delete_power" value="1">
                                    </form>

                                    <!-- Delete Button -->
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['power_id'] ?>)">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['power_id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST">
                                        <input type="hidden" name="power_id" value="<?= $row['power_id'] ?>">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title">Edit Power</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Icon Class</label>
                                                    <input type="text" name="icon_class" class="form-control"
                                                        value="<?= htmlspecialchars($row['icon_class']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Title</label>
                                                    <input type="text" name="title" class="form-control"
                                                        value="<?= htmlspecialchars($row['title']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="update_power" class="btn btn-success">
                                                    <i class="bi bi-save"></i> Save
                                                </button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <script>
        function confirmDelete(id) {
            alertify.confirm("Confirm Deletion", "Are you sure you want to delete this power?",
                function() {
                    document.getElementById("deleteForm" + id).submit();
                },
                function() {
                    alertify.error("Cancelled");
                });
        }

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

    <?php if (isset($_SESSION['message'])): ?>
        <script>
            alertify.<?= $_SESSION['message_type'] ?>("<?= $_SESSION['message'] ?>");
        </script>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

</body>

</html>