<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['voter_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weDecideDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from the database
$voter_id = $_SESSION['voter_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get posted data
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the email already exists for another user
    $check_email_stmt = $conn->prepare("SELECT voter_id FROM voters WHERE email = ? AND voter_id != ?");
    $check_email_stmt->bind_param("si", $email, $voter_id);
    $check_email_stmt->execute();
    $check_email_stmt->store_result();

    if ($check_email_stmt->num_rows > 0) {
        echo "Email already exists! Please use a different email.";
    } else {
        // Update query for phone and email
        $stmt = $conn->prepare("UPDATE voters SET phone = ?, email = ? WHERE voter_id = ?");
        $stmt->bind_param("ssi", $phone, $email, $voter_id);
        if (!$stmt->execute()) {
            echo "Error updating phone and email: " . $stmt->error;
        }
        $stmt->close();

        // Update password if provided
        if (!empty($new_password)) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE voters SET password = ? WHERE voter_id = ?");
                $stmt->bind_param("si", $hashed_password, $voter_id);
                if (!$stmt->execute()) {
                    echo "Error updating password: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Passwords do not match!";
            }
        }

        // Redirect to home after updating
        header("Location: home.php");
        exit();
    }
    $check_email_stmt->close();
} else {
    // Fetch user data for displaying in the form
    $stmt = $conn->prepare("SELECT first_name, last_name, gender, dob, phone, email, nin FROM voters WHERE voter_id = ?");
    $stmt->bind_param("s", $voter_id);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $gender, $dob, $phone, $email, $nin);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>weDecide | Edit Profile - <?php echo htmlspecialchars($first_name); ?> <?php echo htmlspecialchars($last_name); ?></title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<?php include 'include/header.php'?>

    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <form action="edit_profile.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" disabled />
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" disabled />
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="text" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" disabled />
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars(ucfirst($gender)); ?>" disabled />
                </div>
            </div><div class="form-group full-width">
    <label for="profile_picture">Upload Profile Picture:</label>
    <input type="file" id="profile_picture" name="profile_picture" disabled/>
</div>

<div class="form-group full-width">
    <label for="nin">National Identification Number (NIN):</label>
    <input type="text" id="nin" name="nin" value="<?php echo htmlspecialchars($nin); ?>" disabled />
</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
                </div>
                <div class="form-group">
                    <label for="phone">Mobile Number:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="new_password">Enter Password:</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" />
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" />
                </div>
            </div>
            
            <button type="submit">SAVE</button>
        </form>
        <a href="home.php" class="cancelBtn">CANCEL</a>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
<?php include('include/footer.php');?>