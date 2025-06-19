<?php
session_start();
include 'config/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Handle update
if (isset($_POST['update_origin'])) {
    $id = $_POST['origin_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $query = "UPDATE origin SET content='$content' WHERE origin_id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Origin updated successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update origin.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: origin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Darna | Origin Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../pic/logo.png" type="image/x-icon">
</head>

<body>
    <?php include 'config/admin_layout.php'; ?>

    <div class="main-content">
        <h4 class="mb-4">📖 Origin Section</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Content</th>
                        <th>Image</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $origin = mysqli_query($conn, "SELECT * FROM origin ORDER BY created_at DESC");
                    $count = 1;
                    while ($row = mysqli_fetch_assoc($origin)):
                        $imgPath = '../' . $row['image_path'];
                    ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td style="max-width: 400px;"><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                            <td><img src="<?= $imgPath ?>" width="100" height="150" style="object-fit:cover;"></td>
                            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['origin_id'] ?>">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $row['origin_id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form method="POST">
                                    <input type="hidden" name="origin_id" value="<?= $row['origin_id'] ?>">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title">Edit Origin</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Content</label>
                                                <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($row['content']) ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Image Preview</label><br>
                                                <img src="<?= $imgPath ?>" width="150" height="200" style="object-fit:cover;">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update_origin" class="btn btn-success">
                                                <i class="bi bi-save"></i> Save Changes
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>

    <?php if (isset($_SESSION['message'])): ?>
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
        <script>
            alertify.<?= $_SESSION['message_type'] ?>("<?= $_SESSION['message'] ?>");
        </script>
    <?php unset($_SESSION['message'], $_SESSION['message_type']);
    endif; ?>
</body>

</html>