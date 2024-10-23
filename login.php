<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - weDecide</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
  <?php
session_start();
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

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voter_id = $_POST['voter_id'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to find the user by voter ID
    $stmt = $conn->prepare("SELECT id, password, first_name, last_name, gender, dob, phone, email, nin FROM voters WHERE voter_id = ?");
    $stmt->bind_param("s", $voter_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $first_name, $last_name, $gender, $dob, $phone, $email, $nin);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        
        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables and redirect to home.php
            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['gender'] = $gender;
            $_SESSION['dob'] = $dob;
            $_SESSION['phone'] = $phone;
            $_SESSION['email'] = $email;
            $_SESSION['nin'] = $nin;
            $_SESSION['voter_id'] = $voter_id;

            // Redirect to home.php after successful login
            header("Location: home.php");
            exit;
        } else {
            $loginError = "Incorrect Password!";
        }
    } else {
        $loginError = "Invalid Voter ID!";
    }
    $stmt->close();
}

$conn->close();
?>

    <div class="login-container">
      <div class="login-left">
        <img src="assets/images/ballot_woman.png" alt="Illustration of a Laptop" />
      </div>
      <div class="login-right">
        <h2>LOGIN TO VOTE</h2>
        <?php if ($loginError): ?>
          <p style="color: red;"><?php echo $loginError; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
          <div class="input-group">
            <input type="text" name="voter_id" placeholder="Voter ID" required />
          </div>
          <div class="input-group">
            <input type="password" name="password" placeholder="Password" required />
          </div>
          <div class="input-group">
            <button type="submit">LOGIN</button>
          </div>
          <div class="forgot-links">
            Not a user? <a href="register.php">Register now</a>
          </div>
        </form>
      </div>
    </div>
    <script src="assets/js/script.js"></script>
  </body>
</html>

<?php include('include/footer.php');?>