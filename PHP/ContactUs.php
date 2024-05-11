<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>About Us - Earthly Eco</title>
    <link rel="stylesheet" href="../CSS/aboutus.css" />
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../CSS/back-home.css">
    <link rel="stylesheet" type="text/css" href="../CSS/contactus.css">
</head>
<body>
<?php
session_start();
require_once("connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}
require_once('home-back.php');
?>
<header class="header">
    <h1>Contact Us</h1>
</header>

<section class="about-section">
    <div class="container">
        <p>
            Thank you for your interest in EarthlyEcoMarket! We're here to assist you in any way we can. Please feel free to reach out to us using the following contact information.
        </p>
        <div class="contact-info">
            <h3>Contact Us:</h3>
            <p>
            Email: info@earthlyecomarket.com<br>
            Phone: +1 (555) 123-4567<br>
            Address: 123 Green Street, Glassboro, NJ

            </p>
        </div>
        <div class="container">
            <form action="./Userfeedback.php" method="POST" enctype="multipart/form-data">
                <h1>FeedbackText</h1>
                <label for="FeedbackType">FeedbackType:</label>
                <select id="FeedbackType" name="FeedbackType" required>
                    <option value="">Select Feedback Type</option>
                    <option value="Question">Question</option>
                    <option value="Complaint">Complaint</option>
                    <option value="Suggestion">Suggestion</option>
                    <option value="Praise">Praise</option>
                </select>

                <label for="FeedbackText">FeedbackText:</label>
                <textarea id="FeedbackText" name="FeedbackText" placeholder="Your FeedbackText" required></textarea>


                <div class="buttons">
                    <button type="submit" name="submit" value="submit">Save</button>
                    <button onclick="window.location.href='./ContactUs.php'; return false;" class="cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</section>
<footer class="footer">
    <div class="container">
        <p>&copy; 2024 Earthly Eco</p>
    </div>
</footer>
</body>
</html>
