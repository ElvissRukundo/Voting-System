<?php
session_start();

// Database connection
$servername = "localhost"; // or "127.0.0.1"
$username = "root";
$password = "";
$dbname = "weDecideDB"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for vote error or success message
$vote_error = isset($_SESSION['vote_error']) ? $_SESSION['vote_error'] : '';
$vote_success = isset($_SESSION['vote_success']) ? $_SESSION['vote_success'] : '';

// Clear the messages after they are displayed
unset($_SESSION['vote_error']);
unset($_SESSION['vote_success']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>weDecide - Welcome</title>
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
  <?php include 'include/header.php'?>

    <!-- Page Content -->
    <div class="container">

      <!-- Voting Form -->
       <h2>Vote for your candidate</h2>
      <form action="submit_vote.php" method="post">
  <table class="candidate-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Party</th>
        <th>Select to Vote</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Fetch candidates from the database
      $sql = "SELECT id, candidates_name, candidate_image, political_party_name, party_logo FROM candidates";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          // Output data of each row
          while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['candidates_name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['political_party_name']) . "</td>";
              echo '<td><input type="radio" name="candidate_id" value="' . $row['id'] . '" required></td>';
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='3'>No candidates available</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- Submit Vote Button -->
  <button class="vote-button" type="submit">Submit Vote</button>
</form>

    </div>

    <!-- Modal Popup for Error -->
    <?php if ($vote_error): ?>
    <div class="vote_modal" id="errorModal">
      <div class="vote_modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p><?php echo $vote_error; ?></p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Modal Popup for Success -->
    <?php if ($vote_success): ?>
    <div class="vote_modal" id="successModal">
      <div class="vote_modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p><?php echo $vote_success; ?></p>
      </div>
    </div>
    <?php endif; ?>

    <script src="assets/js/script.js"></script>

    <script>
      // Show modal on page load if error or success exists
      window.onload = function() {
        const errorModal = document.getElementById("errorModal");
        const successModal = document.getElementById("successModal");

        if (errorModal) {
          errorModal.style.display = "block";
        }

        if (successModal) {
          successModal.style.display = "block";
        }
      };

      // Close modal function
      function closeModal() {
        const modals = document.querySelectorAll(".vote_modal");
        modals.forEach(vote_modal => {
          vote_modal.style.display = "none";
        });
      }
    </script>
  </body>
</html>

<?php include('include/footer.php');?>