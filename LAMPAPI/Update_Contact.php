<?php
// LAMPAPI/UpdateContact.php
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
    /*
      Expecting JSON like:
      {
        "id": 123,
        "firstname": "NewFirst",
        "lastname": "NewLast",
        "phone": "555-1234",
        "email": "new@example.com"
      }
      (All four fields required to keep it simple and consistent.)
    */

    // Basic validation
    $required = ["id","firstname","lastname","phone","email"];
    foreach ($required as $k) {
        if (!isset($input[$k])) {
            sendJsonResult("failure", "Missing field: $k");
            exit;
        }
    }
    if (!is_numeric($input["id"])) {
        sendJsonResult("failure", "Invalid contact id");
        exit;
    }

    $contactId = (int)$input["id"];
    $userId    = (int)$_SESSION["userid"];
    $first     = trim($input["firstname"]);
    $last      = trim($input["lastname"]);
    $phone     = trim($input["phone"]);
    $email     = trim($input["email"]);

    // Enforce ownership with userID in the WHERE clause
    $stmt = $conn->prepare("
        UPDATE Contacts
           SET firstname = ?, lastname = ?, phone = ?, email = ?
         WHERE id = ? AND userID = ?
    ");
    $stmt->bind_param("ssssii", $first, $last, $phone, $email, $contactId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // Not found or data identical or not owned
        // We can check if the row exists for this user to give a clearer message
        $check = $conn->prepare("SELECT id FROM Contacts WHERE id = ? AND userID = ?");
        $check->bind_param("ii", $contactId, $userId);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        if (!$exists) {
            sendJsonResult("failure", "Contact not found");
            exit;
        }
        // It exists but nothing changed
        sendJsonResult("success", "No changes detected");
        exit;
    }

    sendJsonResult("success", "Contact Updated");
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

/*JS Func
async function updateContact(contact) {
  // contact should be: { id, firstname, lastname, phone, email }
  const res = await fetch('/LAMPAPI/UpdateContact.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    // If different origin/subdomain: credentials: 'include'
    body: JSON.stringify(contact)
  });
  const data = await res.json();

  if (data.status === 'success') {
    // Update in UI (optimistic or by re-fetching)
    // e.g., refreshSearchResults(); or update state directly
  } else {
    console.error(data.message || 'Update failed');
  }
}
*/