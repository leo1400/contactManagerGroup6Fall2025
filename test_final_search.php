<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

echo "Testing FINAL search fix:\n\n";

$userID = 2;
$searches = ['leo', 'leo l', 'LEO L', 'love'];

foreach($searches as $searchTerm) {
    $search = '%' . $searchTerm . '%';
    $stmt = $conn->prepare("SELECT firstname, lastname FROM Contacts WHERE (LOWER(firstname) like LOWER(?) OR LOWER(lastname) like LOWER(?) OR LOWER(CONCAT(TRIM(firstname), ' ', TRIM(lastname))) like LOWER(?)) AND userID=?");
    $stmt->bind_param('sssi', $search, $search, $search, $userID);
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
