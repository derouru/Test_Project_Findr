<?php
// php debugging block
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

// redirect the users to the home page if they are not yet logged in
if (!isset($_SESSION['user_name'])) {
    header('Location: home.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lost & Found Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-4">
    <div class="w-full max-w-7xl bg-white rounded-lg shadow-lg p-6 mt-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Lost & Found Items List</h2>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700">Welcome, <strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong></span>
                <a href="logout.php" class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">Logout</a>
            </div>
        </div>

        <div class="mb-4">
            <a href="./create.php" class="inline-block px-5 py-2 bg-amber-300 hover:bg-amber-400 text-gray-900 font-semibold rounded-md">Add Entry</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Image</th>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Item ID</th>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Box Location</th>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Lost At</th>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Description</th>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Found When</th>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Finder Details</th>
                        <th class="px-4 py-2 border border-gray-300 text-left text-sm font-medium text-gray-700">Claimed</th>
                        <th class="px-4 py-2 border border-gray-300 text-center text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // DB credentials
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "findrdatabase"; // Replace with your actual DB name

                    // Create connection
                    $connection = new mysqli($servername, $username, $password, $database);

                    // Check connection
                    if ($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }

                    // Query all rows from lostfounditems
                    $sql = "SELECT * FROM lostfounditems";
                    $result = $connection->query($sql);

                    if (!$result) {
                        die("Invalid query: " . $connection->error);
                    }

                    // Loop through rows
                    while ($row = $result->fetch_assoc()) {
                        // Convert blob image to base64 for HTML display
                        $imgData = base64_encode($row['image']);
                        $imgTag = '<img class="w-16 h-16 object-cover rounded-md" src="data:image/jpeg;base64,' . $imgData . '" alt="Item Image">';

                        $claimedText = $row['claimed'] ? 'Yes' : 'No';

                        $itemId = (int)$row['item_id']; // safer for URLs

                        // HTML-escape text fields
                        $boxLocation = htmlspecialchars($row['box_location']);
                        $lostAt = htmlspecialchars($row['lost_at']);
                        $description = htmlspecialchars($row['description']);
                        $foundWhen = htmlspecialchars($row['found_when']);
                        $finderDetails = htmlspecialchars($row['finder_details']);

                        echo "
                        <tr class='even:bg-gray-50 hover:bg-gray-100'>
                            <td class='px-4 py-2 border border-gray-300'>$imgTag</td>
                            <td class='px-4 py-2 border border-gray-300'>$itemId</td>
                            <td class='px-4 py-2 border border-gray-300'>$boxLocation</td>
                            <td class='px-4 py-2 border border-gray-300'>$lostAt</td>
                            <td class='px-4 py-2 border border-gray-300'>$description</td>
                            <td class='px-4 py-2 border border-gray-300'>$foundWhen</td>
                            <td class='px-4 py-2 border border-gray-300'>$finderDetails</td>
                            <td class='px-4 py-2 border border-gray-300'>$claimedText</td>
                            <td class='px-4 py-2 border border-gray-300 text-center space-x-2'>
                                <a href='edit.php?id=$itemId' class='inline-block px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm'>Edit</a>
                                <a href='delete.php?id=$itemId' onclick='return confirm(\"Are you sure you want to delete this entry?\");' class='inline-block px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm'>Delete</a>
                            </td>
                        </tr>
                        ";
                    }
                    $connection->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
