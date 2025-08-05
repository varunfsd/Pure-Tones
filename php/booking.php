<?php
header('Content-Type: application/json');

// Database connection settings — change these to your own
$host = 'localhost';
$db   = 'beauty_parlour';      // your database name
$user = 'root';                // default XAMPP username
$pass = '';   

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Get POST data (sanitize inputs)
$name = trim($_POST['name'] ?? '');
$country_code = trim($_POST['country_code'] ?? '+91');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? null);
$service = trim($_POST['service'] ?? '');
$stylist = trim($_POST['stylist'] ?? null);
$appointment_date = trim($_POST['date'] ?? '');
$appointment_time = trim($_POST['time'] ?? '');
$message = trim($_POST['message'] ?? null);
$terms_accepted = isset($_POST['terms']) && $_POST['terms'] == 'true' ? 1 : 0;

// Basic validation server-side (similar to JS validation)
if ($name === '' || $phone === '' || $service === '' || $appointment_date === '' || $appointment_time === '' || !$terms_accepted) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields correctly.']);
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO appointments (name, country_code, phone, email, service, stylist, appointment_date, appointment_time, message, terms_accepted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssi", $name, $country_code, $phone, $email, $service, $stylist, $appointment_date, $appointment_time, $message, $terms_accepted);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Appointment booked successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();