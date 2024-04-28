<?php
// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include("includes/db_connection.php");

// Fetch applications of the logged-in user
$userID = $_SESSION['userID'];
$sql = "SELECT * FROM applications WHERE userID='$userID'";
$result = $conn->query($sql);

// Handle log out
if (isset($_POST['logout'])) {
    // Remove userID from session
    unset($_SESSION['userID']);
    // Redirect to index.php
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col">
            <a href="application.php" class="btn btn-primary">Apply</a>
        </div>
        <div class="col text-right">
            <form method="post">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>

    <h2>Applications</h2>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Application Id</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Date of Birth</th>
                <th scope="col">Address</th>
                <th scope="col">Email</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $row['firstName']; ?></td>
                        <td><?php echo $row['lastName']; ?></td>
                        <td><?php echo $row['dob']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if ($row['status'] == 'submitted') { ?>
                                <a href="payment.php?application_id=<?php echo $row['id']; ?>" class="btn btn-success">Pay Now</a>
                            <?php } else { ?>
                                N/A
                                <?php } ?>
                        </td>
                    </tr>
            <?php }
            } else { ?>
                <tr>
                    <td colspan="9" class="text-center">No applications found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
