<?php
// Database configuration
$host = 'localhost';
$db   = 'beauty_parlour';      // your database name
$user = 'root';                // default XAMPP username
$pass = '';                   // default XAMPP password is empty
// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to sanitize input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and assign variables
    $full_name = sanitize($_POST['name'] ?? '');
    $phone_number = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $gender = sanitize($_POST['gender'] ?? '');
    $dob = sanitize($_POST['dob'] ?? null);
    $city = sanitize($_POST['city'] ?? '');
    $terms_accepted = isset($_POST['terms']) ? 1 : 0;

    // Server-side validations
    if (empty($full_name) || empty($phone_number) || empty($email) || empty($password) || empty($confirm_password) || empty($gender) || empty($city) || !$terms_accepted) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields and accept terms.']);
        exit;
    }

    if (!preg_match('/^[6-9][0-9]{9}$/', $phone_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid phone number format.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    if ($dob && strtotime($dob) > time()) {
        echo json_encode(['status' => 'error', 'message' => 'Date of birth cannot be in the future.']);
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email is already registered.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, phone_number, email, password, gender, dob, city, terms_accepted) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $full_name, $phone_number, $email, $hashed_password, $gender, $dob, $city, $terms_accepted);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error in registration. Please try again.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
