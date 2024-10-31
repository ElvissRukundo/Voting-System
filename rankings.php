<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weDecideDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT c.candidates_name, c.political_party_name AS party, COUNT(v.id) AS votes
        FROM candidates c
        LEFT JOIN votes v ON c.candidates_name = v.candidates_name
        GROUP BY c.candidates_name, c.political_party_name
        ORDER BY votes DESC";

$result = $conn->query($sql);

$candidates = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $candidates[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Candidate Rankings - weDecide</title>
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
    <?php include 'include/header.php' ?>
    <div class="rankings_container">
        <h2>Candidate Rankings</h2>
        <table class="rankings_table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Party</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                foreach ($candidates as $candidate) {
                    echo "<tr>
                            <td>{$rank}</td>
                            <td>{$candidate['candidates_name']}</td>
                            <td>{$candidate['party']}</td>
                            <td>{$candidate['votes']}</td>
                          </tr>";
                    $rank++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="assets/js/script.js"></script>
    <script>
        setTimeout(() => {
            location.reload();
        }, 60000);
    </script>
</body>

</html>