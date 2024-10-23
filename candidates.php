<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weDecideDB";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch candidate details
$sql = "SELECT c.candidates_name, c.date_of_birth, c.candidate_image, p.fullname AS party_name, p.acronym AS party_acronym, p.logo AS party_logo
        FROM candidates c
        INNER JOIN political_parties p ON c.political_party = p.id";
$result = $conn->query($sql);

?>

  <title>weDecide - Welcome</title>

  <body>
  <?php include 'include/header.php'?>
<h1>Presidential Candidates</h1>

<div class="candidates-container">
    <?php
    if ($result->num_rows > 0) {
        // Loop through each candidate
        while ($row = $result->fetch_assoc()) {
            // Calculate the candidate's age
            $birthdate = new DateTime($row['date_of_birth']);
            $today = new DateTime('today');
            $age = $today->diff($birthdate)->y;

            echo '<div class="candidate-card">';
            echo '<img src="' . $row['candidate_image'] . '" alt="' . $row['candidates_name'] . '" class="candidate-image">';
            echo '<h2>' . $row['candidates_name'] . '</h2>';
            echo '<p>Age: ' . $age . '</p>';
            echo '<p>' . $row['party_name'] . ' (' . $row['party_acronym'] . ')</p>';
            echo '</div>';
        }
    } else {
        echo "<p>No candidates found.</p>";
    }
    ?>
</div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
<?php include('include/footer.php');?>
