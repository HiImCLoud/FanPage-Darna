<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// ADD media
if (isset($_POST['add_media'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $actress = mysqli_real_escape_string($conn, $_POST['actress']);

    $insert = mysqli_query($conn, "INSERT INTO media (title, year, actress) VALUES ('$title', '$year', '$actress')");

    $_SESSION['message'] = $insert ? "Media added successfully!" : "Failed to add media.";
    $_SESSION['message_type'] = $insert ? "success" : "error";
    header("Location: media.php");
    exit();
}

// UPDATE media
if (isset($_POST['update_media'])) {
    $id = $_POST['media_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $actress = mysqli_real_escape_string($conn, $_POST['actress']);

    $update = mysqli_query($conn, "UPDATE media SET title='$title', year='$year', actress='$actress' WHERE media_id=$id");

    $_SESSION['message'] = $update ? "Media updated successfully!" : "Failed to update media.";
    $_SESSION['message_type'] = $update ? "success" : "error";
    header("Location: media.php");
    exit();
}

// DELETE media
if (isset($_POST['delete_media'])) {
    $id = $_POST['media_id'];
    $delete = mysqli_query($conn, "DELETE FROM media WHERE media_id=$id");

    $_SESSION['message'] = $delete ? "Media deleted successfully!" : "Failed to delete media.";
    $_SESSION['message_type'] = $delete ? "success" : "error";
    header("Location: media.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Darna | Media</title>
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
            <h4 class="mb-3">🎬 Media Section</h4>

            <!-- Add Media -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">Add New Media</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Title" required>
                            </div>
                            <div class="col-md-4">
                                <label>Year</label>
                                <input type="text" name="year" class="form-control" placeholder="Enter Year" required>
                            </div>
                            <div class=" col-md-4">
                                <label>Actress</label>
                                <input type="text" name="actress" class="form-control" placeholder="Enter Actress Name" required>
                            </div>
                        </div>
                        <button type="submit" name="add_media" class="btn btn-success mt-3">
                            <i class="bi bi-plus-circle"></i> Add Media
                        </button>
                    </form>
                </div>
            </div>

            <!-- Media Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Actress</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $media = mysqli_query($conn, "SELECT * FROM media ORDER BY created_at DESC");
                        $count = 1;
                        while ($row = mysqli_fetch_assoc($media)):
                        ?>
                            <tr>
                                <td><?= $count++ ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['year']) ?></td>
                                <td><?= htmlspecialchars($row['actress']) ?></td>
                                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['media_id'] ?>">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    <form id="deleteForm<?= $row['media_id'] ?>" method="POST" class="d-inline">
                                        <input type="hidden" name="media_id" value="<?= $row['media_id'] ?>">
                                        <input type="hidden" name="delete_media" value="1">
                                    </form>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $row['media_id'] ?>)">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?= $row['media_id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST">
                                        <input type="hidden" name="media_id" value="<?= $row['media_id'] ?>">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title">Edit Media</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Title</label>
                                                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Year</label>
                                                    <input type="text" name="year" class="form-control" value="<?= htmlspecialchars($row['year']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Actress</label>
                                                    <input type="text" name="actress" class="form-control" value="<?= htmlspecialchars($row['actress']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="update_media" class="btn btn-success">
                                                    <i class="bi bi-save"></i> Save Changes
                                                </button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        function confirmDelete(id) {
            alertify.confirm("Confirm Deletion", "Are you sure you want to delete this media?",
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