<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

echo "Debug search:\n";

// Let's see what the actual data looks like
$stmt = $conn->prepare("SELECT firstname, lastname, CONCAT(firstname, ' ', lastname) as fullname FROM Contacts WHERE userID=?");
$userID = 2;
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
echo "Actual data:\n";
while($row = $result->fetch_assoc()) {
    echo "First: '" . $row['firstname'] . "', Last: '" . $row['lastname'] . "', Full: '" . $row['fullname'] . "'\n";
}

echo "\nTesting searches:\n";

// Test various search patterns
$searches = ['leo', 'love', 'leo l', 'Leo L'];
foreach($searches as $searchTerm) {
    $search = '%' . $searchTerm . '%';
    $stmt = $conn->prepare("SELECT firstname, lastname FROM Contacts WHERE CONCAT(firstname, ' ', lastname) LIKE ? AND userID=?");
    $stmt->bind_param('si', $search, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "Search '$searchTerm': ";
    $found = false;
    while($row = $result->fetch_assoc()) {
        echo $row['firstname'] . ' ' . $row['lastname'] . " ";
        $found = true;
    }
    if (!$found) echo "No results";
    echo "\n";
}
?>
