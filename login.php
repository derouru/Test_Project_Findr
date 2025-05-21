<?php
// Start the session
session_start();
include('database.php');

// Initialize variables
$input_username = '';
$input_password = '';
$failed = 0;
$fail_message = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';

    $query = mysqli_prepare($connection, "SELECT * FROM users WHERE user_name = ?");
    if (!$query) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    if (!mysqli_stmt_bind_param($query, "s", $input_username)) {
        die("Bind failed: " . mysqli_stmt_error($query));
    }

    if (!mysqli_stmt_execute($query)) {
        die("Execute failed: " . mysqli_stmt_error($query));
    }

    $result = mysqli_stmt_get_result($query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $fetched_username = $row['user_name'];
        $fetched_password = $row['password'];
        $fetched_user_id = $row['user_id'];

        if (($input_username === $fetched_username) && password_verify($input_password, $fetched_password)) {
            $_SESSION['user_name'] = $fetched_username;
            $_SESSION['user_id'] = $fetched_user_id;
            header("Location: index.php");
            exit();
        } else {
            $failed = 1;
            $fail_message = 'Login failed: Invalid password.';
        }
    } else {
        $failed = 1;
        $fail_message = 'Login failed: User does not exist.';
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | Findr Admin Center</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <form method="POST" action="" class="bg-white p-10 rounded-lg shadow-lg max-w-md w-full">
    <h2 class="text-2xl font-bold text-center mb-8 text-gray-800">Findr Admin Center</h2>

    <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
    <input
      type="text"
      id="username"
      name="username"
      required
      value="<?= htmlspecialchars($input_username) ?>"
      class="w-full px-4 py-2 mb-6 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500"
    />

    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
    <input
      type="password"
      id="password"
      name="password"
      required
      class="w-full px-4 py-2 mb-6 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-amber-500"
    />

    <button
      type="submit"
      class="w-full py-3 rounded-md bg-amber-300 font-semibold hover:bg-amber-500 transition"
    >
      Login
    </button>

    <?php if ($failed): ?>
      <p class="mt-4 text-center text-red-600 font-semibold"><?= htmlspecialchars($fail_message) ?></p>
    <?php endif; ?>
  </form>

</body>
</html>
