<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "wonder wedding";

// Create connection
$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the latest package for the user
$user_email = $_SESSION['email'];
$sql = "SELECT * FROM user_packages WHERE user_email = '$user_email' ORDER BY created_at DESC LIMIT 1";
$result = mysqli_query($con, $sql);
$package = mysqli_fetch_assoc($result);

if (!$package) {
    header('Location: Full_Wedding_EventForm.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Budget Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #ff69b4;
            text-align: center;
            margin-bottom: 30px;
        }
        .event-section {
            margin-bottom: 30px;
            border-bottom: 1px dashed #ffb6c1;
            padding-bottom: 20px;
        }
        h2 {
            color: #ff69b4;
            margin-bottom: 15px;
        }
        .budget-details {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .budget-item {
            width: 48%;
            margin-bottom: 10px;
        }
        .total-section {
            background-color: #ffebf1;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .grand-total {
            font-size: 24px;
            font-weight: bold;
            color: #ff1493;
            text-align: center;
            margin-top: 20px;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 12px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .print-btn:hover {
            background-color: #ff1493;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Wedding Budget Summary</h1>
        
        <!-- Engagement Section -->
        <div class="event-section">
            <h2>Engagement Details</h2>
            <div class="budget-details">
                <div class="budget-item">
                    <strong>Date:</strong> <?php echo htmlspecialchars($package['engagement_date']); ?>
                </div>
                <div class="budget-item">
                    <strong>Time:</strong> <?php echo htmlspecialchars($package['engagement_time']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue:</strong> <?php echo htmlspecialchars($package['engagement_place']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue Cost:</strong> ₹<?php echo number_format($package['engagement_place_amount'], 2); ?>
                </div>
                <div class="budget-item">
                    <strong>Number of Guests:</strong> <?php echo number_format($package['engagement_guests']); ?>
                </div>
                <div class="budget-item">
                    <strong>Food Cost:</strong> ₹<?php echo number_format($package['engagement_food_cost'], 2); ?>
                </div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Engagement Total:</strong> ₹<?php echo number_format($package['engagement_total'], 2); ?>
            </div>
        </div>
        
        <!-- Marriage Section -->
        <div class="event-section">
            <h2>Marriage Details</h2>
            <div class="budget-details">
                <div class="budget-item">
                    <strong>Date:</strong> <?php echo htmlspecialchars($package['marriage_date']); ?>
                </div>
                <div class="budget-item">
                    <strong>Time:</strong> <?php echo htmlspecialchars($package['marriage_time']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue:</strong> <?php echo htmlspecialchars($package['marriage_place']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue Cost:</strong> ₹<?php echo number_format($package['marriage_place_amount'], 2); ?>
                </div>
                <div class="budget-item">
                    <strong>Number of Guests:</strong> <?php echo number_format($package['marriage_guests']); ?>
                </div>
                <div class="budget-item">
                    <strong>Food Cost:</strong> ₹<?php echo number_format($package['marriage_food_cost'], 2); ?>
                </div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Marriage Total:</strong> ₹<?php echo number_format($package['marriage_total'], 2); ?>
            </div>
        </div>
        
        <!-- Reception Section -->
        <div class="event-section">
            <h2>Reception Details</h2>
            <div class="budget-details">
                <div class="budget-item">
                    <strong>Date:</strong> <?php echo htmlspecialchars($package['reception_date']); ?>
                </div>
                <div class="budget-item">
                    <strong>Time:</strong> <?php echo htmlspecialchars($package['reception_time']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue:</strong> <?php echo htmlspecialchars($package['reception_place']); ?>
                </div>
                <div class="budget-item">
                    <strong>Venue Cost:</strong> ₹<?php echo number_format($package['reception_place_amount'], 2); ?>
                </div>
                <div class="budget-item">
                    <strong>Number of Guests:</strong> <?php echo number_format($package['reception_guests']); ?>
                </div>
                <div class="budget-item">
                    <strong>Food Cost:</strong> ₹<?php echo number_format($package['reception_food_cost'], 2); ?>
                </div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Reception Total:</strong> ₹<?php echo number_format($package['reception_total'], 2); ?>
            </div>
        </div>
        <div style="margin-top: 20px;">
            <h4 style="color: #d63384;">Vendor Details</h4>
            <div class="budget-details">
                <div class="budget-item"><strong>Photographer:</strong> ₹<?php echo number_format($package['photographer'], 2); ?></div>
                <div class="budget-item"><strong>Decoration:</strong> ₹<?php echo number_format($package['decoration'], 2); ?></div>
                <div class="budget-item"><strong>Entertainment:</strong> ₹<?php echo number_format($package['entertainment'], 2); ?></div>
                <div class="budget-item"><strong>Makeup:</strong> ₹<?php echo number_format($package['makeup'], 2); ?></div>
            </div>
            <div style="text-align: right; margin-top: 10px;">
                <strong>Vendor Total:</strong> ₹<?php echo number_format($package['reception_total'], 2); ?>
            </div>
        </div>

        <!-- Grand Total Section -->
        <div class="total-section">
            <div class="grand-total">
                Grand Total: ₹<?php echo number_format($package['grand_total'], 2); ?>
            </div>
        </div>
        
        <a href="#" class="print-btn" onclick="window.print()">Print Budget</a>
    </div>
</body>
</html>