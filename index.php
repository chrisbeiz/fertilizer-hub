<?php
session_start();

include 'connect.php'; // Include the connection class file

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create an instance of the connection class
    $connection = new MyConnection();

    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare the SQL statement
        $stmt = $connection->get_connection()->prepare("SELECT * FROM `admin` WHERE username=:username and status=1");
        
        // Bind parameter
        $stmt->bindParam(':username', $username);
        
        // Execute the query
        $stmt->execute();

        // Check if a matching record is found
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $result['password'];
            
            // Verify the password using password_verify
            if (password_verify($password, $hashed_password)) {
                $_SESSION['loggedin'] = true;
                $_SESSION['name'] = $result['name'];
                $_SESSION['username'] = $result['username'];
                header('Location: adminDashboard.php'); // Redirect to dashboard upon successful login
                exit;
            } else {
                // Authentication failed
                $_SESSION['loggedin'] = false;
                $_SESSION['login_error'] = "Invalid username or password.";
            }
        } else {
            // Authentication failed
            $_SESSION['loggedin'] = false;
            $_SESSION['login_error'] = "Invalid username or password.";
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
    <title>Welcome to Hub - Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Optional: Add custom CSS to style the login form */
        .login-form {
            margin-top: 100px; /* Adjust margin top as needed */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-form">
                <div class="card">
                    <div class="card-header">
                        Welcome to Hub - Login
                    </div>
                    <div class="card-body">
                        <?php
                        // Display login error message if set
                        if (isset($_SESSION['login_error'])) {
                            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['login_error'] . '</div>';
                            unset($_SESSION['login_error']); // Clear the login error message
                        }
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
