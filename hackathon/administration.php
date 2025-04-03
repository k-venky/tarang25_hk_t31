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

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $message = trim($_POST["message"]);

    if (!empty($title) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $message);
        
        if ($stmt->execute()) {
            // Fetch student emails
            $emails = [];
            $result = $conn->query("SELECT email FROM users WHERE email IS NOT NULL");
            while ($row = $result->fetch_assoc()) {
                $emails[] = $row['email'];
            }

            // Send emails if students exist
            if (!empty($emails)) {
                sendEmails($emails, $title, $message);
            }

            echo "<script>alert('Announcement posted & emails sent successfully!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Both fields are required.');</script>";
    }
}

// Fetch announcements
$announcements = [];
$result = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

$conn->close();

// Function to send emails
function sendEmails($emails, $title, $message) {
    $subject = "New Announcement: $title";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: patthiuma7@gmail.com\r\n"; // Replace with valid email

    foreach ($emails as $email) {
        mail($email, $subject, "<h3>$title</h3><p>$message</p>", $headers);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body { background-color: #f8f9fa; }
        .dashboard-card { transition: transform 0.3s ease-in-out; }
        .dashboard-card:hover { transform: scale(1.03); }
        .table-responsive { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="index.html">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5">
        <div class="row">
            <!-- Post Announcement -->
            <div class="col-md-12">
                <div class="card shadow-sm dashboard-card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-bullhorn"></i> Post Announcement</h4>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Post</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements Table -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-clipboard-list"></i> Announcements</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Posted On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($announcements) > 0) { ?>
                                        <?php foreach ($announcements as $ann) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($ann['title']); ?></td>
                                                <td><?php echo htmlspecialchars($ann['message']); ?></td>
                                                <td><?php echo date('d M Y, H:i', strtotime($ann['created_at'])); ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No announcements posted yet.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
