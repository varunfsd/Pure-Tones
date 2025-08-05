<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "";
$dbname = "beauty_parlour";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Get email from query parameter
    $email = isset($_GET['email']) ? trim($_GET['email']) : '';

    if ($email === '') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email parameter is missing.'
        ]);
        exit;
    }

    // ✅ Use prepared statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT name, country_code, phone, email, service, stylist, appointment_date, appointment_time, message 
                           FROM appointments 
                           WHERE email = :email 
                           ORDER BY appointment_date DESC, appointment_time DESC");

    $stmt->execute(['email' => $email]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'bookings' => $bookings
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
