<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Items Found</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./output.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> <!-- HELPS TAILWIND WORK -->
</head>
<body class="bg-white text-gray-800 p-6">

    <!-- Back Button -->
    <div id="logo" class="text-[50px] mb-10 mx-5">
      <h1>
        <a href="./home.php"><b>FINDR</b></a>
      </h1>
    </div>

    <!-- Search and Filter -->
    <div class="mb-4 flex flex-col sm:flex-row justify-center items-center gap-4">
        <!-- Search Bar -->
        <input
            type="text"
            id="searchInput"
            placeholder="Search description, location, date..."
            class="w-full sm:w-[300px] px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring focus:border-blue-300 shadow"
        />

        <!-- Claimed Filter Button -->
        <button
            id="claimedFilterBtn"
            class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-full font-semibold shadow"
        >
            Filter: All
        </button>
    </div>

    <!-- Page Title -->
    <h2 class="text-2xl font-bold mb-4 text-center">List of Found Items</h2>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 text-sm" id="itemsTable">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Item ID</th>
                    <th class="px-4 py-2 border">Box Location</th>
                    <th class="px-4 py-2 border">Lost At</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Found When</th>
                    <th class="px-4 py-2 border">Finder Details</th>
                    <th class="px-4 py-2 border">Claimed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "findrdatabase"; // Adjust if needed

                $connection = new mysqli($servername, $username, $password, $database);

                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                $sql = "SELECT item_id, box_location, lost_at, description, found_when, finder_details, claimed FROM lostfounditems";
                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                while ($row = $result->fetch_assoc()) {
                    $claimedText = $row['claimed'] ? 'Yes' : 'No';

                    // Combined searchable string (excluding claimed)
                    $searchData = strtolower(
                        $row['description'] . " " .
                        $row['box_location'] . " " .
                        $row['lost_at'] . " " .
                        $row['found_when']
                    );

                    echo "
                    <tr class='hover:bg-gray-50' 
                        data-search='$searchData' 
                        data-claimed='$claimedText'>
                        <td class='px-4 py-2 border'>{$row['item_id']}</td>
                        <td class='px-4 py-2 border'>{$row['box_location']}</td>
                        <td class='px-4 py-2 border'>{$row['lost_at']}</td>
                        <td class='px-4 py-2 border'>{$row['description']}</td>
                        <td class='px-4 py-2 border'>{$row['found_when']}</td>
                        <td class='px-4 py-2 border'>{$row['finder_details']}</td>
                        <td class='px-4 py-2 border'>$claimedText</td>
                    </tr>
                    ";
                }

                $connection->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- JS for filtering -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const filterBtn = document.getElementById('claimedFilterBtn');
        const tableRows = document.querySelectorAll('#itemsTable tbody tr');

        let claimedFilterState = 'all'; // all, claimed, unclaimed

        function applyFilters() {
            const query = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const searchData = row.dataset.search;
                const isClaimed = row.dataset.claimed.toLowerCase(); // "yes" or "no"

                const matchesSearch = searchData.includes(query);
                let matchesClaimed = true;

                if (claimedFilterState === 'claimed') {
                    matchesClaimed = isClaimed === 'yes';
                } else if (claimedFilterState === 'unclaimed') {
                    matchesClaimed = isClaimed === 'no';
                }

                row.style.display = (matchesSearch && matchesClaimed) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', applyFilters);

        filterBtn.addEventListener('click', () => {
            if (claimedFilterState === 'all') {
                claimedFilterState = 'claimed';
                filterBtn.textContent = 'Filter: Claimed';
            } else if (claimedFilterState === 'claimed') {
                claimedFilterState = 'unclaimed';
                filterBtn.textContent = 'Filter: Unclaimed';
            } else {
                claimedFilterState = 'all';
                filterBtn.textContent = 'Filter: All';
            }

            applyFilters();
        });
    </script>

</body>
</html>
