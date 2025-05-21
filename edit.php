<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "findrdatabase";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$item_id = "";
$box_location = "";
$lost_at = "";
$description = "";
$found_when = "";
$finder_details = "";
$claimed = false;

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if 'id' parameter exists
    if (!isset($_GET['id'])) {
        header("Location: index.php");
        exit;
    }
    $item_id = $_GET['id'];

    // Prepare and execute query safely (avoid SQL injection)
    $stmt = $connection->prepare("SELECT * FROM lostfounditems WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: index.php");
        exit;
    }

    // Fill variables with current data
    $box_location = $row['box_location'];
    $lost_at = $row['lost_at'];
    $description = $row['description'];
    $found_when = $row['found_when'];
    $finder_details = $row['finder_details'];
    $claimed = (bool)$row['claimed'];

    $stmt->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST: Update the data

    $item_id = $_POST['item_id'];
    $box_location = $_POST['box_location'];
    $lost_at = $_POST['lost_at'];
    $description = $_POST['description'];
    $found_when = $_POST['found_when'];
    $finder_details = $_POST['finder_details'];
    $claimed = isset($_POST['claimed']) ? 1 : 0;

    // Basic validation (you can extend this)
    if (empty($box_location) || empty($lost_at) || empty($description)) {
        $errorMessage = "Box location, Lost At, and Description are required.";
    } else {
        $stmt = $connection->prepare("UPDATE lostfounditems SET box_location=?, lost_at=?, description=?, found_when=?, finder_details=?, claimed=? WHERE item_id=?");
        $stmt->bind_param("sssssii", $box_location, $lost_at, $description, $found_when, $finder_details, $claimed, $item_id);
        $result = $stmt->execute();

        if ($result) {
            $successMessage = "Item updated successfully.";
            header("Location: index.php");
            exit;
        } else {
            $errorMessage = "Update failed: " . $connection->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Lost & Found Item</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-3xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Lost & Found Item #<?php echo htmlspecialchars($item_id); ?></h1>

    <?php if (!empty($errorMessage)): ?>
        <div class="mb-4 p-4 bg-yellow-200 text-yellow-800 rounded">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>" />

        <div>
            <label class="block font-semibold mb-1" for="box_location">Box Location <span class="text-red-500">*</span></label>
            <input
                type="text"
                id="box_location"
                name="box_location"
                value="<?php echo htmlspecialchars($box_location); ?>"
                class="w-full border border-gray-300 rounded px-3 py-2"
                required
            />
        </div>

        <div>
            <label class="block font-semibold mb-1" for="lost_at">Lost At <span class="text-red-500">*</span></label>
            <input
                type="text"
                id="lost_at"
                name="lost_at"
                value="<?php echo htmlspecialchars($lost_at); ?>"
                class="w-full border border-gray-300 rounded px-3 py-2"
                required
            />
        </div>

        <div>
            <label class="block font-semibold mb-1" for="description">Description <span class="text-red-500">*</span></label>
            <textarea
                id="description"
                name="description"
                class="w-full border border-gray-300 rounded px-3 py-2"
                rows="3"
                required
            ><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div>
            <label class="block font-semibold mb-1" for="found_when">Found When</label>
            <input
                type="text"
                id="found_when"
                name="found_when"
                value="<?php echo htmlspecialchars($found_when); ?>"
                class="w-full border border-gray-300 rounded px-3 py-2"
            />
        </div>

        <div>
            <label class="block font-semibold mb-1" for="finder_details">Finder Details</label>
            <input
                type="text"
                id="finder_details"
                name="finder_details"
                value="<?php echo htmlspecialchars($finder_details); ?>"
                class="w-full border border-gray-300 rounded px-3 py-2"
            />
        </div>

        <div class="flex items-center space-x-2">
            <input
                type="checkbox"
                id="claimed"
                name="claimed"
                value="1"
                <?php echo $claimed ? "checked" : ""; ?>
            />
            <label for="claimed" class="font-semibold">Claimed?</label>
        </div>

        <div class="flex space-x-4 mt-6">
            <button
                type="submit"
                class="bg-amber-300 hover:bg-amber-400 text-black font-bold px-6 py-2 rounded"
            >
                Save Changes
            </button>
            <a
                href="index.php"
                class="bg-gray-300 hover:bg-gray-400 text-black font-bold px-6 py-2 rounded"
            >
                Cancel
            </a>
        </div>
    </form>
</div>
</body>
</html>
