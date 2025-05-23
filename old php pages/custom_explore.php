<?php
session_start();

// Get the event type from URL parameter
$event_type = isset($_GET['event_type']) ? $_GET['event_type'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Wedding Venues - <?php echo ucfirst($event_type); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff0f5;
            margin: 0;
            padding: 0;
        }
        #nav-bar ul {
            list-style-type: none;
            padding: 0;
            background-color: #ff69b4;
            display: flex;
            align-items: center;
        }
        #nav-bar li {
            padding: 15px 20px;
        }
        #nav-bar li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        .logo {
            height: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }
        .date-input {
            text-align: center;
            margin: 30px 0;
        }
        .venue-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .venue-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: hotpink;
        }
        .venue-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .venue-card {
            width: 280px;
            background-color: white;
            border: 2px solid #ffc0cb;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 192, 203, 0.5);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .venue-card:hover {
            transform: scale(1.05);
        }
        .venue-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .venue-info {
            padding: 10px;
        }
        .venue-type {
            background-color: #ffb6c1;
            color: white;
            text-align: center;
            padding: 5px;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
</head>
<body>

    <div id="nav-bar">
        <ul>
            <li id="tt">Wedding Wonders</li>
            <li class="logotxt"><a href="home.php"><img src="home.png" class="logo"> Home</a></li>
            <li class="logotxt"><a href="package.php"><img src="provision.png" class="logo"> Packages</a></li>
            <li class="logotxt"><a href="vendor.php"><img src="accessible.png" class="logo"> Vendors</a></li>
            <li class="logotxt"><a href="places.php">Places</a></li>
            <li class="logotxt"><a href="budget.php"><img src="budget.png" class="logo"> Budget</a></li>
            <li class="logotxt" id="log"><a href="profile.php"><img src="user.png" class="logo"> Profile</a></li>
        </ul>
    </div>

    <div class="date-input">
        <h2 style="color: hotpink;">Select Venue for <?php echo ucfirst($event_type); ?></h2>
    </div>

    <div id="venues-container">
        <div class="venue-section">
            <div class="venue-title">Available Venues</div>
            <div class="venue-grid">

                <!-- Sample venue card structure -->
                <div class="venue-card" onclick="selectVenue('Beach Paradise, Chennai', 150000)">
                    <div class="venue-type">Beach Resort</div>
                    <img src="beach.jpg" alt="Beach Paradise">
                    <div class="venue-info">
                        <h3>Beach Paradise</h3>
                        <p>Capacity: 200 People</p>
                        <p>Address: 123 Marina Beach Road, Chennai</p>
                        <p>Cost: ₹1,50,000</p>
                    </div>
                </div>

                <div class="venue-card" onclick="selectVenue('Green Garden, Chennai', 100000)">
                    <div class="venue-type">Outdoor Place</div>
                    <img src="garden.jpg" alt="Green Garden">
                    <div class="venue-info">
                        <h3>Green Garden</h3>
                        <p>Capacity: 150 People</p>
                        <p>Address: 456 Besant Nagar Avenue, Chennai</p>
                        <p>Cost: ₹1,00,000</p>
                    </div>
                </div>

                <!-- Add more venues similarly -->
                <div class="venue-card" onclick="selectVenue('Royal Banquet Hall, Chennai', 180000)">
                    <div class="venue-type">Banquet Hall</div>
                    <img src="banquet.jpg" alt="Royal Banquet Hall">
                    <div class="venue-info">
                        <h3>Royal Banquet Hall</h3>
                        <p>Capacity: 300 People</p>
                        <p>Address: 789 T. Nagar High Road, Chennai</p>
                        <p>Cost: ₹1,80,000</p>
                    </div>
                </div>
                <!-- 4 -->
                <div class="venue-card" onclick="selectVenue('Luxury Resort, Chennai', 250000)">
                    <div class="venue-type">Hotel & Resort</div>
                    <img src="hotel.jpg" alt="Luxury Resort">
                    <div class="venue-info">
                        <h3>Luxury Resort</h3>
                        <p>Capacity: 250 People</p>
                        <p>Address: 321 ECR Elegance Blvd, Chennai</p>
                        <p>Cost: ₹2,50,000</p>
                    </div>
                </div>

                <!-- 5 -->
                <div class="venue-card" onclick="selectVenue('Sacred Temple, Chennai', 8000)">
                    <div class="venue-type">Temple</div>
                    <img src="temple.jpg" alt="Sacred Temple">
                    <div class="venue-info">
                        <h3>Sacred Temple</h3>
                        <p>Capacity: 100 People</p>
                        <p>Address: 654 Mylapore Street, Chennai</p>
                        <p>Cost: ₹8,000</p>
                    </div>
                </div>

                <!-- 6 -->
                <div class="venue-card" onclick="selectVenue('Sunset Sands, Chennai', 130000)">
                    <div class="venue-type">Beach Resort</div>
                    <img src="beach1.jpeg" alt="Sunset Sands">
                    <div class="venue-info">
                        <h3>Sunset Sands</h3>
                        <p>Capacity: 180 People</p>
                        <p>Address: 78 Thiruvanmiyur Marina Drive, Chennai</p>
                        <p>Cost: ₹1,30,000</p>
                    </div>
                </div>

                <!-- 7 -->
                <div class="venue-card" onclick="selectVenue('Palm Garden, Chennai', 110000)">
                    <div class="venue-type">Outdoor Place</div>
                    <img src="garden1.jpeg" alt="Palm Garden">
                    <div class="venue-info">
                        <h3>Palm Garden</h3>
                        <p>Capacity: 170 People</p>
                        <p>Address: 99 Anna Nagar Green Belt, Chennai</p>
                        <p>Cost: ₹1,10,000</p>
                    </div>
                </div>

                <!-- 8 -->
                <div class="venue-card" onclick="selectVenue('Elite Banquet, Chennai', 200000)">
                    <div class="venue-type">Banquet Hall</div>
                    <img src="banquet1.jpeg" alt="Elite Banquet">
                    <div class="venue-info">
                        <h3>Elite Banquet</h3>
                        <p>Capacity: 280 People</p>
                        <p>Address: 66 Cathedral Road, Chennai</p>
                        <p>Cost: ₹2,00,000</p>
                    </div>
                </div>

                <!-- 9 -->
                <div class="venue-card" onclick="selectVenue('Royal Garden Palace, Chennai', 270000)">
                    <div class="venue-type">Hotel & Resort</div>
                    <img src="hotel.jpg" alt="Royal Garden Palace">
                    <div class="venue-info">
                        <h3>Royal Garden Palace</h3>
                        <p>Capacity: 350 People</p>
                        <p>Address: 88 Nungambakkam Regal Road, Chennai</p>
                        <p>Cost: ₹2,70,000</p>
                    </div>
                </div>

                <!-- 10 -->
                <div class="venue-card" onclick="selectVenue('Heritage Church, Chennai', 12000)">
                    <div class="venue-type">Temple</div>
                    <img src="chruch.jpeg" alt="Heritage Church">
                    <div class="venue-info">
                        <h3>Heritage Church</h3>
                        <p>Capacity: 120 People</p>
                        <p>Address: 145 Santhome Divine Path, Chennai</p>
                        <p>Cost: ₹12,000</p>
                    </div>
                </div>

                <!-- 11 -->
                <div class="venue-card" onclick="selectVenue('Ocean Breeze, Chennai', 160000)">
                    <div class="venue-type">Beach Resort</div>
                    <img src="beach2.jpeg" alt="Ocean Breeze">
                    <div class="venue-info">
                        <h3>Ocean Breeze</h3>
                        <p>Capacity: 220 People</p>
                        <p>Address: 23 Bay of Bengal Coastline, Chennai</p>
                        <p>Cost: ₹1,60,000</p>
                    </div>
                </div>

                <!-- 12 -->
                <div class="venue-card" onclick="selectVenue('Botanical Greens, Chennai', 90000)">
                    <div class="venue-type">Outdoor Place</div>
                    <img src="garden2.jpeg" alt="Botanical Greens">
                    <div class="venue-info">
                        <h3>Botanical Greens</h3>
                        <p>Capacity: 160 People</p>
                        <p>Address: 400 Guindy Flora Park, Chennai</p>
                        <p>Cost: ₹90,000</p>
                    </div>
                </div>

                <!-- 13 -->
                <div class="venue-card" onclick="selectVenue('Grand Celebration Hall, Chennai', 300000)">
                    <div class="venue-type">Banquet Hall</div>
                    <img src="banquet2.jpeg" alt="Grand Celebration Hall">
                    <div class="venue-info">
                        <h3>Grand Celebration Hall</h3>
                        <p>Capacity: 320 People</p>
                        <p>Address: 501 Mount Road, Chennai</p>
                        <p>Cost: ₹3,00,000</p>
                    </div>
                </div>
		<!-- 14 -->
                <div class="venue-card" onclick="selectVenue('Sapphire Suites, Chennai', 240000)">
                    <div class="venue-type">Hotel & Resort</div>
                    <img src="hotel2.jpeg" alt="Sapphire Suites">
                    <div class="venue-info">
                        <h3>Sapphire Suites</h3>
                        <p>Capacity: 240 People</p>
                        <p>Address: 777 Adyar Royal Heights, Chennai</p>
                        <p>Cost: ₹2,40,000</p>
                    </div>
                </div>
		<!-- 15 -->
                <div class="venue-card" onclick="selectVenue('Holy Blessings, Chennai', 6000)">
                    <div class="venue-type">Temple</div>
                    <img src="temple1.jpeg" alt="Holy Blessings">
                    <div class="venue-info">
                        <h3>Holy Blessings</h3>
                        <p>Capacity: 90 People</p>
                        <p>Address: 999 Mandaveli Riverbank, Chennai</p>
                        <p>Cost: ₹6,000</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function selectVenue(venueName, venueCost) {
            const eventType = "<?php echo $event_type; ?>";
            window.location.href = `Custom_Wedding_EventForm.php?venue=${encodeURIComponent(venueName)}&cost=${venueCost}&event_type=${eventType}`;
        }
    </script>

</body>
</html>
