<?php
session_start();

include 'connect.php'; // Include the connection class file

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit;
}

// Check if the form has been submitted (for approving or canceling)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which button was clicked
    if (isset($_POST['approve'])) {
        // Handle the approve action
        $id = $_POST['approve'];
        approveFarmer($id);
    } elseif (isset($_POST['cancel'])) {
        // Handle the cancel action
        $id = $_POST['cancel'];
        cancelFarmer($id);
    }
}

// Function to approve a farmer by changing their status to 'Approved'
function approveFarmer($id) {
    $connection = new MyConnection();

    try {
        $stmt = $connection->get_connection()->prepare("UPDATE `farmers` SET status='Approved' WHERE famID=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to cancel a farmer by deleting their record
function cancelFarmer($id) {
    $connection = new MyConnection();

    try {
        $stmt = $connection->get_connection()->prepare("UPDATE `farmers` SET status='Canceled' WHERE famID=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch the list of farmers with status 'Appending'
$connection = new MyConnection();
$farmers = $connection->select("SELECT * FROM `farmers` WHERE status='Appending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Farmers - Hub</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
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
        <h2><center>Welcome, <?php echo $_SESSION['name']; ?>! admin dashboard.</center></h2>
        <h2 class="mb-4">Manage Request of Farmers</h2>
        <?php if (empty($farmers)): ?>
            <div class="alert alert-info" role="alert">
                No farmers awaiting approval.
            </div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Telephone</th>
                        <th>KG</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($farmers as $farmer): ?>
                        <tr>
                            <td><?php echo $farmer['famID']; ?></td>
                            <td><?php echo $farmer['fullname']; ?></td>
                            <td><?php echo $farmer['telephone']; ?></td>
                            <td><?php echo $farmer['KG']; ?></td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $farmer['famID']; ?>">
                                    <button type="submit" name="approve" value="<?php echo $farmer['famID']; ?>" class="btn btn-success">Approve</button>
                                    <button type="submit" name="cancel" value="<?php echo $farmer['famID']; ?>" class="btn btn-danger">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
