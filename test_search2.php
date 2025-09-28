<?php
session_start();
$_SESSION["userid"] = 2;

require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

echo "Testing FIXED search:\n\n";

$stmt = $conn->prepare("SELECT firstname, lastname FROM Contacts WHERE (firstname like ? OR lastname like ? OR CONCAT(firstname, ' ', lastname) like ? OR email like ? OR phone like ?) and userID=?");

// Test the problematic "leo l" search
$search = '%leo l%';
$userID = 2;
$stmt->bind_param('sssssi', $search, $search, $search, $search, $search, $userID);
$stmt->execute();
$result = $stmt->get_result();
echo "Search 'leo l' (FIXED):\n";
while($row = $result->fetch_assoc()) {
    echo "Found: " . $row['firstname'] . ' ' . $row['lastname'] . "\n";
}
?>
