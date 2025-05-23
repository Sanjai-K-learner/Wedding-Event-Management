<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
$user_email = $_SESSION['email'];
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "wonder wedding";

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process venue selection and persist event session
if (isset($_GET['venue']) && isset($_GET['cost']) && isset($_GET['event_type'])) {
    $event_type = $_GET['event_type'];
    $_SESSION[$event_type . '_place'] = $_GET['venue'];
    $_SESSION[$event_type . '_place_amount'] = $_GET['cost'];
    $_SESSION['selected_event'] = $event_type;
    $_SESSION["select_$event_type"] = true;
    header('Location: Custom_Wedding_EventForm.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_SESSION['email'];
    $food_cost_per_guest = 500;
    $grand_total = 0;

    $columns = "user_email";
    $values = "'$user_email'";

    $event_names = ['engagement', 'reception', 'marriage'];

    foreach ($event_names as $event) {
        if (isset($_POST["select_$event"])) {
            $_SESSION["select_$event"] = true;
            $_SESSION["{$event}_date"] = $_POST["{$event}_date"];
            $_SESSION["{$event}_time"] = $_POST["{$event}_time"];
            $_SESSION["{$event}_guests"] = $_POST["{$event}_guests"];
            $_SESSION['selected_event'] = $event;

            $date = mysqli_real_escape_string($con, $_POST["{$event}_date"]);
            $time = mysqli_real_escape_string($con, $_POST["{$event}_time"]);
            $guests = intval($_POST["{$event}_guests"]);
            $place = isset($_POST["{$event}_place"]) ? mysqli_real_escape_string($con, $_POST["{$event}_place"]) : '';
            $place_amount = isset($_POST["{$event}_place_amount"]) ? floatval($_POST["{$event}_place_amount"]) : 0;
            $grand_total += $place_amount;

            $columns .= ", {$event}_date, {$event}_time, {$event}_place, {$event}_place_amount, {$event}_guests";
            $values .= ", '$date', '$time', '$place', $place_amount, $guests";
        } else {
            unset($_SESSION["select_$event"]);
            unset($_SESSION["{$event}_date"]);
            unset($_SESSION["{$event}_time"]);
            unset($_SESSION["{$event}_guests"]);
            unset($_SESSION["{$event}_place"]);
            unset($_SESSION["{$event}_place_amount"]);
        }
    }

    $columns .= ", grand_total, created_at";
    $values .= ", $grand_total, NOW()";

    $sql = "INSERT INTO custom_packages ($columns) VALUES ($values)";

    if (mysqli_query($con, $sql)) {
        $_SESSION['success'] = "Event details saved successfully!";
        header('Location: budget_custom.php');
        exit;
    } else {
        $error = "Error saving event details: " . mysqli_error($con);
    }
}

$event_data = [];
foreach (['engagement', 'reception', 'marriage'] as $event) {
    $event_data[$event] = [
        'selected' => $_SESSION["select_$event"] ?? false,
        'date' => $_SESSION["{$event}_date"] ?? '',
        'time' => $_SESSION["{$event}_time"] ?? '',
        'guests' => $_SESSION["{$event}_guests"] ?? '',
        'place' => $_SESSION["{$event}_place"] ?? '',
        'amount' => $_SESSION["{$event}_place_amount"] ?? 0
    ];
}
$selected_event = $_SESSION['selected_event'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom Wedding Event Planning</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url("awesomeback.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      padding: 20px;
      color: white;
    }
    h1 {
      text-align: center;
      color: deeppink;
      margin-bottom: 20px;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .box {
      background: rgba(0, 0, 0, 0.6);
      border: 2px solid deeppink;
      border-radius: 8px;
      padding: 20px;
      width: 450px;
      display: none;
    }
    .imgcls {
      width: 250px;
      height: 150px;
      display: block;
      margin: 10px auto;
      border: 1px solid gold;
      border-radius: 5px;
    }
    .box input, .box select {
      width: 100%;
      padding: 5px;
      margin-top: 8px;
    }
    .place-details {
      background: rgba(255, 20, 147, 0.2);
      padding: 10px;
      margin-top: 10px;
      border-radius: 5px;
    }
    .explore-btn, #submit-button, .vendor-btn {
      margin-top: 15px;
      background-color: deeppink;
      border: none;
      color: white;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    .vendor-btn:disabled {
      background-color: #cccccc;
      cursor: not-allowed;
    }
  </style>
  <script>
    function toggleEventBox(event) {
      const checkbox = document.getElementById("select_" + event);
      const box = document.getElementById(event + "-box");
      box.style.display = checkbox.checked ? "block" : "none";
      
      const requiredFields = box.querySelectorAll('[required]');
      requiredFields.forEach(field => {
        field.required = checkbox.checked;
      });
      
      // Update vendor button disabled state
      const vendorBtn = box.querySelector('.vendor-btn');
      if (vendorBtn) {
        const dateInput = box.querySelector('input[type="date"]');
        const timeInput = box.querySelector('input[type="time"]');
        vendorBtn.disabled = !dateInput.value || !timeInput.value;
      }
    }
    
    function bookVendors(eventType) {
      const box = document.getElementById(eventType + "-box");
      const date = box.querySelector('input[type="date"]').value;
      const time = box.querySelector('input[type="time"]').value;
      
      if (!date || !time) {
        alert("Please set both date and time before booking vendors");
        return;
      }
      
      window.location.href = `Vendors.php?event=${encodeURIComponent(eventType)}&date=${encodeURIComponent(date)}&time=${encodeURIComponent(time)}`;
    }
    
    window.onload = function() {
      ['engagement', 'reception', 'marriage'].forEach(event => {
        const eventData = <?php echo json_encode($event_data); ?>[event];
        const checkbox = document.getElementById("select_" + event);
        const box = document.getElementById(event + "-box");
        
        if (eventData['selected']) {
          checkbox.checked = true;
          box.style.display = "block";
          
          const inputs = box.querySelectorAll('input, select');
          inputs.forEach(input => {
            input.required = true;
          });
          
          // Set vendor button state
          const vendorBtn = box.querySelector('.vendor-btn');
          if (vendorBtn) {
            vendorBtn.disabled = !eventData.date || !eventData.time;
          }
        }
        
        // Add event listeners for date/time changes
        const dateInput = box.querySelector('input[type="date"]');
        const timeInput = box.querySelector('input[type="time"]');
        if (dateInput && timeInput) {
          dateInput.addEventListener('change', () => {
            const vendorBtn = box.querySelector('.vendor-btn');
            if (vendorBtn) {
              vendorBtn.disabled = !dateInput.value || !timeInput.value;
            }
          });
          timeInput.addEventListener('change', () => {
            const vendorBtn = box.querySelector('.vendor-btn');
            if (vendorBtn) {
              vendorBtn.disabled = !dateInput.value || !timeInput.value;
            }
          });
        }
      });
    }
  </script>
</head>
<body>
<h1>Wonder Wedding - Custom Package Planning</h1>

<?php if (isset($error)) echo "<div style='color: red; text-align: center;'>$error</div>"; ?>
<?php if (isset($_SESSION['success'])) {
  echo "<div style='color: green; text-align: center;'>{$_SESSION['success']}</div>";
  unset($_SESSION['success']);
} ?>

<form method="POST">
  <div class="container">
    <?php foreach (['engagement', 'reception', 'marriage'] as $event): ?>
      <div>
        <label><input type="checkbox" name="select_<?= $event ?>" id="select_<?= $event ?>" onchange="toggleEventBox('<?= $event ?>')" <?= $event_data[$event]['selected'] ? 'checked' : '' ?>> <?= ucfirst($event) ?></label>
        <div class="box" id="<?= $event ?>-box">
          <h3><?= ucfirst($event) ?></h3>
          <img src="<?= $event ?>.jpeg" class="imgcls">
          <?php if (!empty($event_data[$event]['place'])): ?>
            <div class="place-details">
              Selected: <?= htmlspecialchars($event_data[$event]['place']) ?><br>
              Cost: ₹<?= number_format($event_data[$event]['amount'], 2) ?>
              <input type="hidden" name="<?= $event ?>_place" value="<?= htmlspecialchars($event_data[$event]['place']) ?>">
              <input type="hidden" name="<?= $event ?>_place_amount" value="<?= $event_data[$event]['amount'] ?>">
            </div>
          <?php endif; ?>
          <button type="button" class="explore-btn" onclick="window.location.href='custom_explore.php?event_type=<?= $event ?>'">
            <?= !empty($event_data[$event]['place']) ? 'Change Place' : 'Explore Places' ?>
          </button>
          <label>Date</label>
          <input type="date" name="<?= $event ?>_date" value="<?= $event_data[$event]['date'] ?>" <?= $event_data[$event]['selected'] ? 'required' : '' ?>>
          <label>Time</label>
          <input type="time" name="<?= $event ?>_time" value="<?= $event_data[$event]['time'] ?>" <?= $event_data[$event]['selected'] ? 'required' : '' ?>>
          <label>Guests</label>
          <select name="<?= $event ?>_guests" <?= $event_data[$event]['selected'] ? 'required' : '' ?>>
            <option value="500" <?= $event_data[$event]['guests'] == '500' ? 'selected' : '' ?>>500-1000</option>
            <option value="1000" <?= $event_data[$event]['guests'] == '1000' ? 'selected' : '' ?>>1000-2000</option>
            <option value="2000" <?= $event_data[$event]['guests'] == '2000' ? 'selected' : '' ?>>2000 above</option>
          </select>
          <button type="button" class="vendor-btn" onclick="bookVendors('<?= $event ?>')" 
                  <?= empty($event_data[$event]['date']) || empty($event_data[$event]['time']) ? 'disabled' : '' ?>
                  title="<?= empty($event_data[$event]['date']) || empty($event_data[$event]['time']) ? 'Please set date and time first' : 'Book vendors for this event' ?>">
            Book Vendors
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div style="text-align: center; margin-top: 20px;">
    <button type="submit" id="submit-button" onclick="budget_custom.php">Submit & Continue to Budget</button>
  </div>
</form>
</body>
</html>