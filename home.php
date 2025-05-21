<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./output.css" rel="stylesheet" />
    <script src="script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> <!-- HELPS TAILWIND WORK -->
    <title>FINDR | Homepage</title>
  </head>
  <body class="bg-white text-[30px]">
    <div
      class="text-center items-center justify-center flex-col flex min-h-[92vh] w-full"
    >
      <!--LOGO-->
      <div id="logo" class="text-[50px] mb-10">
        <h1><b>FINDR</b></h1>
      </div>

      <!--BUTTONS-->
      <div class="flex-col">
        <!--On clicking I Lost An Item, go to userLost.php-->
        <button
          type="button"
          id="buttonLost"
          class="text-black bg-amber-300 hover:bg-amber-500 focus:outline-none font-semibold rounded-full text-lg px-5 py-2.5 text-center me-2 mb-2 dark:bg-amber-300 dark:hover:bg-amber-500 drop-shadow-gray-500 drop-shadow-lg"
        >
          I lost an item
        </button>
        <br />
        <!--On clicking I Found An Item, go to userFound.php-->
        <button
          type="button"
          id="buttonFound"
          class="text-black bg-amber-300 hover:bg-amber-500 focus:outline-none font-semibold rounded-full text-lg px-5 py-2.5 text-center me-2 mb-2 dark:bg-amber-300 dark:hover:bg-amber-500 drop-shadow-gray-500 drop-shadow-lg"
        >
          I found an item
        </button>
        <br />
      </div>
    </div>
    
    <!--FOOTER-->
    <div class="flex-col text-center text-[15px]">
        <!--On clicking Admin Center, go to login.php-->
        <a class="underline" href="./login.php">Admin Center</a><br /><br />
    </div>
  </body>
</html>

<script>
  document.getElementById("buttonLost").addEventListener("click", function () {
    window.location.href = "userLost.php";
  });

  document.getElementById("buttonFound").addEventListener("click", function () {
    window.location.href = "userFound.php";
  });
</script>
