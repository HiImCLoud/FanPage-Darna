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
    <title>Darna | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Make sure it's responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../pic/logo.png" type="image/x-icon">
    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
            /* Prevent horizontal scrolling */
        }

        .card-maroon {
            background-color: maroon;
            color: gold;
        }

        .card-maroon .bi {
            font-size: 32px;
        }

        .card h5 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .card h3 {
            font-size: 26px;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 500px;
        }

        canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'config/admin_layout.php'; ?>

    <div class="main-content">
        <h4 class="mb-4">📊 Dashboard</h4>
        <div class="container-fluid mt-4">
            <div class="row g-3">
                <!-- Total Media -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-maroon text-center shadow p-3">
                        <div><i class="bi bi-film"></i></div>
                        <h5>Total Media</h5>
                        <h3><?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM media"))['total']; ?></h3>
                    </div>
                </div>

                <!-- Total Powers -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-maroon text-center shadow p-3">
                        <div><i class="bi bi-lightning-charge"></i></div>
                        <h5>Total Powers</h5>
                        <h3><?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM powers"))['total']; ?></h3>
                    </div>
                </div>

                <!-- Origin Check -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-maroon text-center shadow p-3">
                        <div><i class="bi bi-book"></i></div>
                        <h5>Origin Entry</h5>
                        <h3><?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM origin"))['total']; ?></h3>
                    </div>
                </div>

                <!-- Admin Count -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="card card-maroon text-center shadow p-3">
                        <div><i class="bi bi-person-gear"></i></div>
                        <h5>Total Admins</h5>
                        <h3><?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM admin"))['total']; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            Overview Chart
                        </div>
                        <div class="card-body chart-container">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Media', 'Powers', 'Origin', 'Admins'],
                datasets: [{
                    label: 'Total Entries',
                    data: [
                        <?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM media"))['total'] ?>,
                        <?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM powers"))['total'] ?>,
                        <?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM origin"))['total'] ?>,
                        <?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM admin"))['total'] ?>
                    ],
                    backgroundColor: 'maroon'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>
</body>

</html>