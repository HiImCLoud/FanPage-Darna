<?php
include 'config/db.php';

$query = "SELECT * FROM carousel ORDER BY carousel_id ASC";
$result = mysqli_query($conn, $query);
$slides = [];
while ($row = mysqli_fetch_assoc($result)) {
    $slides[] = $row;
}

$query = "SELECT * FROM origin ORDER BY origin_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$origin = mysqli_fetch_assoc($result);


$query = "SELECT * FROM powers";
$result = mysqli_query($conn, $query);


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darna | Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="shortcut icon" href="pic/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.css"
        integrity="sha512-DKdRaC0QGJ/kjx0U0TtJNCamKnN4l+wsMdION3GG0WVK6hIoJ1UPHRHeXNiGsXdrmq19JJxgIubb/Z7Og2qJww=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: black;">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="pic/logo.png" alt="Logo" width="30" height="30"> Darna
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="background-color: white; border-radius: 5px;"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#origin">Origin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#powers">Powers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#media">Media</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Gallery</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">

        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php foreach ($slides as $index => $slide): ?>
                <button type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="<?= $index ?>"
                    class="<?= $index === 0 ? 'active' : '' ?>"
                    aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                    aria-label="Slide <?= $index + 1 ?>"></button>
            <?php endforeach; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="carousel-caption">
                        <h1 class="carousel-title"><?= htmlspecialchars($slide['title']) ?></h1>
                        <p class="carousel-description"><?= htmlspecialchars($slide['description']) ?></p>
                    </div>
                    <img src="<?= htmlspecialchars($slide['image_path']) ?>" class="d-block w-100" alt="cover">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


    <section id="origin" class="py-5">
        <div class="container">
            <h2 class="display-4 text-center">Origin</h2>
            <p class="lead text-center">
                <svg width="100%" height="2" xmlns="http://www.w3.org/2000/svg">
                    <line x1="25%" y1="1" x2="75%" y2="1" stroke="maroon" stroke-width="2" />
                </svg>
            </p>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow-lg">
                        <div class="row no-gutters">
                            <div class="col-md-6 p-4">
                                <div class="card-body">
                                    <?= $origin ? $origin['content'] : '<p>No origin content found.</p>' ?>
                                </div>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <img src="<?= $origin ? htmlspecialchars($origin['image_path']) : 'pic/default.png' ?>"
                                    alt="Darna" class="img-fluid m-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <hr>

    <section id="powers" class="py-5">
        <div class="container">
            <h2 class="display-4 text-center">Powers</h2>
            <p class="lead text-center">
                <svg width="100%" height="2" xmlns="http://www.w3.org/2000/svg">
                    <line x1="25%" y1="1" x2="75%" y2="1" stroke="maroon" stroke-width="2" />
                </svg>
            </p>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <div class="col">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="<?= htmlspecialchars($row['icon_class']) ?>" style="font-size: 60px; color: maroon;"></i>
                                <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <hr>
    <section id="media" class="py-5">
        <div class="container">
            <h2 class="display-4 text-center">Media</h2>
            <p class="lead text-center">
                <svg width="100%" height="2" xmlns="http://www.w3.org/2000/svg">
                    <line x1="25%" y1="1" x2="75%" y2="1" stroke="maroon" stroke-width="2" />
                </svg>
            </p>
            <div class="container mt-5">
                <table id="myTable" class="display">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Actress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM media ORDER BY year ASC";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['actress']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <hr>
    <section id="gallery" class="py-5">
        <div class="container">
            <h2 class="display-4 text-center">Gallery</h2>
            <p class="lead text-center">
                <svg width="100%" height="2" xmlns="http://www.w3.org/2000/svg">
                    <line x1="25%" y1="1" x2="75%" y2="1" stroke="maroon" stroke-width="2" />
                </svg>
            </p>

            <div class="row">
                <?php
                include 'config/db.php';

                $query = "SELECT * FROM gallery ORDER BY created_at DESC";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    $title = htmlspecialchars($row['title']);
                    $imagePath = htmlspecialchars($row['image_path']);
                    echo '
                <div class="col-md-4">
                    <a href="' . $imagePath . '" data-lightbox="gallery" data-title="' . $title . '">
                        <div class="gallery-item">
                            <img src="' . $imagePath . '" class="img-fluid mb-5" alt="' . $title . '">
                        </div>
                    </a>
                </div>';
                }
                ?>
            </div>
        </div>
    </section>




    <footer class="footer text-black text-center py-5">
        <div class="container">
            <h3 class="mb-4">Darna</h3>
            <p>&copy; 2025 All rights reserved</p>
        </div>
    </footer>

    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"
        integrity="sha512-KbRFbjA5bwNan6DvPl1ODUolvTTZ/vckssnFhka5cG80JVa5zSlRPCr055xSgU/q6oMIGhZWLhcbgIC0fyw3RQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="app.js"></script>
</body>

</html>