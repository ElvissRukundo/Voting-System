<?php
session_start();

if (!isset($_SESSION['voter_id'])) {
    header("Location: login.php");
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weDecideDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$voter_id = $_SESSION['voter_id'];
$stmt = $conn->prepare("SELECT first_name, last_name, gender, dob, phone, email, nin, profile_pic FROM voters WHERE voter_id = ?");
$stmt->bind_param("s", $voter_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $gender, $dob, $phone, $email, $nin, $profile_pic);
$stmt->fetch();
$stmt->close();
$conn->close();
$birthdate = new DateTime($dob);
$today = new DateTime();
$age = $today->diff($birthdate)->y;

$profile_pic = $profile_pic ? $profile_pic : 'assets/images/default_profile.png';
?>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>weDecide | <?php echo htmlspecialchars($first_name); ?> <?php echo htmlspecialchars($last_name); ?></title>
  <body>
  <?php include 'include/header.php'?>

    <div class="profile-container">
      <div class="profile-left">
        <img src="<?php echo $profile_pic;?>" alt="Profile Picture" class="profile-pic" />
        <div class="buttons-container">
        <a href="edit_profile.php"><button class="edit-profile-btn"><i class="fas fa-edit"></i> Edit Profile</button> </a>
        <a href="logout.php"><button class="logout-btn"><i class="fas fa-sign-out"></i> Logout</button></a>
</div>
      </div>
      <div class="profile-right">
        <h2 class="profile-title">Hello, <?php echo $first_name . ' ' . $last_name; ?>!</h2>
        <div class="profile-info">
          <p><strong>Name:</strong> <?php echo $first_name . ' ' . $last_name; ?></p>
          <p><strong>Gender:</strong> <?php echo ucfirst($gender); ?></p>
          <p><strong>Age:</strong> <?php echo $age; ?></p>
          <p><strong>Mobile Number:</strong> <?php echo $phone; ?></p>
          <p><strong>Email:</strong> <?php echo $email; ?></p>
          <p><strong>NIN:</strong> <?php echo $nin; ?></p>
          <p><strong>Voter ID:</strong> <?php echo $voter_id; ?></p>
        </div>
      </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="assets/js/script.js"></script>
  </body>
</html>