<?php
session_start();
$_SESSION["userid"] = 2; // Test with our user

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

echo "Testing search functionality:\n\n";

// Test 1: Search for "leo"
$stmt = $conn->prepare("SELECT firstname, lastname FROM Contacts WHERE (firstname LIKE ? OR lastname LIKE ?) AND userID=?");
$search = '%leo%';
$userID = 2;
$stmt->bind_param('sss', $search, $search, $userID);
$stmt->execute();
$result = $stmt->get_result();
echo "Search 'leo':\n";
while($row = $result->fetch_assoc()) {
    echo "Found: " . $row['firstname'] . ' ' . $row['lastname'] . "\n";
}

// Test 2: Search for "leo l"
$search = '%leo l%';
$stmt->bind_param('sss', $search, $search, $userID);
$stmt->execute();
$result = $stmt->get_result();
echo "\nSearch 'leo l':\n";
while($row = $result->fetch_assoc()) {
    echo "Found: " . $row['firstname'] . ' ' . $row['lastname'] . "\n";
}

// Test 3: Search for "love"
$search = '%love%';
$stmt->bind_param('sss', $search, $search, $userID);
$stmt->execute();
$result = $result->get_result();
echo "\nSearch 'love':\n";
while($row = $result->fetch_assoc()) {
    echo "Found: " . $row['firstname'] . ' ' . $row['lastname'] . "\n";
}
?>
