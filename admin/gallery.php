<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Image upload handler
function handleUpload($fileInputName)
{
    $targetDir = "../pic/";
    $filename = basename($_FILES[$fileInputName]["name"]);
    $targetFile = $targetDir . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if actual image
    $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
    if ($check === false) $uploadOk = 0;

    // Only allow certain types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) $uploadOk = 0;

    if ($uploadOk && move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
        return "pic/" . $filename; // relative path used in src
    }
    return false;
}

// ADD
if (isset($_POST['add_gallery'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $imagePath = handleUpload("image");

    if ($imagePath) {
        $insert = mysqli_query($conn, "INSERT INTO gallery (title, image_path) VALUES ('$title', '$imagePath')");
        $_SESSION['message'] = $insert ? "Image added!" : "Failed to add.";
        $_SESSION['message_type'] = $insert ? "success" : "error";
    } else {
        $_SESSION['message'] = "Image upload failed.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: gallery.php");
    exit();
}

// UPDATE
if (isset($_POST['update_gallery'])) {
    $id = $_POST['gallery_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $newPath = handleUpload("image");

    if ($newPath) {
        $query = "UPDATE gallery SET title='$title', image_path='$newPath' WHERE gallery_id=$id";
    } else {
        $query = "UPDATE gallery SET title='$title' WHERE gallery_id=$id";
    }

    $update = mysqli_query($conn, $query);
    $_SESSION['message'] = $update ? "Gallery updated." : "Failed to update.";
    $_SESSION['message_type'] = $update ? "success" : "error";
    header("Location: gallery.php");
    exit();
}

// DELETE
if (isset($_POST['delete_gallery'])) {
    $id = $_POST['gallery_id'];
    $delete = mysqli_query($conn, "DELETE FROM gallery WHERE gallery_id=$id");
    $_SESSION['message'] = $delete ? "Deleted successfully." : "Delete failed.";
    $_SESSION['message_type'] = $delete ? "success" : "error";
    header("Location: gallery.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Darna | Gallery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="shortcut icon" href="../pic/logo.png" type="image/x-icon">
</head>

<body>
    <?php include 'config/admin_layout.php'; ?>
    <div class="main-content p-4">
        <h4 class="mb-3">🖼️ Gallery Management</h4>

        <!-- Add Modal Trigger -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle"></i> Add New
            </button>
        </div>


        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5>Add Gallery Image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Title" required>
                            </div>
                            <div class="mb-3">
                                <label>Upload Image</label>
                                <input type="file" name="image" accept="image/*" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button name="add_gallery" class="btn btn-success"><i class="bi bi-save"></i> Save</button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Gallery Table -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Preview</th>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $gallery = mysqli_query($conn, "SELECT * FROM gallery ORDER BY created_at DESC");
                    $count = 1;
                    while ($row = mysqli_fetch_assoc($gallery)):
                    ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><img src="../<?= $row['image_path'] ?>" width="100" height="60" style="object-fit:cover;"></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['gallery_id'] ?>">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <form method="POST" id="deleteForm<?= $row['gallery_id'] ?>" class="d-inline">
                                    <input type="hidden" name="gallery_id" value="<?= $row['gallery_id'] ?>">
                                    <input type="hidden" name="delete_gallery">
                                    <button type="button" onclick="confirmDelete(<?= $row['gallery_id'] ?>)" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $row['gallery_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="gallery_id" value="<?= $row['gallery_id'] ?>">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark text-white">
                                            <h5>Edit Gallery</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Title</label>
                                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Upload New Image (optional)</label>
                                                <input type="file" name="image" class="form-control" accept="image/*">
                                            </div>
                                            <div class="mb-3">
                                                <label>Current Image:</label><br>
                                                <img src="../<?= $row['image_path'] ?>" width="100" height="60" style="object-fit:cover;">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button name="update_gallery" class="btn btn-success"><i class="bi bi-save"></i> Save</button>
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        function confirmDelete(id) {
            alertify.confirm("Confirm", "Delete this image?", function() {
                document.getElementById("deleteForm" + id).submit();
            }, function() {
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
    <?php unset($_SESSION['message'], $_SESSION['message_type']);
    endif; ?>

</body>

</html>