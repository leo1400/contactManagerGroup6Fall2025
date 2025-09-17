<?php
// LAMPAPI/DeleteContact.php
session_start();
header("Content-Type: application/json");

// Must be logged in
if (!isset($_SESSION["userid"])) {
    sendJsonResult("failure", "User Not Logged In");
    // Optional: redirect like your other files
    // header("Location: http://contactymanager.shop/");
    exit;
}

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$sqlUser    = $_ENV['DB_USER'];
$sqlPass    = $_ENV['DB_PASS'];

$conn = new mysqli($servername, $sqlUser, $sqlPass, $_ENV['DB_NAME']);
if ($conn->connect_error) {
    sendJsonResult("failure", "Database Connection Failed");
    exit;
}

try {
    $input = getUserInput();

    // Expecting: { "id": 123 }
    if (!isset($input["id"]) || !is_numeric($input["id"])) {
        sendJsonResult("failure", "Missing or invalid contact id");
        exit;
    }

    $contactId = (int)$input["id"];
    $userId = (int)$_SESSION["userid"];

    // Enforce ownership in the WHERE clause
    $stmt = $conn->prepare("DELETE FROM Contacts WHERE id = ? AND userID = ?");
    $stmt->bind_param("ii", $contactId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // Either not found or not owned by this user
        sendJsonResult("failure", "Contact not found");
        exit;
    }

    sendJsonResult("success", "Contact Deleted");
    exit;

} catch (Exception $e) {
    sendJsonResult("failure", "Something Went Wrong");
    exit;
}

function getUserInput() {
    return json_decode(file_get_contents("php://input"), true);
}

function sendJsonResult($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
}


/* JS func
async function deleteContact(id) {
  const res = await fetch('/LAMPAPI/DeleteContact.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    // If API is a different origin/subdomain, add: credentials: 'include'
    body: JSON.stringify({ id })
  });
  const data = await res.json();

  if (data.status === 'success') {
    // remove from UI list, re-run your search(), or refresh table
    // e.g., contacts = contacts.filter(c => c.id !== id); render();
  } else {
    // show error toast/modal
    console.error(data.message || 'Delete failed');
  }
}
*/