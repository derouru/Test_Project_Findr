<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="./output.css" rel="stylesheet" />
    <script src="script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> <!-- HELPS TAILWIND WORK -->
    <title>FINDR | Lost and Found Form</title>
  </head>
  <body class="bg-white text-[30px]">
    <!--LOGO-->
    <div id="logo" class="text-[50px] mb-10 mx-5">
      <h1>
        <a href="./home.php"><b>FINDR</b></a>
      </h1>
    </div>

    <div class="text-center flex-col flex min-h-[92vh]">
      <!--Admin Center Label-->
      <div
        class="text-black bg-amber-300 self-center me-2 mb-[100px] w-[80%] outline-3 outline-black overflow-clip"
      >
        <h1 class="text-[25px]">I found an item</h1>
      </div>

      <!--SEARCH-->
      <div class="">
        <form>
          <label for="enterCode">Enter valid code:</label>
          <input
            class="bg-gray-300 mb-2 w-[50%]"
            id="enterCode"
            name="enterCode"
          /><br />
          <input
            type="submit"
            class="text-black bg-amber-300 hover:bg-amber-500 focus:outline-none font-semibold rounded-full text-lg px-5 py-2.5 text-center me-2 mt-2 dark:bg-amber-300 dark:hover:bg-amber-500 drop-shadow-gray-500 drop-shadow-lg"
            value="Submit"
          />
        </form>
      </div>
    </div>
  </body>
</html>