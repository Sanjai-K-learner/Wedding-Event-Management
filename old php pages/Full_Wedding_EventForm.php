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

// Process venue selection from explore page
if (isset($_GET['venue']) && isset($_GET['cost'])) {
    $event_type = $_GET['event_type'] ?? '';
    $_SESSION[$event_type.'_place'] = $_GET['venue'];
    $_SESSION[$event_type.'_place_amount'] = $_GET['cost'];
    header('Location: Full_Wedding_EventForm.php');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_SESSION['email'];
    
    // Get form data
    $engagement_data = [
        'date' => mysqli_real_escape_string($con, $_POST['engagement_date']),
        'time' => mysqli_real_escape_string($con, $_POST['engagement_time']),
        'guests' => intval($_POST['engagement_guests']),
        'place' => mysqli_real_escape_string($con, $_POST['engagement_place']),
        'place_amount' => floatval($_POST['engagement_place_amount'])
    ];
    
    $reception_data = [
        'date' => mysqli_real_escape_string($con, $_POST['reception_date']),
        'time' => mysqli_real_escape_string($con, $_POST['reception_time']),
        'guests' => intval($_POST['reception_guests']),
        'place' => mysqli_real_escape_string($con, $_POST['reception_place']),
        'place_amount' => floatval($_POST['reception_place_amount'])
    ];
    
    $marriage_data = [
        'date' => mysqli_real_escape_string($con, $_POST['marriage_date']),
        'time' => mysqli_real_escape_string($con, $_POST['marriage_time']),
        'guests' => intval($_POST['marriage_guests']),
        'place' => mysqli_real_escape_string($con, $_POST['marriage_place']),
        'place_amount' => floatval($_POST['marriage_place_amount'])
    ];
    
    // Calculate costs
    $food_cost_per_guest = 250;
    
    $engagement_food_cost = $engagement_data['guests'] * $food_cost_per_guest;
    $reception_food_cost = $reception_data['guests'] * $food_cost_per_guest;
    $marriage_food_cost = $marriage_data['guests'] * $food_cost_per_guest;
    
    $engagement_total = $engagement_data['place_amount'] + $engagement_food_cost;
    $reception_total = $reception_data['place_amount'] + $reception_food_cost;
    $marriage_total = $marriage_data['place_amount'] + $marriage_food_cost;
    
    $grand_total = $engagement_total + $reception_total + $marriage_total+80000+100000+40000+28000;
    
    // Insert into database
    $sql = "INSERT INTO user_packages (
                user_email,decoration, photographer,entertainment,makeup,
                engagement_date, engagement_time, engagement_place, engagement_place_amount, 
                engagement_guests, engagement_food_cost, engagement_total,
                reception_date, reception_time, reception_place, reception_place_amount, 
                reception_guests, reception_food_cost, reception_total,
                marriage_date, marriage_time, marriage_place, marriage_place_amount, 
                marriage_guests, marriage_food_cost, marriage_total,
                grand_total,
                created_at
            ) VALUES (
                '$user_email',80000,100000,40000,28000,
                '{$engagement_data['date']}', '{$engagement_data['time']}', '{$engagement_data['place']}', {$engagement_data['place_amount']},
                {$engagement_data['guests']}, $engagement_food_cost, $engagement_total,
                '{$reception_data['date']}', '{$reception_data['time']}', '{$reception_data['place']}', {$reception_data['place_amount']},
                {$reception_data['guests']}, $reception_food_cost, $reception_total,
                '{$marriage_data['date']}', '{$marriage_data['time']}', '{$marriage_data['place']}', {$marriage_data['place_amount']},
                {$marriage_data['guests']}, $marriage_food_cost, $marriage_total,
                $grand_total,
                NOW()
            )";
    
    if (mysqli_query($con, $sql)) {
        $_SESSION['success'] = "Event details saved successfully!";
        header('Location: budgett.php');
        exit;
    } else {
        $error = "Error saving event details: " . mysqli_error($con);
    }
}

