<?php
session_start();
$conn = new mysqli("localhost", "root", "Abhi@1289", "Abhi");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = $user;
            $_SESSION["role"] = $user["role"]; // Ensure role is stored in session

            echo json_encode(["success" => true, "role" => $user["role"]]);
            exit();
        }
    }

    echo json_encode(["success" => false]);
    $stmt->close();
    $conn->close();
}
?>
