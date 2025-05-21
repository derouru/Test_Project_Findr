<?php
// Enable debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "findrdatabase"; // Adjust if needed

$connection = new mysqli($servername, $username, $password, $database);

$image = null;
$item_id = "";
$box_location = "";
$lost_at = "";
$description = "";
$found_when = "";
$finder_details = "";
$claimed = 0;

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST["item_id"];
    $box_location = $_POST["box_location"];
    $lost_at = $_POST["lost_at"];
    $description = $_POST["description"];
    $found_when = $_POST["found_when"];
    $finder_details = $_POST["finder_details"];

    // image file upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }

    do {
        if (empty($item_id) || empty($box_location) || empty($lost_at) || empty($description) || empty($found_when) || empty($finder_details) || !$image) {
            $errorMessage = "All fields including an image are required.";
            break;
        }

        // Check for duplicate item_id
        $checkStmt = $connection->prepare("SELECT COUNT(*) FROM lostfounditems WHERE item_id = ?");
        $checkStmt->bind_param("s", $item_id);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $errorMessage = "An item with ID '$item_id' already exists.";
            break;
        }

        // Insert into database
        $stmt = $connection->prepare("INSERT INTO lostfounditems (image, item_id, box_location, lost_at, description, found_when, finder_details, claimed) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("bisssss", $image, $item_id, $box_location, $lost_at, $description, $found_when, $finder_details);
        $stmt->send_long_data(0, $image);
        $result = $stmt->execute();

        if (!$result) {
            $errorMessage = "Database error: " . $stmt->error;
            break;
        }

        $successMessage = "Item successfully added!";
        header("Location: index.php");
        exit;

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Lost & Found Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center text-gray-900">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl space-y-6">
        <h2 class="text-2xl font-bold text-center">Add Lost & Found Item</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-md"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-semibold mb-1">Item ID</label>
                <input type="number" name="item_id" class="w-full border rounded-lg px-4 py-2 focus:ring-amber-300 focus:outline-none" value="<?= htmlspecialchars($item_id) ?>">
            </div>

            <div>
                <label class="block font-semibold mb-1">Box Location</label>
                <input type="text" name="box_location" class="w-full border rounded-lg px-4 py-2 focus:ring-amber-300 focus:outline-none" value="<?= htmlspecialchars($box_location) ?>">
            </div>

            <div>
                <label class="block font-semibold mb-1">Lost At</label>
                <input type="text" name="lost_at" class="w-full border rounded-lg px-4 py-2 focus:ring-amber-300 focus:outline-none" value="<?= htmlspecialchars($lost_at) ?>">
            </div>

            <div>
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" class="w-full border rounded-lg px-4 py-2 focus:ring-amber-300 focus:outline-none"><?= htmlspecialchars($description) ?></textarea>
            </div>

            <div>
                <label class="block font-semibold mb-1">Found When</label>
                <input type="text" id="foundWhenPicker" name="found_when" class="w-full border rounded-lg px-4 py-2 focus:ring-amber-300 focus:outline-none" value="<?= htmlspecialchars($found_when) ?>">
            </div>

            <div>
                <label class="block font-semibold mb-1">Finder Details</label>
                <input type="text" name="finder_details" class="w-full border rounded-lg px-4 py-2 focus:ring-amber-300 focus:outline-none" value="<?= htmlspecialchars($finder_details) ?>">
            </div>

            <div>
                <label class="block font-semibold mb-1">Upload Image</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-4 py-2 bg-white">
            </div>

            <div class="flex gap-4 pt-2">
                <button type="submit" class="flex-1 bg-amber-300 text-black font-semibold py-2 rounded-lg hover:bg-amber-400 transition">Submit</button>
                <a href="index.php" class="flex-1 bg-gray-200 text-black text-center font-semibold py-2 rounded-lg hover:bg-gray-300 transition">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        flatpickr("#foundWhenPicker", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });
    </script>
</body>
</html>
