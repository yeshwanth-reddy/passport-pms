<?php include("includes/db_connection.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>User Registration</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="needs-validation" novalidate>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
                <div class="invalid-feedback">
                    Please provide a first name.
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
                <div class="invalid-feedback">
                    Please provide a last name.
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">
                Please provide a valid email address.
            </div>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">
                Please choose a username.
            </div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">
                Please choose a password.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Form validation script -->
<script>
// JavaScript for form validation using Bootstrap
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>

</body>
</html>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // It's recommended to use hashing for passwords

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
    $emailResult = $conn->query($checkEmailQuery);
    if ($emailResult->num_rows > 0) {
        echo "<script>alert('Email already exists. Please choose a different email.');</script>";
    } else {
        // Check if username already exists
        $checkUsernameQuery = "SELECT * FROM users WHERE username='$username'";
        $usernameResult = $conn->query($checkUsernameQuery);
        if ($usernameResult->num_rows > 0) {
            echo "<script>alert('Username already exists. Please choose a different username.');</script>";
        } else {
            // Insert user data into database
            $sql = "INSERT INTO users (firstName, lastName, email, username, password) 
                    VALUES ('$firstName', '$lastName', '$email', '$username', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Registration successful'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
            }
        }
    }
}
?>
