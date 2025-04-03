<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "Abhi@1289";
$dbname = "Abhi";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch announcements
$announcements = [];
$result = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Announcements</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Roboto", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f3f4;
            color: #202124;
        }
        header {
            background: #212529;
            color: white;
            text-align: center;
            padding: 40px 20px;
            font-size: 28px;
            font-weight: 700;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        .announcement {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.3s ease-in-out;
        }
        .announcement:hover {
            transform: translateY(-3px);
        }
        .announcement-content {
            max-width: 80%;
        }
        .announcement h3 {
            font-size: 20px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 5px;
        }
        .announcement p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 5px;
            color: #333;
        }
        .date {
            font-size: 14px;
            color: #555;
            font-weight: 600;
            min-width: 120px;
            text-align: right;
        }
        footer {
            background: #212529;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .announcement {
                flex-direction: column;
                align-items: flex-start;
            }
            .announcement-content {
                max-width: 100%;
            }
            .date {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>

<header>Campus Announcements</header>

<div class="container">
    <?php if (count($announcements) > 0) { ?>
        <?php foreach ($announcements as $ann) { ?>
            <div class="announcement">
                <div class="announcement-content">
                    <h3><?php echo htmlspecialchars($ann['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($ann['message'])); ?></p>
                </div>
                <span class="date"><?php echo date('d M Y, H:i', strtotime($ann['created_at'])); ?></span>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="announcement">
            <div class="announcement-content">
                <h3>No Announcements Yet</h3>
                <p>Stay tuned for upcoming announcements.</p>
            </div>
        </div>
    <?php } ?>
</div>

<footer>© 2025 Campus Name | All Rights Reserved</footer>

</body>
</html>