// Get saved places from session if available
$engagement_place = $_SESSION['engagement_place'] ?? '';
$engagement_place_amount = $_SESSION['engagement_place_amount'] ?? 0;
$reception_place = $_SESSION['reception_place'] ?? '';
$reception_place_amount = $_SESSION['reception_place_amount'] ?? 0;
$marriage_place = $_SESSION['marriage_place'] ?? '';
$marriage_place_amount = $_SESSION['marriage_place_amount'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Full Wedding Event Planning</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url("awesomeback.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      margin: 0;
      padding: 20px;
      color: white;
    }
    
    .container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    
    .box {
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5));
      border: 2px solid deeppink;
      border-radius: 8px;
      padding: 20px;
      width: 450px;
      margin: 20px;
    }
    
    .imgcls {
      width: 250px;
      height: 150px;
      margin: 10px auto;
      display: block;
      border: 1px solid gold;
      border-radius: 5px;
    }
    
    .box input[type="date"],
    .box input[type="time"],
    .box select {
      width: 200px;
      height: 30px;
      border: 1px solid deeppink;
      border-radius: 4px;
      padding: 5px;
      margin-top: 5px;
    }
    
    h5 {
      color: deeppink;
      font-size: large;
      margin: 15px 0 5px;
    }
    
    .box h3 {
      font-size: 24px;
      color: white;
      text-align: center;
      margin-bottom: 15px;
    }
    
    .place-details {
      margin: 10px 0;
      font-size: 16px;
      color: white;
      background: rgba(255,20,147,0.2);
      padding: 10px;
      border-radius: 5px;
    }
    
    .explore-btn {
      padding: 8px 16px;
      background-color: deeppink;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin: 10px 0;
      font-weight: bold;
    }
    
    .explore-btn:hover {
      background-color: #ff1493;
    }
    
    #submit-button {
      margin: 30px auto;
      padding: 12px 24px;
      background-color: deeppink;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      display: block;
      font-size: 18px;
      font-weight: bold;
    }
    
    #submit-button:hover {
      background-color: #ff1493;
      transform: scale(1.05);
    }
    
    .error-message {
      color: red;
      text-align: center;
      padding: 10px;
      background-color: white;
      margin: 10px auto;
      width: 50%;
      border-radius: 5px;
    }
    
    .success-message {
      color: green;
      text-align: center;
      padding: 10px;
      background-color: white;
      margin: 10px auto;
      width: 50%;
      border-radius: 5px;
    }
    
    h1 {
      text-align: center;
      color: deeppink;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
  <h1>Wonder Wedding - Full Package Planning</h1>
  
  <?php if (isset($error)): ?>
    <div class="error-message"><?php echo $error; ?></div>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['success'])): ?>
    <div class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="container">
      <!-- Engagement Box -->
      <div class="box">
        <h3>Engagement</h3>
        <img src="engagement.jpeg" alt="Engagement" class="imgcls">
        
        <?php if ($engagement_place): ?>
          <div class="place-details">
            Selected Place: <?php echo htmlspecialchars($engagement_place); ?> 
            <br>Cost: ₹<?php echo number_format($engagement_place_amount, 2); ?>
            <input type="hidden" name="engagement_place" value="<?php echo htmlspecialchars($engagement_place); ?>">
            <input type="hidden" name="engagement_place_amount" value="<?php echo $engagement_place_amount; ?>">
          </div>
        <?php endif; ?>
        
        <button type="button" class="explore-btn" onclick="window.location.href='explore.php?event_type=engagement'">
          <?php echo $engagement_place ? 'Change Place' : 'Explore Places'; ?>
        </button>
        
        <h5>Engagement Date</h5>
        <input type="date" name="engagement_date" id="engagement-date" required>
        
        <h5>Time</h5>
        <input type="time" name="engagement_time" id="engagement-time" required>
        
        <h5>Number of Guests</h5>
        <select name="engagement_guests" id="engagement-guests" required>
          <option value="500">500-1000</option>
          <option value="1000">1000-2000</option>
          <option value="2000">2000 above</option>
        </select>
      </div>

      <!-- Reception Box -->
      <div class="box">
        <h3>Reception</h3>
        <img src="reception.jpeg" alt="Reception" class="imgcls">
        
        <?php if ($reception_place): ?>
          <div class="place-details">
            Selected Place: <?php echo htmlspecialchars($reception_place); ?> 
            <br>Cost: ₹<?php echo number_format($reception_place_amount, 2); ?>
            <input type="hidden" name="reception_place" value="<?php echo htmlspecialchars($reception_place); ?>">
            <input type="hidden" name="reception_place_amount" value="<?php echo $reception_place_amount; ?>">
          </div>
        <?php endif; ?>
        
        <button type="button" class="explore-btn" onclick="window.location.href='explore.php?event_type=reception'">
          <?php echo $reception_place ? 'Change Place' : 'Explore Places'; ?>
        </button>
        
        <h5>Reception Date</h5>
        <input type="date" name="reception_date" id="reception-date" required>
        
        <h5>Time</h5>
        <input type="time" name="reception_time" id="reception-time" required>
        
        <h5>Number of Guests</h5>
        <select name="reception_guests" id="reception-guests" required>
          <option value="500">500-1000</option>
          <option value="1000">1000-2000</option>
          <option value="2000">2000 above</option>
        </select>
      </div>

      <!-- Marriage Box -->
      <div class="box">
        <h3>Marriage</h3>
        <img src="marriage.jpeg" alt="Marriage" class="imgcls">
        
        <?php if ($marriage_place): ?>
          <div class="place-details">
            Selected Place: <?php echo htmlspecialchars($marriage_place); ?> 
            <br>Cost: ₹<?php echo number_format($marriage_place_amount, 2); ?>
            <input type="hidden" name="marriage_place" value="<?php echo htmlspecialchars($marriage_place); ?>">
            <input type="hidden" name="marriage_place_amount" value="<?php echo $marriage_place_amount; ?>">
          </div>
        <?php endif; ?>
        
        <button type="button" class="explore-btn" onclick="window.location.href='explore.php?event_type=marriage'">
          <?php echo $marriage_place ? 'Change Place' : 'Explore Places'; ?>
        </button>
        
        <h5>Marriage Date</h5>
        <input type="date" name="marriage_date" id="marriage-date" required>
        
        <h5>Time</h5>
        <input type="time" name="marriage_time" id="marriage-time" required>
        
        <h5>Number of Guests</h5>
        <select name="marriage_guests" id="marriage-guests" required>
          <option value="500">500-1000</option>
          <option value="1000">1000-2000</option>
          <option value="2000">2000 above</option>
        </select>
      </div>
    </div>

    <button type="submit" id="submit-button" >Submit & Continue to Budget</button>
  </form>
</body>
</html>