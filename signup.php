<?php
session_start();

include 'connect.php'; // Include the connection class file

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create an instance of the connection class
    $connection = new MyConnection();

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    try {
        // Prepare the SQL statement
        $stmt = $connection->get_connection()->prepare("SELECT * FROM `admin` WHERE username=:username");

        // Bind parameter
        $stmt->bindParam(':username', $username);

        // Execute the query
        $stmt->execute();

        // Check if a matching record is found
        if ($stmt->rowCount() > 0) {
            // Username already exists, show error message
            $_SESSION['signup_error'] = "Username already exists. Please choose a different username.";
        } else {
            // Insert user data into the database
            $insert_stmt = $connection->get_connection()->prepare("INSERT INTO `admin` (name, email, username, password) VALUES (:name, :email, :username, :password)");

            // Bind parameters
            $insert_stmt->bindParam(':name', $name);
            $insert_stmt->bindParam(':email', $email);
            $insert_stmt->bindParam(':username', $username);
            $insert_stmt->bindParam(':password', $hashed_password);

            // Execute the insert query
            $insert_stmt->execute();

            // Redirect to login page after successful signup
            header('Location: index.php');
            exit;
        }
    } catch (PDOException $e) {
        // Error handling
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Hub</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Optional: Add custom CSS to style the signup form */
        .signup-form {
            margin-top: 100px; /* Adjust margin top as needed */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 signup-form">
                <div class="card">
                    <div class="card-header">
                        Sign Up - Hub
                    </div>
                    <div class="card-body">
                        <?php
                        // Display signup error message if set
                        if (isset($_SESSION['signup_error'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['signup_error'] . '</div>';
                            unset($_SESSION['signup_error']); // Clear the signup error message
                        }
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
