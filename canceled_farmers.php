<?php
session_start();

include 'connect.php'; // Include the connection class file

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch the list of canceled farmers
$connection = new MyConnection();
$canceledFarmers = $connection->select("SELECT * FROM `farmers` WHERE status='Canceled'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canceled Farmers - Hub</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Hub Admin</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admindashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="approved_farmers.php">Approved Farmers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="canceled_farmers.php">Canceled Farmers</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2><center>Welcome, <?php echo $_SESSION['name']; ?>! Canceled Farmers</center></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Telephone</th>
                    <th>KG</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if($canceledFarmers): ?>
                    <?php foreach ($canceledFarmers as $farmer): ?>
                        <tr>
                            <td><?php echo $farmer['famID']; ?></td>
                            <td><?php echo $farmer['fullname']; ?></td>
                            <td><?php echo $farmer['telephone']; ?></td>
                            <td><?php echo $farmer['KG']; ?></td>
                            <td><?php echo $farmer['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No available Canceled</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
