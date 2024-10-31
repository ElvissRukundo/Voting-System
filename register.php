<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Now - weDecide</title>
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/favicons/site.webmanifest" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
    <link rel="stylesheet" href="assets/css/style.css">
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weDecideDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$voter_id = '';
$showModal = false;
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $first_name = trim($_POST['first-name']);
  $last_name = trim($_POST['last-name']);
  $dob = $_POST['dob'];
  $gender = $_POST['gender'];
  $nin = trim($_POST['nin']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm-password'];
  
  $profile_pic = '';
  if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == 0) {
      $target_dir = "admin/uploads/";
      $target_file = $target_dir . basename($_FILES["profile-pic"]["name"]);
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      $allowed_types = ['jpg', 'jpeg', 'png'];
      if (in_array($imageFileType, $allowed_types)) {
          if (move_uploaded_file($_FILES["profile-pic"]["tmp_name"], $target_file)) {
              $profile_pic = $target_file;
          } else {
              $error_message = "Sorry, there was an error uploading your file.";
          }
      } else {
          $error_message = "Only JPG, JPEG, and PNG files are allowed.";
      }
  } else {
      $error_message = "Please upload a profile picture.";
  }

  $today = new DateTime();
  $birth_date = new DateTime($dob);
  $age = $today->diff($birth_date)->y;

  if ($age < 18) {
      $error_message = "You must be at least 18 years old to register.";
  }

  if (empty($error_message)) {
      if ($gender == "female" && strpos($nin, "CF") !== 0) {
          $error_message = "Invalid NIN format.";
      } elseif ($gender == "male" && strpos($nin, "CM") !== 0) {
          $error_message = "Invalid NIN format.";
      }
  }

  if ($password !== $confirm_password) {
      $error_message = "Passwords do not match.";
  }

  if (empty($error_message)) {
      $stmt = $conn->prepare("SELECT * FROM voters WHERE nin = ? OR email = ? OR phone = ?");
      $stmt->bind_param("sss", $nin, $email, $phone);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          $error_message = "NIN, email, or mobile number already registered.";
      }
      $stmt->close();
  }

  if (empty($error_message)) {
      $voter_id = $nin[12] . $nin[5] . $nin[8] . $nin[1] . $nin[10] . $nin[3];
      $stmt = $conn->prepare("INSERT INTO voters (first_name, last_name, dob, gender, nin, profile_pic, email, phone, password, voter_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $hashed_password = password_hash($password, PASSWORD_BCRYPT);
      $stmt->bind_param("ssssssssss", $first_name, $last_name, $dob, $gender, $nin, $profile_pic, $email, $phone, $hashed_password, $voter_id);

      if ($stmt->execute()) {
          $showModal = true;
      } else {
          $error_message = "Error: Could not complete registration.";
      }

      $stmt->close();
  }
}

$conn->close();
?>

<div class="register_container">
    <div class="image-section">
        <img src="assets/images/ballot_man.png" alt="Illustration" />
    </div>
    <div class="register_form">
        <h2>REGISTER TO VOTE</h2>
        <span style="font-size: medium; color: white; background: #1da1f2; padding: 2px; ">All Bio Data should match that on your National ID.</span>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" placeholder="Enter Your First Name" required />
            </div>
            <div class="input-group">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" placeholder="Enter Your Last Name" required />
            </div>
            <div class="input-group">
                <label for="dob">Date Of Birth</label>
                <input type="date" id="dob" name="dob" placeholder="Date Of Birth" required />
            </div>
            <div class="input-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" required>
                    <option value="" selected>Select Gender</option>
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                </select>
            </div>
            <div class="input-group full-width">
                <div class="half-width">
                    <label for="profile-pic">Upload Profile Picture</label>
                    <input type="file" id="profile-pic" name="profile-pic" accept=".jpg, .jpeg, .png" required />
                </div>
                <div class="half-width">
                    <label for="nin">National Identification Number (NIN)</label>
                    <input type="text" id="nin" name="nin" placeholder="Enter Your NIN" required />
                </div>
            </div>
            <div class="input-group">
            <label for="email">Enter Your Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email" required />
            </div>
            <div class="input-group">
            <label for="phone">Enter Your Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter Your Mobile No." required />
            </div>
            <div class="input-group">
            <label for="email">Enter Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Your Password" required />
            </div>
            <div class="input-group">
            <label for="email">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required />
            </div>
            <div class="input-group full-width">
                <button type="submit">Submit</button>
            </div>
            <p style="font-size: 14px;">Already have an account? <a href="login.php">Login</a></p>
            <p style="font-size: 14px;">Go to <a href="index.php">Homepage</a></p>
        </form>
    </div>
</div>

<div id="myModal" class="modal <?php if ($showModal) echo 'show'; ?>">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h6>Registration Successful!</h6>
        <p>Your Voter ID is: <strong><?php echo htmlspecialchars($voter_id); ?></strong></p>
        <p>Keep it safe for your next login.</p>
        <br>
        <a href="login.php">Login Now</a>
    </div>
</div>

<script>
    var modal = document.getElementById("myModal");

    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    <?php if ($showModal) { ?>
        modal.style.display = "block";
    <?php } ?>
</script>

</body>
</html> 