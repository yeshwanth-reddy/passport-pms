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

// Define variables
$application_id = $userID = $amount = $payment_date = '';

// Handle payment process when Pay Now button is clicked
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['application_id'])) {
    $application_id = $_GET['application_id'];
    $userID = $_SESSION['userID'];

    // Fetch application information
    $sql_application = "SELECT * FROM applications WHERE id = '$application_id' AND userID = '$userID'";
    $result_application = $conn->query($sql_application);

    if ($result_application->num_rows == 1) {
        $row_application = $result_application->fetch_assoc();
        $amount = 2500.0; // Default amount
    } else {
        echo "<script>alert('Application not found');</script>";
        exit(); // Exit script if application not found
    }
} else if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['application_id'])) {
    header("Location: applications.php");
    exit();
}

// Process payment and update application status when Pay Now button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_now'])) {
    // Get form data
    $application_id = $_POST['application_id'];
    $userID = $_SESSION['userID'];

    // Insert payment record into payments table
    $payment_date = date('Y-m-d H:i:s'); // Current date and time
    $sql_payment = "INSERT INTO payments (applicationId, userID, amount, paymentDate, status) 
                    VALUES ('$application_id', '$userID', '2500.0', '$payment_date', 'paid')";
    if ($conn->query($sql_payment) === TRUE) {
        // Update application status in the database
        $sql_update_application = "UPDATE applications SET status = 'paid' WHERE id = '$application_id' AND userID = '$userID'";
        if ($conn->query($sql_update_application) === TRUE) {
            echo "<script>alert('Payment successful'); window.location.href = 'applications.php';</script>";
        } else {
            echo "<script>alert('Error updating application status: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error inserting payment record: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Payment</h2>
    <div class="row">
        <div class="col">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                <p><strong>Application ID:</strong> <?php echo $application_id; ?></p>
                <p><strong>User ID:</strong> <?php echo $userID; ?></p>
                <p><strong>Amount:</strong> <?php echo $amount; ?> rupees</p>
                <button type="submit" class="btn btn-success" name="pay_now">Pay Now</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
