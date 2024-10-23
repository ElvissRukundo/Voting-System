<?php
// contact.php

// Database connection variables
$servername = "localhost"; // Change if necessary
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "weDecideDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $subject = $conn->real_escape_string(trim($_POST['subject']));
    $messageContent = $conn->real_escape_string(trim($_POST['message']));

    // Insert data into the contacts table
    $sql = "INSERT INTO contacts (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$messageContent')";

    if ($conn->query($sql) === TRUE) {
        $message = "Message sent successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<title>Contact Us - weDecide</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<body>
<?php include 'include/header.php'?>

    <div class="contact_container">
        <div class="image-section">
            <img src="assets/images/contact.png" alt="Contact Illustration" />
        </div>
        <div class="contact_form">
            <h2>GET IN TOUCH WITH US</h2>

            <?php if ($message): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="contact.php" method="POST">
                <div class="input-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter Your Name" required />
                </div>
                <div class="input-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter Your Email" required />
                </div>
                <div class="input-group full-width">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="Enter Subject." required />
                </div>
                <div class="input-group full-width">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" placeholder="Enter Your Message" rows="5" required></textarea>
                </div>
                <div class="input-group full-width">
                    <button type="submit">Send Message</button>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php include('include/footer.php');?>