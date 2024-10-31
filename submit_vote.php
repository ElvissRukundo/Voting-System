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

if (isset($_POST['candidate_id'])) {
    $candidate_id = $_POST['candidate_id'];
    $user_id = $_SESSION['user_id'];

    $candidate_sql = "SELECT candidates_name FROM candidates WHERE id = ?";
    $stmt = $conn->prepare($candidate_sql);
    $stmt->bind_param("i", $candidate_id);
    $stmt->execute();
    $candidate_result = $stmt->get_result();

    if ($candidate_result->num_rows > 0) {
        $candidate_row = $candidate_result->fetch_assoc();
        $candidates_name = $candidate_row['candidates_name'];

        $check_vote_sql = "SELECT * FROM votes WHERE user_id = ?";
        $stmt = $conn->prepare($check_vote_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        error_log("User ID: " . $user_id);
        error_log("Votes found: " . $result->num_rows);

        if ($result->num_rows > 0) {
            $_SESSION['vote_error'] = "You have already voted.";
        } else {
            $vote_sql = "INSERT INTO votes (user_id, candidates_name) VALUES (?, ?)";
            $stmt = $conn->prepare($vote_sql);
            $stmt->bind_param("is", $user_id, $candidates_name);

            if ($stmt->execute()) {
                $_SESSION['vote_success'] = "Vote submitted!. Thank you for participating";
            } else {
                $_SESSION['vote_error'] = "An error occurred while submitting your vote. Please try again.";
            }
        }
    } else {
        $_SESSION['vote_error'] = "Invalid candidate selected.";
    }
    $stmt->close();
} else {
    $_SESSION['vote_error'] = "Please select a candidate to vote for.";
}

header("Location: vote.php");
exit();